<?php 
include("sessionHandling.php");
?>
<!doctype html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="route.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chesterfield Group B</title>
    </head>

    <body id="bootstrap-overrides">
        <div class="route-container">
            <div class="header-container">
                <a href="index.php" class="back-btn">Pick another route</a>
            </div>
            <div class="image-box">
                <img src="./img/edge_2.jpg" />
            </div>


            <div class="info-box-container show">
                <div class="info-box">
                    <div id="toggle-visibility" onclick="ToggleVisibility()">
                        <div class="arrow down"></div>
                    </div>
                    <h4>Instruction</h4>
                    <div class="instruction-container">
                        <div class="instruction-highlight"></div>
                        <div class="instruction-text">At the next junction turn <b>Left</b></div>
                    </div>
                    <?php if(true) : ?>
                        <p>Additional notes</p>
                    <?php endif ?>
                    <div class="button-container">
                        <button class="btn btn-primary">Previous Step</button>
                        <button class="btn btn-primary">Next Step</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

<script>
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