<?php
session_start();

// Check if session is expired (5 minutes = 300 seconds)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // Session expired
    session_unset();
    session_destroy();
    $booking = null;
} else {
    $_SESSION['LAST_ACTIVITY'] = time(); // Update timestamp
    $booking = $_SESSION["booking"] ?? null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking Confirmed | SingZone</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            padding: 4rem 1rem;
        }
        .confirmation-box {
            background-color: #1f1f1f;
            display: inline-block;
            padding: 2rem 3rem;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(255, 204, 0, 0.4);
            max-width: 600px;
        }
        h1 {
            color: #ffcc00;
            font-size: 2rem;
        }
        p {
            font-size: 1.1rem;
            margin-top: 1rem;
        }
        a {
            display: inline-block;
            margin-top: 2rem;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
        }
        a:hover {
            background-color: #e6b800;
        }
        .booking-details {
            text-align: left;
            margin-top: 1.5rem;
        }
    </style>
</head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2EC9KMBLMD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2EC9KMBLMD');
</script>
<body>
    <div class="confirmation-box">
        <h1>🎉 Booking Confirmed!</h1>
        <p>Thank you for booking with <strong>SingZone</strong>.</p>
        <p>Your session has been recorded successfully.</p>

        <div class="booking-details">
            <?php if ($booking): ?>
                <p><strong>Name:</strong> <?= htmlspecialchars($booking["name"]) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($booking["email"]) ?></p>
                <p><strong>Session:</strong> <?= htmlspecialchars($booking["session"]) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($booking["date"]) ?></p>
                <p><strong>Time:</strong> <?= htmlspecialchars($booking["time"]) ?></p>
                <?php if (!empty($booking["comments"])): ?>
                    <p><strong>Comments:</strong> <?= htmlspecialchars($booking["comments"]) ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p style="color:red;">⚠️ Your session has expired or booking information is not available.</p>
            <?php endif; ?>
        </div>

        <a href="index.html">Back to Home</a>
    </div>
</body>
</html>
