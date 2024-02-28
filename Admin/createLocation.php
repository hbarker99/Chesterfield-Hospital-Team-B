<?php
$db = new SQLite3("databasepractice.db");
session_start();

//unset($_SESSION['confirmedLocationName']); // Clear the confirmed location name after submission
$locationName = $_SESSION['confirmedLocationName'] ?? '';
$isEndpoint = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmName'])) {
        $locationName = trim($_POST['locationName']);
        $_SESSION['confirmedLocationName'] = $locationName;

        // Insert only if the name is new
        if ($locationName) {
            $stmt = $db->prepare('INSERT INTO Node (name) VALUES (:name) ON CONFLICT(node_id) DO NOTHING');
            $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
            $stmt->execute();
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['submitLocation'])) {
        $edgeCount = count($_POST['endNodeId']); // Determine how many edges were added
        $locationId = $db->querySingle("SELECT node_id FROM Node WHERE name = '$locationName'");
        
        for ($i = 0; $i < $edgeCount; $i++) {
            $startNodeId = $locationId; // Current location is the start node
            $endNodeId = intval($_POST['endNodeId'][$i]);
            $direction = $_POST['direction'][$i];
            $distance = floatval($_POST['distance'][$i]);
            $notes = $_POST['notes'][$i];

            // Handle file upload
            $imagePath = null;
            if (!empty($_FILES['image']['name'][$i])) {
                $fileName = $_FILES['image']['name'][$i];
                $tempName = $_FILES['image']['tmp_name'][$i];
                $imagePath = $targetDir . basename($fileName);
                move_uploaded_file($tempName, $imagePath);
            }

            // Insert edge data into Edges table
            $insertEdgeStmt = $db->prepare('INSERT INTO Edges (start_node_id, end_node_id, direction, distance, image, notes) VALUES (:start_node_id, :end_node_id, :direction, :distance, :image, :notes)');
            $insertEdgeStmt->bindValue(':start_node_id', $startNodeId, SQLITE3_INTEGER);
            $insertEdgeStmt->bindValue(':end_node_id', $endNodeId, SQLITE3_INTEGER);
            $insertEdgeStmt->bindValue(':direction', $direction, SQLITE3_TEXT);
            $insertEdgeStmt->bindValue(':distance', $distance, SQLITE3_FLOAT);
            $insertEdgeStmt->bindValue(':image', $imagePath, SQLITE3_TEXT);
            $insertEdgeStmt->bindValue(':notes', $notes, SQLITE3_TEXT);
            $insertEdgeStmt->execute();
        }

        unset($_SESSION['confirmedLocationName']); // Clear the confirmed location name after submission
        $_SESSION['flash_message'] = 'Location and edges added successfully.';
        header("Location: admincrud.php");
        exit();
    }
}

$nodesResult = $db->query('SELECT node_id, name FROM Node');
$nodes = [];
while ($node = $nodesResult->fetchArray(SQLITE3_ASSOC)) {
    $nodes[] = $node;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Location and Edges</title>
    <link rel="stylesheet" href="../editEdges.css">
</head>
<body>
<header>
    <h1>Create New Location and Edges</h1>
</header>
<main>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="locationName">Location Name:</label>
            <input type="text" id="locationName" name="locationName" value="<?= htmlspecialchars($locationName) ?>" required <?= $locationName ? 'readonly' : '' ?>>
            <?php if (!$locationName): ?>
                <button type="submit" name="confirmName">Confirm Name</button>
            <?php endif; ?>
        </div>

        <?php if ($locationName): ?>
            <div id="edgesContainer">
                <!-- JavaScript will append edge forms here -->
            </div>
            <button type="button" onclick="addEdgeForm()">Add Edge</button>
            <button type="submit" name="submitLocation">Create Location and Edges</button>
        <?php endif; ?>
    </form>
</main>

<script>
// JavaScript for adding and managing edge forms
let edgeFormCount = 0;
function addEdgeForm() {
    edgeFormCount++;
    let container = document.getElementById('edgesContainer');
    let html = `
        <div id="edgeForm${edgeFormCount}">
            <!-- Edge fields with appropriate name attributes for array processing in PHP -->
            <label for="endNodeId${edgeFormCount}">End Node:</label>
            <select name="endNodeId[]" id="endNodeId${edgeFormCount}">
                <?php foreach ($nodes as $node): ?>
                    <option value="<?= $node['node_id']; ?>"><?= htmlspecialchars($node['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <!-- Add other fields like direction, distance, etc., similarly -->
            <button type="button" onclick="removeEdgeForm(${edgeFormCount})">Remove Edge</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeEdgeForm(edgeFormId) {
    let formToRemove = document.getElementById('edgeForm' + edgeFormId);
    if (formToRemove) {
        formToRemove.remove();
    }
}
</script>
</body>
</html>