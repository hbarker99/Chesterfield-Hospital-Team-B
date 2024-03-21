var canvas, context;
var currentState, isDragging;
var currentAddingCategory;
var activeNodes;
var hoveredNode, hoveredEdge;
var selectedNode, selectedEdge;

var newConnectionSelectedNodes = [];

var canvasPos, worldPos;

var mouseDown = false;

var offsetX = 0;
var offsetY = 0;
var primaryColor = '#AE2573';
var secondaryColor = '#78BE20';

var imageUploadInputs = [];

SetupCanvas();
SizeCanvas();

fetchDatabaseNodes();
fetchDatabaseEdges();

var canvasLeft = canvas.offsetLeft + canvas.clientLeft;
var canvasTop = canvas.offsetTop + canvas.clientTop;

let startX, startY;
let offsetStartX, offsetStartY;
const nodeSize = 20;
const edgeSize = 6;

var nodes = [];
let edges = [];

SetupEventListeners();
Reset();

function SetupEventListeners() {

    canvas.addEventListener('mousedown', () => {
        SetUpStartPos();
        mouseDown = true;
    });

    canvas.addEventListener('mouseup', () => {
        mouseDown = false;
        setTimeout(() => isDragging = false, 10);
    })

    canvas.addEventListener('click', (event) => {
        SetPositions(event);
        HandleSelection();
        Frame();
    });

    canvas.addEventListener('mousemove', (event) => {
        HandleMouseMove();
        SetPositions(event);
        Frame();
    });

    document.getElementById('delete').addEventListener('click', () => {
        HandleDelete();
        Frame();
    });

    document.getElementById('cancel').addEventListener('click', () => {
        HandleCancel();
        Frame();
    });

    document.getElementById('apply').addEventListener('click', () => {
        HandleApply();
        Frame();
    });

    document.getElementById('new-connection').addEventListener('click', () => {
        NewConnectionMode();
        Frame();
    });

    document.getElementById('new-door').addEventListener('click', () => {
        AddingActivity(0);
        Frame();
    });

    document.getElementById('new-entrance').addEventListener('click', () => {
        AddingActivity(1);
        Frame();
    });

    document.getElementById('new-junction').addEventListener('click', () => {
        AddingActivity(2);
        Frame();
    });

    document.getElementById('new-corridor').addEventListener('click', () => {
        AddingActivity(5);
        Frame();
    });

    document.getElementById('new-destination').addEventListener('click', () => {
        AddingActivity(4);
        Frame();
    });

    const routeOne = document.getElementById('route-one');
    const routeTwo = document.getElementById('route-two');

    const routeOneFileUpload = routeOne.querySelector('input');
    const routeTwoFileUpload = routeTwo.querySelector('input');

    routeOne.querySelector('button').addEventListener("click", () => routeOneFileUpload.click());
    routeTwo.querySelector('button').addEventListener("click", () => routeTwoFileUpload.click());
}

function HandleDelete() {
    if (currentState === "edge") {
        const confirmed = confirm("Are you sure you want to delete this edge?");

        if (!confirmed)
            return;

        DeleteEdge(selectedEdge, true);
    }

    else if (currentState === "node") {
        const confirmed = confirm("Are you sure you want to delete this node and all it's connections?");

        if (!confirmed)
            return;

        DeleteNode(selectedNode, true);
    }
}

function HandleMouseMove(event) {
    if (mouseDown)
        isDragging = true;

    if (isDragging)
        PanCanvas();
}
function SetUpStartPos(){
    startX = canvasPos.x;
    startY = canvasPos.y;

    offsetStartX = offsetX;
    offsetStartY = offsetY;
}
function HandleCancel() {
    Reset();
}

function HandleApply() {
    if (currentState === "node") {
        const nodeId = selectedNode.node_id;
        const newName = document.querySelector('#visible-name input').value;
        UpdateNodeName(nodeId, newName);
    }

    else if (currentState === "edge") {
        UpdateImages();
    }
}

function SetupNodes() {
    nodes.forEach(node => {
        DrawNode(node, GetCategoryColour(node.category));
    });
}

function SetPositions(event) {
    canvasPos = { x: event.pageX - canvasLeft, y: event.pageY - canvasTop };
    worldPos = { x: event.pageX - canvasLeft + offsetX, y: event.pageY - canvasTop + offsetY};
}

function DrawNode(node, fillColor = 'green') {
    if (currentState == "node" && node.node_id == selectedNode.node_id)
        fillColor = 'gold';

    context.beginPath();
    context.rect(node.x - offsetX, node.y - offsetY, nodeSize, nodeSize);
    context.fillStyle = fillColor;
    context.fill();
    context.lineWidth = 2;
    context.strokeStyle = '#003300';
    context.stroke();
}

function HandleSelection(event) {
    if (isDragging && DistanceMovedSinceDrag() > 3)
        return;   

    if (currentState === "connection") {
        if (hoveredNode != null) {
            const alreadySelected = newConnectionSelectedNodes.findIndex(node => node.node_id === hoveredNode.node_id);
            if (alreadySelected < 0 && newConnectionSelectedNodes.length < 2)
                newConnectionSelectedNodes.push(hoveredNode);

            else if (alreadySelected > -1)
                newConnectionSelectedNodes.splice(alreadySelected, 1);
        }

        DisplayConnectionInformation();

        if (newConnectionSelectedNodes.length == 2)
            CreateConnection();

        return;
    }

    if (hoveredNode != null) {
        if (selectedNode && hoveredNode.node_id === selectedNode.node_id)
            DeselectNode();
        else
            SelectNode(hoveredNode);

        return;
    }

    if (hoveredEdge != null) {
        if (selectedEdge && hoveredEdge.edge_id === selectedEdge.edge_id)
            DeselectEdge();

        else
            SelectEdge(hoveredEdge);

        return;
    }

    if (currentState === "new node") {
        const newNode = {
            name: "",
            category: currentAddingCategory,
            x: worldPos.x - nodeSize/2,
            y: worldPos.y - nodeSize/2
        };
        console.log(newNode)
        Reset();
        AddNewNode(newNode);
        return;
    }

    Reset();
}

function DistanceMovedSinceDrag() {
    return Math.sqrt(Math.pow(startX - canvasPos.x, 2) + Math.pow(startY - canvasPos.y, 2));
}

function SelectEdge(edge) {
    if (selectedNode == null)
        selectedNode = nodes.find(node => node.node_id === edge.start_node_id);

    selectedEdge = edge;
    DisplayEdgeInfo();

    currentState = "edge";
}

function SelectNode(node) {
    selectedNode = node;
    DisplayNodeInfo();

    currentState = "node";
}

function DeselectNode() {
    Reset();
}

function DeselectEdge() {
    Reset();
    hoveredEdge = null;
}

function DeleteEdge(edge, requiresReset) {

    fetch('deleteEdge.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(edge)
    })
        .then((response) => {
            if (!response.ok) {
                alert("Failed to delete edge.");
                throw new Error("Failed to delete");
            }
            edges.splice(edges.findIndex(edge => edge.start_node_id === selectedEdge.start_node_id && edge.end_node_id === selectedEdge.end_node_id), 1);
            edges.splice(edges.findIndex(edge => edge.start_node_id === selectedEdge.end_node_id && edge.end_node_id === selectedEdge.start_node_id), 1);

            if (requiresReset) {
                Reset();
                Frame();
            }
        });
}

function DeleteNode(node) {

    GetNodeEdges().forEach(edge => DeleteEdge(edge, false));

    fetch('deleteNode.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(selectedNode)
    })
        .then((response) => {
            if (!response.ok) {
                alert("Failed to delete node.");
                throw new Error("Failed to node");
            }
            nodes.splice(nodes.findIndex(nodeInList => nodeInList.node_id === node.node_id), 1);

            Reset();
            Frame();
        });
}

function GetNodeEdges() {
    return edges.filter(edge => edge.start_node_id === selectedNode.node_id);
}


function DisplayNodeInfo() {
    const specificInfo = ResetInformationTo('node');

    const title = specificInfo.querySelector("#title");
    title.textContent = GetNodeName(selectedNode);

    const name = specificInfo.querySelector("#visible-name");
    const nameInput = name.querySelector("input");
    nameInput.value = selectedNode.name;

}

function ResetCanvas() {
    context.clearRect(0, 0, canvas.width, canvas.height);
}

function DrawConnectedNodes(currentNode) {
    connectedNodes = GetConnectedNodes(currentNode);
    DrawEdges(currentNode);

    connectedNodes.forEach(node => {
        DrawNode(node, GetCategoryColour(node.category));
    });
    DrawNode(currentNode, 'gold');
}

function GetConnectedNodes(currentNode) {

    const connectedNodeIds = edges.filter(edge => edge.start_node_id === currentNode.node_id).map(edge => edge.end_node_id);
    const connectedNodes = connectedNodeIds.map(nodeId => nodes.find(node => node.node_id === nodeId));

    return connectedNodes;
}

function DisplayEdgeInfo() {
    const specificInfo = ResetInformationTo('edge');

    const routes = ["one", "two"];

    routes.forEach(route => {
        const routeInfo = specificInfo.querySelector("#route-" + route);

        const startNode = GetNodeFromId(selectedEdge.start_node_id);
        const endNode = GetNodeFromId(selectedEdge.end_node_id);

        const startDisplayNode = route === "one" ? startNode : endNode;
        const endDisplayNode = route === "one" ? endNode : startNode;

        const primary = route === "one" ? primaryColor : secondaryColor;
        const secondary = route === "one" ? secondaryColor : primaryColor;

        routeInfo.querySelector("#route-title").innerHTML = "From <span style=\"color: " + primary + "\">" + GetNodeName(startDisplayNode) + "</span> to <span style=\"color: " + secondary + "\">" + GetNodeName(endDisplayNode) + "</span>";
    
        const imageContainer = routeInfo.querySelector("img");
        imageContainer.style.display = "block";

        const image = GetEdgeImagePath(startDisplayNode, endDisplayNode);

        const uploadButton = routeInfo.querySelector("button");


        if (image) {
            imageContainer.src = image;
            uploadButton.textContent = "Change";
        }
        else {
            imageContainer.style.display = "none";
            uploadButton.textContent = "Upload";

        }
        SetupImageChangeListeners(routeInfo);
    
    })
}

function UpdateImages() {

    imageUploadInputs.forEach(imageUploadElement => {
        if (imageUploadElement.files.length == 0)
            return;

        const file = imageUploadElement.files[0];

        const parentId = imageUploadElement.parentElement.parentElement.id;
        const routeOne = parentId.includes("one");

        let edge;

        if (routeOne)
            edge = selectedEdge;

        else
            edge = GetEdge(selectedEdge.end_node_id, selectedEdge.start_node_id);

        console.log(edge);

        UploadImage(file).then((response) => {
            const edgePayload = {
                edge_id: edge.edge_id,
                image_name: file.name
            };
            
            fetch("updateEdge.php", {
                method: "POST",
                body: JSON.stringify(edgePayload)
            }).then(response => {
                edge.image = file.name;
                Reset();
                Frame();
            });
        })
    })
}

function UploadImage(file) {
    var data = new FormData();
    data.append('uploading', file);

    return fetch("uploadImage.php", {
        method: "POST",
        body: data
    })
        .then((response) => {
            if (!response.ok)
                throw new Error("File failed to upload");

            return response;
    })
}

function SetupImageChangeListeners(routeInfo) {
    const imageUploadInput = routeInfo.querySelector('#image-edge-upload');

    imageUploadInput.addEventListener('change', PreviewImage);
    imageUploadInputs.push(imageUploadInput);
}
function PreviewImage(event) {
    const file = this.files[0];
    const imageDisplay = this.parentElement.querySelector("img");
    imageDisplay.src = URL.createObjectURL(file);
    imageDisplay.style.display = "block";
}

function GetEdgeImagePath(startNode, endNode) {
    const edge = GetEdge(startNode.node_id, endNode.node_id);

    if (edge.image)
        return "./img/" + edge.image;

    else
        return null;
}

function GetEdge(startNodeId, endNodeId) {
    return edges.find(edge => edge.start_node_id === startNodeId && edge.end_node_id === endNodeId);
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

function GetNodeAtLocation(location, activeNodes = nodes) {
    return activeNodes.find(point => {
        return location.y - nodeSize / 2 > point.y - ((nodeSize / 2) + 3)
            && location.y - nodeSize / 2 < point.y + ((nodeSize / 2) + 3)
            && location.x - nodeSize / 2 > point.x - ((nodeSize / 2) + 3)
            && location.x - nodeSize / 2 < point.x + ((nodeSize / 2) + 3)
    });
}

function fetchDatabaseNodes() {
    fetch('getNodes.php')
        .then(response => response.json())
        .then(recievedNodes => {

            recievedNodes = recievedNodes.map(node => {
                return {
                    name: node.name,
                    category: parseInt(node.category) - 1,
                    x: parseInt(node.x),
                    y: parseInt(node.y),
                    node_id: parseInt(node.node_id)
                }
            });
            console.log(recievedNodes);
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
            edges = data.map(edge => {
                return {
                    edge_id: parseInt(edge.edge_id),
                    start_node_id: parseInt(edge.start_node_id),
                    end_node_id: parseInt(edge.end_node_id),
                    distance: parseInt(edge.distance),
                    image: edge.image,
                    notes: edge.notes,
                    accessibility_notes: edge.accessibility_notes
                }
            });
        })
        .catch(error => console.error('Error fetching edges:', error));
}

function UpdateNodeName(nodeId, newName) {
    const payload = { id: nodeId, name: newName };
    fetch('updateNode.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const result = GetNodeFromId(nodeId);

            result.name = newName;

            Reset();
            Frame();
        } else {
            alert('Failed to update node name: ' + data.message);
        }
    })
    .catch(error => console.error('Error updating node name:', error));
    ResetSelectedInformation();
    Frame();
}


function Frame() {
    SetHoveredStates();
    ResetCanvas();
    DrawFrame();
    SetCursor();
}

function SetCursor() {
    if (hoveredEdge || hoveredNode)
        canvas.style.cursor = 'pointer';
    else
        canvas.style.cursor = 'default';
}

function SetHoveredStates() {
    if (currentState === "edge")
        activeNodes = [
            GetNodeFromId(selectedEdge.start_node_id),
            GetNodeFromId(selectedEdge.end_node_id)
        ];

    else
        activeNodes = nodes;

    hoveredNode = GetNodeAtLocation({ x: worldPos.x, y: worldPos.y }, activeNodes);


    if (currentState === "node" || currentState === "edge")
        SetHoveredEdge();
}

function DrawFrame() {
    if (currentState === "node") {
        SetupNodes();
        DrawConnectedNodes(selectedNode);
    }

    else if (currentState === "edge") {
        DrawEdge(activeNodes[0], activeNodes[1]);
        DrawNode(activeNodes[0], primaryColor);
        DrawNode(activeNodes[1], secondaryColor);
    }

    else
        SetupNodes();

    if (currentState === "connection") {
        const from = newConnectionSelectedNodes[0];
        const to = newConnectionSelectedNodes[1];

        if (from)
            DrawNode(from, "gold");

        if (to)
            DrawNode(to, "gold");
    }

    if (hoveredNode)
        DrawConnectedNodes(hoveredNode);
}

function SetHoveredEdge() {
    hoveredEdge = null;

    GetConnectedNodes(selectedNode).forEach(endNode => {
        if (hoveredNode)
            return;

        nearestPoint = LinepointNearestMouse({ start: selectedNode, end: endNode }, worldPos.x, worldPos.y);
        var dx = nearestPoint.x - worldPos.x;
        var dy = nearestPoint.y - worldPos.y;

        const distance = Math.abs(Math.sqrt(dx * dx + dy * dy));

        if (distance < 10) {
            hoveredEdge = GetEdge(selectedNode.node_id, endNode.node_id);
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
    const start = { x: startNode.x + (nodeSize / 2) - offsetX, y: startNode.y + (nodeSize / 2) - offsetY };
    const end = { x: endNode.x + (nodeSize / 2) - offsetX, y: endNode.y + (nodeSize / 2) - offsetY };

    let edgeChecking = hoveredEdge;

    if (currentState === "edge")
        edgeChecking = selectedEdge;

    // Highlight hovered edge / selected edge
    if (edgeChecking && edgeChecking.start_node_id === startNode.node_id && edgeChecking.end_node_id === endNode.node_id)
        DrawLine(start, end, 'gold', 6);

    DrawLine(start, end);
}

function DrawLine(start, end, color = 'black', thickness = 3) {
    context.beginPath();
    context.moveTo(start.x, start.y);
    context.lineTo(end.x, end.y);
    context.strokeStyle = color;
    context.lineWidth = thickness;
    context.stroke();
}

function HighlightEdge(startNode, endNode) {
    context.beginPath();
    context.moveTo(startNode.x + (nodeSize / 2), startNode.y + (nodeSize / 2) );
    context.lineTo(endNode.x + (nodeSize / 2), endNode.y + (nodeSize / 2));
    context.strokeStyle = 'gold';
    context.lineWidth = 1;
    context.stroke();
}

function AddNewNode(node) {
    node.category += 1;
    
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
                node_id: data.id,
                name: node.name,
                category: node.category - 1,
                x: node.x,
                y: node.y
            };
            nodes.push(newNode);
            DrawNode(newNode);
            SelectNode(newNode);
            Frame();
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Network or server error occurred');
    });
}

function GetCategoryColour(categoryId) {
    switch (categoryId) {
        case 0:
            return "#41B6E6";

        case 1:
            return "#0072CE";

        case 2:
            return "#009639";

        case 3:
            return "#78BE20";

        case 4:
            return "#AE2573";

        case 5:
            return "#FFFFFF";

        default:
            return "green";
    }
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

function GetNodeName(node) {

    if (node.name)
        return node.name;

    return GetCategoryName(node.category);
}


function NewConnectionMode() {
    ResetSelectedInformation();
    DisplayConnectionInformation();

    currentState = "connection";
}

function CreateConnection() {
    const to = newConnectionSelectedNodes[0];
    const from = newConnectionSelectedNodes[1];

    const edge = {
        start_node_id: from.node_id,
        end_node_id: to.node_id,
        distance: 1,
        direction: GetDirection(from.node_id, to.node_id)
    };

    fetch('createEdge.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(edge),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            return response.json();
        })
        .then(json => {
            const alternate = {
                edge_id: json.id,
                start_node_id: to.node_id,
                end_node_id: from.node_id,
                distance: 1,
                direction: (edge.direction + 2) % 4
            }

            edge.edge_id = json.id - 1;

            edges.push(edge);
            edges.push(alternate);
        
            SelectEdge(edge);
            Frame();
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Network or server error occurred');
        });
}

function GetDirection(startNodeId, endNodeId) {
    const start = GetNodeFromId(startNodeId);
    const end = GetNodeFromId(endNodeId);

    const dx = end.x - start.x;
    const dy = end.y - start.y;

    let theta;

    if (dx === 0) {
        if (dy === 0)
            theta = 0;

        else if (dy > 0)
            theta = 90;

        else
            theta = -90;

    }

    else if (dy == 0) {
        if (dx > 0)
            theta = 0;

        else
            theta = 180;
    }

    else
        theta = Math.atan2(dy / dx);

    thetaDeg = theta / Math.PI;
    return AngleToDirection(thetaDeg);
    
}

function AngleToDirection(angle) {
    if (angle > 135)
        return 4;

    if (angle > 45)
        return 1;

    if (angle > -45)
        return 2;

    if (angle > -135)
        return 3

    return 4;
}

function DisplayConnectionInformation() {
    const specificInfo = ResetInformationTo("connection", false, false);

    specificInfo.querySelector(".title").textContent = "Creating new connection";

    const nodeFrom = newConnectionSelectedNodes[0];
    const nodeTo = newConnectionSelectedNodes[1];

    const fromText = specificInfo.querySelector("#from");
    const toText = specificInfo.querySelector("#to");

    fromText.textContent = nodeFrom ? "From " + GetNodeName(nodeFrom) : "";
    toText.textContent = nodeTo ? "To " + GetNodeName(nodeTo) : "";
}

function Reset() {
    ResetInformationTo();
    ResetSelectedInformation();
    Frame();
}

function ResetSelectedInformation() {
    newConnectionSelectedNodes = [];
    selectedNode = null;
    selectedEdge = null;
    currentState = null;
    currentAddingCategory = null;
    imageUploadInputs = [];
}
function ResetInformationTo(displaying, includeApplyButton = true, includeDeleteButton = true) {
    document.getElementById("connection-info-container").style.display = "none";
    document.getElementById("node-info-container").style.display = "none";
    document.getElementById("edge-info-container").style.display = "none";
    document.getElementById("adding-info-container").style.display = "none";

    const buttons = document.getElementById("button-container");
    buttons.style.display = "none";
    document.getElementById("apply").style.display = "block";
    document.getElementById("delete").style.display = "block";


    if (!displaying)
        return;

    buttons.style.display = "flex";

    if(!includeApplyButton)
        document.getElementById("apply").style.display = "none";

    if (!includeDeleteButton)
        document.getElementById("delete").style.display = "none";

    var displaying = document.getElementById(displaying + "-info-container");
    displaying.style.display = "block";

    return displaying;
}


function AddingActivity(addingCategory) {
    Reset();

    currentAddingCategory = addingCategory;
    const specificInfo = ResetInformationTo("adding", false, false);
    specificInfo.querySelector(".title").textContent = "New " + GetCategoryName(addingCategory);
    currentState = "new node";
}

// Add function to handle panning
function PanCanvas() {
    offsetX =  offsetStartX + startX - canvasPos.x;
    offsetY = offsetStartY + startY - canvasPos.y;
    ResetCanvas();
    SetupNodes(); // Redraw all nodes and edges with new pan offset
    let x = String(-offsetX);
    let y = String(-offsetY);
    moveMap(x, y);
}
function moveMap(x, y) {
    document.getElementById("map").style.backgroundPosition = x + 'px ' + y + 'px';
    // $("map").css('background-position', x+'px '+y+'px');
    Frame();
}
