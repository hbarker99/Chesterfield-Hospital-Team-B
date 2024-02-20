<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chesterfield Royal Hospital Admin Login</title>
    <link rel="stylesheet" href="../adminlogin.css">
</head>
<body>
    <div class="login-wrapper">
        <h1>Admin Login</h1>
        <div class="login-form">
            <?php
            // Placeholder for server-side logic
            ?>

            <form method="post">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input required type="text" name="Username" id="Username" />
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
                    <input required type="password" name="Password" id="Password" />
                </div>

                <div class="form-actions">
                    <input type="submit" value="Login" name="login" id="login" />
                    <a href="./forgot_password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
