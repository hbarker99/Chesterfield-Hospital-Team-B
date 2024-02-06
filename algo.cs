using System;
using System.Collections.Generic;

public class Node
{
    public int Distance = int.MaxValue;
    public Node Previous;
    public string Name;
    public Dictionary<Node, int> Neighbours = new Dictionary<Node, int>();

    public Node(string Name)
    {
        this.Name = Name;
    }
}

public class Dijkstra
{
    public static void CalculateShortestPathFrom(Node startNode)
    {
        var queue = new List<Node> { startNode };

        startNode.Distance = 0;

        while (queue.Count > 0)
        {
            queue.Sort((node1, node2) => node1.Distance.CompareTo(node2.Distance));

            var node = queue[0];
            queue.RemoveAt(0);

            foreach (var neighbour in node.Neighbours)
            {
                var altDistance = node.Distance + neighbour.Value;
                if (altDistance < neighbour.Key.Distance)
                {
                    neighbour.Key.Distance = altDistance;
                    neighbour.Key.Previous = node;
                    queue.Add(neighbour.Key);
                }
            }
        }
    }
}

class Program
{
    static void Main(string[] args)
    {
        // Create nodes
        Node node1 = new Node("Main Entrance");
        Node node2 = new Node("2");
        Node node3 = new Node("3");
        Node node4 = new Node("4");
        Node node5 = new Node("5");
        Node node6 = new Node("6");
        Node node7 = new Node("7");
        Node node8 = new Node("8");
        Node node9 = new Node("9");
        Node node10 = new Node("Chapel Junction");

        // Set neighbours
        node1.Neighbours[node2] = 2; // The number represents the distance between the nodes
        node2.Neighbours[node3] = 1;
        node3.Neighbours[node4] = 4;
        node4.Neighbours[node5] = 4;
        node5.Neighbours[node8] = 2;
        node5.Neighbours[node7] = 2;
        node4.Neighbours[node9] = 2;
        node9.Neighbours[node10] = 2;

        // Calculate shortest path
        Dijkstra.CalculateShortestPathFrom(node1);

        // Print shortest path to node C
        Node? node = node10;
        List<Node> path = new List<Node>();
        while (node != null)
        {
            path.Add(node);
            node = node.Previous;
        }
        path.Reverse();
        foreach(Node n in path)
        {
            Console.WriteLine($"Go to {n.Name}");
        }
    }
}
