<?php
$db = new SQLite3("databasepractice.db");
var_dump($_POST['node_id']);


$edges = [];
$nodeId = isset($_POST['node_id']) ? intval($_POST['node_id']) : null;

if ($nodeId) {
    $stmt = $db->prepare('SELECT edge_id, end_node_id FROM Edges WHERE start_node_id = :nodeId');
    $stmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $edgesResult = $stmt->execute();

    while ($edge = $edgesResult->fetchArray(SQLITE3_ASSOC)) {
        $edges[] = $edge;
    }
}

if (isset($_POST['updateEdges'])) {
    $edgeIds = $_POST['edgeId'];
    $newDestinations = $_POST['newDestination'];

    foreach ($edgeIds as $index => $edgeId) {
        $newDestination = intval($newDestinations[$index]);
        $updateStmt = $db->prepare('UPDATE Edges SET end_node_id = :destination WHERE edge_id = :edgeId');
        $updateStmt->bindValue(':destination', $newDestination, SQLITE3_INTEGER);
        $updateStmt->bindValue(':edgeId', intval($edgeId), SQLITE3_INTEGER);
        $updateStmt->execute();
    }

    echo "<p>Edges updated successfully.</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Edges</title>
    <link rel="stylesheet" href="../admincrud.css">
</head>
<body>
    <header>
        <h1>Edit Edges for Node: <?php echo htmlspecialchars($nodeId); ?></h1>
    </header>

    <main>
        <?php if ($nodeId && count($edges) > 0): ?>
            <form method="post">
                <input type="hidden" name="node_id" value="<?php echo htmlspecialchars($nodeId); ?>">
                <?php foreach ($edges as $edge): ?>
                    <div>
                        <label for="edge-<?php echo $edge['edge_id']; ?>">Edge to Node ID:</label>
                        <input type="text" name="newDestination[]" value="<?php echo htmlspecialchars($edge['end_node_id']); ?>">
                        <input type="hidden" name="edgeId[]" value="<?php echo $edge['edge_id']; ?>">
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="updateEdges">Update Edges</button>
            </form>
        <?php else: ?>
            <p>No edges found for the selected node.</p>
        <?php endif; ?>
    </main>
</body>
</html>

