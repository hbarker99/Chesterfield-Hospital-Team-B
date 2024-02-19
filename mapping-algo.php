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
    $db = new SQLite3("C:\\xampp\\htdocs\\chesterfield\\Chesterfield-Hospital-Team-B\\database.db");
    $stmt = $db->prepare('SELECT node_id FROM Node');


    $result = $stmt->execute();

    $rows_array = [];
    
    while ($row = $result->fetchArray()) {
        $rows_array[] = $row;
    }

    foreach ($rows_array as $row) {
        $node_id = $row['node_id'];
        $nodeObjects['node_' . $node_id] = new Node($node_id);
        echo nl2br('node_' . $node_id."\n");
}

$node1 = new Node("Main Entrance");
$node2 = new Node("2");
$node3 = new Node("3");
$node4 = new Node("4");
$node5 = new Node("5");

// EDGE SCOPE
$start = 2;
$end = 4;

        $db = new SQLite3("C:\\xampp\\htdocs\\chesterfield\\Chesterfield-Hospital-Team-B\\database.db");
        $stmt = $db->prepare('SELECT start_node_id, end_node_id, distance FROM Edges');

        $edgesResult = $stmt->execute();

    
        while ($row = $edgesResult->fetchArray()) {
            $edgesResult_array[] = $row;
        }
    
        foreach ($edgesResult_array as $row) {
            //echo $row['distance'];
            $startNode = $nodeObjects['node_'.$row['start_node_id']];
            echo nl2br($row['start_node_id'])."\n";
            echo nl2br( $row['end_node_id'])."\n";
            $endNode = $nodeObjects['node_'.$row['end_node_id']];
            //echo nl2br($endNode->."\n");
            $nodeObjects[$startNode]->addNeighbour($nodeObjects[$endNode], $row['distance']);
        }

$node1->addNeighbour($node2, 2);
$node2->addNeighbour($node3, 1);
$node2->addNeighbour($node4, 3);
$node1->addNeighbour($node5, 10);
$node4->addNeighbour($node5, 1);

$nodes = [$node1, $node2, $node3, $node4, $node5];
//EdgeCall($nodeObjects);

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

$solutions = [];

    $start = 2;
    $end = 4;
    
    foreach ($nodeObjects as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }

    // Calculate shortest path

    $nodeObjects[$start]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodeObjects[$start], $nodeObjects[$end]);

    echo nl2br("Start at " . $path[0]->name . "\n");
    for ($i = 1; $i < count($path); $i++) {
        // Print the direction and the node name
        // echo "Go " . $n->dir . " to " . $n->name . "\n";
        echo nl2br("Go to " . $path[$i]->name . "\n");
    }
}
?>
