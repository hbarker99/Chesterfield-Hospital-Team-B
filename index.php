<?php require("navBar.php");
include_once("dbString.php");





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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Welcome! You are at: 
                        <?php 
                        echo $location?> 
                    </h1>
                </div>
                <div class="modal-body">
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Get route</button>
                </div>
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
