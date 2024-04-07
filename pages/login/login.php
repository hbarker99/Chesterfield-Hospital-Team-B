<?php
$db = new SQLite3($_SERVER['DOCUMENT_ROOT'] . "/pages/login/admin_database.db");
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
        $_SESSION['user'] = $users[0]['username'];
        header("Location: /map");
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
    <link rel="stylesheet" href="/pages/login/login.css">
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
                </div>
            </form>
        </div>
    </div>
</body>

</html>