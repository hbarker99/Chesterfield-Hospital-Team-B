<?php

include("database.db");
     

function adminlogin($Username, $Password, $UserID)
{
    $db = connect();

    if (!$db)
        return false;

    $sql = 'SELECT Username From admin where Username = :Username and Password =:Password';
    $stmt = $db->prepare($sql); //prepare the sql statement
    $stmt->bindParam(':Username', $Username, SQLITE3_TEXT);
    $stmt->bindParam(':Password', $Password, SQLITE3_TEXT);

    //execute the sql statement
    $res = $stmt->execute();
    if ($stmt) {
        $row = $res->fetchArray(SQLITE3_NUM);
        if (empty($row)) {
            return false;
        } else {
            return true;
        }
    }
    return false;
}
?>