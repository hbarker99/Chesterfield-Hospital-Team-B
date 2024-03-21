<?php
$db = new SQLite3("databasepractice.db");
session_start();

// Function to handle adding or updating location
function handleLocation($db, &$locationName, &$locationId) {
    if (!empty($_SESSION['confirmedLocationName'])) {
        $locationName = $_SESSION['confirmedLocationName'];
        // Fetch location ID based on name
        $stmt = $db->prepare("SELECT node_id FROM Node WHERE name = :name");
        $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $locationId = $row['node_id'] ?? 0;
    } elseif (!empty($_POST['locationName'])) {
        $locationName = trim($_POST['locationName']);
        // Insert new location
        $stmt = $db->prepare("INSERT INTO Node (name) VALUES (:name)");
        $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
        $stmt->execute();
        $locationId = $db->lastInsertRowID();
        $_SESSION['confirmedLocationName'] = $locationName;
    }
}

// Function to handle edge addition
function addEdges($db, $locationId) {
    // Assume $_POST['endNode'], $_POST['distance'], etc., are arrays from the form
    if (!empty($_POST['endNode']) && is_array($_POST['endNode'])) {
        foreach ($_POST['endNode'] as $i => $endNode) {
            $distance = $_POST['distance'][$i] ?? 0;
            // Add more parameters as needed
            $stmt = $db->prepare("INSERT INTO Edges (start_node_id, end_node_id, distance) VALUES (:start, :end, :distance)");
            $stmt->bindValue(':start', $locationId, SQLITE3_INTEGER);
            $stmt->bindValue(':end', $endNode, SQLITE3_INTEGER);
            $stmt->bindValue(':distance', $distance, SQLITE3_FLOAT);
            // Execute edge insertion
            $stmt->execute();
        }
    }
}

$locationName = '';
$locationId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmName']) || isset($_POST['submitLocation'])) {
        handleLocation($db, $locationName, $locationId);
        if (isset($_POST['submitLocation'])) {
            addEdges($db, $locationId);
            // Redirect or display success message
            $_SESSION['flash_message'] = "Location and edges have been successfully added.";
            unset($_SESSION['confirmedLocationName']);
            header('Location: admincrud.php');
            exit();
        }
    } elseif (isset($_POST['changeName'])) {

        $locationName = $_POST['locationName'] ?? '';
    
        $locationidStmt = $db->prepare('SELECT node_id FROM Node WHERE name = :locationName');
        $locationidStmt->bindValue(':locationName', $locationName, SQLITE3_TEXT);
        $result = $locationidStmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row) {
            $locationid = $row['node_id'];
    
            $deleteStmt = $db->prepare('DELETE FROM Node WHERE node_id = :nodeId');
            $deleteStmt->bindValue(':nodeId', $locationid, SQLITE3_INTEGER);
            $deleteStmt->execute();
    
            unset($_SESSION['confirmedLocationName']);
    
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Fetch nodes for the form
$nodes = [];
$result = $db->query('SELECT node_id, name FROM Node');
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $nodes[] = $row;
}

$directions = [];
$result2 = $db->query('SELECT direction_id,direction FROM Direction');
while ($row = $result2->fetchArray(SQLITE3_ASSOC)) {
    $directions[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Location and Edges</title>
    <link rel="stylesheet" href="style.css"> <!-- Make sure this path is correct -->
</head>
<body>
    <header>
        <h1>Create New Location and Edges</h1>
    </header>
    <main>
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <p><?= $_SESSION['flash_message']; ?></p>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- Location Name Field -->
            <label for="locationName">Location Name:</label>
            <input type="text" id="locationName" name="locationName" value="<?= htmlspecialchars($locationName); ?>" <?= !empty($locationName) ? 'readonly' : ''; ?> required>
            
            <!-- Dynamic Edge Forms Container -->
            <div id="edgesContainer"></div>

            <?php if (empty($locationName)): ?>
                <button type="submit" name="confirmName">Confirm Name</button>
            <?php else: ?>
                <button type="button" id="addEdgeButton">Add Edge</button>
                <button type="submit" name="submitLocation">Submit Location and Edges</button>
                <button type="submit" name="changeName">Change Name</button>
            <?php endif; ?>
        </form>
    </main>

    <script>
        document.getElementById('addEdgeButton')?.addEventListener('click', function() {
            const container = document.getElementById('edgesContainer');
            const edgeForm = document.createElement('div');
            edgeForm.innerHTML = `
                <label for="endNode">End Node:</label>
                <select name="endNode[]">
                    <?php foreach ($nodes as $node): ?>
                        <option value="<?= $node['node_id']; ?>"><?= htmlspecialchars($node['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="distance">Distance:</label>
                <input type="number" name="distance[]">
                <?php /*<label for="direction">Direction:</label>
                        <select name="direction[]">
                            <?php foreach ($directions as $direction): ?>
                                <option value="<?= $direction['direction_id']; ?>"> <?= htmlspecialchars($direction['direction']); ?></option>
                            <?php endforeach; ?>
                        </select> */?>

                    <?php /* <label for="image-<?php echo $edge['edge_id']; ?>">Upload Image:</label>
                        <input type="file" name="newImage[]" id="image-<?php echo $edge['edge_id']; ?>">

                        <label>Notes:</label>
                        <textarea name="newNote[]"><?php echo $edge['notes']; ?></textarea> */ ?>
                <button type="button" onclick="this.parentElement.remove()">Remove</button>
            `;
            container.appendChild(edgeForm);
        });
    </script>
</body>
</html>







<?php
/* 
<?php
$db = new SQLite3("databasepractice.db");
session_start();

// Function to handle adding or updating location
function handleLocation($db, &$locationName, &$locationId) {
    if (!empty($_SESSION['confirmedLocationName'])) {
        $locationName = $_SESSION['confirmedLocationName'];
        // Fetch location ID based on name
        $stmt = $db->prepare("SELECT node_id FROM Node WHERE name = :name");
        $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $locationId = $row['node_id'] ?? 0;
    } elseif (!empty($_POST['locationName'])) {
        $locationName = trim($_POST['locationName']);
        // Insert new location
        $stmt = $db->prepare("INSERT INTO Node (name) VALUES (:name)");
        $stmt->bindValue(':name', $locationName, SQLITE3_TEXT);
        $stmt->execute();
        $locationId = $db->lastInsertRowID();
        $_SESSION['confirmedLocationName'] = $locationName;
    }
}

// Function to handle edge addition
function addEdges($db, $locationId) {
    // Assume $_POST['endNode'], $_POST['distance'], etc., are arrays from the form
    if (!empty($_POST['endNode']) && is_array($_POST['endNode'])) {
        foreach ($_POST['endNode'] as $i => $endNode) {
            $distance = $_POST['distance'][$i] ?? 0;
            // Add more parameters as needed
            $stmt = $db->prepare("INSERT INTO Edges (start_node_id, end_node_id, distance) VALUES (:start, :end, :distance)");
            $stmt->bindValue(':start', $locationId, SQLITE3_INTEGER);
            $stmt->bindValue(':end', $endNode, SQLITE3_INTEGER);
            $stmt->bindValue(':distance', $distance, SQLITE3_FLOAT);
            // Execute edge insertion
            $stmt->execute();
        }
    }
}

$locationName = '';
$locationId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmName']) || isset($_POST['submitLocation'])) {
        handleLocation($db, $locationName, $locationId);
        if (isset($_POST['submitLocation'])) {
            addEdges($db, $locationId);
            // Redirect or display success message
            $_SESSION['flash_message'] = "Location and edges have been successfully added.";
            unset($_SESSION['confirmedLocationName']);
            header('Location: admincrud.php');
            exit();
        }
    } elseif (isset($_POST['changeName'])) {

        $locationName = $_POST['locationName'] ?? '';
    
        $locationidStmt = $db->prepare('SELECT node_id FROM Node WHERE name = :locationName');
        $locationidStmt->bindValue(':locationName', $locationName, SQLITE3_TEXT);
        $result = $locationidStmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row) {
            $locationid = $row['node_id'];
    
            $deleteStmt = $db->prepare('DELETE FROM Node WHERE node_id = :nodeId');
            $deleteStmt->bindValue(':nodeId', $locationid, SQLITE3_INTEGER);
            $deleteStmt->execute();
    
            unset($_SESSION['confirmedLocationName']);
    
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Fetch nodes for the form
$nodes = [];
$result = $db->query('SELECT node_id, name FROM Node');
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $nodes[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Location and Edges</title>
    <link rel="stylesheet" href="style.css"> <!-- Make sure this path is correct -->
</head>
<body>
    <header>
        <h1>Create New Location and Edges</h1>
    </header>
    <main>
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <p><?= $_SESSION['flash_message']; ?></p>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- Location Name Field -->
            <label for="locationName">Location Name:</label>
            <input type="text" id="locationName" name="locationName" value="<?= htmlspecialchars($locationName); ?>" <?= !empty($locationName) ? 'readonly' : ''; ?> required>
            
            <!-- Dynamic Edge Forms Container -->
            <div id="edgesContainer"></div>

            <?php if (empty($locationName)): ?>
                <button type="submit" name="confirmName">Confirm Name</button>
            <?php else: ?>
                <button type="button" id="addEdgeButton">Add Edge</button>
                <button type="submit" name="submitLocation">Submit Location and Edges</button>
                <button type="submit" name="changeName">Change Name</button>
            <?php endif; ?>
        </form>
    </main>

    <script>
        document.getElementById('addEdgeButton')?.addEventListener('click', function() {
            const container = document.getElementById('edgesContainer');
            const edgeForm = document.createElement('div');
            edgeForm.innerHTML = `
                <label for="endNode">End Node:</label>
                <select name="endNode[]">
                    <?php foreach ($nodes as $node): ?>
                        <option value="<?= $node['node_id']; ?>"><?= htmlspecialchars($node['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="distance">Distance:</label>
                <input type="text" name="distance[]">
                <!-- Add more fields as needed -->
                <button type="button" onclick="this.parentElement.remove()">Remove</button>
            `;
            container.appendChild(edgeForm);
        });
    </script>
</body>
</html>
*/
?>
