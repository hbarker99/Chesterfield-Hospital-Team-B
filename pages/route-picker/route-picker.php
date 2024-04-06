<?php
include_once('components/database.php');


include("components/indexPHP/startLocation.php");
require("pages/route-picker/sessionHandling.php");

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Route Picker</title>
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="pages/route-picker/route-picker.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
    </head>

    <body id="bootstrap-overrides">
        <div class="page-container">
            <div> 
                <div class="header-container">
                    <h1 class="page-title">Plan Your Route</h1>
                    <img src="../../assets/images/chesterfield_royal_hospital_logo.svg" alt="Chesterfield Royal Hospital Logo" class="logo">
                </div>
                <form method="post" action="pages/route-picker/indexRedirect.php" autocomplete="off">
                    <div class="form-container">
                        <?php if(!isset($_GET['location'])):?>
                        <div class="location-input form-item">
                            <label>Choose where you are</label>
                            <?php $DropdownId = 1; include './components/dropdown/dropdown.php'; ?>
                            <?php if (isset($_GET['location'])):?>
                            <input type="hidden" id="startPoint" name="startPoint" value="<?php echo $_GET['location']; ?>">
                            <?php else:?>
                            <input type="hidden" id="startPoint" name="startPoint" required>
                            <?php endif;?>
                        </div>
                        <?php endif;?>
                        <div class="location-input form-item">
                            <label>Choose where you want to go</label>
                            <?php $DropdownId = 2;include './components/dropdown/dropdown.php'; ?>
                            <input type="hidden" id="endPoint" name="endPoint" required>
                        </div>
                

                        <label class="mb-4 form-item checkbox-container"> Check for accessibility information
                            <input type="checkbox" id="accessibilityCheck" name="accessibilityCheck" />
                            <span class="checkmark"></span>
                        </label>

                        <div class="form-item form-button">
                            <button type="submit" name="getRoute" class="btn btn-primary">Get route</button>
                        </div>
                        <div class="form-item form-button">
                            <button type="submit" name="getRoutePDF"class="btn btn-primary">Get route as a PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require ("footer.php"); ?>
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script>
    const form = document.querySelector("form");


form.addEventListener(
    "submit",
    event => {
        invalidInputs = GetRouteDropdowns().filter(x => x.value === '');

        if (invalidInputs.length === 0){
            SetRoute();
            return;
        }

        invalidInputs.forEach(input => {
            input.parentNode.classList.add('error');
        })
        event.preventDefault();
    }
)
</script>

<script>
    function GetRouteDropdowns() {
        return [document.getElementById("startPoint"), document.getElementById("endPoint")]
    }

    async function SetRoute(){
        const start = document.getElementById("startPoint").value;
        const end = document.getElementById("endPoint").value

        sessionStorage.setItem("startPoint", start);
        sessionStorage.setItem("endPoint", end);
        console.log("Sart");
        await fetch("saveRouteInfo.php", {
            body: JSON.stringify({start_point: start, end_point: end })
        }).then(() => {console.log("Fin");});
        console.log(sessionStorage.getItem("startPoint"));
    }
</script>