<!doctype html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../../../style.css"/>
        <link rel="stylesheet" href="route.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Route - Chesterfield Group B</title>
        <script src="route.js"></script>
    </head>

    <body id="bootstrap-overrides">
        <div class="route-container">
            <div class="header-container">
                <a href="../../../index.php" class="back-btn">Pick another route</a>
            </div>
            <div class="image-box">
                <img id="image-id" src="" />
                <div class="direction-container">
                    <div class="direction-arrow" id="arrow"></div>
                </div>
            </div>

            <div class="info-box-container">
                <div class="info-box">
                    <div id="toggle-visibility" class="btn btn-secondary" onclick="ToggleVisibility()">
                        <div class="arrow down"></div>
                    </div>
                    <div class="instruction-container">
                        <div class="instruction-text" id="instruction"></div>
                    </div>
                        <div class="accessibility-notes" id="accessibility-notes"></div>
                </div>
            </div>
            <div class="button-container">
                <button id="previousStep" style="visibility: hidden"; class="btn btn-primary">Back</button>
                <button id="nextStep"class="btn btn-primary">Next</button>
            </div>
        </div>
    </body>

</html>

<script>

const previousStep = document.getElementById('previousStep');
const nextStep = document.getElementById('nextStep');


previousStep.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--; // Move to the previous step
        }

        Display(currentStep);
    });

nextStep.addEventListener('click', () => {
    if (currentStep < totalSteps - 1) {
        currentStep++; // Move to the next step
    }
    
    Display(currentStep);
});

function ToggleVisibility() {
    var element = document.getElementById("toggle-visibility");
    var parentElement = element.parentElement.parentElement;
    var arrowElement = element.querySelector(".arrow")

    var isHidden = parentElement.classList.contains("hidden")

    if (!isHidden) {
        parentElement.classList.add("hidden");
        element.classList.add("hidden")

        arrowElement.classList.remove("down");
        arrowElement.classList.add("up");
    } else {
        parentElement.classList.remove("hidden");
        element.classList.remove("hidden")

        arrowElement.classList.remove("up");
        arrowElement.classList.add("down");
    }
}
</script>
