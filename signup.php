<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Signup</title>
    <link rel="icon" href="user/img/logo2.png" type="image/png">
    <link rel="stylesheet" href="user/css/login.css?v=1.0">
</head>

<body>
    <div class="wrapper">

        <div class="login-container">
            <div class="login-card">
                <h1>Signup</h1>
                <p>Create an account to use Finance Tracker.</p>
                <form id="signupForm" action="signup_process.php" method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="login-button">Sign Up</button>
                </form>
                <p id="error-message" style="color: red;"></p>
                <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
                <div class="login-link">
                    <p>Already have an account? <a href="login.php">Log in</a></p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>