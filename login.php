<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker - Login</title>
    <link rel="icon" href="user/img/logo2.png" type="image/png">
    <link rel="stylesheet" href="user/css/login.css?v=1.0">
</head>

<body>
    <div class="wrapper">

        <div class="login-container">
            <div class="login-card">
                <h1>Login</h1>
                <p>Log in to continue your Finance Tracker.</p>
                <form id="loginForm" action="login_process.php" method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username or email"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="login-button">Login</button>
                </form>
                <p id="error-message" style="color: red;"></p>
                <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
                <div class="signup-link">
                    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>