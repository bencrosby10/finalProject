<?php
    include ("top.php");

    $myFolder = 'data/';

    $myFileName = 'events';

    $fileExt = '.csv';

    $filename = $myFolder . $myFileName . $fileExt;

    if ($debug) print '<p>filename is ' . $filename;

    $file = fopen($filename, "r");



    if($file){

        // read the header row, copy the line for each header row
        // you have.
        $headers[] = fgetcsv($file);

        // read all the data
        while(!feof($file)){
            $eventData[] = fgetcsv($file);
        }

        if($debug) {
            print '<p>Finished reading data. File closed.</p>';
            print '<p>My data array<p><pre> ';
            print_r($eventData);
            print '</pre></p>';
        }

        if (isset($_GET["confirmCode"])) {

            $confirmCode = $_GET["confirmCode"];

            //Process the data

            foreach ($eventData as $eventDatum) {
                if ($eventDatum[0] != "") {  //the end of file would be a ""
                    if($eventDatum[8] == $confirmCode) {
                        $eventDatum[7] = true;



                        $message = '<h1>Maple Hill Yacht Club | Submit Event</h1>';

                        $message .= '<p>Thank-you, ' . ucfirst($eventDatum[0]) . " " . ucfirst($eventDatum[1]) . "<br>" . "for your event submission. Your event was accepted!</p>";
                        $message .= "<p>Check the website now to see the posted event!</p>";
                        $message .= "<a href='https://hjensen3.w3.uvm.edu/cs008/final/events.php'>Check your event here!</a>";
                        $message .= "<p>Sincerely, Maple Hill Yacht Club</p>";

                        $cc = '';
                        $bcc = '';
                        $from = 'events@mhyc.com';

                        $subject = 'Maple Hill YC: Submit Event';

                        sendMail($eventDatum[2], $cc, $bcc, $from, $subject, $message);

                    }

                }
            }


            fclose($file);
            //overwrite the file
            $fileNEW = fopen($filename,'w');

            foreach ($eventData as $eventDatum) {
                if ($eventDatum[0] != "") {  //the end of file would be a ""
                    fputcsv($file, $eventDatum);
                }
            }
            fclose($fileNEW);

            echo  "<script type='text/javascript'>";
            echo "window.close();";
            echo "</script>";


        } else {
            fclose($file);
        }

    }


?>

<article class="events">

    <h2>MHYC Events</h2>

    <section class="event-view-content">

        <section class="events-card">

            <section class="events-card-img">
                <img src="images/cleanup.png" alt="Spring-Cleanup">
            </section>
            <section class="events-card-text">
                <h2 class="events-card-title">Spring Clean-up</h2>
                <h4 class="events-card-subtitle">Saturday April 8<sup>th</sup> 2018<br>9:00am - 12:00pm</h4>
                <p class="events-card-tags">As spring is upon us it’s time to get boats ready and clan up around the club house. We are looking for eager hands to help clean up downed sticks from winter as well as spreading crushed rock around the junior sailing boat ramp. There will be coffee and donuts provided. Come out and help get the facility looking good for the coming season!</p>
            </section>

        </section>

        <section class="events-card">
            <section class="events-card-img">
                <img class="events-card-img" src="images/ACS-regatta.png" alt="ACS-Regatta">
            </section>
            <section class="events-card-text">
                <h2 class="events-card-title">18<sup>th</sup>Annual ACS Regatta</h2>
                <h4 class="events-card-subtitle">Saturday July 14<sup>th</sup> 2018<br>9:00am - 6:00pm</h4>
                <p class="events-card-tags">Fellow New England Sailors,
                    The MHYC is shooting for 40 boats at this year’s event. All sailors have the opportunity to get directly involved & raise money to cure cancer. This event will start with an island race in the morning and be followed by a live auction and cook out at the club. Please distribute this to all your sailing friends to inform and access all potential competitors. The more fundraisers, the more money and awareness for The American Cancer Society!
                </p>
            </section>
        </section>

        <section class="events-card">
            <section class="events-card-img">
                <a href="dinner-form.php">
                    <img class="events-card-img" src="images/lobster-dinner.png" alt="Annual-Lobster-Dinner">
                </a>
            </section>
            <section class="events-card-text">
                <a href="dinner-form.php">
                    <h2 class="events-card-title">Annual Lobster Dinner!</h2>
                </a>
                <h4 class="events-card-subtitle">Saturday August 18<sup>th</sup> 2018<br>5:00pm</h4>
                <p class="events-card-tags">At only $25, it's a great way of showing support for Maple Hill Junior Sailing, while having an amazing meal and a fun evening. Bring family and friends (children welcome, of course !) Meet old friends, make new ones. See how far MHJS has come, appreciate the work of staff and volunteers, and enjoy! Get your tickets by creating a reservation <a href="dinner-form.php">HERE</a> The event will be held at the Maple Hill Yacht Club (As always, non-seafood choices offered and BYOB.)
                </p>
            </section>
        </section>

        <section class="events-card">

            <section class="events-card-img">
                <img class="events-card-img" src="images/meeting1.jpg" alt="Annual-Meeting ">
            </section>
            <section class="events-card-text">
                <h2 class="events-card-title">MHYC Annual Meeting Dinner</h2>
                <h4 class="events-card-subtitle">Friday October 12<sup>th</sup> 2018<br>6:00pm</h4>
                <p class="events-card-tags">All members are encouraged to attend the Annual Meeting dinner at the Lyon's Den. It is a fun evening, good food and friends, and a wonderful recap of the sailing season for the J/80 Fleet and PHRF Racing / Mixed Fleet, and the Sailing School. It is also a chance to have a say in our leadership and governance of the organization. You can register by emailing <a href="mailto:bscrosby10@uvm.edu">events@mhyc.com</a>
                </p>
            </section>

        </section>

        <section class="events-card">

            <section class="events-card-img">
                <img class="events-card-img" src="" alt="PIC OF BURGEE (TRANSPARENT)">
            </section>
            <section class="events-card-text">
                <h2 class="events-card-title">Fall Project Day at the club</h2>
                <h4 class="events-card-subtitle">Saturday October 13<sup>th</sup>2018<br>8:00am - 12:00pm</h4>
                <p class="events-card-tags">As we close up the yacht club for the winter, there are a few projects we will be trying to complete on the buildings, boats, and grounds. Whether you are handy with a skill or just happy to come lend a hand, please join us for the morning to get things ship-shape. Also, prior to the project day, there is a project to paint the trim on the club, spare pairs of hands needed,
                    if you have a few hours to spare any sunny day. Please contact <a href="mailto:bscrosby10@uvm.edu">Jeff.Rabinowitz@worms.com</a> if you have any questions.
                </p>
            </section>

        </section>

        <section class="events-card">
            <section class="events-card-img">
                <img class="events-card-img" src="images/youth4.jpg" alt="youth-regatta">
            </section>
            <section class="events-card-text">
                <h2 class="events-card-title">Youth Regatta (W.A.R.)</h2>
                <h4 class="events-card-subtitle">Saturday July 19<sup>th</sup> 2018<br>8:00am- 3:00pm</h4>
                <p class="events-card-tags">The Wicked Awesome Regatta (W.A.R.) is organized and Hosted by the Maple Hill Yacht club Junior Sailing program every July. Youth sailors and youth sailing organizations from across New England, New York, and adjacent regions always ensure that the W.A.R. is a welcoming, spirited competition with a fun time had by all. The Maple Hill yacht Club has generously provided the venue for the event for a number of years.  The next W.A.R. is scheduled for July 19, 2018.  For more information, email us at the <a href="mailto:bscrosby10@uvm.edu">juniorsailing@mhyc.com</a>

                </p>
            </section>
        </section>
    </section>



    <h2>Member Events</h2>
        <?php
            foreach ($eventData as $eventDatum) {
            if ($eventDatum[0] != "" and $eventDatum[7]) {  //the end of file would be a ""
                print '<section class="column">';
                    print '<section class="card event-height">';
                        print '<section class="container">';
                            print  "<h2 class='card-title'>" . $eventDatum[5] . "</h2>"; //Title
                            $time = strtotime($eventDatum[6]);
                            $newFormat = date('F j\, Y  \- g:i A',$time);
                            print  "<p class='sub-title'>" . $newFormat . "</p>"; //Date
                            print  "<p>" . $eventDatum[4] . "</p>"; //Description
                        print '</section>';
                    print '</section>';
                print '</section>';
            }
        }
        ?>

    <a href="events-form.php"><button class="button">Submit an event Here</button></a>


</article>
<?php
    include ("footer.php");
?>

