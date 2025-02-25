<?php
session_start(); // Start the session at the top
include("../php/connect.php"); // Use your Harzarian database connection (adjust path if needed)

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Search Game</title>
    <link rel="stylesheet" href="styles.css"> <!-- Use Harzarian's existing CSS for consistency -->
    <style>
        #game-area {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            grid-template-rows: repeat(10, 40px);
            gap: 2px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .grid div {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            border: 1px solid #ccc;
            transition: background-color 0.3s;
        }

        .grid div.selected {
            background-color: yellow;
        }

        .grid div.found {
            background-color: #4CAF50; /* Green, matching Harzarian design */
            color: white;
        }

        .word-list {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        .word-list li {
            font-size: 18px;
            display: inline-block;
            margin: 0 10px;
        }

        .word-list .found {
            text-decoration: line-through;
            color: gray;
        }

        #message {
            margin-top: 10px;
            font-size: 18px;
            color: #333;
            min-height: 30px;
        }

        .returnhome {
            text-align: center;
            margin-top: 2rem;
        }

        .returnbutton {
            background-color: #004080;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .returnbutton:hover {
            background-color: #003366;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="../php/index.php"><h1>Harzarian</h1></a>    
            <nav>
                <a>Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                <a href="../php/logout.php">Log Out</a>
            </nav>
        </div>
    </header>
    
    <main>
        <h2 style="text-align: center; margin-bottom: 1rem;">Word Search Game</h2>
        <p style="text-align: center; margin-bottom: 2rem;">Find the words in the grid! Click letters to select them.</p>

        <div id="game-area" class="card">
            <div id="wordsearch" class="grid">
                <?php
                // Example word list for the word search (in uppercase for consistency)
                $wordList = [
                    "ARRAY", "LOOP", "FUNCTION", "ALGORITHM", "RECURSION", "VARIABLE", "OBJECT", "CLASS", "METHOD", "CONDITION"
                ];

                $gridSize = 10;  // 10x10 grid
                $grid = generateGrid($gridSize, $wordList);

                // Function to generate the word search grid with max attempts to prevent infinite loops
                function generateGrid($gridSize, $wordList) {
                    $grid = array_fill(0, $gridSize, array_fill(0, $gridSize, ''));
                    $maxAttempts = 100; // Prevent infinite loops

                    foreach ($wordList as $word) {
                        $attempts = 0;
                        $placed = false;
                        while (!$placed && $attempts < $maxAttempts) {
                            $placed = placeWordInGrid($grid, $word, $gridSize);
                            $attempts++;
                        }
                        if (!$placed) {
                            // Log or handle failure (e.g., skip word or reduce grid size)
                            error_log("Failed to place word: " . $word . " after $attempts attempts");
                        }
                    }

                    // Fill remaining empty spaces with random letters
                    for ($i = 0; $i < $gridSize; $i++) {
                        for ($j = 0; $j < $gridSize; $j++) {
                            if ($grid[$i][$j] === '') {
                                $grid[$i][$j] = chr(rand(65, 90)); // Random capital letter (A-Z)
                            }
                        }
                    }

                    return $grid;
                }

                // Function to place a word in the grid at a random position with max attempts
                function placeWordInGrid(&$grid, $word, $gridSize) {
                    $wordLength = strlen($word);
                    $directions = ['horizontal', 'vertical', 'diagonal'];
                    $maxAttempts = 10; // Limit attempts per word placement
                    $attempts = 0;

                    while ($attempts < $maxAttempts) {
                        $direction = $directions[rand(0, count($directions) - 1)];
                        $row = rand(0, $gridSize - 1);
                        $col = rand(0, $gridSize - 1);

                        if ($direction === 'horizontal' && $col + $wordLength <= $gridSize) {
                            $canPlace = true;
                            for ($i = 0; $i < $wordLength; $i++) {
                                if ($grid[$row][$col + $i] !== '' && $grid[$row][$col + $i] !== $word[$i]) {
                                    $canPlace = false;
                                    break;
                                }
                            }
                            if ($canPlace) {
                                for ($i = 0; $i < $wordLength; $i++) {
                                    $grid[$row][$col + $i] = $word[$i];
                                }
                                return true;
                            }
                        }

                        if ($direction === 'vertical' && $row + $wordLength <= $gridSize) {
                            $canPlace = true;
                            for ($i = 0; $i < $wordLength; $i++) {
                                if ($grid[$row + $i][$col] !== '' && $grid[$row + $i][$col] !== $word[$i]) {
                                    $canPlace = false;
                                    break;
                                }
                            }
                            if ($canPlace) {
                                for ($i = 0; $i < $wordLength; $i++) {
                                    $grid[$row + $i][$col] = $word[$i];
                                }
                                return true;
                            }
                        }

                        if ($direction === 'diagonal' && $row + $wordLength <= $gridSize && $col + $wordLength <= $gridSize) {
                            $canPlace = true;
                            for ($i = 0; $i < $wordLength; $i++) {
                                if ($grid[$row + $i][$col + $i] !== '' && $grid[$row + $i][$col + $i] !== $word[$i]) {
                                    $canPlace = false;
                                    break;
                                }
                            }
                            if ($canPlace) {
                                for ($i = 0; $i < $wordLength; $i++) {
                                    $grid[$row + $i][$col + $i] = $word[$i];
                                }
                                return true;
                            }
                        }

                        $attempts++;
                    }

                    return false; // Failed to place word after max attempts
                }

                // Generate the grid with data-x and data-y attributes for position tracking
                for ($i = 0; $i < $gridSize; $i++) {
                    for ($j = 0; $j < $gridSize; $j++) {
                        echo "<div class='letter' data-x='$j' data-y='$i' data-letter='" . htmlspecialchars($grid[$i][$j]) . "'>" . $grid[$i][$j] . "</div>";
                    }
                }
                ?>
            </div>

            <ul id="wordList" class="word-list">
                <?php
                // Display word list at the bottom and style found words
                foreach ($wordList as $word) {
                    echo "<li class='word' id='word-" . strtolower($word) . "'>" . $word . "</li>";
                }
                ?>
            </ul>
            <div id="message"></div> <!-- Add message element for feedback -->

            <div class="returnhome" style="text-align: center; margin-top: 2rem;">
                <a href="index.php" class="returnbutton">Back to Games</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <p>© 2024 Harzarian</p>
            <a href="../about_us.html">About Us</a> | <a href="../contact.php">Contact Us</a> | 
            <a href="../cookies.html">Cookies Policy</a> | <a href="../privacy_policy.html">Privacy Policy</a>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wordsearch = document.getElementById('wordsearch');
            const message = document.getElementById('message');
            const selectedCells = [];
            const foundWords = [];
            let currentWord = "";
            
            // Clear any previous messages
            message.textContent = "";

            wordsearch.addEventListener('click', function(event) {
                const cell = event.target;

                if (cell.classList.contains('letter')) {
                    const x = parseInt(cell.getAttribute('data-x'));
                    const y = parseInt(cell.getAttribute('data-y'));
                    const letter = cell.getAttribute('data-letter');

                    // If the cell is already selected, deselect it and all cells after it
                    const index = selectedCells.findIndex(selectedCell => selectedCell.x === x && selectedCell.y === y);
                    if (index !== -1) {
                        // Remove all cells from this point forward
                        const removedCells = selectedCells.splice(index);
                        removedCells.forEach(removedCell => {
                            const cellElement = document.querySelector(`[data-x="${removedCell.x}"][data-y="${removedCell.y}"]`);
                            if (cellElement) {
                                cellElement.classList.remove('selected');
                            }
                        });
                        currentWord = selectedCells.map(cell => cell.letter).join('');
                    } else {
                        // Add cell to selection if it's connected to the previous cell or it's the first cell
                        const isAdjacent = selectedCells.length === 0 || 
                            (Math.abs(selectedCells[selectedCells.length - 1].x - x) <= 1 && 
                             Math.abs(selectedCells[selectedCells.length - 1].y - y) <= 1);
                        
                        if (isAdjacent) {
                            cell.classList.add('selected');
                            selectedCells.push({x, y, letter});
                            currentWord += letter;
                        } else {
                            // Not adjacent, start new selection
                            clearSelection();
                            cell.classList.add('selected');
                            selectedCells.push({x, y, letter});
                            currentWord = letter;
                        }
                    }

                    // Check if word matches a word in the list
                    checkWord();
                }
            });

            function checkWord() {
                const words = <?php echo json_encode(array_map('strtolower', $wordList)); ?>;
                const wordToCheck = currentWord.toLowerCase();
                
                // Check if the current word matches any word in the list
                if (words.includes(wordToCheck) && !foundWords.includes(wordToCheck)) {
                    foundWords.push(wordToCheck);
                    
                    // Mark cells as found
                    selectedCells.forEach(cell => {
                        const cellElement = document.querySelector(`[data-x="${cell.x}"][data-y="${cell.y}"]`);
                        if (cellElement) {
                            cellElement.classList.remove('selected');
                            cellElement.classList.add('found');
                        }
                    });

                    // Mark word as found in the list
                    const wordElement = document.getElementById(`word-${wordToCheck}`);
                    if (wordElement) {
                        wordElement.classList.add('found');
                    }

                    message.textContent = `You found the word: ${currentWord.toUpperCase()}!`;
                    
                    // Clear selection
                    selectedCells.length = 0;
                    currentWord = "";
                    
                    // Check if all words are found
                    if (foundWords.length === words.length) {
                        message.textContent = 'Congratulations! You found all the words!';
                    }
                }
            }

            function clearSelection() {
                selectedCells.forEach(cell => {
                    const cellElement = document.querySelector(`[data-x="${cell.x}"][data-y="${cell.y}"]`);
                    if (cellElement && !cellElement.classList.contains('found')) {
                        cellElement.classList.remove('selected');
                    }
                });
                selectedCells.length = 0;
                currentWord = "";
                message.textContent = "";
            }
        });
    </script>
</body>
</html>