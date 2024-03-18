var canvas, context;
formEvents = [];

SetupCanvas();
SizeCanvas();

var canvasLeft = canvas.offsetLeft + canvas.clientLeft;
var canvasTop = canvas.offsetTop + canvas.clientTop;

const nodeRadius = 20;

var nodes = []

var addingNewNode = true;

canvas.addEventListener('click', ClickCanvas)

function SetupPoints() {
    nodes.forEach(point => {
        DrawPoint(point);
    });

    DisplayNode(nodes[nodes.length - 1]);
}

function DrawPoint(location) {
    context.beginPath();
    context.rect(location.x, location.y, nodeRadius, nodeRadius);
    context.fillStyle = 'green';
    context.fill();
    context.lineWidth = 3;
    context.strokeStyle = '#003300';
    context.stroke();
}

function ClickCanvas(event) {
    const name = "test";
    const category = 0;
    const x = event.pageX - canvasLeft;
    const y = event.pageY - canvasTop;

    const nodeSelected = GetSelectedNode({ x, y });

    if (nodeSelected != null) {
        DisplayNode(nodeSelected);
        return;
    }
    const newnode = {name, category, x, y};

    AddNewNode(newnode);
}

function DisplayNode(node) {


    console.log(node)
    document.getElementById("edge-info-container").style.display = "none";

    var info = document.getElementById("info-container");

    const title = info.querySelector("#title");
    title.textContent = GetCategoryName(node.category);

    const specificInfo = info.querySelector("#node-info-container");
    specificInfo.style.display = "block";

    const name = specificInfo.querySelector("#visible-name");
    const nameInput = name.querySelector("input");
    nameInput.initialValue = 'Hiya';
    formEvents = nameInput.addEventListener("keyup", HandleInputChange);
}

function HandleInputChange(event) {
    
}

function DisplayEdge(edge) {
    document.getElementById("node-info-container").style.display = "none";

    var info = document.getElementById("edge-info-container");
    info.style.display = "block";

    console.log(info.querySelector("#title"));
}

function SetupCanvas() {
    canvas = document.getElementById('map');
    context = canvas.getContext('2d');

    addEventListener("resize", event => {
        SizeCanvas();
        SetupPoints();
    });
}
function SizeCanvas() {
    canvasContainer = document.getElementById("canvas-container");

    canvas.style.width = canvasContainer.offsetWidth;
    canvas.style.height = canvasContainer.offsetHeight;
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}

function GetSelectedNode(selected) {
    return nodes.find(point => {
        return selected.y > point.y - nodeRadius
            && selected.y < point.y + nodeRadius
            && selected.x > point.x - nodeRadius
            && selected.x < point.x + nodeRadius
    });
}

function fetchDatabaseNodes() {
    fetch('getNodes.php')
        .then(response => response.json())
        .then(recievedNodes => {
            recievedNodes.forEach(node => nodes.push(node));
            SetupPoints();
        })
        .catch(error => {
            console.error('Error fetching nodes:', error);
        });
}

let edges = [];

function fetchEdges() {
    fetch('getEdges.php')
        .then(response => response.json())
        .then(data => {
            edges = data;
            console.log('Edges:', edges);
        })
        .catch(error => console.error('Error fetching edges:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    fetchDatabaseNodes();
    fetchEdges();
});

canvas.addEventListener('mousemove', (event) => {
    const mouseX = event.pageX - canvasLeft;
    const mouseY = event.pageY - canvasTop;

    nodes.forEach(node => {
        const distance = Math.sqrt(Math.pow(mouseX - node.x, 2) + Math.pow(mouseY - node.y, 2));
        if (distance < nodeRadius) {
            highlightNodeAndEdges(node);
        }
    });
});

function highlightNodeAndEdges(node) {
    
    DrawPoint({ x: node.x, y: node.y, color: 'red' });

    
    edges.filter(edge => edge.start_node_id === node.id || edge.end_node_id === node.id)
         .forEach(edge => {
             const startNode = nodes.find(n => n.id === edge.start_node_id);
             const endNode = nodes.find(n => n.id === edge.end_node_id);
             drawEdge(startNode, endNode);
         });
}

function drawEdge(startNode, endNode) {
    context.beginPath();
    context.moveTo(startNode.x, startNode.y);
    context.lineTo(endNode.x, endNode.y);
    context.strokeStyle = '#ff0000'; 
    context.stroke();
}

function AddNewNode(node) {
    console.log("Sending node data:", node); //Testing
    fetch('addNode.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(node),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            alert('Error: ' + data.error); 
        } else {
            console.log('Success:', data);
            const newNode = {
                id: data.id,
                name: node.name,
                category: node.category,
                x: node.x,
                y: node.y
            };
            nodes.push(newNode);
            DrawPoint(newNode);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Network or server error occurred');
    });
}

function GetCategoryName(categoryId) {
    switch (categoryId) {
        case 0:
            return "Door";
            break;

        case 1:
            return "Entrance";
            break;

        case 2:
            return "Junction";
            break;

        case 3:
            return "Stairs";
            break;

        case 4:
            return "Destination";
            break;

        case 5:
            return "Corridor";
            break;
    }
}

function GetNodes() {
    //Return a list of node objects
    return [{ name: "New node", category: 1, x: 100, y: 100, node_id: 1 }]
}

function CreateNode(node) {
    const creatingNode = { name: "New node", category: 1, x: 100, y: 100 };
    //Create the node in the database
    //Return the new node id - just a number
}

function NewEdge() {

    //Start node,
    //End node
    //
}