using System;
using System.Collections.Generic;

public class Node
{
    public int Distance = int.MaxValue;
    public Node Previous;
    public string Name;
    public Dictionary<Node, int> Neighbours = new Dictionary<Node, int>();
    // Add a property for the direction
    //public Direction? Dir;

    public Node(string Name)
    {
        this.Name = Name;
    }

    // method should make the neighbour relationship bi-directional
    public void AddNeighbour(Node node, int distance)
    {
        Neighbours[node] = distance;
        node.Neighbours[this] = distance;
    }
}

public class Dijkstra
{
    // list for the
    private static Dictionary<(Node, Node), List<Node>> paths = new Dictionary<(Node, Node), List<Node>>();

    public static List<Node> CalculateShortestPathFrom(Node startNode, Node endNode)
    {
        if (paths.ContainsKey((startNode, endNode)))
        {
            Console.WriteLine("Route already found");
            return paths[(startNode, endNode)];
        }

        var queue = new List<Node> { startNode };

        startNode.Distance = 0;

        while (queue.Count > 0)
        {
            queue.Sort((node1, node2) => node1.Distance.CompareTo(node2.Distance));

            var currentNode = queue[0];
            queue.RemoveAt(0);

            foreach (var neighbour in currentNode.Neighbours)
            {
                var altDistance = currentNode.Distance + neighbour.Value;
                if (altDistance < neighbour.Key.Distance)
                {
                    neighbour.Key.Distance = altDistance;
                    neighbour.Key.Previous = currentNode;
                    queue.Add(neighbour.Key);
                }
            }
        }

        // Storing the path
        Node? node = endNode;
        List<Node> path = new List<Node>();
        while (node != null)
        {
            path.Add(node);
            node = node.Previous;
        }
        path.Reverse();

        paths[(startNode, endNode)] = path;

        return path;
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
        Node node6 = new Node("Suite 8");
        Node node7 = new Node("Emergency Entrance");
        Node node8 = new Node("8");
        Node node9 = new Node("9");
        Node node10 = new Node("Chapel");
        Node node11 = new Node("11");
        Node node12 = new Node("12");
        Node node13 = new Node("Suite 3");
        Node node14 = new Node("Suite 3");
        Node node15 = new Node("Suite 1");
        Node node16 = new Node("Cafe @ The Royal");



        // Set neighbours using 
        node1.AddNeighbour(node2, 2);
        node2.AddNeighbour(node3, 1);
        node2.AddNeighbour(node15, 3);
        node15.AddNeighbour(node16, 1);
        node3.AddNeighbour(node4, 4);
        node3.AddNeighbour(node12, 5);
        node4.AddNeighbour(node5, 4);
        node5.AddNeighbour(node6, 2);
        node5.AddNeighbour(node8, 2);
        node5.AddNeighbour(node7, 2);
        node4.AddNeighbour(node9, 2);
        node9.AddNeighbour(node10, 2);
        node8.AddNeighbour(node11, 2);
        node11.AddNeighbour(node12, 1);
        node12.AddNeighbour(node13, 1);


        List<Node> nodes = new List<Node>
        {
            node1,
            node2,
            node3,
            node4,
            node5,
            node6,
            node7,
            node8,
            node9,
            node10,
            node11,
            node12,
            node13,
            node14,
            node15,
            node16
        };
        List<List<Node>> solutions = new List<List<Node>>();

        while (true)
        {
            foreach (Node n in nodes)
            {
                n.Distance = int.MaxValue;
                n.Previous = null;
            }

            // Calculate shortest path
            Console.WriteLine("Choose Node to start at");
            int start = int.Parse(Console.ReadLine());
            Console.WriteLine("Choose Node to end at");
            int end = int.Parse(Console.ReadLine());
            Console.WriteLine();
            nodes[start - 1].Distance = 0; // Set the starting node's distance to 0
            List<Node> path = Dijkstra.CalculateShortestPathFrom(nodes[start - 1], nodes[end - 1]);

            // Print shortest path to node C
            //Node? node = nodes[end - 1];
            //List<Node> path = new List<Node>();
            //while (node != null)
            //{
            //    path.Add(node);
            //    node = node.Previous;
            //}
            //path.Reverse();
            //solutions.Add(path);
            Console.WriteLine($"Start at {path[0].Name}");
            for (int i = 1; i < path.Count; i++)
            {
                // Print the direction and the node name
                // Console.WriteLine($"Go {n.dir} to {n.Name}");
                Console.WriteLine($"Go to {path[i].Name}");
            }
            Console.WriteLine();
        }
    }
}
