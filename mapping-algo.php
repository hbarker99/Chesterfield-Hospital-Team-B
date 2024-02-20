<style>
  .image-box {
    width: 400px;
    height: 800px;
    overflow: hidden; /* To crop excess content */
  }

  .image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Choose the desired value */
  }
</style>



<?php


#### testing post stuffs


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if startPoint and endPoint are set in the POST data
    if (isset($_POST['startPoint']) && isset($_POST['endPoint'])) {
        // Retrieve the values of startPoint and endPoint
        $startPoint = $_POST['startPoint'];
        $endPoint = $_POST['endPoint'];
        
        // Print out the form details
        echo "Start Point: " . $startPoint . "<br>";
        echo "End Point: " . $endPoint . "<br>";
        
        // Check if the accessibility check is set
        if (isset($_POST['accessibilityCheck'])) {
            // Accessibility check is checked
            echo "Accessibility Check: Checked<br>";
        } else {
            // Accessibility check is not checked
            echo "Accessibility Check: Not Checked<br>";
        }
        // You can perform further processing here based on the form data
    } else {
        echo "Error: startPoint and/or endPoint not set.";
    }
} else {
    echo "Error: Form not submitted.";
}


#####
class Node
{
    public $distance = PHP_INT_MAX;
    public $previous = null;
    public $nodeId;
    public $neighbours = [];

    public function __construct($name)
    {
        $this->nodeId = $name;
    }

    public function addNeighbour($node, $distance, $edgeId)
    {
        $this->neighbours[] = ["node" => $node, "distance" => $distance, "edgeId" => $edgeId];
        //$node->neighbours[] = ["node" => $this, "distance" => $distance, "edgeId" => $edgeId];
    }
}

class Dijkstra
{

    public static function calculateShortestPathFrom($startNode, $endNode)
    {
        $queue = [$startNode];
        $startNode->distance = 0;

        while (count($queue) > 0) {
            usort($queue, function ($node1, $node2) {
                return $node1->distance <=> $node2->distance;
            });

            $currentNode = array_shift($queue);

            foreach ($currentNode->neighbours as $neighbour) {
                $altDistance = $currentNode->distance + $neighbour["distance"];
                if ($altDistance < $neighbour["node"]->distance) {
                    $neighbour["node"]->distance = $altDistance;
                    $neighbour["node"]->previous = $currentNode;
                    $neighbour["node"]->previousEdgeId = $neighbour["edgeId"];
                    array_push($queue, $neighbour["node"]);
                }
            }
        }

        $node = $endNode;
        $path = [];

        while ($node !== null) {
            if(isset($node->previousEdgeId)){
            array_push($path, $node->previousEdgeId);
            }
            else{
                array_push($path, 0);
            }
            $node = $node->previous;
        }
        array_pop($path);
        $path = array_reverse($path);

        
        return $path;
    }
}


// NODE SCOPE
{
    $db = new SQLite3("database.db");
    $stmt = $db->prepare('SELECT node_id FROM Node');


    $result = $stmt->execute();

    $rows_array = [];
    
    while ($row = $result->fetchArray()) {
        $rows_array[] = $row;
    }

    foreach ($rows_array as $row) {
        $node_id = $row['node_id'];
        $nodeObjects['node_' . $node_id] = new Node($node_id);
        //echo nl2br('node_' . $node_id."\n");
}

$node1 = new Node("Main Entrance");
$node2 = new Node("2");
$node3 = new Node("3");
$node4 = new Node("4");
$node5 = new Node("5");

// EDGE SCOPE


        $db = new SQLite3("database.db");
        $stmt = $db->prepare('SELECT edge_id, start_node_id, end_node_id, distance FROM Edges');

        $edgesResult = $stmt->execute();

    
        while ($row = $edgesResult->fetchArray()) {
            $edgesResult_array[] = $row;
        }
    
        foreach ($edgesResult_array as $row) {

            $startNode = $nodeObjects['node_'.$row['start_node_id']];

            $endNode = $nodeObjects['node_'.$row['end_node_id']];

            $nodeObjects['node_'.$row['start_node_id']]->addNeighbour($nodeObjects['node_'.$row['end_node_id']], $row['distance'], $row['edge_id']);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form was submitted, process the data
            $start = $_POST['startPoint'];
            echo nl2br("start index as: $start\n");
            $end = $_POST['endPoint'];
            echo nl2br("end index as: $end\n");
           
        }
?>

       
<!-- <form action="" method="post">
    <label for="start">Start Node:</label>
    <select name="start" id="start">
        <?#php foreach ($nodeObjects as $node): ?>
            <option value="<?#php echo $node->nodeId; ?>"><?#php echo $node->nodeId; ?></option>
        <?#php endforeach; ?>
    </select>

    <label for="end">End Node:</label>
    <select name="end" id="end">
        <?#php foreach ($nodeObjects as $node): ?>
            <option value="<?#php echo $node->nodeId; ?>"><?#php echo $node->nodeId; ?></option>
        <?#php endforeach; ?>
    </select>

    <input type="submit" value="Submit">
</form>
        commenting out html+php combo is a pain-->



<?php

$solutions = [];

    
    foreach ($nodeObjects as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }
    
    // Calculate shortest path

    $nodeObjects['node_'.$start]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodeObjects['node_'.$start], $nodeObjects['node_'.$end]);

    /*echo nl2br("Start at " . $path[1]->nodeId . "\n");
    for ($i = 1; $i < count($path); $i++) {
        // Print the direction and the node name
        // echo "Go " . $n->dir . " to " . $n->name . "\n";
        echo nl2br("Go to " . $path[$i]->nodeId . "\n");
    }
    echo nl2br("Total Edges: ".count($path)."\n");

    for ($i = 0; $i < count($path); $i++) {
        echo nl2br("Edge: ".$path[$i]."\n");
    }*/


    $db = new SQLite3("database.db");
    $stmt = $db->prepare("SELECT image, direction, notes FROM Edges WHERE edge_id IN(".implode(',',$path).")");

    $edgesResult = $stmt->execute();

    $final_path = [];
    while ($row = $edgesResult->fetchArray()) {
        $final_path[] = $row;
    }
    echo nl2br("final path steps: ". count($final_path)."\n");
    for ($i = 0; $i < count($final_path); $i++) {
        echo nl2br("Step ".($i+1)."\n");
        if($i+1 <= count($final_path)){
        //if($i+1 < count($final_path)){

            //$direction = $final_path[$i]['direction']-$final_path[$i+1]['direction'];
            $direction = $final_path[$i]['direction']-$final_path[$i+1]['direction'];
            switch($direction){
                case 0: 
                    echo 'straight';
                    break;
                case 1:
                case -3:
                    echo 'left';
                    break;
                case -1:
                case 3:
                    echo 'right';
                    break;
            }
            echo nl2br($direction."\n");
        }?>
<div class="image-box">
  <img src="./img/<?php echo $final_path[$i]['image'];?>" alt="Your Image">
</div>
<?php
        echo nl2br("IMG: ".$final_path[$i]['image']."\n");
    }
}
?>
