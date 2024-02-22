<?php
session_start();
$db = new SQLite3("databasepractice.db");

if (isset($_POST['delete']) && isset($_POST['node_id'])) {
    $nodeId = intval($_POST['node_id']);

    // Prepare the delete statement to remove the node
    $stmt = $db->prepare('DELETE FROM Node WHERE node_id = :nodeId');
    $stmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);

    if($stmt->execute()) {

        $_SESSION['flash_message'] = "Location deleted successfully.";
    } else {

        $_SESSION['flash_message'] = "Error deleting Location.";
    }

    header("Location: admincrud.php");
    exit();
}
?>