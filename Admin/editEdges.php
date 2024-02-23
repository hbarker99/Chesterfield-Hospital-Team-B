<?php
$db = new SQLite3("databasepractice.db");
session_start();

$nodeId = isset($_POST['node_id']) ? intval($_POST['node_id']) : null;
$edges = [];
$nodeName = '';

if ($nodeId) {

    $nodeStmt = $db->prepare('SELECT name FROM Node WHERE node_id = :nodeId');
    $nodeStmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $nodeResult = $nodeStmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($nodeResult) {
        $nodeName = $nodeResult['name'];
    }

    $stmt = $db->prepare('SELECT edge_id, start_node_id, end_node_id,distance,image,direction,notes FROM Edges WHERE start_node_id = :nodeId OR end_node_id = :nodeId');
    $stmt->bindValue(':nodeId', $nodeId, SQLITE3_INTEGER);
    $edgesResult = $stmt->execute();

    while ($edge = $edgesResult->fetchArray(SQLITE3_ASSOC)) {
        $edges[] = $edge;
    }
}

$nodes = [];
$nodesResult = $db->query('SELECT node_id, name FROM Node');
while ($node = $nodesResult->fetchArray(SQLITE3_ASSOC)) {
    $nodes[] = $node;
}

$directions = [];
$directionsResult = $db->query('SELECT direction_id, direction FROM Direction');
while ($direction = $directionsResult->fetchArray(SQLITE3_ASSOC)) {
    $directions[] = $direction;
}

if (isset($_POST['updateEdges'])) {

    $targetDir = "../img/";

    for ($i = 0; $i < count($_POST['edgeId']); $i++) {
        $edgeId = intval($_POST['edgeId'][$i]);

        if (!empty($_FILES['newImage']['name'][$i])) {
            $fileName = "edge_" . $edgeId . ".jpg";
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['newImage']['tmp_name'][$i], $targetFilePath)) {
                echo "File uploaded successfully for edge ID $edgeId.<br>";
            } else {
                echo "Error uploading file for edge ID $edgeId.<br>";
            }
        }
    }

    for ($i = 0; $i < count($_POST['edgeId']); $i++) {
        $edgeId = intval($_POST['edgeId'][$i]);
        $newSource = intval($_POST['newSource'][$i]);
        $newDestination = intval($_POST['newDestination'][$i]);
        $newDirection = intval($_POST['newDirection'][$i]);
        $newDistance = floatval($_POST['newDistance'][$i]);
        $newImage = $_POST['newImage'][$i];
        $newNote = $_POST['newNote'][$i];

        $updateStmt = $db->prepare('UPDATE Edges SET start_node_id = :newSource, end_node_id = :newDestination, direction = :newDirection, distance = :newDistance, image = :newImage, notes = :newNote WHERE edge_id = :edgeId');

        $updateStmt->bindValue(':newSource', $newSource, SQLITE3_INTEGER);
        $updateStmt->bindValue(':newDestination', $newDestination, SQLITE3_INTEGER);
        $updateStmt->bindValue(':newDirection', $newDirection, SQLITE3_INTEGER); // Adjust if your direction handling differs
        $updateStmt->bindValue(':newDistance', $newDistance, SQLITE3_FLOAT);
        $updateStmt->bindValue(':newImage', $newImage, SQLITE3_TEXT);
        $updateStmt->bindValue(':newNote', $newNote, SQLITE3_TEXT);
        $updateStmt->bindValue(':edgeId', $edgeId, SQLITE3_INTEGER);

        $result = $updateStmt->execute();

        if ($result === false) {

            $_SESSION['flash_message'] = 'Error updating edge with ID: ' . $edgeId;
            header("Location: admincrud.php");
            exit();
        }
    }

    $_SESSION['flash_message'] = 'Edges updated successfully.';
    header("Location: admincrud.php");
    exit();
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
        <h1>Edit Edges for Location:
            <?php echo $nodeName; ?>
        </h1>
    </header>

    <main>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="node_id" value="<?php echo htmlspecialchars($nodeId); ?>">
            <?php foreach ($edges as $edge): ?>
                <div>
                    <label>Edge ID:
                        <?php echo $edge['edge_id']; ?>
                    </label>
                    <input type="hidden" name="edgeId[]" value="<?php echo $edge['edge_id']; ?>">

                    <label for="source-<?php echo $edge['edge_id']; ?>">Source Node:</label>
                        <select name="newSource[]" id="source-<?php echo $edge['edge_id']; ?>">
                            <?php foreach ($nodes as $node): ?>
                                <option value="<?php echo $node['node_id']; ?>" <?php echo $node['node_id'] == $edge['start_node_id'] ? 'selected' : ''; ?>>
                                    <?php echo $node['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="destination-<?php echo $edge['edge_id']; ?>">Destination Node:</label>
                        <select name="newDestination[]" id="destination-<?php echo $edge['edge_id']; ?>">
                            <?php foreach ($nodes as $node): ?>
                                <option value="<?php echo $node['node_id']; ?>" <?php echo $node['node_id'] == $edge['end_node_id'] ? 'selected' : ''; ?>>
                                    <?php echo $node['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="direction-<?php echo $edge['edge_id']; ?>">Direction:</label>
                        <select name="newDirection[]" id="direction-<?php echo $edge['edge_id']; ?>">
                            <?php foreach ($directions as $direction): ?>
                                <option value="<?php echo $direction['direction_id']; ?>" <?php echo $direction['direction_id'] == $edge['direction'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($direction['direction']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>Distance:</label>
                        <input type="text" name="newDistance[]" value="<?php echo $edge['distance']; ?>">

                        <label for="image-<?php echo $edge['edge_id']; ?>">Upload Image:</label>
                        <input type="file" name="newImage[]" id="image-<?php echo $edge['edge_id']; ?>">

                        <label>Notes:</label>
                        <textarea name="newNote[]"><?php echo $edge['notes']; ?></textarea>
                </div>

            <?php endforeach; ?>

    <button type="submit" name="updateEdges">Update Edges</button>

        </form>
      <a href="admincrud.php">Back to Locations</a>
    </main>
</body>

</html>