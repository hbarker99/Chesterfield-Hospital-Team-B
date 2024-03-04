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
    const x = event.pageX - canvasLeft;
    const y = event.pageY - canvasTop;

    const nodeSelected = GetSelectedNode({ x, y });

    if (nodeSelected != null) {
        console.log(nodeSelected);
        return;
    }

    AddNewNode({ x, y });
}

function GetSelectedNode(selected) {
    return nodes.find(point => {
        return selected.y > point.y - nodeRadius
            && selected.y < point.y + nodeRadius
            && selected.x > point.x - nodeRadius
            && selected.x < point.x + nodeRadius
    });
}

function AddNewNode(point) {
    const nodeIds = points.map(point => point.id);
    const newId = Math.max(Math.max(...nodeIds) + 1, 0);
    const newPoint = { x: point.x, y: point.y, id: newId };

    points.push(newPoint);
    SetupPoints();
}