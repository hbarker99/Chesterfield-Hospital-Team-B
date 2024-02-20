<?php require("navBar.php");
include_once("dbString.php");
include("./components/indexPHP/startLocation.php");
require ("footer.php");
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chesterfield Group B</title>
    </head>

    <body id="bootstrap-overrides">
        <div>
            <h1>Welcome!
                <?php if(isset($_GET['location'])):
                $startLocation = startLocation(); ?>
                You are at: <?php echo($startLocation);?>
                <?php endif;?>
            </h1>
        </div>
        <div> 
            <form method="post" action="mapping-algo.php">
                <div>
                    <p>Enter where you are</p>
                    <?php $DropdownId = 1; include './components/dropdown/dropdown.php'; ?>
                </div>
                <div>
                    <p>Enter where you want to go</p>
                    <?php $DropdownId = 2;include './components/dropdown/dropdown.php'; ?>
                </div>

                <input type="hidden" id="startPoint" name="startPoint">
                <input type="hidden" id="endPoint" name="endPoint">

                <div class="mb-4 form-switch">
                    <input type="checkbox" class="form-check-input" role="switch" id="accessibilityCheck" name="accessibilityCheck">
                    <label class="form-check-label" for="accessibilityCheck">Check for accessibility information</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Get route</button>
                </div>
            </form>
        </div>
    </body>
</html>