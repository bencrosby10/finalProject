<?php
    include ("top.php");

    $myFolder = 'data/';

    $myFileName = 'image-list';

    $fileExt = '.csv';

    $filename = $myFolder . $myFileName . $fileExt;

    $file = fopen($filename, "r");

    if($file){

        while(!feof($file)){
            $imageData[] = fgetcsv($file);
        }

        if($debug) {
            print '<p>Finished reading data. File closed.</p>';
            print '<p>My data array<p><pre> ';
            print_r($imageData);
            print '</pre></p>';
        }

        fclose($file);
    }

?>
<article class="gallery">

    <h2>Gallery</h2>

    <section class="normal-padding">

        <?php

            //Categorey, location, alt, description
            foreach ($imageData as $imageDatum) {
                if($imageDatum[0] == "regatta") {
                    print "<section class=\"gallery-item responsive\">";
                    print "<a target='_blank' href=\"images/" . $imageDatum[1] . "\">";
                    print "<img src=\"images/". $imageDatum[1] . "\" alt=\"" . $imageDatum[2] . "\" width=\"600\" height=\"400\" >";
                    print "</a>";
                    print "<p class='description'>". $imageDatum[3] . "</p>";
                    print "</section>" . PHP_EOL;
                }

            }

            //Categorey, location, alt, description
            foreach ($imageData as $imageDatum) {
                if($imageDatum[0] == "lobster") {
                    print "<section class=\"gallery-item responsive\">";
                    print "<a target='_blank' href=\"images/" . $imageDatum[1] . "\">";
                    print "<img src=\"images/". $imageDatum[1] . "\" alt=\"" . $imageDatum[2] . "\" width=\"600\" height=\"400\" >";
                    print "</a>";
                    print "<p class='description'>". $imageDatum[3] . "</p>";
                    print "</section>" . PHP_EOL;
                }

            }

            //Categorey, location, alt, description
            foreach ($imageData as $imageDatum) {
                if($imageDatum[0] == "meeting") {
                    print "<section class=\"gallery-item responsive\">";
                    print "<a target='_blank' href=\"images/" . $imageDatum[1] . "\">";
                    print "<img src=\"images/". $imageDatum[1] . "\" alt=\"" . $imageDatum[2] . "\" width=\"600\" height=\"400\" >";
                    print "</a>";
                    print "<p class='description'>". $imageDatum[3] . "</p>";
                    print "</section>" . PHP_EOL;
                }

            }
            //Categorey, location, alt, description
            foreach ($imageData as $imageDatum) {
                if($imageDatum[0] == "youth") {
                    print "<section class=\"gallery-item responsive\">";
                    print "<a target='_blank' href=\"images/" . $imageDatum[1] . "\">";
                    print "<img src=\"images/". $imageDatum[1] . "\" alt=\"" . $imageDatum[2] . "\" width=\"600\" height=\"400\" >";
                    print "</a>";
                    print "<p class='description'>". $imageDatum[3] . "</p>";
                    print "</section>" . PHP_EOL;
                }

            }

        ?>

    </section>

</article>
<?php
    include ("footer.php");
?>

