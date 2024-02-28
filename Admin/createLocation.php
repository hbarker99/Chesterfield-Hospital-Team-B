<?php
$db = new SQLite3("databasepractice.db");
session_start();

// Variables to hold submitted form data
$locationName = '';
$edgeCount = 0;
$isEndpoint = false;

// Arrays to store dynamically added edge data
$edges = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmName'])) {
        // Add location name to database and set it in session
        $locationName = trim($_POST['locationName']);
        if (!empty($locationName)) {
            $stmt = $db->prepare('INSERT INTO Node (name) VALUES (:name)');
            $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
            $stmt->execute();
            $_SESSION['confirmedLocationName'] = $locationName;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}
        else if (isset($_POST['submitLocation'])) {
    $locationName = $_POST['locationName'];
    $edgeCount = intval($_POST['edgeCount']);
    $isEndpoint = isset($_POST['isEndpoint']) ? 1 : 0; // 1 for true, 0 for false

    // Insert the new location into the Node table
    $insertStmt = $db->prepare('INSERT INTO Node (name, endpoint) VALUES (:name, :isEndpoint)');
    $insertStmt->bindValue(':name', $locationName, SQLITE3_TEXT);
    $insertStmt->bindValue(':isEndpoint', $isEndpoint, SQLITE3_INTEGER);
    $result = $insertStmt->execute();
    $newNodeId = $db->lastInsertRowID(); // Get the ID of the newly created location

    // Process each edge if edges are connected
    if ($edgeCount > 0) {
        if ($newNodeId) {
            for ($i = 0; $i < $edgeCount; $i++) {
                // Assume each edge has start_node_id, end_node_id, direction, distance, image, and notes
                $newSource = intval($_POST['newSource'][$i]);
                $newDestination = intval($_POST['newDestination'][$i]);
                $newDirection = intval($_POST['newDirection'][$i]);
                $newDistance = floatval($_POST['newDistance'][$i]);
                $newNotes = $_POST['newNote'][$i];
    
                // Handling file upload for each edge
                $fileName = !empty($_FILES['newImage']['name'][$i]) ? $_FILES['newImage']['name'][$i] : null;
                $filePath = null;
                if ($fileName) {
                    $filePath = $targetDir . basename($fileName);
                    // Move the uploaded file to the target directory
                    move_uploaded_file($_FILES['newImage']['tmp_name'][$i], $filePath);
                }
    
                // Insert edge into the database
                $insertEdgeStmt = $db->prepare('INSERT INTO Edges (start_node_id, end_node_id, direction, distance, image, notes) VALUES (:start_node_id, :end_node_id, :direction, :distance, :image, :notes)');
                $insertEdgeStmt->bindValue(':start_node_id', $newSource, SQLITE3_INTEGER);
                $insertEdgeStmt->bindValue(':end_node_id', $newDestination, SQLITE3_INTEGER);
                $insertEdgeStmt->bindValue(':direction', $newDirection, SQLITE3_INTEGER);
                $insertEdgeStmt->bindValue(':distance', $newDistance, SQLITE3_FLOAT);
                $insertEdgeStmt->bindValue(':image', $filePath, SQLITE3_TEXT);
                $insertEdgeStmt->bindValue(':notes', $newNotes, SQLITE3_TEXT);
                $insertEdgeStmt->execute();
            }
    }
}

    $_SESSION['flash_message'] = 'Location added successfully.';
    header("Location: admincrud.php");
    exit();
}

// Fetch nodes and directions for dropdown selections
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Location</title>
    <link rel="stylesheet" href="../editEdges.css">
</head>
<body>
    <header>
        <h1>Create New Location</h1>
    </header>
    <main>
        <form method="post" enctype="multipart/form-data">
            <?php if (empty($locationName)): ?>
                <label for="locationName">Location Name:</label>
                <input type="text" id="locationName" name="locationName" required>
                <button type="submit" name="confirmName">Confirm Name</button>
            <?php else: ?>
                <input type="hidden" name="locationName" value="<?= htmlspecialchars($locationName) ?>">
                Location: <?= htmlspecialchars($locationName) ?>
                <!-- Add form elements for edge creation here -->
                <button type="submit" name="submitLocation">Create Edges</button>
            <?php endif; ?>
        </form>
        <?php if (!empty($locationName)): ?>
            <!-- Optional: Add edge creation form here -->
        <?php endif; ?>
        <a href="admincrud.php">Back to Locations</a>
    </main>
</body>


    <script>
/*document.addEventListener('DOMContentLoaded', function() {
    const confirmNameBtn = document.getElementById('confirmName');
    const locationNameInput = document.getElementById('locationName');
    const additionalDetails = document.getElementById('additionalDetails');
    const submitLocationBtn = document.getElementById('submitLocation');
    const edgeCountSelector = document.getElementById('edgeCount');

    confirmNameBtn.addEventListener('click', function() {
        const locationName = locationNameInput.value.trim();
        if (locationName !== '') {
            locationNameInput.disabled = true;
            confirmNameBtn.disabled = true;
            additionalDetails.style.display = 'block';
            submitLocationBtn.style.display = 'inline-block';

            // Dynamically add the new location name to dropdowns
            const newOptionHTML = `<option value="new">${locationName} (New)</option>`;
            document.querySelectorAll('select[name="newSource[]"], select[name="newDestination[]"]').forEach(select => {
                select.insertAdjacentHTML('beforeend', newOptionHTML);
                select.value = "new";
            });
        } else {
            alert('Please enter a location name.');
        }
    });

        edgeCountSelector.addEventListener('change', function() {
            const selectedCount = parseInt(edgeCountSelector.value, 10);
            const currentCount = edgesContainer.getElementsByClassName('edge-details').length;

            // Adding fields
            while (selectedCount > currentCount) {
                const edgeDetailDiv = document.createElement('div');
                edgeDetailDiv.className = 'edge-details';
                edgeDetailDiv.innerHTML = `
                    <label>Start Node:</label>
                    <select name="newSource[]">
                        <?php //foreach ($nodes as $node): ?>
                            <option value="<?php echo $node['node_id']; ?>"><?//php echo htmlspecialchars($node['name']); ?></option>
                        <?php //endforeach; ?>
                    </select>

                    <label>Destination Node:</label>
                    <select name="newDestination[]">
                        <?php //foreach ($nodes as $node): ?>
                            <option value="<?//php echo $node['node_id']; ?>"><?//php echo htmlspecialchars($node['name']); ?></option>
                        <?php //endforeach; ?>
                    </select>

                    <label>Direction:</label>
                    <select name="newDirection[]">
                        <?php //foreach ($directions as $direction): ?>
                            <option value="<?//php echo $direction['direction_id']; ?>"><?//php echo htmlspecialchars($direction['direction']); ?></option>
                        <?php //endforeach; ?>
                    </select>

                    <label>Distance:</label>
                    <input type="text" name="newDistance[]">

                    <label>Image:</label>
                    <input type="file" name="newImage[]">

                    <label>Notes:</label>
                    <textarea name="newNote[]"></textarea>
                `;
                edgesContainer.appendChild(edgeDetailDiv);
                currentCount++;
            }

            // Removing fields
            while (selectedCount < currentCount) {
                const lastField = edgesContainer.lastChild;
                if (lastField) {
                    edgesContainer.removeChild(lastField);
                    currentCount--;
                }
            }
        });
    });*/
    </script>
</body>
</html>