<?php
$db = new SQLite3("databasepractice.db");
session_start();

$nodeId = isset($_POST['node_id']) ? intval($_POST['node_id']) : null;
$edges = [];
$nodeName = '';

// Handle request to delete an edge
if(isset($_POST['deleteEdge'])) {
    $edgeIdToDelete = $_POST['deleteEdge'];
    $deleteStmt = $db->prepare("DELETE FROM Edges WHERE edge_id = :edgeId");
    $deleteStmt->bindValue(':edgeId', $edgeIdToDelete, SQLITE3_INTEGER);
    $deleteStmt->execute();
}

// Handle adding new edges
if(isset($_POST['addNewEdge'])) {
    // Logic to insert new edge based on provided data
    // Assume form fields for new edge are named accordingly (e.g., newEdgeSource, newEdgeDestination, etc.)
    $source = $_POST['newEdgeSource'];
    $destination = $_POST['newEdgeDestination'];
    // Additional data collection as needed

    $insertStmt = $db->prepare("INSERT INTO Edges (start_node_id, end_node_id) VALUES (:source, :destination)");
    $insertStmt->bindValue(':source', $source, SQLITE3_INTEGER);
    $insertStmt->bindValue(':destination', $destination, SQLITE3_INTEGER);
    $insertStmt->execute();
}


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

    for ($i = 0; $i < count($_POST['edge_id']); $i++) {
        $edgeId = intval($_POST['edge_id'][$i]);

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

    if (isset($_POST['newEdgeSource'])) {
        foreach ($_POST['newEdgeSource'] as $index => $sourceId) {
            $destinationId = $_POST['newEdgeDestination'][$index];
            $direction = $_POST['newEdgeDirection'][$index];
            $distance = $_POST['newEdgeDistance'][$index];
            $notes = $_POST['newEdgeNotes'][$index];
            // Handle image upload for new edge

            // Insert new edge into database
            $insertEdgeStmt = $db->prepare("INSERT INTO Edges (start_node_id, end_node_id, direction, distance, notes) VALUES (?, ?, ?, ?, ?)");
            $insertEdgeStmt->execute([$sourceId, $destinationId, $direction, $distance, $notes]);
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

                <div class="edge">
        <!-- Edge details -->
        <button type="submit" name="deleteEdge" value="<?= $edge['edge_id']; ?>">Delete Edge</button>
                </div>

            <?php endforeach; ?>

            <div id="newEdgesContainer"></div>
    <button type="button" id="addEdgeButton">Add New Edge</button>

    <button type="submit" name="updateEdges">Update Edges</button>

        </form>

        <script>
document.getElementById('addEdgeButton').addEventListener('click', function() {
    const container = document.getElementById('newEdgesContainer');
    const newEdgeIndex = container.children.length; // This index can be used to differentiate between new edge forms.
    const newEdgeHTML = `
        <div class="new-edge-form">
            <strong>New Edge #${newEdgeIndex + 1}</strong><br>
            <label for="newEdgeSource_${newEdgeIndex}">Source Node:</label>
            <select name="newEdgeSource[]" id="newEdgeSource_${newEdgeIndex}">
                <?php foreach ($nodes as $node): ?>
                    <option value="<?= $node['node_id']; ?>"><?= htmlspecialchars($node['name']); ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="newEdgeDestination_${newEdgeIndex}">Destination Node:</label>
            <select name="newEdgeDestination[]" id="newEdgeDestination_${newEdgeIndex}">
                <?php foreach ($nodes as $node): ?>
                    <option value="<?= $node['node_id']; ?>"><?= htmlspecialchars($node['name']); ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="newEdgeDistance_${newEdgeIndex}">Distance:</label>
            <input type="number" name="newEdgeDistance[]" id="newEdgeDistance_${newEdgeIndex}" step="0.01"><br>

            <label for="newEdgeDirection_${newEdgeIndex}">Direction:</label>
            <select name="newEdgeDirection[]" id="newEdgeDirection_${newEdgeIndex}">
                <?php foreach ($directions as $direction): ?>
                    <option value="<?= $direction['direction_id']; ?>"><?= htmlspecialchars($direction['direction']); ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="newEdgeNotes_${newEdgeIndex}">Notes:</label>
            <textarea name="newEdgeNotes[]" id="newEdgeNotes_${newEdgeIndex}"></textarea><br>

            <label for="newEdgeImage_${newEdgeIndex}">Image:</label>
            <input type="file" name="newEdgeImage[]" id="newEdgeImage_${newEdgeIndex}"><br>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newEdgeHTML);
});
</script>


        <a href="admincrud.php">Back to Locations</a>
    </main>
</body>

</html>