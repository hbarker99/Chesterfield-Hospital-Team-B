const points = [];

const buttons = [{
    text: "+ New Node",
    width: 50,
    height: 20
}]

const canvas = document.getElementById('map');
const context = canvas.getContext('2d');

var canvasLeft = canvas.offsetLeft + canvas.clientLeft;
var canvasTop = canvas.offsetTop + canvas.clientTop;

const nodeRadius = 30;

var nodes = []

var addingNewNode = true;

canvas.addEventListener('click', ClickCanvas)

SetupPoints();

function SetupPoints() {
    nodes = []

    points.forEach(point => {
        nodes.push(point);
        DrawPoint(point);
    })
}

function DrawPoint(location) {
    context.beginPath();
    context.arc(location.x, location.y, nodeRadius, 0, 2 * Math.PI, false);
    context.fillStyle = 'green';
    context.fill();
    context.lineWidth = 5;
    context.strokeStyle = '#003300';
    context.stroke();
}

function ClickCanvas(event) {
    const name = "test";
    const category = 25
    const x = event.pageX - canvasLeft;
    const y = event.pageY - canvasTop;

    const nodeSelected = GetSelectedNode({ x, y });

    if (nodeSelected != null) {
        console.log(nodeSelected);
        return;
    }
    const newnode = {name, category, x, y};

    AddNewNode(newnode);
}

function GetSelectedNode(selected) {
    return nodes.find(point => {
        return selected.y > point.y - nodeRadius
            && selected.y < point.y + nodeRadius
            && selected.x > point.x - nodeRadius
            && selected.x < point.x + nodeRadius
    });
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
            const newPoint = {
                id: data.id,
                name: node.name,
                category: node.category,
                x: node.x,
                y: node.y
            };
            points.push(newPoint);
            DrawPoint(newPoint);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Network or server error occurred');
    });
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