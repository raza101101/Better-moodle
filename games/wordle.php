<?php
require_once('../../config.php');
require_login();

$PAGE->set_url(new moodle_url('/local/better_moodle_games/wordle.php'));
$PAGE->set_title('Wordle Clone');
$PAGE->set_heading('Wordle Clone');
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
?>

<h2>Wordle Clone</h2>
<p>Guess the five-letter word!</p>

<div id="game-area">
    <input type="text" id="guess" maxlength="5" placeholder="Enter a word">
    <button onclick="checkGuess()">Submit</button>
    <p id="feedback"></p>
</div>

<script>
    const words = ["ascii", "cache", "debug", "email", "error", "logic", "layer", "query", "stack", "virus", "write"];
    let targetWord = words[Math.floor(Math.random() * words.length)];
    function checkGuess() {
        let userGuess = document.getElementById("guess").value.toLowerCase();
        let feedback = "";
        if (userGuess.length !== 5) {
            feedback = "Please enter a five-letter word.";
        } else if (userGuess === targetWord) {
            feedback = "Correct! Well done.";
        } else {
            feedback = "Incorrect, try again.";
        }
        document.getElementById("feedback").innerText = feedback;
    }
</script>

<?php
echo $OUTPUT->footer();
?>
