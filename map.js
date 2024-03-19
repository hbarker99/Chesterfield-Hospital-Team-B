var canvas, context;
var currentState;
var activeNodes;
var hoveredNode, hoveredEdge;
var selectedNode, selectedEdge;
var mousePos;

var primaryColor = 'red';
var secondaryColor = 'blue';

SetupCanvas();
SizeCanvas();

fetchDatabaseNodes();
fetchDatabaseEdges();

var canvasLeft = canvas.offsetLeft + canvas.clientLeft;
var canvasTop = canvas.offsetTop + canvas.clientTop;

const nodeSize = 20;
const edgeSize = 6;

var nodes = [];
let edges = [];

var addingNewNode = true;

canvas.addEventListener('click', (event) => {
    SetMousePos(event);
    HandleSelection();
    HandleHover();
});

canvas.addEventListener('mousemove', (event) => {
    SetMousePos(event);
    HandleHover();
});

function SetupNodes() {
    nodes.forEach(node => {
        DrawNode(node);
    });
}

function SetMousePos(event) {
    mousePos = { x: event.pageX - canvasLeft, y: event.pageY - canvasTop };
}

function DrawNode(node, fillColor = 'green') {
    if (currentState == "node" && node.node_id == selectedNode.node_id)
        fillColor = 'gold';

    context.beginPath();
    context.rect(node.x, node.y, nodeSize, nodeSize);
    context.fillStyle = fillColor;
    context.fill();
    context.lineWidth = 3;
    context.strokeStyle = '#003300';
    context.stroke();
}

function HandleSelection(event) {
    const name = "test";
    const category = 0;

    if (hoveredNode != null) {
        selectedNode = hoveredNode;
        DisplayNode();

        currentState = "node";
        return;
    }

    if (hoveredEdge != null) {
        selectedEdge = hoveredEdge;
        DisplayEdge();

        currentState = "edge";
        return;
    }

    if (currentState === "new door") {
        const x = mousePos.x;
        const y = mousePos.y;
        const newnode = { name, category, x, y };
        AddNewNode(newnode);

        return;
    }

    currentState = null;
}

function DisplayNode() {
    document.getElementById("edge-info-container").style.display = "none";

    var info = document.getElementById("info-container");

    const title = info.querySelector("#title");
    title.textContent = GetCategoryName(selectedNode.category);

    const specificInfo = info.querySelector("#node-info-container");
    specificInfo.style.display = "block";

    const name = specificInfo.querySelector("#visible-name");
    const nameInput = name.querySelector("input");
    nameInput.initialValue = 'Hiya';
    formEvents = nameInput.addEventListener("keyup", HandleInputChange);

    ResetCanvas();
    DrawConnectedNodes(selectedNode);
}

function ResetCanvas() {
    context.clearRect(0, 0, canvas.width, canvas.height);
}

function DrawConnectedNodes(currentNode) {
    connectedNodes = GetConnectedNodes(currentNode);
    DrawEdges(currentNode);

    connectedNodes.forEach(node => {
        DrawNode(node);
    });
    DrawNode(currentNode, 'gold');
}


function GetConnectedNodes(currentNode) {

    const connectedNodeIds = edges.filter(edge => edge.start_node_id === currentNode.node_id).map(edge => edge.end_node_id);
    const connectedNodes = connectedNodeIds.map(nodeId => nodes.find(node => node.node_id === nodeId));

    return connectedNodes;
}

function DisplayEdge() {
    document.getElementById("node-info-container").style.display = "none";

    var info = document.getElementById("info-container");

    const specificInfo = info.querySelector("#edge-info-container");
    specificInfo.style.display = "block";

    routes = ["one", "two"];

    routes.forEach(route => {
        const routeInfo = specificInfo.querySelector("#route-" + route);

        const startNode = GetNodeFromId(selectedEdge.start_node_id);
        const endNode = GetNodeFromId(selectedEdge.end_node_id);

        const startCategory = route === "one" ? startNode.category : endNode.category;
        const endCategory = route === "one" ? endNode.category : startNode.category;

        const primary = route === "one" ? primaryColor : secondaryColor;
        const secondary = route === "one" ? secondaryColor : primaryColor;

        routeInfo.querySelector("#route-title").innerHTML = "From <span style=\"color: " + primary + "\">" + GetCategoryName(startCategory) + "</span> to <span style=\"color: " + secondary + "\">" + GetCategoryName(endCategory) + "</span>";
    })

}

function GetNodeFromId(id) {
    return nodes.find(node => node.node_id === id);
}

function HandleInputChange(event) {
    
}

function SetupCanvas() {
    canvas = document.getElementById('map');
    context = canvas.getContext('2d');

    addEventListener("resize", event => {
        SizeCanvas();
        SetupNodes();
    });
}
function SizeCanvas() {
    canvasContainer = document.getElementById("canvas-container");

    canvas.style.width = canvasContainer.offsetWidth;
    canvas.style.height = canvasContainer.offsetHeight;
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}

function GetNodeAtLocation(selected, activeNodes = nodes) {
    return activeNodes.find(point => {
        return selected.y - nodeSize / 2 > point.y - ((nodeSize / 2) + 3)
            && selected.y - nodeSize / 2 < point.y + ((nodeSize / 2) + 3)
            && selected.x - nodeSize / 2 > point.x - ((nodeSize / 2) + 3)
            && selected.x - nodeSize / 2 < point.x + ((nodeSize / 2) + 3)
    });
}

function fetchDatabaseNodes() {
    fetch('getNodes.php')
        .then(response => response.json())
        .then(recievedNodes => {
            recievedNodes.forEach(node => nodes.push(node));
            SetupNodes();
        })
        .catch(error => {
            console.error('Error fetching nodes:', error);
        });
}


function fetchDatabaseEdges() {
    fetch('getEdges.php')
        .then(response => response.json())
        .then(data => {
            edges = data;
            console.log('Edges:', edges);
        })
        .catch(error => console.error('Error fetching edges:', error));
}


function HandleHover() {
    SetHoveredStates();
    ResetCanvas();
    DrawFrame();
}

function SetHoveredStates() {
    if (currentState === "node")
        activeNodes = GetConnectedNodes(selectedNode);

    else if (currentState === "edge")
        activeNodes = [
            GetNodeFromId(selectedEdge.start_node_id),
            GetNodeFromId(selectedEdge.end_node_id)
        ];

    else
        activeNodes = nodes;

    hoveredNode = GetNodeAtLocation({ x: mousePos.x, y: mousePos.y }, activeNodes);
}

function DrawFrame() {
    if (currentState === "node" || currentState === "edge")
        SetHoveredEdge();

    if (currentState === "node")
        DrawConnectedNodes(selectedNode);

    else if (currentState === "edge") {
        DrawEdge(activeNodes[0], activeNodes[1]);
        DrawNode(activeNodes[0], primaryColor);
        DrawNode(activeNodes[1], secondaryColor);
    }

    else
        SetupNodes();

    if (hoveredNode)
        DrawConnectedNodes(hoveredNode);
}

function SetHoveredEdge() {
    hoveredEdge = null;

    GetConnectedNodes(selectedNode).forEach(endNode => {
        nearestPoint = LinepointNearestMouse({ start: selectedNode, end: endNode }, mousePos.x, mousePos.y);
        var dx = nearestPoint.x - mousePos.x;
        var dy = nearestPoint.y - mousePos.y;

        const distance = Math.abs(Math.sqrt(dx * dx + dy * dy));

        if (distance < 10) {
            hoveredEdge = edges.find(edge => edge.start_node_id === selectedNode.node_id && edge.end_node_id === endNode.node_id);
        }
    })
}


function LinepointNearestMouse(line, x, y) {

    const endX = line.end.x + (nodeSize / 2);
    const endY = line.end.y + (nodeSize / 2);
    const startX = line.start.x + (nodeSize / 2);
    const startY = line.start.y + (nodeSize / 2);

    lerp = function (a, b, x) {
        return (a + x * (b - a));
    };
    var dx = endX - startX;
    var dy = endY - startY;

    var t = ((x - startX) * dx + (y - startY) * dy) / (dx * dx + dy * dy);

    var lineX = lerp(startX, endX, t);
    var lineY = lerp(startY, endY, t);

    smallX = Math.min(startX, endX) + 10;
    bigX = Math.max(startX, endX) - 10;
    smallY = Math.min(startY, endY) + 10;
    bigY = Math.max(startY, endY) - 10;

    clampedX = Math.max(Math.min(lineX, bigX), smallX);
    clampedY = Math.max(Math.min(lineY, bigY), smallY);

    return ({
        x: clampedX,
        y: clampedY
    });
};

function DrawEdges(currentNode) {
    connectedNodes = GetConnectedNodes(currentNode);
    connectedNodes.forEach(endNode => {
        DrawEdge(currentNode, endNode);
    });
}

function DrawEdge(startNode, endNode) {
    const start = { x: startNode.x + (nodeSize / 2), y: startNode.y + (nodeSize / 2) };
    const end = { x: endNode.x + (nodeSize / 2), y: endNode.y + (nodeSize / 2) };

    let edgeChecking = hoveredEdge;

    if (currentState === "edge")
        edgeChecking = selectedEdge;    

    //Highlight hovered edge / selected edge
    if (edgeChecking && edgeChecking.start_node_id === startNode.node_id && edgeChecking.end_node_id === endNode.node_id)
        DrawLine(start, end, 'gold', 6);

    DrawLine(start, end);
}

function DrawLine(start, end, color='black', thickness=3) {
    context.beginPath();
    context.moveTo(start.x, start.y);
    context.lineTo(end.x, end.y);
    context.strokeStyle = color;
    context.lineWidth = thickness;
    context.stroke();
}

function HighlightEdge(startNode, endNode) {
    context.beginPath();
    context.moveTo(startNode.x + (nodeSize / 2), startNode.y + (nodeSize / 2));
    context.lineTo(endNode.x + (nodeSize / 2), endNode.y + (nodeSize / 2));
    context.strokeStyle = 'gold';
    context.lineWidth = 1;
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
            DrawNode(newNode);
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