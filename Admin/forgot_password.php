<?php

function checkAdminExist($username)
{
    $db = new SQLite3("admin_database.db");
    if (!$db)
        return false;

    $sql = 'SELECT username FROM Login WHERE username = :username';
    $stmt = $db->prepare($sql); // Prepare the SQL statement
    $stmt->bindParam(':username', $username, SQLITE3_TEXT);

    // Execute the SQL statement
    $res = $stmt->execute();
    
    // Fetching array
    if ($stmt) {
        $row = $res->fetchArray(SQLITE3_NUM);
        if (!empty($row)) { // If the row is not empty, admin with provided username exists
            return true;
        }
    }
    
    return false; // Admin with provided username does not exist
}
 
function resetPassword($username, $password)
{
    $db = new SQLite3("admin_database.db");
    if (!$db)
        return false;

    $sql = 'UPDATE Login SET password = :password WHERE username = :username';
    $stmt = $db->prepare($sql); // Prepare the SQL statement
    $stmt->bindParam(':password', $password, SQLITE3_TEXT);
    $stmt->bindParam(':username', $username, SQLITE3_TEXT);

    // Execute the SQL statement
    $result = $stmt->execute();

    // Check if the password was successfully updated
    if ($result) {
        return true;
    }

    return false;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];

    if (checkAdminExist($username)) {
        // Reset the password
        if (resetPassword($username, $newPassword)) {
            echo "Password for admin $username has been successfully reset.";
        } else {
            echo "Failed to reset password. Please try again.";
        }
    } else {
        echo "Admin with username $username does not exist.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
    <link rel="stylesheet" href="../admin.css">
    
</head>
<body>
    <h2>Reset Admin Password</h2>
    <form method="post">
        <label for="username">Admin Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
