<?php
session_start();
$db = new SQLite3("databasepractice.db");

// Determine node ID from either GET or POST
$nodeId = isset($_GET['node_id']) ? intval($_GET['node_id']) : (isset($_POST['node_id']) ? intval($_POST['node_id']) : 0);

if ($nodeId > 0) {
    // Check for references in the Edges table
    $checkStmt = $db->prepare('SELECT COUNT(*) AS count FROM Edges WHERE start_node_id = :nodeId OR end_node_id = :nodeId');
    $checkStmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $result = $checkStmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row['count'] > 0) {
        // There are references to this node in Edges table
        $_SESSION['flash_message'] = "This node is referenced in edges. Please update or delete these edges before deleting the node.";
    } else {
        // Safe to delete the node as there are no references in the Edges table
        $deleteStmt = $db->prepare('DELETE FROM Node WHERE node_id = :nodeId');
        $deleteStmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
        if ($deleteStmt->execute()) {
            $_SESSION['flash_message'] = "Node deleted successfully.";
        } else {
            $_SESSION['flash_message'] = "Error deleting node.";
        }
    }

    // Redirect back to the node list or appropriate page
    header("Location: admincrud.php");
    exit();
} else {
    // If nodeId is not set or invalid, redirect or show an error
    $_SESSION['flash_message'] = "Invalid node ID.";
    header("Location: admincrud.php");
    exit();
}
