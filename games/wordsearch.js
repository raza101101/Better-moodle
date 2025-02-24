const wordsearch = document.getElementById('wordsearch');
const message = document.getElementById('message');
const selectedCells = [];
const foundWords = [];
let currentWord = "";

wordsearch.addEventListener('click', function(event) {
    // Ensure the clicked element is a letter box (div) and not the parent container
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
