<?php
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

    $path_parts = pathinfo($phpSelf);

    $domain = "//";

    $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

    $domain .= $server;

    $debug = false;

    if (isset($_GET["debug"])) {
        $debug = true;
    }


    print  PHP_EOL . '<!-- include libraries -->' . PHP_EOL;

    require_once("lib/security.php");


    if ($path_parts['filename'] == "form" or $path_parts['filename'] == "dinner-form" or $path_parts['filename'] == "apply" or $path_parts['filename'] == "events-form") {
        print PHP_EOL . '<!-- include form libraries -->' . PHP_EOL;
        include "lib/validation-functions.php";
        include 'lib/mail-message.php';
    }

    if($path_parts['filename'] == "events") {
        include 'lib/mail-message.php';
    }

    print  PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Maple Hill YC | <?php print ucfirst($path_parts['filename'])?></title>

    <meta charset="utf-8">
    <meta name="author" content="Hunter Jensen - Ben Crosby">
    <meta name="description" content="maple hill yacht club website">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="styles/custom.css" type="text/css" media="screen">
    <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative|Raleway" rel="stylesheet">
    <link rel="SHORTCUT ICON" href="images/MHYC-burgee-tiny.png">


</head>

<?php
    print '<body id="' . $path_parts['filename'] . '">'; //REMEMBER TO CLOSE WITH A BODY AT END OF EACH FILE

    include  ("header.php");
    include  ("nav.php");
?>