<?php 

// Object oriented representation of a point(Node) in a graph
// This could be a door, endpoint or junction in a map.

class Node
{
    public $distance = PHP_INT_MAX;     // Set to max during initialisation
    public $previous = null;            // Reference to previous node in shortest path
    public $nodeId;                     // A unique ID for the node
    public $neighbours = [];            // Array serving as adjacency list

    // Constructor for creating new Node object

    public function __construct(string $name)
    {
        $this->nodeId = $name;
    }

    /**
     * Adds a neighbouring node to the current node.
     *
     * @param Node   $node     The neighbouring Node object.
     * @param int    $distance The distance to the neighbouring node.
     * @param string $edgeId   Identifier for the connecting edge. Used in PDF script.
     */
    public function addNeighbour(Node $node, int $distance, string $edgeId)
    {
        $this->neighbours[] = ["node" => $node, "distance" => $distance, "edge_id" => $edgeId];
    }
} ?>