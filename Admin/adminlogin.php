<?php

$db = new SQLite3("admin_database.db");
$stmt = $db->prepare('SELECT username, password FROM Login WHERE username=:username AND password=:password');

$stmt->bindParam(':username', $_POST['username'], SQLITE3_TEXT);
$stmt->bindParam(':password', $_POST['password'], SQLITE3_TEXT);

$result = $stmt->execute();

$users = [];

while ($row = $result->fetchArray()) {
    $users[] = $row;
}

if (isset($_POST['login'])) {
    if ($users != null) {
        header("Location: admincrud.php");
    } else {
        echo '<script>alert("Wrong Username or Password")</script>';
    }
}
?>

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
            <form method="post">
                <div class="form-group">
                    <label for="Username">Username</label>
                    <input required type="text" name="username" id="username" />
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
                    <input required type="password" name="password" id="password" />
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