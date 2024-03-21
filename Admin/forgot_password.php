<?php

function checkAdminExist($email)
{
    $db = new SQLite3("admin_database.db");
    if (!$db)
        return false;

    $sql = 'SELECT email FROM Login WHERE email = :email';
    $stmt = $db->prepare($sql); // Prepare the SQL statement
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);

    // Execute the SQL statement
    $res = $stmt->execute();
    
    // Fetching array
    if ($stmt) {
        $row = $res->fetchArray(SQLITE3_NUM);
        if (!empty($row)) { // If the row is not empty, admin with provided email exists
            return true;
        }
    }
    
    return false; // Admin with provided email does not exist
}
 
function resetPassword($email, $password)
{
    $db = new SQLite3("admin_database.db");
    if (!$db)
        return false;

    $sql = 'UPDATE Login SET password = :password WHERE email = :email';
    $stmt = $db->prepare($sql); // Prepare the SQL statement
    $stmt->bindParam(':password', $password, SQLITE3_TEXT);
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);

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
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    if (checkAdminExist($email)) {
        // Reset the password
        if (resetPassword($email, $newPassword)) {
            echo "Password for admin $email has been successfully reset.";
        } else {
            echo "Failed to reset password.Please try again.";
        }
    } else {
        echo "Admin with email id $email not exist.";
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
        <label for="email">Enter your emailid:</label>
        <input type="text" id="email" name="email" required><br><br>
        <label for="new_password">Enter your New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
