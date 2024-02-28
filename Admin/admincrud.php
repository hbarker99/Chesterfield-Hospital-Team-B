<?php
session_start(); 
    $db = new SQLite3("databasepractice.db");
    $stmt = $db->prepare('SELECT node_id, name FROM Node');


    $result = $stmt->execute();

    $rows_array = [];
    
    while ($row = $result->fetchArray()) {
        $rows_array[] = $row;
    }

    if(isset($_POST['edit'])){
        if($users != null){
            header("Location: admincrud.php");
        }
        else{
            echo "Error Editing Location";
        }
    }
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Management</title>
    <link rel="stylesheet" href="../admincrud.css">
</head>
<body>
    <header>
        <h1>Location Management</h1>
        <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message">
            <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
        <?php endif; ?>
    </header>

    <main>
        <section class="existing-locations">
            <h2>Existing Locations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Location Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_array as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['node_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <form action="editEdges.php" method="post" style="display: inline;">
                                <input type="hidden" name="node_id" value="<?php echo $row['node_id']; ?>">
                                <button type="submit" name="edit" class="action-btn">Edit</button>
                            </form>
                            <form action="viewRelatedEdges.php" method="get" style="display: inline;">
                                <input type="hidden" name="node_id" value="<?php echo $row['node_id']; ?>">
                                <button type="submit" class="action-btn" onclick="return confirm('Are you sure you want to delete this node? This action will require you to update or delete related edges.');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Additional row for adding a new location -->
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <a href="createLocation.php" class="action-btn">Add New Location</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>


