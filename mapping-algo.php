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
    private static $paths = [];

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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form was submitted, process the data
    $start = $_POST['start'];
    echo nl2br("start index as: $start\n");
    $end = $_POST['end'];
    echo nl2br("end index as: $end\n");
   
}

$node1 = new Node("Main Entrance");
$node2 = new Node("2");
$node3 = new Node("3");
$node4 = new Node("4");
$node5 = new Node("5");

$node1->addNeighbour($node2, 2);
$node2->addNeighbour($node3, 1);
$node2->addNeighbour($node4, 3);

$node1->addNeighbour($node5, 10);

$node4->addNeighbour($node5, 1);

$nodes = [$node1, $node2, $node3, $node4, $node5];


?>

<form action="" method="post">
    <label for="start">Start Node:</label>
    <select name="start" id="start">
        <?php foreach ($nodes as $index => $node): ?>
            <option value="<?php echo $index; ?>"><?php echo $node->name; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="end">End Node:</label>
    <select name="end" id="end">
        <?php foreach ($nodes as $index => $node): ?>
            <option value="<?php echo $index; ?>"><?php echo $node->name; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Submit">
</form>
<?php

$solutions = [];

    foreach ($nodes as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }

    // Calculate shortest path

    $nodes[$start]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodes[$start], $nodes[$end]);

    echo nl2br("Start at " . $path[0]->name . "\n");
    for ($i = 1; $i < count($path); $i++) {
        // Print the direction and the node name
        // echo "Go " . $n->dir . " to " . $n->name . "\n";
        echo nl2br("Go to " . $path[$i]->name . "\n");
    }
?>
