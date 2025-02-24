<?php
session_start();

// Example word list for the word search
$wordList = [
    "ARRAY", "LOOP", "FUNCTION", "ALGORITHM", "RECURSION", "VARIABLE", "OBJECT", "CLASS", "METHOD", "CONDITION"
];

// Create the grid
$gridSize = 10;  // 10x10 grid
$grid = generateGrid($gridSize, $wordList);

// Function to generate the word search grid
function generateGrid($gridSize, $wordList) {
    $grid = array_fill(0, $gridSize, array_fill(0, $gridSize, ''));

    // Randomly place words in the grid
    foreach ($wordList as $word) {
        placeWordInGrid($grid, $word, $gridSize);
    }

    // Fill remaining empty spaces with random letters
    for ($i = 0; $i < $gridSize; $i++) {
        for ($j = 0; $j < $gridSize; $j++) {
            if ($grid[$i][$j] === '') {
                $grid[$i][$j] = chr(rand(65, 90));  // Random capital letter
            }
        }
    }

    return $grid;
}

// Function to place a word in the grid at a random position
function placeWordInGrid(&$grid, $word, $gridSize) {
    $wordLength = strlen($word);
    $directions = ['horizontal', 'vertical', 'diagonal'];
    $direction = $directions[rand(0, count($directions) - 1)];
    
    // Try to find a valid position
    $placed = false;
    while (!$placed) {
        $row = rand(0, $gridSize - 1);
        $col = rand(0, $gridSize - 1);

        if ($direction == 'horizontal' && $col + $wordLength <= $gridSize) {
            $canPlace = true;
            for ($i = 0; $i < $wordLength; $i++) {
                if ($grid[$row][$col + $i] != '') {
                    $canPlace = false;
                    break;
                }
            }
            if ($canPlace) {
                for ($i = 0; $i < $wordLength; $i++) {
                    $grid[$row][$col + $i] = $word[$i];
                }
                $placed = true;
            }
        }

        if ($direction == 'vertical' && $row + $wordLength <= $gridSize) {
            $canPlace = true;
            for ($i = 0; $i < $wordLength; $i++) {
                if ($grid[$row + $i][$col] != '') {
                    $canPlace = false;
                    break;
                }
            }
            if ($canPlace) {
                for ($i = 0; $i < $wordLength; $i++) {
                    $grid[$row + $i][$col] = $word[$i];
                }
                $placed = true;
            }
        }

        if ($direction == 'diagonal' && $row + $wordLength <= $gridSize && $col + $wordLength <= $gridSize) {
            $canPlace = true;
            for ($i = 0; $i < $wordLength; $i++) {
                if ($grid[$row + $i][$col + $i] != '') {
                    $canPlace = false;
                    break;
                }
            }
            if ($canPlace) {
                for ($i = 0; $i < $wordLength; $i++) {
                    $grid[$row + $i][$col + $i] = $word[$i];
                }
                $placed = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Search Game</title>
    <script src="wordsearch.js"></script>  <!-- Link to the external JavaScript file -->
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            grid-template-rows: repeat(10, 40px);
            gap: 2px;
            margin-bottom: 20px;
        }
        .grid div {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: #f4f4f4;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .grid div.selected {
            background-color: yellow;
        }
        .grid div.correct {
            background-color: green;
            color: white;
        }
        .word-list {
            list-style-type: none;
            padding: 0;
        }
        .word-list li {
            font-size: 18px;
        }
        .word-list .found {
            text-decoration: line-through;
            color: gray;
        }
    </style>
</head>
<body>
    <h1>Word Search Game</h1>
    <div id="wordsearch">
        <div class="grid">
            <?php
            // Generate the grid using the PHP function and output the letters as divs
            foreach ($grid as $row) {
                foreach ($row as $letter) {
                    echo "<div class='letter' data-letter='" . $letter . "'>" . $letter . "</div>";
                }
            }
            ?>
        </div>

        <ul id="wordList" class="word-list">
            <?php
            // Display word list at the bottom and style found words
            foreach ($wordList as $word) {
                echo "<li class='word'>" . $word . "</li>";
            }
            ?>
        </ul>
    </div>

    <script>
        let selectedLetters = [];
        const wordList = <?php echo json_encode($wordList); ?>; // Pass PHP array to JavaScript
        let foundWords = [];

        const gridDivs = document.querySelectorAll('.grid div');
        gridDivs.forEach(div => {
            div.addEventListener('click', function() {
                const letter = div.getAttribute('data-letter');
                toggleLetterSelection(div, letter);
            });
        });

        function toggleLetterSelection(div, letter) {
            if (div.classList.contains('selected')) {
                div.classList.remove('selected');
                selectedLetters = selectedLetters.filter(l => l !== letter);
            } else {
                div.classList.add('selected');
                selectedLetters.push(letter);
            }

            checkWord();
        }

        function checkWord() {
            const selectedWord = selectedLetters.join('');
            if (wordList.includes(selectedWord) && !foundWords.includes(selectedWord)) {
                foundWords.push(selectedWord);
                crossOutWord(selectedWord);
                markWordCorrect(selectedWord);
            }
        }

        function crossOutWord(word) {
            const wordItems = document.querySelectorAll('.word');
            wordItems.forEach(item => {
                if (item.textContent === word) {
                    item.classList.add('found');
                }
            });
        }

        function markWordCorrect(word) {
            selectedLetters.forEach(letter => {
                const divs = document.querySelectorAll('.grid div');
                divs.forEach(div => {
                    if (div.textContent === letter && !div.classList.contains('correct')) {
                        div.classList.add('correct');
                    }
                });
            });
        }
    </script>
</body>
</html>

