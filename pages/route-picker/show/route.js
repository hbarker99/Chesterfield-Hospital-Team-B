let json;
const apiRoute = '../api/' 
//let nodes;
//FetchNodesJSON();
FetchRouteJSON();


let currentStep = 0;



let totalSteps;

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

    fetchWithRetry(apiRoute + 'mapping-algo.php?start_node='+start+'&end_node='+end, 3)
    .then(process)
    .then(passedData=>{
        json = passedData;
        Display(0);

        totalSteps = json.length;
    }

    )
    .catch(error => {
        console.error('Error fetching JSON', error);
    });

}

function fetchWithRetry(url, retries) {
    return fetch(url)
    .catch(error => {
        if (retries === 1) {
            throw error;
        }
        return new Promise(resolve => {
            setTimeout(() => resolve(fetchWithRetry(url, retries - 1)), 100);
        });
    });
}

function Display(currentStep) {
    const instruction = document.getElementById('instruction');
    document.getElementById('image-id').src = '../../../assets/map/' + json[currentStep].image;
    document.getElementById('accessibility-notes').textContent = json[currentStep].accessibility_notes || null;
    instruction.innerHTML = json[currentStep].instruction;

    if (currentStep === 0) {
        previousStep.style.visibility = "hidden";
    }
    else if (currentStep === totalSteps - 1) {
        document.getElementById('instruction').textContent = "You have reached your destination.";
        nextStep.style.display = "none";
    }
    else {
        previousStep.style.visibility = "visible";
        nextStep.style.display = "block";
    }

    var arrowElement = document.getElementById("arrow");
    var direction = json[currentStep].direction;
    if (currentStep > 0)
        arrowElement.classList.remove(['left'], ['right']);

    if (direction != 'forward')
        setTimeout(() => arrowElement.classList.add(direction), 100);
}

function process(response) {
    if(!response.ok){
        //window.location.href = './error.html';
        throw new Error(response.error)

    }
    return response.json();
}

function FetchNodesJSON(){
    fetchWithRetry('../../components/dropdown/data-copy.php', 3)
    .then(process)
    .then(nodes_data=>{
        nodes =  nodes_data;
    })    
};