<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for booking details
session_start();

// Define text file to store bookings
$bookings_file = "bookings.txt";
$errors = [];
$success = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get and sanitize input
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $session = trim($_POST["session"] ?? "");
    $date = trim($_POST["date"] ?? "");
    $time = trim($_POST["time"] ?? "");
    $comments = trim($_POST["comments"] ?? "");

    // Validate inputs (server-side)
    if (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (empty($session)) {
        $errors[] = "Please select a session type.";
    }
    if (empty($date) || $date < date("Y-m-d")) {
        $errors[] = "Please select a valid date.";
    }
    if (empty($time)) {
        $errors[] = "Please select a time.";
    }

    // If no errors, save booking to text file
    if (empty($errors)) {
        $data = "$name | $email | $session | $date | $time | $comments" . PHP_EOL;
        file_put_contents($bookings_file, $data, FILE_APPEND);

        // Store details in session for confirmation page
        $_SESSION["booking"] = compact("name", "email", "session", "date", "time", "comments");

        // Save name and email in cookies for 30 days
        setcookie("user_name", $name, time() + (86400 * 30), "/");
        setcookie("user_email", $email, time() + (86400 * 30), "/");

        // Redirect to confirmation page
        header("Location: confirmation.php");
        exit();
    }
}

// Prefill from cookies if not submitted
$prefill_name = $_COOKIE["user_name"] ?? ($_POST["name"] ?? "");
$prefill_email = $_COOKIE["user_email"] ?? ($_POST["email"] ?? "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Session | SingZone</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: white;
        }
        header {
            background-color: #e91e63;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        nav a {
            color: white;
            margin-left: 1.5rem;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            margin: 0 auto;
        }
        h2 { text-align: center; color: #e91e63; }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        input, select, textarea, button {
            padding: 0.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background-color: #e91e63;
            color: white;
            cursor: pointer;
        }
        .error {
            background: #ff4e50;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header>
    <a href="index.html" style="text-decoration: none; color: white;"><h1>SingZone</h1></a>
    <nav>
        <a href="index.html#events">Events</a>
        <a href="index.html#book">Book Now</a>
        <a href="login.html">Login</a>
    </nav>
</header>

<div class="container">
    <h2>Book a Session</h2>
    <p style="text-align:center;">Select your preferred session type and provide your details. We'll handle the rest!</p>

    <!-- Display errors -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($prefill_name) ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($prefill_email) ?>">

        <label for="session">Choose Session Type</label>
        <select id="session" name="session">
            <option value="">-- Select a Session --</option>
            <option value="openmic" <?= (($session ?? '') === 'openmic') ? 'selected' : '' ?>>🎙 Open Mic</option>
            <option value="karaoke" <?= (($session ?? '') === 'karaoke') ? 'selected' : '' ?>>🎵 Karaoke Room</option>
        </select>

        <label for="date">Preferred Date</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($_POST['date'] ?? '') ?>">

        <label for="time">Preferred Time</label>
        <input type="time" id="time" name="time" value="<?= htmlspecialchars($_POST['time'] ?? '') ?>">

        <label for="comments">Additional Notes</label>
        <textarea id="comments" name="comments" rows="4"><?= htmlspecialchars($_POST['comments'] ?? '') ?></textarea>

        <button type="submit">Confirm Booking</button>
    </form>
</div>

</body>
</html>
