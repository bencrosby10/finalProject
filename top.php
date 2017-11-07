<?php
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

    $path_parts = pathinfo($phpSelf);
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Sailing</title>

    <meta charset="utf-8">
    <meta name="author" content="Hunter Jensen - Ben Crosby">
    <meta name="description" content="------Blank Sailing Website-----">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="styles/custom.css" type="text/css" media="screen">

</head>

<?php
    print '<body id="' . $path_parts['filename'] . '">'; //REMEMBER TO CLOSE WITH A BODY AT END OF EACH FILE

    include  ("header.php");
    include  ("nav.php");
?>