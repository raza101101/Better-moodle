<?php


require_once('../../config.php'); //for harry

// Ensure user is logged in
require_login();

// Set up Moodle page
$PAGE->set_url(new moodle_url('/local/better_moodle_games/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Better Moodle Games');
$PAGE->set_heading('Better Moodle Games');
$PAGE->set_pagelayout('standard');

// Output starts
echo $OUTPUT->header();
?>

<style>
    .game-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .game-card {
        width: 200px;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        text-align: center;
        background: #f9f9f9;
    }
    .game-card a {
        text-decoration: none;
        font-weight: bold;
        color: #0073e6;
    }
</style>

<div class="game-container">
    <div class="game-card">
        <h3>Numbers Game</h3>
        <p>Test your arithmetic skills</p>
        <a href="numbers_game.php">Play Now</a>
    </div>
    <div class="game-card">
        <h3>Wordle Clone</h3>
        <p>Guess the five-letter word</p>
        <a href="wordle.php">Play Now</a>
    </div>
    <div class="game-card">
        <h3>Liverpool Hope Trivia</h3>
        <p>How well do you know the university?</p>
        <a href="trivia.php">Play Now</a>
    </div>
</div>

<?php
// Output ends
echo $OUTPUT->footer();
?>
