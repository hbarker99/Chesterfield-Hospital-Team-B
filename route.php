<?php 
include("sessionHandling.php");
include("mapping-algo.php");


if(isset($_POST['next'])){
    if($_SESSION['current_step'] < count($final_path) - 1) {
        $_SESSION['current_step']++;
    }
} else if (isset($_POST['previous'])){
    
    if($_SESSION['current_step'] > 0) {
        $_SESSION['current_step']--;
    }
}

if (!isset($_SESSION['show_instructions'])) {
    $_SESSION['show_instructions'] = true;
}
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
                <img src="./img/<?php echo $final_path[$_SESSION['current_step']]['image'] ?>" />
            </div>


            <div class="info-box-container">
                <div class="info-box">
                    <div id="toggle-visibility" class="btn btn-secondary" onclick="ToggleVisibility()">
                        <div class="arrow down"></div>
                    </div>
                    <div class="instruction-container">
                        <div class="instruction-highlight"></div>
                        <?php if($_SESSION['current_step'] == count($final_path) - 1) {?>
                            <div class="instruction-text">You have reached your destination.</div>
                        <?php } elseif($_SESSION['current_step'] == 0) { ?>
                            <div class="instruction-text">Begin facing the same direction as the image. Then continue forwards.</div>
                        <?php } else { ?>
                            <div class="instruction-text">At the next junction turn <b><?php echo $final_path[$_SESSION['current_step']]['direction'];?></b></div>
                        <?php } ?>
                    </div>
                    <?php if(true) : ?>
                        <div class="additional-notes">
                            <p>Additional notes</p>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <form method="post">
                <div class="button-container">
                    <input <?php if($_SESSION['current_step'] == 0) echo " style='visibility: hidden';"; ?> type="submit" class="btn btn-primary" name="previous" value="Back" />
                    <input <?php if($_SESSION['current_step'] == count($final_path) - 1) echo " style='visibility: hidden';"; ?> type="submit" class="btn btn-primary" name="next" value="Next" />
                </div>
            </form>
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