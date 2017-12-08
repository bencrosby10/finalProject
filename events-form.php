<?php
    include ("top.php");

    print '<article class="events-form">';

    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1 Initialize variables
    //
    if ($debug) {

        print '<p>Post Array:</p><pre>';
        print_r($_POST);
        print '</pre>';
    }

    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1b Security


    $thisURL = $domain . $phpSelf;


    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1c Form variables
    //
    // Initialize variables one for each form element
    // in the order they appear on the form

    $firstName = "";

    $lastName = "";

    $email = "";

    $phoneNumber = "";

    $description = "";

    $date = "";

    $title = "";

    $confirmCode = '';

    $confirmCodeINIT = substr(md5(microtime()),rand(0,26),5);

    $confirmCode = $confirmCodeINIT;



    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1d form error flags
    //
    // Initialize Error Flags one for each form element we validate
    // in the order they appear in section 1c.

    $firstNameERROR = false;

    $lastNameERROR = false;

    $emailERROR = false;

    $phoneERROR = false;

    $descriptionERROR = false;

    $titleERROR = false;

    $dateERROR = false;


    ////%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1e misc variables
    //
    // create array to hold error messages filled (if any) in 2d displayed in 3c.

    $errorMsg = array();

    // array used to hold form values that will be written to a CSV file

    $dataRecord = array();

    // have we mailed the information to the user?

    $mailed = false;

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2 Process for when the form is submitted
    //

    if (isset($_POST["btnSubmit"])) {

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2a Security
        //

        if (!securityCheck($thisURL)) {
            $msg = '<p>Sorry you cannot access this page. ';
            $msg .= 'Security breach detected</p>';
            die($msg);
        }


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2b Sanitize (clean) data
        // remove any potential JavaScript or html code from users input on the
        // form. Note it is best to follow the same order as declared in section 1c.


        $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $firstName;

        $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $lastName;

        $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
        $dataRecord[] = $email;

        $phoneNumber = htmlentities($_POST["txtPhoneNumber"],ENT_QUOTES,"UTF-8");
        $dataRecord[] = $phoneNumber;

        $description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $description;

        $title = htmlentities($_POST["txtTitle"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $title;

        $date = htmlentities($_POST["dtsDate"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $date;

        $dataRecord[] = false;

        $dataRecord[] = $confirmCode;






        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2c Validation
        //
        // Validation section. Check each value for possible errors, empty or not what we expect.

        if ($firstName == "") {
            $errorMsg[] = "Please enter your first name";
            $firstNameERROR = true;

        } elseif (!verifyAlphaNum($firstName)) {
            $errorMsg[] = "Your first name appears to have an extra character";
            $firstNameERROR = true;
        }

        if ($lastName == "") {
            $errorMsg[] = "Please enter your last name";
            $lastNameERROR = true;

        } elseif (!verifyAlphaNum($lastName)) {
            $errorMsg[] = "Your last name appears to have an extra character";
            $lastNameERROR = true;
        }

        if($email == "") {
            $errorMsg[] = "You need to enter an email address";
            $emailERROR = true;
        } elseif (!verifyEmail($email)){
            $errorMsg[] = "Your email is incorrect";
            $emailERROR = true;
        }

        if(!verifyPhone($phoneNumber)) {
            $errorMsg[] = "Your phone number appears to be incorrect";
            $phoneERROR = true;
        }

        if ($title == "") {
            $errorMsg[] = "You need to give a title";
            $titleERROR = true;
        } elseif(!verifyAlphaNum($title)) {
            $errorMsg[] = "Your title appears to have extra characters that are not allowed";
            $titleERROR = true;
        }

        if ($description == "") {
            $errorMsg[] = "You need to give a description";
            $descriptionERROR = true;
        } elseif(!verifyAlphaNum($description)) {
            $errorMsg[] = "Your description appears to have extra characters that are not allowed";
            $descriptionERROR = true;
        }

        if (strtotime($date) < time()) {
            $errorMsg[] = "Your date seems to be invalid";
            $dateERROR = true;
        }

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2d Process Form - Passed Validation
        //
        // Process for when the form passes validation (the errorMsg array is empty)

        if(!$errorMsg) {

            if ($debug) {
                print PHP_EOL . "<p>Form is valid</p>";
            }


            //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            //
            // SECTION: 2e Save Data
            // This block saves the data to a CSV file.

            $myFolder = 'data/';
            $myFileName = 'events';
            $fileExt = '.csv';

            $filename = $myFolder . $myFileName . $fileExt;
            if($debug) {
                print PHP_EOL . '<p>filename is '. $filename. '</p>';
            }

            $file = fopen($filename, 'a'); // now we just open the file for append

            fputcsv($file, $dataRecord);  // write the forms info

            fclose($file); // close the file

            //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            //
            // SECTION: 2f Create message
            //
            // build a message to display on the screen in section 3a and to mail
            // to the person filling out the form (section 2g).'

            $message = '<h1>Maple Hill Yacht Club | Submit Event</h1>';

            $message .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . " for your event submission. We will get back to you shortly if your event is accepted</p>";
            $message .= "<p>Check your email often for a response</p>";
            $message .= "<p>Sincerely, Maple Hill Yacht Club</p>";

            $message .= '<h2>Your Information</h2>';

            foreach ($_POST as $htmlName => $value) {

                if ($htmlName != "btnSubmit") { //TODO: Remove confirm code and accepted values

                    $message .= '<p>';
                    $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

                    foreach ($camelCase as $oneWord) {
                        $message .= $oneWord . ' ';

                    }
                    $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
                }
            }





            //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            //
            // SECTION: 2g Mail to user
            //
            // Process for mailing a message which contains the forms data
            // the message was built in section 2f.

            $to = $email;
            $cc = '';
            $bcc = '';
            $from = 'events@mhyc.com';

            $subject = 'Maple Hill YC: Submit Event';

            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);


            if($mailed) {
                $adminMessage = "<h1>New submission from Maple Hill YC Events form form ..... be sure to check their event and click their event below";
                $adminMessage .="<a href='https://hjensen3.w3.uvm.edu/cs008/final/events.php?confirmCode=". $confirmCode . "&debug=true'><button>Confirm the event here</button></a>";
                $adminMessage .= "<p>-----------------------------------------------------</p><p> </p><p> </p>";
                $adminMessage .= $message;

                sendMail("bscrosby@uvm.edu", "hjensen3@uvm.edu", $bcc, $from, "Maple Hill YC: New Event submitted", $adminMessage);
                //sendMail("hjensen3@uvm.edu", $cc, $bcc, $from, "Maple Hill YC: New Event submitted", $adminMessage);
            }

        }
    }

    //#################################
    //
    // SECTION 3 Display Form
    //
    ?>

        <?php
        //####################################
        //
        // SECTION 3a.
        //

        print '<h2>Submit a new Event</h2>';

        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print "<h3>Thank-you for your submission, be sure to check your email often!</h3>";
            print "<p>For your records, a copy of this data has been sent:</p>";
            print "<p>To: " . $email . "</p>";

        } else {
            print '<h3>Please fill out the event form</h3>';

            //#################################
            //
            // SECTION 3b Error Messages
            //

            if($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print "<h3>Your submission has the following errors!</h3>" . PHP_EOL;
                print '<ol>' . PHP_EOL;
                foreach ($errorMsg as $error) {
                    print '<li>' . $error . '</li>' . PHP_EOL;
                }
                print '</ol>' . PHP_EOL;
                print '</div>' . PHP_EOL;


            }

            //#################################
            //
            // SECTION 3c html Form

            ?>

            <form action="<?php print $phpSelf; ?>" id="formRegister"  method="post">

                <fieldset class="contact">
                    <legend>Contact Info</legend>
                    <p>
                        <label class="required text-field" for="txtFirstName">First Name</label>
                        <input autofocus <?php if($firstNameERROR) print 'class="mistake"'; ?>
                               id="txtFirstName"
                               maxlength="45"
                               name="txtFirstName"
                               onfocus="this.select()"
                               placeholder="Enter Your first name"
                               tabindex="100"
                               type="text"
                               value="<?php print ucfirst($firstName); ?>"
                        >
                    </p>
                    <p>
                        <label class="required text-field" for="txtLastName">Last Name</label>
                        <input <?php if($lastNameERROR) print 'class="mistake"'; ?>
                               id="txtLastName"
                               maxlength="45"
                               name="txtLastName"
                               onfocus="this.select()"
                               placeholder="Enter Your last name"
                               tabindex="110"
                               type="text"
                               value="<?php print ucfirst($lastName); ?>"
                        >
                    </p>
                    <p>
                        <label class="required text-field" for="txtEmail">Email</label>
                        <input
                            <?php if($emailERROR) print 'class="mistake"'; ?>
                                id="txtEmail"
                                maxlength="45"
                                name="txtEmail"
                                onfocus="this.select()"
                                placeholder="Email Address"
                                tabindex="120"
                                type="text"
                                value="<?php print $email; ?>" >
                    </p>

                    <p>
                        <label class="required text-field" for="txtPhoneNumber">Phone Number</label>
                        <input <?php if($phoneERROR) print 'class="mistake"'; ?> id="txtPhoneNumber" maxlength="55" name="txtPhoneNumber" onfocus="this.select()" placeholder="Enter a phone number" tabindex="150" type="tel" value="<?php print $phoneNumber; ?>">
                    </p>

                </fieldset>

                <fieldset>
                    <legend>Event Details</legend>

                    <h3>Please, give your event a short title (15 characters)</h3>
                    <p>
                        <textarea <?php if ($titleERROR) print 'class="mistake"'; ?> id="txtTitle" name="txtTitle"  maxlength="15" onfocus="this.select()" tabindex="200"><?php print $title; ?></textarea>
                    </p>


                    <h3>Please, give your event a description</h3>
                    <p>
                        <textarea <?php if ($descriptionERROR) print 'class="mistake"'; ?> id="txtDescription" name="txtDescription" maxlength="200" onfocus="this.select()" tabindex="210"><?php print $description; ?></textarea>
                    </p>

                    <h3>Please, specify a date for your event</h3>

                    <p>
                        <input <?php if ($dateERROR) print 'class="mistake"'; ?> type="datetime-local" min="datetime-local" id="dtsDate" name="dtsDate" onfocus="this.select()" tabindex="220" value="<?php print $date; ?>">
                    </p>

                </fieldset>

                <fieldset class="buttons">
                    <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Submit" >
                </fieldset>
            </form>


            <?php
        }
        ?>
</article>
<?php
    include ("footer.php");
?>
