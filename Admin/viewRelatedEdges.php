<?php
session_start();
$db = new SQLite3("databasepractice.db");
$nodeId = isset($_GET['node_id']) ? intval($_GET['node_id']) : 0;

$stmt = $db->prepare('SELECT e.edge_id, ns.name AS start_node_name, ne.name AS end_node_name FROM Edges e JOIN Node ns ON e.start_node_id = ns.node_id JOIN Node ne ON e.end_node_id = ne.node_id WHERE e.start_node_id = :nodeId OR e.end_node_id = :nodeId');
$stmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
$edgesResult = $stmt->execute();

$relatedEdges = [];
while ($edge = $edgesResult->fetchArray(SQLITE3_ASSOC)) {
    $relatedEdges[] = $edge;
}

$nodeName = '';
if ($nodeId) {

    $nodeStmt = $db->prepare('SELECT name FROM Node WHERE node_id = :nodeId');
    $nodeStmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $nodeResult = $nodeStmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($nodeResult) {
        $nodeName = $nodeResult['name'];
    }
}

if (empty($relatedEdges)) {

    $_GET['node_id'] = $nodeId;
    include('deleteNode.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Related Edges</title>
    <link rel="stylesheet" href="../admincrud.css">
</head>
<body>
    <header>
        <h1>Related Edges for Location: <?= $nodeName; ?></h1>
    </header>
    <main>
        <?php if (!empty($relatedEdges)): ?>
            <p>Please address the following related edges before deleting the node:</p>
            <table>
                <thead>
                    <tr>
                        <th>Edge ID</th>
                        <th>Start Node</th>
                        <th>End Node</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relatedEdges as $edge): ?>
                    <tr>
                        <td><?= $edge['edge_id']; ?></td>
                        <td><?= $edge['start_node_name']; ?></td>
                        <td><?= $edge['end_node_name']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        <a href="admincrud.php">Back to Locations</a>
    </main>
</body>
</html>
