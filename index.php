<?php
require("navBar.php");
include_once("dbString.php");

function locationFill(){

    $db = new SQLite3(get_string());
    $stmt = $db->prepare('SELECT name FROM Node WHERE endpoint=1');
    $result = $stmt->execute();
    $rows_array = [];
    while ($row=$result->fetchArray())
    {
        $rows_array[]=$row;
    }
    return $rows_array;
}

function startLocation() {
    $db = new SQLite3(get_string());
    $stmt = $db->prepare('SELECT name FROM Node WHERE node_id=:nodeId');
    $stmt->bindParam(':nodeId', $_GET['location'], SQLITE3_INTEGER);
    $result = $stmt->execute();
    $data = $result->fetchArray(SQLITE3_ASSOC);    
    return $data['name'];
}
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
    <h1>Hello world</h1>

    <p id="demo"></p>
    <!-- Modal -->
    <div class="modal modal-xl fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Welcome!
                        <?php if(isset($_GET['location'])):
                        $startLocation = startLocation(); ?>
                        You are at: <?php echo($startLocation);?>
                        <?php endif;?>
                    </h1>
                </div>

                <form onsubmit="return false">
                    <div class="modal-body">
                        <?php if(!isset($_GET['location'])): ?> 
                        <div class="form-floating">
                            <select class="form-select" id="startLocation" name="startLocation" aria-label="startLocation">
                                <option selected>Where are you located?</option>
                                <?php
                                $locations = locationFill();
                                foreach($locations as $location){
                                    echo '<option value="'.$location['name'].'">'.$location['name'].'</option>';
                                } ?>
                            </select>
                            <label for="startLocation">Pick your location</label>
                        </div> 
                        <?php endif; ?>
                        
                        <div class="form-floating">
                            <select class="form-select" id="endLocation" name="endLocation" aria-label="endLocation">
                                <option selected>Where are you headed?</option>
                                <?php
                                $locations = locationFill();
                                foreach($locations as $location){
                                    echo '<option value="'.$location['name'].'">'.$location['name'].'</option>';
                                } ?>
                            </select>
                            <label for="endLocation">Pick your goal location</label>
                        </div> 

                        <div class="mb-4 form-switch">
                            <input type="checkbox" class="form-check-input" role="switch" id="accessibilitySwitch">
                            <label class="form-check-label" for="exampleCheck0">Check for accessibility information</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" onclick="getRoute()" class="btn btn-primary" data-bs-dismiss="modal">Get route</button>
                    </div>
                </form> 

            </div>
        </div>
    </body>
</html>


<script>
    const myModal = new bootstrap.Modal(document.getElementById('myModal'));
    myModal.show();
</script>
<script>
    function getRoute(){
    text = "hellooooo"
    document.getElementById("demo").innerHTML = text;
    }
</script>