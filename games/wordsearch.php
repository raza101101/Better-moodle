<?php
session_start();

// List of computer science related words
$words = ['algorithm', 'binary', 'database', 'html', 'css', 'javascript', 'python', 'function', 'variable', 'loop'];

$gridSize = 10; // Size of the word search grid
$grid = array_fill(0, $gridSize, array_fill(0, $gridSize, '')); // Create an empty grid

// Helper function to insert a word into the grid
function insertWordIntoGrid($word, $grid) {
    $wordLength = strlen($word);
    $directions = [
        [0, 1], // Horizontal
        [1, 0], // Vertical
        [1, 1], // Diagonal down-right
        [-1, 1], // Diagonal up-right
    ];

    $placed = false;
    while (!$placed) {
        $direction = $directions[array_rand($directions)];
        $startX = rand(0, $GLOBALS['gridSize'] - 1);
        $startY = rand(0, $GLOBALS['gridSize'] - 1);
        $endX = $startX + ($direction[0] * ($wordLength - 1));
        $endY = $startY + ($direction[1] * ($wordLength - 1));

        // Ensure the word fits within the grid bounds
        if ($endX >= 0 && $endX < $GLOBALS['gridSize'] && $endY >= 0 && $endY < $GLOBALS['gridSize']) {
            // Check if the word can fit
            $canPlace = true;
            for ($i = 0; $i < $wordLength; $i++) {
                $x = $startX + ($direction[0] * $i);
                $y = $startY + ($direction[1] * $i);
                if ($grid[$x][$y] !== '') {
                    $canPlace = false;
                    break;
                }
            }

            if ($canPlace) {
                // Place the word
                for ($i = 0; $i < $wordLength; $i++) {
                    $x = $startX + ($direction[0] * $i);
                    $y = $startY + ($direction[1] * $i);
                    $grid[$x][$y] = strtoupper($word[$i]); // Capitalize the word letters
                }
                $placed = true;
            }
        }
    }
    return $grid;
}

// Insert words into the grid
foreach ($words as $word) {
    $grid = insertWordIntoGrid($word, $grid);
}

// Fill empty spaces with random letters
$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
for ($x = 0; $x < $gridSize; $x++) {
    for ($y = 0; $y < $gridSize; $y++) {
        if ($grid[$x][$y] === '') {
            $grid[$x][$y] = $alphabet[rand(0, strlen($alphabet) - 1)];
        }
    }
}

// Store the grid in the session for later comparison
$_SESSION['grid'] = $grid;
$_SESSION['words'] = $words;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Science Word Search Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        .wordsearch {
            display: grid;
            grid-template-columns: repeat(<?php echo $gridSize; ?>, 40px);
            grid-gap: 5px;
            justify-content: center;
            margin-top: 20px;
        }

        .wordsearch div {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: 1px solid #ccc;
            cursor: pointer;
            text-transform: uppercase; /* Capitalize the text */
        }

        .selected {
            background-color: yellow;
        }

        .found {
            background-color: lightgreen;
        }

        .word-list {
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
        }

        .word-list li {
            display: inline-block;
            margin: 5px;
            font-size: 18px;
        }

        .crossed-out {
            text-decoration: line-through;
            color: gray;
        }

        #message {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Computer Science Word Search Game</h1>
    <div id="message"></div>
    
    <div class="wordsearch" id="wordsearch">
        <?php
        // Generate the grid on the page
        for ($x = 0; $x < $gridSize; $x++) {
            for ($y = 0; $y < $gridSize; $y++) {
                echo "<div data-x='$x' data-y='$y'>" . htmlspecialchars($grid[$x][$y]) . "</div>";
            }
        }
        ?>
    </div>

    <ul class="word-list" id="wordList">
        <?php
        // Display the list of words to find
        foreach ($words as $word) {
            echo "<li id='word-$word'>$word</li>";
        }
        ?>
    </ul>

    <script>
        const wordsearch = document.getElementById('wordsearch');
        const message = document.getElementById('message');
        const selectedCells = [];
        const foundWords = [];
        let currentWord = "";

        wordsearch.addEventListener('click', function(event) {
            const cell = event.target;

            if (cell.tagName === 'DIV') {
                const x = parseInt(cell.getAttribute('data-x'));
                const y = parseInt(cell.getAttribute('data-y'));
                const letter = cell.innerText;

                // If the cell is already selected, deselect it
                if (selectedCells.some(cell => cell.x === x && cell.y === y)) {
                    cell.classList.remove('selected');
                    const index = selectedCells.findIndex(cell => cell.x === x && cell.y === y);
                    selectedCells.splice(index, 1);
                    currentWord = currentWord.slice(0, -1);
                } else {
                    // Select the cell
                    cell.classList.add('selected');
                    selectedCells.push({x, y, letter});
                    currentWord += letter;
                }

                // Check if word matches a word in the list
                if (currentWord.length > 1) {
                    checkWord();
                }
            }
        });

        function checkWord() {
            const word = currentWord.toLowerCase();
            const words = <?php echo json_encode($words); ?>;

            if (words.includes(word) && !foundWords.includes(word)) {
                foundWords.push(word);
                selectedCells.forEach(cell => {
                    const cellElement = document.querySelector(`[data-x="${cell.x}"][data-y="${cell.y}"]`);
                    cellElement.classList.add('found');
                });

                // Cross out the found word in the list
                document.getElementById(`word-${currentWord}`).classList.add('crossed-out');
                message.textContent = `You found the word: ${currentWord}!`;

                // Clear selected cells
                selectedCells.length = 0;
                currentWord = "";

                if (foundWords.length === words.length) {
                    message.textContent = 'Congratulations! You found all the words!';
                }
            }
        }
    </script>
</body>
</html>
