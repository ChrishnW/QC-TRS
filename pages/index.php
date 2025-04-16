<?php include '../include/header.php'; ?>

<div class="container-fluid">
    <h1>Guess the Number</h1>
    <p id="message">Enter a number between 1 and 15:</p>
    <input type="number" id="guess" />
    <button onclick="checkGuess()" id="checkGuess">Submit Guess</button>
    <button onclick="restartGame()" id="restartButton" style="display: none;">Restart Game</button>
    <p id="result"></p>
</div>

<script>
    let randomNumber = Math.floor(Math.random() * 15) + 1;
    let attempts = 0;
    const maxAttempts = 5;

    document.getElementById("guess").addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            checkGuess();
        }
    });

    function checkGuess() {
        const userGuess = Number(document.getElementById("guess").value);
        const result = document.getElementById("result");

        if (userGuess === randomNumber) {
            result.textContent = "Congratulations! You guessed the correct number!";
            document.getElementById("guess").disabled = true; // Disable input on correct guess
            document.getElementById("restartButton").style.display = "inline"; // Show Restart button
        } else if (userGuess < randomNumber) {
            result.textContent = "Too low! Try again.";
        } else {
            result.textContent = "Too high! Try again.";
        }

        if (attempts >= maxAttempts) {
            result.textContent = `Game over! The correct number was ${randomNumber}.`;
            document.getElementById("guess").disabled = true; // Disable input after max attempts
            document.getElementById("checkGuess").style.display = "none"; 
            document.getElementById("restartButton").style.display = "inline"; // Show Restart button
        } else {
            attempts++;
        }
    }

    function restartGame() {
        randomNumber = Math.floor(Math.random() * 15) + 1; // Generate a new random number
        attempts = 0; // Reset attempts
        document.getElementById("guess").disabled = false; // Enable input field
        document.getElementById("guess").value = ""; // Clear input field
        document.getElementById("result").textContent = ""; // Clear result text
        document.getElementById("checkGuess").style.display = "inline"; 
        document.getElementById("restartButton").style.display = "none"; // Hide Restart button
    }
</script>

<?php include '../include/footer.php'; ?>