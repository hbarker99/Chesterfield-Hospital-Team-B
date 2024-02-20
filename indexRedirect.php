<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the checkbox is checked
    if (isset($_POST['pdfCheckbox']) && $_POST['pdfCheckbox'] == 'on') { # might need to change from 'on' to checked
        // If checked, redirect to a different page
        header('Location: directionPDF.php'); #dno where its going yet 
        exit;
    } else {
        // If not checked, redirect to another page
        header('Location: route.php');
        exit;
    }
}