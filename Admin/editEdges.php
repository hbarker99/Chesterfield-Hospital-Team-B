<?php
$db = new SQLite3("databasepractice.db");
session_start();

$nodeId = isset($_POST['node_id']) ? intval($_POST['node_id']) : null;
$edges = [];

if ($nodeId) {
    $stmt = $db->prepare('SELECT edge_id, start_node_id, end_node_id,distance FROM Edges WHERE start_node_id = :nodeId');
    $stmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $edgesResult = $stmt->execute();

    while ($edge = $edgesResult->fetchArray(SQLITE3_ASSOC)) {
        $edges[] = $edge;
    }
}

if (isset($_POST['updateEdges'])) {
    $edgeIds = $_POST['edgeId'];
    $newSources = $_POST['newSource'];
    $newDestinations = $_POST['newDestination'];
    $newDistances = $_POST['newDistance'];

    for ($i = 0; $i < count($edgeIds); $i++) {
        $edgeId = intval($edgeIds[$i]);
        $newSource = intval($newSources[$i]);
        $newDestination = intval($newDestinations[$i]);
        $newDistance = floatval($newDistances[$i]);

        $updateStmt = $db->prepare('UPDATE Edges SET start_node_id = :newSource, end_node_id = :newDestination, distance = :newDistance WHERE edge_id = :edgeId');
        $updateStmt->bindValue(':newSource', $newSource, SQLITE3_INTEGER);
        $updateStmt->bindValue(':newDestination', $newDestination, SQLITE3_INTEGER);
        $updateStmt->bindValue(':newDistance', $newDistance, SQLITE3_INTEGER);
        $updateStmt->bindValue(':edgeId', $edgeId, SQLITE3_INTEGER);
        $updateStmt->execute();
    }
    $_SESSION['flash_message'] = 'Location updated successfully.';
    header("Location: admincrud.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Edges</title>
    <link rel="stylesheet" href="../editEdges.css">
</head>
<body>
    <header>
        <h1>Edit Edges for Node: <?php echo htmlspecialchars($nodeId); ?></h1>
    </header>

    <main>
    <form method="post">
    <input type="hidden" name="node_id" value="<?php echo htmlspecialchars($nodeId); ?>">
    <?php foreach ($edges as $edge): ?>
        <div>
            <label>Edge ID: <?php echo $edge['edge_id']; ?></label>
            <input type="hidden" name="edgeId[]" value="<?php echo $edge['edge_id']; ?>">

            <label>Source Node ID:</label>
            <input type="text" name="newSource[]" value="<?php echo $edge['start_node_id']; ?>">

            <label>Destination Node ID:</label>
            <input type="text" name="newDestination[]" value="<?php echo $edge['end_node_id']; ?>">

            <label>Distance:</label>
            <input type="text" name="newDistance[]" value="<?php echo $edge['distance']; ?>">
        </div>
    <?php endforeach; ?>
    <button type="submit" name="updateEdges">Update Edges</button>
</form>

        <a href="admincrud.php">Back to Nodes</a>
    </main>
</body>
</html>

