<?php
session_start();
include("../php/connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../php/login.php");
    exit();
}

// questions
$questions = [
    [
        'question' => 'What is 3x + 5 = 11? Solve for x.',
        'choices' => [1, 2, 3, 4],
        'correct' => 2
    ],
    [
        'question' => 'What is 4x - 7 = 5? Solve for x.',
        'choices' => [1, 2, 3, 4],
        'correct' => 3
    ],
    [
        'question' => 'What is 4x + 9 = 21? Solve for x.',
        'choices' => [3, 4, 5, 6],
        'correct' => 1
    ],
    [
        'question' => 'What is 5x - 8 = 12? Solve for x.',
        'choices' => [3, 4, 5, 6],
        'correct' => 2
    ],
    [
        'question' => 'What is 6x + 2 = 20? Solve for x.',
        'choices' => [3, 4, 5, 6],
        'correct' => 1
    ]
];

// Randomize questions order
shuffle($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algebra Quiz</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #game-area {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .question {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .choices {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .choice {
            background-color: #d3d6da;
            border: none;
            padding: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            border-radius: 4px;
        }

        .choice:hover {
            background-color: #b0b3b8;
        }

        .button {
            padding: 10px 20px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #004080;
        }

        #game-summary {
            margin-top: 2rem;
            padding: 1rem;
            font-size: 1.2rem;
            border: 1px solid #ccc;
        }

        .selected {
            background-color: #c9b458;
        }

        .correct {
            background-color: #6aaa64;
        }

        .incorrect {
            background-color: #ff4d4d;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="../php/index.php"><h1>Harzarian</h1></a>    
            <nav>
                <a style="color: white;">Welcome <?php echo htmlspecialchars($_SESSION['firstName']); ?></a> | 
                <a href="logout.php">Log Out</a>
            </nav>
        </div>
    </header>

    <main>
        <h2 style="text-align: center;">Numbers Game</h2>
        <p style="text-align: center; margin-bottom: 2rem;">Select The Correct Answer!</p>
        <div id="game-area" class="card">
            <div id="question-area"></div>
            <button class="button" id="next-button" onclick="nextQuestion()" disabled>Next Question</button>
            <div id="game-summary"></div>
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
        const questions = <?php echo json_encode($questions); ?>;
        let currentQuestionIndex = 0;
        let score = 0;
        let startTime = Date.now();
        let timerInterval;

        function loadQuestion() {
            const question = questions[currentQuestionIndex];
            const questionArea = document.getElementById('question-area');
            questionArea.innerHTML = `
                <div class="question">${question.question}</div>
                <div class="choices">
                    ${question.choices.map((choice, index) => `
                        <button class="choice" onclick="selectAnswer(${index + 1}, this)">${choice}</button>
                    `).join('')}
                </div>
            `;
            startTimer();
        }

        function selectAnswer(selectedAnswer, buttonElement) {
            const question = questions[currentQuestionIndex];
            const correctAnswer = question.correct;

            // Add selected class to the selected button
            buttonElement.classList.add('selected');

            // Disable all buttons after selection
            const buttons = document.querySelectorAll('.choice');
            buttons.forEach(button => button.disabled = true);

            // Check if selected answer is correct
            if (selectedAnswer === correctAnswer) {
                buttonElement.classList.add('correct');
                score++;
            } else {
                buttonElement.classList.add('incorrect');
                // Highlight correct answer
                const correctButton = buttons[correctAnswer - 1];
                correctButton.classList.add('correct');
            }

            document.getElementById('next-button').disabled = false;
        }

        function nextQuestion() {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                loadQuestion();
                document.getElementById('next-button').disabled = true;
            } else {
                endGame();
            }
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                const elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('timer').textContent = `Time: ${elapsedTime}s`;
            }, 1000);
        }

        function endGame() {
            clearInterval(timerInterval);
            const elapsedTime = Math.floor((Date.now() - startTime) / 1000);

            let gameSummary = '';
            if (score >= 3) {
                gameSummary = `
                    <h3>Success!</h3>
                    <p>You got ${score} out of ${questions.length} correct.</p>
                    <p>Time Taken: ${elapsedTime} seconds.</p>
                `;
            } else {
                gameSummary = `
                    <h3>Better Luck Next Time</h3>
                    <p>You got ${score} out of ${questions.length} correct.</p>
                    <p>Time Taken: ${elapsedTime} seconds.</p>
                `;
            }
            document.getElementById('game-summary').innerHTML = gameSummary;
            document.getElementById('next-button').style.display = 'none';
        }

        loadQuestion();
    </script>
</body>
</html>

