<?php
require_once("./components/database.php");
require("./components/node_class.php");
require("./components/dijkstra_class.php");

$db_pdo = new DatabaseConnection();

$startPoint = $_GET['start_node'] ?? null;
$endPoint = $_GET['end_node'] ?? null;


/*  
*   IN
*   $startPoint and $endPoint are the variables delivered by the form
*   OUT
*   $final_path - 2D array with all steps in order they are visited
*
*/

function check_for_precalculated_path($db_pdo, $startPoint, $endPoint)
{
    $db = new SQLite3("database.db");
    $stmt = $db->prepare("SELECT path_id FROM Path WHERE (start_node_id = $startPoint) AND (end_node_id = $endPoint)");
    $result = $stmt->execute();

    $data = $result->fetchArray(SQLITE3_ASSOC);    
    return $data;
}

$exists = check_for_precalculated_path($db_pdo, $startPoint, $endPoint);

if($exists == null)
{
    //echo 'if null';
    // Path does not exist in the database, calculate new path
    // NODE SELECT 
    {
        $node_sql = "SELECT node_id FROM Node";

        $data = $db_pdo->executeQuery($node_sql);

        $nodeObjects = [];

        foreach ($data as $row) {
            $node_id = $row['node_id'];
            $nodeObjects['node_' . $node_id] = new Node($node_id);
        }
    }

    // EDGE SCOPE

    
    {
        $db = new SQLite3("database.db");

        $stmt = $db->prepare('SELECT edge_id, start_node_id, end_node_id, distance FROM Edges');
        $sql = "SELECT edge_id, start_node_id, end_node_id, distance FROM Edges";
        
        $edgesResult = $stmt->execute();

        while ($row = $edgesResult->fetchArray()) {
            $edgesResult_array[] = $row;
            $nodeObjects['node_'.$row['start_node_id']]->addNeighbour($nodeObjects['node_'.$row['end_node_id']], $row['distance'], $row['edge_id']);
        }

    }
    

    foreach ($nodeObjects as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }
    // Calculate shortest path

    $nodeObjects['node_'.$startPoint]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodeObjects['node_'.$startPoint], $nodeObjects['node_'.$endPoint]);

    var_dump($path);
    $dbFile = 'database.db'; // Replace with your actual database file path
    $dsn = "sqlite:$dbFile";

    try {
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Using implode to add every edge_id from the $path variable

        //$stmt = $db->prepare("SELECT edge_id, image, direction, notes FROM Edges WHERE edge_id IN(".implode(',',$path).") ORDER BY FIELD(edge_id, ".implode(',',$path).")");
        
        $orderQueryPart = '';
        foreach ($path as $index => $value) {
            $orderQueryPart .= "WHEN {$value} THEN {$index} ";
        }

        $stmt = $db->prepare("SELECT Edges.edge_id, Edges.image, Edges.direction, Edges.notes, Node.category, Node.name, Node.floor, Edges.accessibility_notes FROM Edges INNER JOIN Node ON Edges.end_node_id = Node.node_id WHERE edge_id IN(".implode(',',$path).") ORDER BY CASE edge_id {$orderQueryPart} END");

        $edgesResult = $stmt->execute();

        $final_path = [];
        while ($row = $edgesResult->fetchArray()) {
            $final_path[] = $row;
    }

    

    } catch (PDOException $e) {
        echo "Error selecting data: " . $e->getMessage();
    }

    // Add new path to path and steps tables
    
    $stmt = $pdo->prepare("INSERT INTO path (start_node_id, end_node_id) VALUES (?, ?)");

    try {
        $stmt->execute([$startPoint, $endPoint]);
        $path_id = $pdo->lastInsertId();

    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
    }

    
    foreach ($final_path as $position => $step) {
        $edge_id = $step['edge_id'];
    
        // Prepare and execute the INSERT statement
        $stmt = $pdo->prepare("INSERT INTO Steps (path_id, edge_id, position_in_path) VALUES (?, ?, ?)");
        $stmt->execute([$path_id, $edge_id, $position]);
    }
    $db->close(); // Close the database connection
}

else
{
    // path already exists in database, use that instead
    // query steps table with path_id to build edge array
    $db = new SQLite3("database.db");

    //$stmt = $db->prepare('SELECT edge_id, position_in_path FROM Path INNER JOIN Edges on ');
    $collect_path_sql = $db->prepare("SELECT Edges.image, Edges.direction, Edges.notes, Node.category, Node.name, Node.floor, Edges.accessibility_notes
    FROM Steps
    INNER JOIN Edges ON Steps.edge_id = Edges.edge_id
    INNER JOIN Node ON Edges.end_node_id = Node.node_id
    WHERE Steps.path_id = :path_id
    ORDER BY Steps.position_in_path");
    

    // bind params to avoid sql injection
    $collect_path_sql->bindParam(':path_id', $exists['path_id'], PDO::PARAM_INT);


    $edgesResult = $collect_path_sql->execute();

    $final_path = [];
    while ($row = $edgesResult->fetchArray()) {
        $final_path[] = $row;
    }
    
    $edgesResult->finalize(); // Close the result set
    $db->close(); // Close the database connection
}

    function calculate_relative_directions($path)
    {
        // Using compass directions to calculate the relative direction of the instruction
        // N,E,S,W are represented as 1,2,3,4
        // Current compass direction - next compass direction = next relative direction
        // Forward is 0, left is both 1 and -3, right is -1 and 3

        for ($i = 0; $i < count($path); $i++) {
            if($i+1 < count($path)){ // Changed condition here
                

                $direction = $path[$i]['direction']-$path[$i+1]['direction'];
                switch($direction){
                    case 0: 
                        $direction_text = 'forward';
                        break;
                    case 1:
                    case -3:
                        $direction_text = 'left';
                        break;
                    case -1:
                    case 3:
                        $direction_text = 'right';
                        break;
                }

                $path[$i]['direction'] = $direction_text;

                switch ($path[$i]['category']){
                    case 1:
                        $instruction_text = 'Go through the door';

                        if ($i == count($path) - 2) {
                            $instruction_text .= '.';
                            break;
                        }

                        if ($direction_text == 'forward') {
                            $instruction_text .= ' and continue <b>forward</b>.';
                        } else {
                            $instruction_text .= ' and turn <b>'.$direction_text.'</b>.';
                        }

                        break;

                    case 2:
                        $instruction_text = 'Continue through the <b>'.$path[$i]['name'].'</b>.';
                        break;

                    case 3:
                        if ($i + 1 < count($path) and $path[$i + 1]['category'] == 2) {
                            $instruction_text = 'The '.$path[$i + 1]['name'].' is on your <b>'.$direction_text.'</b>.';
                            break;
                        }
                        
                        $instruction_text = 'At the next junction ';
                        if ($direction_text == 'forward') {
                            $instruction_text .= 'continue <b>straight</b>.';
                        } else {
                            $instruction_text .= 'turn <b>'.$direction_text.'</b>.';
                        };
                        break;

                    case 4:
                        for ($x = $i; $x < count($path) - 1; $x++) {
                            if ($path[$x + 1]['category'] != 4) {
                                break;
                            }
                            $end_floor = $path[$x + 1]['floor'];
                        }

                        $path_start = array_slice($path, 0, ($i + 1));
                        $path_end = array_slice($path, ($x + 1));
                        $path = array_merge($path_start, $path_end);

                        $instruction_text = 'Use the stairs or lift to get to ';
                        if ($end_floor == 0) {
                            $instruction_text .= 'the "<b>"ground</b> floor.';
                        } else {
                            $instruction_text .= 'floor "<b>"'.$end_floor.'</b>.';
                        }
                        break;

                    case 5:
                        $instruction_text = 'Go through <b>'.$path[$i]['name'] + '</b>.';
                        break;

                    case 6:
                        $instruction_text = 'Continue along the corridor.';
                }

                $path[$i]['instruction'] = $instruction_text;
            }
        }

    return $path;
    }

    function IsStairs($var) {
        return $var['category'] == 5;
    }

    function IsConnected($var) {
    }
    $final_path = calculate_relative_directions($final_path);
    
    //echo '<pre>' , var_dump($final_path) , '</pre>';

    $json_data = array();
    foreach($final_path as $step){
        $step_array = array(
            "image" => $step["image"],
            "direction" => $step["direction"],
            "notes" => $step["notes"],
            "category" => $step["category"],
            "name" => $step["name"],
            "floor" => $step["floor"],
            "accessibility_notes" => $step["accessibility_notes"],
            "instruction" => $step["instruction"] ?? null
        );

        $json_data[] = $step_array;
    }
    $json_finish = json_encode($json_data, JSON_PRETTY_PRINT);
    //header('Content-Type: application/json');
    //header('Content-Disposition: attachment; filename="data.json"');
    

    echo $json_finish;
    