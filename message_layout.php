<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <link rel="icon" href="user/img/logo2.png" type="image/png">
    <link rel="stylesheet" href="user/css/login.css?v=1.0">
    <style>

    </style>
</head>

<body>
    <div class="wrapper">
        <div class="login-container">
            <div class="login-card">
                <?php
                // Ambil tipe dan pesan dari URL
                $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'info';
                $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'No message available';
                $context = isset($_GET['context']) ? htmlspecialchars($_GET['context']) : 'default';

                // Tampilkan judul berdasarkan tipe pesan
                switch ($type) {
                    case 'success':
                        echo "<h1 class='success'>Success</h1>";
                        break;
                    case 'error':
                        echo "<h1 class='error'>Error</h1>";
                        break;
                    default:
                        echo "<h1 class='info'>Information</h1>";
                }

                // Tampilkan pesan
                echo "<p class='$type'>$message</p>";
                ?>

                <!-- Tautan ke login atau signup -->
                <div>
                    <?php 
                    // Pastikan parameter context diterima
                    if ($context === 'login-failed') { ?>
                    <a href="login.php" class="login-button">Try Again</a>
                    <?php } elseif ($context === 'signup-failed') { ?>
                    <a href="signup.php" class="signup-button">Try Again</a>
                    <?php } elseif ($context === 'signup-success') { ?>
                    <a href="login.php" class="login-button">Go to Login</a>
                    <?php } else { ?>
                    <a href="login.php" class="login-button">Go to Login</a>
                    <a href="signup.php" class="signup-button">Sign Up</a>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</body>

</html>