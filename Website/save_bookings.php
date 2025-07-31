<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $session = trim($_POST['session']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $comments = trim($_POST['comments']);

    // Basic validation
    if (strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($session) || empty($date) || empty($time)) {
        die("Invalid form data. Please go back and fill all fields correctly.");
    }

    // Format the booking info
    $booking = "Name: $name | Email: $email | Session: $session | Date: $date | Time: $time | Comments: $comments\n";

    // Save to bookings.txt
    file_put_contents("bookings.txt", $booking, FILE_APPEND);

    // Redirect to confirmation page
    header("Location: confirmation.php");
    exit();
} else {
    die("Invalid request method.");
}
?>
