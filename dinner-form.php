<?php
    include ("top.php");

    print '<article class="dinner-form">';

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

    $partyTotal = "1";

    $subscription = "Yes";

    $phoneNumber = "";

    $lobsterFood = true;
    $steakFood = false;
    $vegetarianFood = false;
    $applepieFood = false;
    $vanillaFood= false;

    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1d form error flags
    //
    // Initialize Error Flags one for each form element we validate
    // in the order they appear in section 1c.

    $firstNameERROR = false;

    $lastNameERROR = false;

    $emailERROR = false;

    $subscriptionERROR = false;

    $partyERROR = false;

    $menuERROR = false;

    $phoneERROR = false;


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


    $totalChecked = 0;

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

        $subscription = htmlentities($_POST["radSubscription"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $subscription;

        $partyTotal = htmlentities($_POST["lstParty"],ENT_QUOTES,"UTF-8");
        $dataRecord[] = $partyTotal;

        if (isset($_POST["chkLobsterFood"])) {
            $lobsterFood = true;
            $dataRecord[] = htmlentities($_POST["chkLobsterFood"], ENT_QUOTES, "UTF-8");
            $totalChecked++;
        } else {
            $lobsterFood = false;
            $dataRecord[] = ""; //Include it into array if not there when look at weather for formatting later
        }

        if (isset($_POST["chkSteakFood"])) {
            $steakFood = true;
            $dataRecord[] = htmlentities($_POST["chkSteakFood"], ENT_QUOTES, "UTF-8");
            $totalChecked++;
        } else {
            $steakFood = false;
            $dataRecord[] = ""; //Include it into array if not there when look at weather for formatting later
        }

        if (isset($_POST["chkVegetarianFood"])) {
            $vegetarianFood = true;
            $dataRecord[] = htmlentities($_POST["chkVegetarianFood"], ENT_QUOTES, "UTF-8");
            $totalChecked++;
        } else {
            $vegetarianFood = false;
            $dataRecord[] = ""; //Include it into array if not there when look at weather for formatting later
        }

        if (isset($_POST["chkApplepieFood"])) {
            $applepieFood = true;
            $dataRecord[] = htmlentities($_POST["chkApplepieFood"], ENT_QUOTES, "UTF-8");
            $totalChecked++;
        } else {
            $applepieFood = false;
            $dataRecord[] = ""; //Include it into array if not there when look at weather for formatting later
        }

        if (isset($_POST["chkVanillaFood"])) {
            $vanillaFood = true;
            $dataRecord[] = htmlentities($_POST["chkVanillaFood"], ENT_QUOTES, "UTF-8");
            $totalChecked++;
        } else {
            $vanillaFood = false;
            $dataRecord[] = ""; //Include it into array if not there when look at weather for formatting later
        }

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

        if($subscription != "Yes" AND $subscription != "No" AND $subscription != "Important"){
            $errorMsg[] = "Please choose a subscription method";
            $subscriptionERROR = true;
        }

        if($partyTotal == ""){
            $errorMsg[] = "Please select a party number";
            $partyERROR = true;
        }

        if($totalChecked < 1){
            $errorMsg[] = "Please choose at least one menu item";
            $weatherERROR = true;
        }

//        if(!verifyPhone($phoneNumber)) { //TODO: FIX THIS FOR LATER
//            $errorMsg[] = "Your phone number appears to be incorrect";
//            $phoneERROR = true;
//        }

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
            $myFileName = 'reservations';
            $fileExt = '.csv';

            $filename = $myFolder . $myFileName . $fileExt;
            if($debug) {
                print PHP_EOL . '<p>filename is'. $filename. '</p>';
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

            $message = '<h1>Maple Hill Yacht Club | Reservation</h1>';

            $message .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . "<br>" . "We have recorded your reservation for party of " . $partyTotal . ". See you soon!";
            $message .= "<p>Save the date for <b>SATURDAY AUGUST 19<sup>th</sup> at 5:00 PM!</b></p>";


            $message .= '<h2>Your Information</h2>';

            foreach ($_POST as $htmlName => $value) {

                if ($htmlName != "btnSubmit") {

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
            $from = 'reservations@mhyc.com';

            $subject = 'Maple Hill YC: Dinner Reservation';

            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);


            if($mailed) {
                $adminMessage = "<h1>New submission from Maple Hill YC contact form.....";
                $adminMessage .= "<p>-----------------------------------------------------</p><p> </p><p> </p>";
                $adminMessage .= $message;

                sendMail("bscrosby@uvm.edu", "hjensen3@uvm.edu", $bcc, $from, "Maple Hill YC: NEW Dinner Reservation", $adminMessage);
            }

        }
    }

    //#################################
    //
    // SECTION 3 Display Form
    //
    ?>

    <article id='main'>

        <?php
        //####################################
        //
        // SECTION 3a.
        //

        print '<h2>Lobster Dinner Reservation</h2>';

        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print "<h3>Thankyou for your reservation, see you soon!</h3>";
            print "<p>For your records, a copy of this data has been sent:</p>";
            print "<p>To: " . $email . "</p>";

        } else {
            print '<h3>Please make a reservation for the Lobster dinner</h3>';

            //#################################
            //
            // SECTION 3b Error Messages
            //

            if($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print "<h3>Your reservation has the following errors!</h3>" . PHP_EOL;
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
                        <input autofocus <?php if($lastNameERROR) print 'class="mistake"'; ?>
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
                        <label class="required" for="txtPhoneNumber">Phone Number</label>
                        <input <?php if($phoneERROR) print 'class="mistake"'; ?> id="txtPhoneNumber" maxlength="55" name="txtPhoneNumber" onfocus="this.select()" placeholder="Enter a phone number" tabindex="150" type="tel" value="<?php print $phoneNumber; ?>">
                    </p>

                </fieldset>

                <fieldset>
                    <legend>Party</legend>
                    <h3>Please specify how many people will be attending</h3>
                    <p class="listbox <?php if ($partyERROR) print ' mistake'; ?>">
                        <select id="lstParty"
                                name="lstParty"
                                tabindex="320" >
                            <option <?php if($partyTotal=="1") print " selected "; ?>
                                    value="1">1</option>
                            <option <?php if($partyTotal=="2") print " selected "; ?>
                                    value="2">2</option>
                            <option <?php if($partyTotal=="3") print " selected "; ?>
                                    value="3">3</option>
                            <option <?php if($partyTotal=="4") print " selected "; ?>
                                    value="4">4</option>
                            <option <?php if($partyTotal=="5") print " selected "; ?>
                                    value="5">5</option>
                            <option <?php if($partyTotal=="6") print " selected "; ?>
                                    value="6">6</option>
                            <option <?php if($partyTotal=="7") print " selected "; ?>
                                    value="7">7</option>
                            <option <?php if($partyTotal=="8") print " selected "; ?>
                                    value="8">8</option>
                            <option <?php if($partyTotal=="9") print " selected "; ?>
                                    value="9">9</option>
                            <option <?php if($partyTotal=="10") print " selected "; ?>
                                    value="10">10</option>
                            <option <?php if($partyTotal=="more10") print " selected "; ?>
                                    value="more10">More than 10</option>
                        </select>
                    </p>
                </fieldset>

                <fieldset class="checkbox <?php if ($menuERROR) print ' mistake'; ?>">
                    <legend>Menu</legend>
                    <h3>Please select a dinner menu item</h3>
                    <p>
                        <label class="check-field">
                            <input <?php if ($lobsterFood) print " checked "; ?>
                                    id="chkLobsterFood"
                                    name="chkLobsterFood"
                                    tabindex="420"
                                    type="checkbox"
                                    value="Lobster"> Lobster</label>
                    </p>
                    <p>
                        <label class="check-field">
                            <input <?php if ($steakFood)  print " checked "; ?>
                                    id="chkSteakFood"
                                    name="chkSteakFood"
                                    tabindex="430"
                                    type="checkbox"
                                    value="Steak"> Steak</label>
                    </p>
                    <p>
                        <label class="check-field">
                            <input <?php if ($vegetarianFood)  print " checked "; ?>
                                    id="chkVegetarianFood"
                                    name="chkVegetarianFood"
                                    tabindex="440"
                                    type="checkbox"
                                    value="Vegetarian"> Vegetarian</label>
                    </p>

                    <h3>Desserts</h3>

                    <p>
                        <label class="check-field">
                            <input <?php if ($applepieFood)  print " checked "; ?>
                                    id="chkApplepieFood"
                                    name="chkApplepieFood"
                                    tabindex="450"
                                    type="checkbox"
                                    value="Applepie"> Apple Pie</label>
                    </p>
                    <p>
                        <label class="check-field">
                            <input <?php if ($vanillaFood)  print " checked "; ?>
                                    id="chkVanillaFood"
                                    name="chkVanillaFood"
                                    tabindex="460"
                                    type="checkbox"
                                    value="Vanilla"> Vanilla Ice Cream with Maple Syrup</label>
                    </p>
                </fieldset>

                <fieldset class="radio <?php if ($subscriptionERROR) print ' mistake'; ?>">
                    <legend>Subscribe</legend>
                    <h3>Would you like to subscribe to more information?</h3>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radSubscribeYes"
                                   name="radSubscription"
                                   value="Yes"
                                   tabindex="572"
                                <?php if ($subscription == "Yes") print ' checked="checked" '; ?>>
                            Yes</label>
                    </p>

                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radSubscribeNo"
                                   name="radSubscription"
                                   value="No"
                                   tabindex="582"
                                <?php if ($subscription == "No") print ' checked="checked" '; ?>>
                            No</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radSubscribeImportant"
                                   name="radSubscription"
                                   value="Important"
                                   tabindex="592"
                                <?php if ($subscription == "Important") print ' checked="checked" '; ?>>
                            Important updates only</label>
                    </p>
                </fieldset>

                <fieldset class="buttons">
                    <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register" >
                </fieldset>
            </form>


            <?php
        }
        ?>
</article>
<?php
    include ("footer.php");
?>
