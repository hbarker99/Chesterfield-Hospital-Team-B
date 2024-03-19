let json;

FetchRouteJSON();


let currentStep = 0;


let totalSteps;


function FetchRouteJSON(){
    console.log("fetching JSON");

    const start = sessionStorage.getItem("startPoint");
    const end = sessionStorage.getItem("endPoint");

    console.log('mapping-algo.php?start_node='+start+'&end_node='+end);

    fetch('mapping-algo.php?start_node='+start+'&end_node='+end)
    .then(process)
    .then(passedData=>{
        json = passedData;
        Display(0);
        UpdateArrows(0);
        totalSteps = json.length;
    }
        
    )
    .catch(error => {
        console.error('Error fetching JSON', error);
        // Redirect to the custom error page
        window.location.href = './error.html';
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

