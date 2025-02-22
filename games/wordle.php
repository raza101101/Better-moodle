<?php
session_start();
include("../php/connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../php/login.php");
    exit();
}

$wordFile = __DIR__ . '/word.txt';
if (file_exists($wordFile)) {
    $words = file($wordFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($words === false) {
        die("Error reading word.txt");
    }
    $words = array_filter($words, function($word) {
        return strlen($word) === 5 && ctype_alpha($word) && !ctype_space($word);
    });
    $words = array_map('strtolower', $words);
} else {
    die("word.txt not found in the games directory");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #game-area {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
            padding-bottom: 2rem;
        }

        .game-board {
            display: grid;
            grid-template-rows: repeat(6, 1fr);
            gap: 5px;
            margin-bottom: 1rem;
            padding: 10px;
        }

        .guess-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
        }

        .letter-tile {
            width: 60px;
            height: 60px;
            border: 2px solid #d3d6da;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            background: white;
            transition: all 0.2s;
        }

        .letter-tile.filled {
            border-color: #878a8c;
        }

        .letter-tile.correct {
            background-color: #6aaa64;
            color: white;
            border-color: #6aaa64;
        }

        .letter-tile.present {
            background-color: #c9b458;
            color: white;
            border-color: #c9b458;
        }

        .letter-tile.absent {
            background-color: #787c7e;
            color: white;
            border-color: #787c7e;
        }

        .letter-tile.pop {
            transform: scale(1.1);
        }

        .letter-tile.shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .keyboard {
            transform: translateX(-43px);
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 8px;
            margin-top: 1rem;
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .key {
            min-width: 40px;
            height: 58px;
            border-radius: 4px;
            border: none;
            background-color: #d3d6da;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            user-select: none;
        }

        .key.wide {
            min-width: 65px;
        }

        #game-message {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 4px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s;
        }

        #game-message.show {
            opacity: 1;
        }

        #game-message.persist {
            opacity: 1;
        }

        .success { background-color: #6aaa64; color: white; }
        .error { background-color: #ff4d4d; color: white; }
        .warning { background-color: #c9b458; color: white; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="../index.html"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a>
            </nav>
        </div>
    </header>
    
    <main>
        <h2 style="text-align: center; margin-bottom: 0rem;">Wordle</h2>
        <p style="text-align: center; margin-bottom: 2rem;">Guess the five-letter word in 6 tries!</p>

        <div id="game-area" class="card">
            <div class="game-board"></div>
            <div id="game-message"></div>
            <div class="keyboard"></div>
        </div>

        <div class="returnhome">
            <a href="index.php" class="returnbutton">Back to Games</a>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../about_us.html">About Us</a> | <a href="../contact.html">Contact Us</a> | 
            <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>

    <script>
        const words = <?php echo json_encode(array_values($words)); ?>;
        const WORD_LENGTH = 5;
        const MAX_GUESSES = 6;
        
        class WordleGame {
            constructor() {
                this.targetWord = words[Math.floor(Math.random() * words.length)].toLowerCase();
                this.currentGuess = '';
                this.guesses = [];
                this.isGameOver = false;
                this.currentRow = 0;
                
                this.gameBoard = document.querySelector('.game-board');
                this.keyboard = document.querySelector('.keyboard');
                this.messageElement = document.getElementById('game-message');
                
                this.initializeBoard();
                this.initializeKeyboard();
                this.setupEventListeners();
            }
            
            initializeBoard() {
                for (let i = 0; i < MAX_GUESSES; i++) {
                    const row = document.createElement('div');
                    row.className = 'guess-row';
                    for (let j = 0; j < WORD_LENGTH; j++) {
                        const tile = document.createElement('div');
                        tile.className = 'letter-tile';
                        row.appendChild(tile);
                    }
                    this.gameBoard.appendChild(row);
                }
            }
            
            initializeKeyboard() {
                const layout = [
                    ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
                    ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'],
                    ['Enter', 'z', 'x', 'c', 'v', 'b', 'n', 'm', '←']
                ];
                
                layout.forEach(row => {
                    const keyboardRow = document.createElement('div');
                    keyboardRow.className = 'keyboard-row';
                    
                    row.forEach(key => {
                        const button = document.createElement('button');
                        button.textContent = key;
                        button.className = 'key';
                        if (key === 'Enter' || key === '←') button.className += ' wide';
                        button.setAttribute('data-key', key);
                        keyboardRow.appendChild(button);
                    });
                    
                    this.keyboard.appendChild(keyboardRow);
                });
            }
            
            setupEventListeners() {
                document.addEventListener('keydown', (e) => this.handleKeyPress(e));
                this.keyboard.addEventListener('click', (e) => {
                    if (e.target.matches('button')) {
                        const key = e.target.getAttribute('data-key');
                        this.handleInput(key);
                    }
                });
            }
            
            handleKeyPress(e) {
                if (e.key === 'Enter') {
                    this.handleInput('Enter');
                } else if (e.key === 'Backspace') {
                    this.handleInput('←');
                } else if (/^[a-zA-Z]$/.test(e.key)) {
                    this.handleInput(e.key.toLowerCase());
                }
            }
            
            handleInput(key) {
                if (this.isGameOver) return;
                
                if (key === 'Enter') {
                    this.submitGuess();
                } else if (key === '←') {
                    this.deleteLetter();
                } else if (this.currentGuess.length < WORD_LENGTH) {
                    this.addLetter(key);
                }
            }
            
            addLetter(letter) {
                if (this.currentGuess.length < WORD_LENGTH) {
                    this.currentGuess += letter;
                    this.updateDisplay();
                }
            }
            
            deleteLetter() {
                this.currentGuess = this.currentGuess.slice(0, -1);
                this.updateDisplay();
            }
            
            submitGuess() {
                if (this.currentGuess.length !== WORD_LENGTH) {
                    this.showMessage('Word must be 5 letters', 'error');
                    this.shakeRow();
                    return;
                }
                
                if (!words.includes(this.currentGuess)) {
                    this.showMessage('Not in word list', 'error');
                    this.shakeRow();
                    return;
                }
                
                const evaluation = this.evaluateGuess();
                this.animateRow(evaluation);
                this.updateKeyboard(evaluation);
                this.guesses.push(this.currentGuess);
                
                if (this.currentGuess === this.targetWord) {
                    this.gameOver(true);
                } else if (this.guesses.length === MAX_GUESSES) {
                    this.gameOver(false);
                } else {
                    this.currentRow++;
                    this.currentGuess = '';
                    this.updateDisplay();
                }
            }
            
            evaluateGuess() {
                const evaluation = Array(WORD_LENGTH).fill('absent');
                const targetLetters = this.targetWord.split('');
                const guessLetters = this.currentGuess.split('');
                
                // First pass: mark correct positions
                for (let i = 0; i < WORD_LENGTH; i++) {
                    if (guessLetters[i] === targetLetters[i]) {
                        evaluation[i] = 'correct';
                        targetLetters[i] = null;
                        guessLetters[i] = null;
                    }
                }
                
                // Second pass: mark present letters
                for (let i = 0; i < WORD_LENGTH; i++) {
                    if (guessLetters[i] !== null) {
                        const index = targetLetters.indexOf(guessLetters[i]);
                        if (index !== -1) {
                            evaluation[i] = 'present';
                            targetLetters[index] = null;
                        }
                    }
                }
                
                return evaluation;
            }
            
            updateDisplay() {
                const row = this.gameBoard.children[this.currentRow];
                const tiles = row.children;
                
                for (let i = 0; i < WORD_LENGTH; i++) {
                    const tile = tiles[i];
                    if (i < this.currentGuess.length) {
                        tile.textContent = this.currentGuess[i].toUpperCase();
                        tile.classList.add('filled');
                    } else {
                        tile.textContent = '';
                        tile.classList.remove('filled');
                    }
                }
            }
            
            animateRow(evaluation) {
                const row = this.gameBoard.children[this.currentRow];
                const tiles = row.children;
                
                for (let i = 0; i < WORD_LENGTH; i++) {
                    const tile = tiles[i];
                    setTimeout(() => {
                        tile.classList.add(evaluation[i]);
                    }, i * 100);
                }
            }
            
            updateKeyboard(evaluation) {
                const guessLetters = this.currentGuess.split('');
                
                for (let i = 0; i < WORD_LENGTH; i++) {
                    const key = document.querySelector(`[data-key="${guessLetters[i]}"]`);
                    if (key) {
                        // Only upgrade the key's status (absent -> present -> correct)
                        if (evaluation[i] === 'correct') {
                            key.className = 'key correct';
                        } else if (evaluation[i] === 'present' && !key.classList.contains('correct')) {
                            key.className = 'key present';
                        } else if (!key.classList.contains('correct') && !key.classList.contains('present')) {
                            key.className = 'key absent';
                        }
                    }
                }
            }
            
            shakeRow() {
                const row = this.gameBoard.children[this.currentRow];
                row.classList.add('shake');
                setTimeout(() => row.classList.remove('shake'), 500);
            }
            
            showMessage(text, type, persist = false) {
                this.messageElement.textContent = text;
                if (persist) {
                    // For game over/win messages, use persist class
                    this.messageElement.className = `${type} persist`;
                } else {
                    // For temporary messages (like invalid words), use show class and timeout
                    this.messageElement.className = `${type} show`;
                    setTimeout(() => {
                        if (!this.isGameOver) { // Only clear if game isn't over
                            this.messageElement.className = '';
                        }
                    }, 2000);
                }
            }
            
            gameOver(won) {
                this.isGameOver = true;
                const message = won ? 
                    `Spectacular! You got it in ${this.guesses.length} ${this.guesses.length === 1 ? 'try' : 'tries'}` :
                    `Game Over! The word was ${this.targetWord.toUpperCase()}`;
                this.showMessage(message, won ? 'success' : 'warning', true); // Added true for persist
            }
        }

        // Start the game
        new WordleGame();
    </script>
</body>
</html>