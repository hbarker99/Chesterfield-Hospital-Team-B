<?php
class Node
{
    public $distance = PHP_INT_MAX;
    public $previous = null;
    public $name;
    public $neighbours = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addNeighbour($node, $distance)
    {
        $this->neighbours[] = ["node" => $node, "distance" => $distance];
        $node->neighbours[] = ["node" => $this, "distance" => $distance];
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
                    array_push($queue, $neighbour["node"]);
                }
            }
        }

        $node = $endNode;
        $path = [];

        while ($node !== null) {
            array_push($path, $node);
            $node = $node->previous;
        }

        $path = array_reverse($path);
        //self::$paths[[$startNode, $endNode]] = $path;
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
}


// EDGE SCOPE


        $db = new SQLite3("database.db");
        $stmt = $db->prepare('SELECT start_node_id, end_node_id, distance FROM Edges');

        $edgesResult = $stmt->execute();

    
        while ($row = $edgesResult->fetchArray()) {
            $edgesResult_array[] = $row;
        }
    
        foreach ($edgesResult_array as $row) {

            $startNode = $nodeObjects['node_'.$row['start_node_id']];

            $endNode = $nodeObjects['node_'.$row['end_node_id']];

            $nodeObjects['node_'.$row['start_node_id']]->addNeighbour($nodeObjects['node_'.$row['end_node_id']], $row['distance']);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form was submitted, process the data
            $start = $_POST['start'];
            echo nl2br("start index as: $start\n");
            $end = $_POST['end'];
            echo nl2br("end index as: $end\n");
           
        }
?>

<form action="" method="post">
    <label for="start">Start Node:</label>
    <select name="start" id="start">
        <?php foreach ($nodeObjects as $node): ?>
            <option value="<?php echo $node->name; ?>"><?php echo $node->name; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="end">End Node:</label>
    <select name="end" id="end">
        <?php foreach ($nodeObjects as $node): ?>
            <option value="<?php echo $node->name; ?>"><?php echo $node->name; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Submit">
</form>
<?php

    foreach ($nodeObjects as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }
    
    // Calculate shortest path

    $nodeObjects['node_'.$start]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodeObjects['node_'.$start], $nodeObjects['node_'.$end]);
<<<<<<< Updated upstream

    echo nl2br("Start at " . $path[0]->name . "\n");
    for ($i = 1; $i < count($path); $i++) {
        // Print the direction and the node name
        // echo "Go " . $n->dir . " to " . $n->name . "\n";
        echo nl2br("Go to " . $path[$i]->name . "\n");
=======
    echo nl2br("Start at " . $path[0]->nodeId . "\n");
    for ($i = 1; $i < count($path); $i++) {
        echo nl2br("Go to " . $path[$i]->nodeId . "\n");
>>>>>>> Stashed changes
    }
}
?>
