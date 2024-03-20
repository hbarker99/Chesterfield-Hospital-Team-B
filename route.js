let json;
let nodes;
FetchNodesJSON();
FetchRouteJSON();


let currentStep = 0;



let totalSteps;
for(var e in nodes){
    document.write(e.name);
    console.log(e.name);
}

/*
$requestType = $_GET['request_type'];

switch($requestType){
    case 'fetch_nodes':
        // Call the function that handles fetch_nodes requests
        FetchNodesJSON();
        break;
    case 'fetch_route_json':
        // Call the function that handles fetch_route_json requests
        handleFetchRouteJSONRequest();
        break;
    default:
        // Handle unknown request type
        console.log("Unknown request type: $requestType");
        break;
}
*/
function FetchRouteJSON(){
    console.log("fetching JSON");

    const start = sessionStorage.getItem("startPoint");
    const end = sessionStorage.getItem("endPoint");

    console.log('mapping-algo.php?start_node='+start+'&end_node='+end);

    fetchWithRetry('mapping-algo.php?start_node='+start+'&end_node='+end, 3)
    .then(process)
    .then(passedData=>{
        json = passedData;
        UpdateArrows(0);
        Display(0);

        totalSteps = json.length;
    }

    )
    .catch(error => {
        console.error('Error fetching JSON', error);
        // Redirect to the custom error page
        window.location.href = './error.html';
    });

}

function fetchWithRetry(url, retries) {
    return fetch(url)
    .catch(error => {
        if (retries === 1) {
            throw error;
        }
        return new Promise(resolve => {
            setTimeout(() => resolve(fetchWithRetry(url, retries - 1)), 1000);
        });
    });
}

function Display(currentStep) {
    document.getElementById('instruction').innerHTML = json[currentStep].instruction;
    document.getElementById('image-id').src = './img/' + json[currentStep].image;
    document.getElementById('accessibility-notes').textContent = json[currentStep].accessibility_notes || null;

    var arrowElement = document.getElementById("arrow");
    var direction = json.direction;
    if (direction != 'forward'){
        setTimeout(() => arrowElement.classList.add(direction), 100);
    }
}

function process(response) {
    if(!response.ok){
        window.location.href = './error.html';
        throw new Error(response.error)

    }
    return response.json();
}

function FetchNodesJSON(){
    console.log('fetching nodes');
    fetchWithRetry('./components/dropdown/data-copy.php', 3)
    .then(process)
    .then(nodes_data=>{
        nodes =  nodes_data;
    })    
};