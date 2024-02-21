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
function check_for_precalculated_path($startPoint, $endPoint)
{
    $db = new SQLite3("database.db");
    $stmt = $db->prepare("SELECT path_id FROM Path WHERE (start_node_id = $startPoint) AND (end_node_id = $endPoint)");
    $result = $stmt->execute();

    $data = $result->fetchArray(SQLITE3_ASSOC);    
    return $data;
}

$exists = check_for_precalculated_path($startPoint, $endPoint);
if($exists== null)
{
    echo 'path not found';
    // Path does not exist in the database, calculate new path

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

    {
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
        //echo nl2br("start index as: $start\n");
        $end = $_POST['endPoint'];
        //echo nl2br("end index as: $end\n");

        }
    }

    foreach ($nodeObjects as $n) {
        $n->distance = PHP_INT_MAX;
        $n->previous = null;
    }
    
    // Calculate shortest path

    $nodeObjects['node_'.$startPoint]->distance = 0; // Set the starting node's distance to 0
    $path = Dijkstra::calculateShortestPathFrom($nodeObjects['node_'.$startPoint], $nodeObjects['node_'.$endPoint]);


    $db = new SQLite3("database.db");
    $stmt = $db->prepare("SELECT image, direction, notes FROM Edges WHERE edge_id IN(".implode(',',$path).")");

    $edgesResult = $stmt->execute();

    $final_path = [];
    while ($row = $edgesResult->fetchArray()) {
        $final_path[] = $row;
    }
    
    }
}
else
{
    // path already exists in database, use that instead
    echo 'path exists';
}

    calculate_relative_directions($final_path);
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
                        $path[$i]['direction'] = 'forward';
                        break;
                    case 1:
                    case -3:
                        $path[$i]['direction'] = 'left';
                        break;
                    case -1:
                    case 3:
                        $path[$i]['direction'] = 'right';
                        break;
                }
            }
        }
    }
    
    ?>
        <!-- 
<div class="image-box">
  <img src="./img/<?php echo $final_path[$i]['image'];?>" alt="Your Image">
</div>!--> 
<?php
        //echo nl2br("IMG: ".$final_path[$i]['image']."\n");
    

?>
