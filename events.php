<?php
    include ("top.php");

    $myFolder = 'data/';

    $myFileName = 'events';

    $fileExt = '.csv';

    $filename = $myFolder . $myFileName . $fileExt;

    if ($debug) print '<p>filename is ' . $filename;


    if (isset($_GET["confirmCode"])) {

        $confirmCode = $_GET["confirmCode"];

        $file = fopen($filename, "r");

        $sentMail = false;

        if($file){

            // Read into memory

            while(!feof($file)){
                $eventData[] = fgetcsv($file);
            }


            fclose($file);

            // Overwrite memory data single line and send mail message



            for($i = 0; $i < count($eventData); $i++){
                if($eventData[$i][8] == $confirmCode) {

                    if (!$eventData[$i][7]) {

                        $eventData[$i][7] = true; // Change to true HERE

                        $message = '<h1 style="font-family: "Raleway", sans-serif">Maple Hill Yacht Club | Submit Event</h1>';

                        $message .= '<p>Thank-you, ' . ucfirst($eventData[$i][0]) . " " . ucfirst($eventData[$i][1]) . "<br>" . "for your event submission. Your event was accepted!</p>";
                        $message .= "<p>Check the website now to see the posted event!</p>";
                        $message .= "<a href='https://hjensen3.w3.uvm.edu/cs008/final/events.php'>Check your event here!</a>";
                        $message .= "<p>Sincerely, Maple Hill Yacht Club</p>";

                        $cc = '';
                        $bcc = '';
                        $from = 'events@mhyc.com';

                        $subject = 'Maple Hill YC: Submit Event';

                        $sentMail = sendMail($eventData[$i][2], $cc, $bcc, $from, $subject, $message);
                    }
                }
            }

            if($debug) {
                print '<p>Finished reading data. File closed.</p>';
                print '<p>My data array<p><pre> ';
                print_r($eventData);
                print '</pre></p>';
            }

            // Overwrite the file with changed data

            $fileNEW = fopen($filename,'w');
            fclose($fileNEW);

            $fileNEWtwo = fopen($filename,'a');

            foreach ($eventData as $eventDatum) {
                print '<p>' . gettype($eventDatum) . '</p>';
                fputcsv($fileNEWtwo, $eventDatum);
            }

            fclose($fileNEWtwo);

        }

        // Close the window

        if($sentMail) {
            echo "<script type='text/javascript'>";
            echo "window.close();";
            echo "</script>";
            
        } else {

            $message = '<h1 style="font-family:\'Raleway\', \'sans-serif\',serif">Maple Hill Yacht Club | Submit Event</h1>';

            $message .= '<p>There was an error with the confirmation of the event</p>';
            $message .= "<p>Sincerely, Maple Hill Yacht Club</p>";

            $cc = '';
            $bcc = '';
            $from = 'events@mhyc.com';

            $subject = 'Maple Hill YC: Submit Event';

            sendMail("bscrosby@uvm.edu", "hjensen3@uvm.edu", $bcc, $from, $subject, $message);

            echo "<script type='text/javascript'>";
            echo "window.close();";
            echo "</script>";
        }

    } else {

        $file = fopen($filename, "r");

        if($file){

            while(!feof($file)){
                $eventData[] = fgetcsv($file);
            }

            if($debug) {
                print '<p>Finished reading data. File closed.</p>';
                print '<p>My data array<p><pre> ';
                print_r($eventData);
                print '</pre></p>';
            }

            fclose($file);
        }

    }


?>

<article class="events">

    <h2>MHYC Events</h2>

    <section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <img class="events-card-img" src="images/cleanup.png" alt="Spring-Cleanup">
                    </section>
                    <h2 class="card-title official-event-title">Spring Clean-up</h2>
                    <h4 class="subtitle">Saturday April 8<sup>th</sup> 2018<br>9:00am - 12:00pm</h4>
                    <p class="card-description">As spring is upon us it’s time to get boats ready and clan up around the club house. We are looking for eager hands to help clean up downed sticks from winter as well as spreading crushed rock around the junior sailing boat ramp. There will be coffee and donuts provided. Come out and help get the facility looking good for the coming season!</p>
                </section>
            </section>
        </section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <img class="events-card-img" src="images/ACS-regatta.png" alt="ACS-Regatta">
                    </section>
                    <h2 class="card-title official-event-title">18<sup>th</sup>Annual ACS Regatta</h2>
                    <h4 class="subtitle">Saturday July 14<sup>th</sup> 2018<br>9:00am - 6:00pm</h4>
                    <p class="card-description">Fellow New England Sailors,
                        The MHYC is shooting for 40 boats at this year’s event. All sailors have the opportunity to get directly involved & raise money to cure cancer. This event will start with an island race in the morning and be followed by a live auction and cook out at the club. Please distribute this to all your sailing friends to inform and access all potential competitors. The more fundraisers, the more money and awareness for The American Cancer Society!
                    </p>
                </section>
            </section>
        </section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <a href="dinner-form.php">
                            <img class="events-card-img" src="images/lobster-dinner.png" alt="Annual-Lobster-Dinner">
                        </a>
                    </section>
                    <a href="dinner-form.php">
                        <h2 class="card-title official-event-title">Annual Lobster Dinner!</h2>
                    </a>
                    <h4 class="subtitle">Saturday August 18<sup>th</sup> 2018<br>5:00pm</h4>
                    <p class="card-description">At only $25, it's a great way of showing support for Maple Hill Junior Sailing, while having an amazing meal and a fun evening. Bring family and friends (children welcome, of course !) Meet old friends, make new ones. See how far MHJS has come, appreciate the work of staff and volunteers, and enjoy! Get your tickets by creating a reservation <a href="dinner-form.php">HERE</a> The event will be held at the Maple Hill Yacht Club (As always, non-seafood choices offered and BYOB.)
                    </p>
                </section>
            </section>
        </section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <img class="events-card-img" src="images/meeting1.jpg" alt="Annual-Meeting ">
                    </section>
                    <h2 class="card-title official-event-title">MHYC Annual Meeting Dinner</h2>
                    <h4 class="subtitle">Friday October 12<sup>th</sup> 2018<br>6:00pm</h4>
                    <p class="card-description">All members are encouraged to attend the Annual Meeting dinner at the Lyon's Den. It is a fun evening, good food and friends, and a wonderful recap of the sailing season for the J/80 Fleet and PHRF Racing / Mixed Fleet, and the Sailing School. It is also a chance to have a say in our leadership and governance of the organization. You can register by emailing <a href="mailto:bscrosby10@uvm.edu">events@mhyc.com</a>
                    </p>
                </section>
            </section>
        </section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <img class="events-card-img" src="" alt="PIC OF BURGEE (TRANSPARENT)">
                    </section>
                    <h2 class="card-title official-event-title">Fall Project Day at the club</h2>
                    <h4 class="subtitle">Saturday October 13<sup>th</sup>2018<br>8:00am - 12:00pm</h4>
                    <p class="card-description">As we close up the yacht club for the winter, there are a few projects we will be trying to complete on the buildings, boats, and grounds. Whether you are handy with a skill or just happy to come lend a hand, please join us for the morning to get things ship-shape. Also, prior to the project day, there is a project to paint the trim on the club, spare pairs of hands needed,
                        if you have a few hours to spare any sunny day. Please contact <a href="mailto:bscrosby10@uvm.edu">Jeff.Rabinowitz@worms.com</a> if you have any questions.
                    </p>
                </section>
            </section>
        </section>

        <section class="column">
            <section class="card">
                <section class="container events-card">
                    <section class="events-card-img">
                        <img class="events-card-img" src="images/youth4.jpg" alt="youth-regatta">
                    </section>
                        <h2 class="card-title">Youth Regatta (W.A.R.)</h2>
                        <h4 class="subtitle">Saturday July 19<sup>th</sup> 2018<br>8:00am- 3:00pm</h4>
                        <p class="card-description">The Wicked Awesome Regatta (W.A.R.) is organized and Hosted by the Maple Hill Yacht club Junior Sailing program every July. Youth sailors and youth sailing organizations from across New England, New York, and adjacent regions always ensure that the W.A.R. is a welcoming, spirited competition with a fun time had by all. The Maple Hill yacht Club has generously provided the venue for the event for a number of years.  The next W.A.R. is scheduled for July 19, 2018.  For more information, email us at the <a href="mailto:bscrosby10@uvm.edu">juniorsailing@mhyc.com</a></p>
                </section>
            </section>
        </section>

    </section>


    <section>
        <h2 class="spacing-vertical">Member Events</h2>
            <?php
                if(filesize($filename) == 0) {
                    print '<h2>There are currently no events at this time</h2>';
                } else {
                    foreach ($eventData as $eventDatum) {
                        if ($eventDatum[0] != "") {  //the end of file would be a ""
                            if ($eventDatum[7]) {
                                print '<section class="column">';
                                print '<section class="card event-height">';
                                print '<section class="container">';
                                print  "<h2 class='card-title'>" . $eventDatum[5] . "</h2>"; //Title
                                $time = strtotime($eventDatum[6]);
                                $newFormat = date('F j\, Y  \- g:i A', $time);
                                print  "<p class='sub-title'>" . $newFormat . "</p>"; //Date
                                print  "<p>" . $eventDatum[4] . "</p>"; //Description
                                print '</section>';
                                print '</section>';
                                print '</section>';
                            }
                        }
                    }
                }
            ?>

        <a href="events-form.php"><button class="button">Submit an event Here</button></a>

    </section>

</article>
<?php
    include ("footer.php");
?>

