<?php require("navBar.php");
include_once("dbString.php");


function locationFill(){

    $db = new SQLite3(get_string());
    $stmt = $db->prepare('SELECT name FROM Node WHERE enpoint=1');
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
$startLocation = startLocation();
echo $startLocation;
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chesterfield Group B</title>
  </head>


<body>
    <h1>Hello world</h1>

    <!-- Modal -->
    <div class="modal modal-xl fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Welcome!
                        <?php if(isset($_GET['location'])):?>
                        You are at: <?php echo($startLocation);?>
                        <?php endif;?>
                    </h1>
                </div>
                <div class="modal-body">
                    <?php if(!isset($_GET['location'])): ?> 
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>Where are you located?</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">Works with selects</label>
                    </div> 
                    <?php endif; ?>
 
                    <form>
                        <div class="mb-3">
                            <label for="end-location" class="col-form-label">Where are you heading?</label>

                            <input type="text" class="form-control" id="end-location">
                        </div>
                        <div class="mb-4 form-switch">
                            <input type="checkbox" class="form-check-input" role="switch" id="exampleCheck0">
                            <label class="form-check-label" for="exampleCheck0">Check for accessibility information</label>
                        </div>
                    </form> 
                </div>

                <div class="modal-footer">
                    <button type="button" class="button-primary" data-bs-dismiss="modal">Get route</button>


                </div>
            </div>
        </div>


    </body>

</html>

<?php require ("footer.php");?>
<script>
    const myModal = new bootstrap.Modal(document.getElementById('myModal'));
    myModal.show();
</script>
