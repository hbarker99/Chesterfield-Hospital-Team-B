<?php require("navBar.php");
include_once("dbString.php");

function locationFill(){

    $db = new SQLite3(get_string());
    $stmt = $db->prepare('SELECT name, node_id FROM Node WHERE endpoint=1');
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
        <?php include './components/dropdown/dropdown.php'; ?>
        <div>
            <h1>Welcome!
                <?php if(isset($_GET['location'])):
                $startLocation = startLocation(); ?>
                You are at: <?php echo($startLocation);?>
                <?php endif;?>
            </h1>
        </div>
        <div>
            <?php if(!isset($_GET['location'])): ?> 

            <form method="post" action="mapping-algo.php">
                <div class="form-floating">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                        <option selected>Where are you located?</option>
                        <?php
                        $locations = locationFill();
                        foreach($locations as $location){
                            echo '<option value="'.$location['node_id'].'">'.$location['name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="floatingSelect">Pick your location</label>
                </div> 
                <?php endif; ?>

                <div class="form-floating">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                        <option selected>Where are you headed?</option>
                        <?php
                        $locations = locationFill();
                        foreach($locations as $location){
                            echo '<option value="'.$location['node_id'].'">'.$location['name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="floatingSelect">Pick your goal location</label>
                </div> 
                <div class="mb-4 form-switch">
                    <input type="checkbox" class="form-check-input" role="switch" id="exampleCheck0">
                    <label class="form-check-label" for="exampleCheck0">Check for accessibility information</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Get route</button>
                </div>
            </form> 
        </div>

    </body>

</html>