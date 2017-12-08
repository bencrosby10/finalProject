<?php
    include ("top.php");

    print '<article class="apply">';

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

    $position = "Commodore";

    $phoneNumber = "";

    $elseQuestion = "";

    $experinceQuestion = "";

    $goodQuestion = "";

    $questions = "";

    $age = "less16";


    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    //
    // SECTION: 1d form error flags
    //
    // Initialize Error Flags one for each form element we validate
    // in the order they appear in section 1c.

    $firstNameERROR = false;

    $lastNameERROR = false;

    $emailERROR = false;

    $positionERROR = false;

    $phoneERROR = false;

    $elseQuestionERROR = false;

    $experinceQuestionERROR = false;

    $goodQuestionERROR = false;

    $ageERROR = false;

    $questionsERROR = false;


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

        $position = htmlentities($_POST["radPosition"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $position;

        $questions = htmlentities($_POST["txtQuestions"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $questions;

        $elseQuestion = htmlentities($_POST["txtelseQuestion"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $elseQuestion;

        $experinceQuestion = htmlentities($_POST["txtexperinceQuestion"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $experinceQuestion;

        $goodQuestion = htmlentities($_POST["txtgoodQuestion"], ENT_QUOTES, "UTF-8");
        $dataRecord[] = $goodQuestion;

        $age = htmlentities($_POST["lstAge"],ENT_QUOTES,"UTF-8");
        $dataRecord[] = $age;

        $phoneNumber = htmlentities($_POST["txtPhoneNumber"],ENT_QUOTES,"UTF-8");
        $dataRecord[] = $phoneNumber;




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

        if ($questions == "") {
            $errorMsg[] = "You need to give a response";
            $questionsERROR = true;
        } elseif(!verifyAlphaNum($questions)) {
            $errorMsg[] = "Your response appears to have extra characters that are not allowed";
            $questionsERROR = true;
        }

        if ($goodQuestion == "") {
            $errorMsg[] = "You need to give a response";
            $goodQuestionERROR = true;
        } elseif(!verifyAlphaNum($goodQuestion)) {
            $errorMsg[] = "Your response appears to have extra characters that are not allowed";
            $goodQuestionERROR = true;
        }

        if ($elseQuestion == "") {
            $errorMsg[] = "You need to give a response";
            $elseQuestionERROR = true;
        } elseif(!verifyAlphaNum($elseQuestion)) {
            $errorMsg[] = "Your response appears to have extra characters that are not allowed";
            $elseQuestionERROR = true;
        }

        if ($experinceQuestion == "") {
            $errorMsg[] = "You need to give a response";
            $experinceQuestionERROR = true;
        } elseif(!verifyAlphaNum($experinceQuestion)) {
            $errorMsg[] = "Your response appears to have extra characters that are not allowed";
            $experinceQuestionERROR = true;
        }

        if($position != "Commodore" AND $position != "Vice-Commodore" AND $position != "Security" AND $position != "Security" AND $position != "Staff" AND $position != "DockHand" AND $position != "Chef" AND $position != "WebsiteDeveloper"){
            $errorMsg[] = "Please choose a hire method";
            $positionERROR = true;
        }

        if(!verifyPhone($phoneNumber)) {
            $errorMsg[] = "Your phone number appears to be incorrect";
            $phoneERROR = true;
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
            $myFileName = 'apply';
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

            $message = '<h1>Maple Hill Yacht Club | Apply</h1>';

            $message .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . "<br>" . "for your application. We will get back to you shortly</p>";
            $message .= "<p>Check your email often for a response</p>";
            $message .= "<p>Sincerely, Maple Hill Yacht Club</p>";

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
            $from = 'apply@mhyc.com';

            $subject = 'Maple Hill YC: Application';

            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);


            if($mailed) {
                $adminMessage = "<h1>New submission from Maple Hill YC Apply form form..... be sure to check their application!";
                $adminMessage .= "<p>-----------------------------------------------------</p><p> </p><p> </p>";
                $adminMessage .= $message;

                sendMail("bscrosby@uvm.edu", "hjensen3@uvm.edu", $bcc, $from, "Maple Hill YC: New Application", $adminMessage);


                if($age == "less16") {
                    $replyMessage = '<h1>Maple Hill Yacht Club | Apply</h1>';
                    $replyMessage .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . "<br>" . " for your application.</p>";
                    $replyMessage .= "<p>We have received your application, however, you need to be older than 15 to apply at MHYC.</p>";
                    $replyMessage .= "<p>We apologise for any problems this may have caused.</p>";
                    $replyMessage .= "<p>Sincerely, Maple Hill Yacht Club</p>";
                    sendMail($to, $cc, $bcc, $from, $subject, $replyMessage);

                }
                elseif ($age != "old21" AND $position == "Chef"){
                    $replyMessage = '<h1>Maple Hill Yacht Club | Apply</h1>';
                    $replyMessage .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . "<br>" . " for your application.</p>";
                    $replyMessage .= "<p>We have received your application, however, you need to be older than <b>21</b> to work as a chef at MHYC</p>";
                    $replyMessage .= "<p>We apologise for any problems this may have caused.</p>";
                    $replyMessage .= "<p>Sincerely, Maple Hill Yacht Club</p>";
                    sendMail($to, $cc, $bcc, $from, $subject, $replyMessage);
                }
                else{
                    $replyMessage = '<h1>Maple Hill Yacht Club | Apply</h1>';
                    $replyMessage .= '<p>Thank-you, ' . ucfirst($firstName) . " " . ucfirst($lastName) . "<br>" . " for your application.</p>";
                    $replyMessage .= "<p>We have received your application, however, we are not hiring at the moment. Please check check again soon!</p>";
                    $replyMessage .= "<p>Sincerely, Maple Hill Yacht Club</p>";

                    sendMail($to, $cc, $bcc, $from, $subject, $replyMessage);
                }



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

        print '<h2>Maple Hill YC Apply</h2>';

        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print "<h3>Thank-you for your application, be sure to check your email often!</h3>";
            print "<p>For your records, a copy of this data has been sent:</p>";
            print "<p>To: " . $email . "</p>";

        } else {
            print '<h3>Please fill out the application form</h3>';

            //#################################
            //
            // SECTION 3b Error Messages
            //

            if($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print "<h3>Your application has the following errors!</h3>" . PHP_EOL;
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

                <fieldset class="listbox" <?php if ($ageERROR) print ' mistake'; ?>>
                    <h3>Please select your age range.</h3>
                    <p>
                        <select id="lstAge"
                                name="lstAge"
                                tabindex="320" >
                            <option <?php if($age=="less16") print " selected "; ?>
                                    value="less16">Less than 16 (0-15)</option>
                            <option <?php if($age=="old16") print " selected "; ?>
                                    value="old16">Older than 16 (16-17)</option>
                            <option <?php if($age=="old18") print " selected "; ?>
                                    value="old18">Older than 18 (18-20)</option>
                            <option <?php if($age=="old21") print " selected "; ?>
                                    value="old21">Older than 21 (> 21)</option>
                        </select>
                    </p>
                </fieldset>

                <fieldset>
                    <legend>Questions</legend>
                    <h3>Why do you want to work at Maple Hill YC?</h3>
                    <p>
                        <textarea <?php if ($questionsERROR) print 'class="mistake"'; ?> id="txtQuestions" name="txtQuestions" onfocus="this.select()" tabindex="200"><?php print $questions; ?></textarea>
                    </p>

                    <h3>What makes you a good candidate for the position?</h3>
                    <p>
                        <textarea <?php if ($goodQuestionERROR) print 'class="mistake"'; ?> id="txtgoodQuestion" name="txtgoodQuestion" onfocus="this.select()" tabindex="210"><?php print $goodQuestion; ?></textarea>
                    </p>

                    <h3>What other work experience have you had?</h3>
                    <p>
                        <textarea <?php if ($experinceQuestionERROR) print 'class="mistake"'; ?> id="txtexperinceQuestion" name="txtexperinceQuestion" onfocus="this.select()" tabindex="220"><?php print $experinceQuestion; ?></textarea>
                    </p>

                    <h3>Anything else you would like us to know?</h3>
                    <p>
                        <textarea <?php if ($elseQuestionERROR) print 'class="mistake"'; ?> id="txtelseQuestion" name="txtelseQuestion" onfocus="this.select()" tabindex="230"><?php print $elseQuestion; ?></textarea>
                    </p>

                </fieldset>

                <fieldset class="radio <?php if ($positionERROR) print ' mistake'; ?>">
                    <legend>Position</legend>
                    <h3>What position are you applying for?</h3>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radCommodore"
                                   name="radPosition"
                                   value="Commodore"
                                   tabindex="572"
                                <?php if ($position == "Commodore") print ' checked="checked" '; ?>>
                            Commodore</label>
                    </p>

                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radViceCommodore"
                                   name="radPosition"
                                   value="Vice-Commodore"
                                   tabindex="582"
                                <?php if ($position == "Vice-Commodore") print ' checked="checked" '; ?>>
                            Vice-Commodore</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radSecurity"
                                   name="radPosition"
                                   value="Security"
                                   tabindex="592"
                                <?php if ($position == "Security") print ' checked="checked" '; ?>>
                            Security</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radStaff"
                                   name="radPosition"
                                   value="Staff"
                                   tabindex="593"
                                <?php if ($position == "Staff") print ' checked="checked" '; ?>>
                            Staff</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radDockHand"
                                   name="radPosition"
                                   value="DockHand"
                                   tabindex="594"
                                <?php if ($position == "DockHand") print ' checked="checked" '; ?>>
                            Dock Hand</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radChef"
                                   name="radPosition"
                                   value="Chef"
                                   tabindex="595"
                                <?php if ($position == "Chef") print ' checked="checked" '; ?>>
                            Chef</label>
                    </p>
                    <p>
                        <label class="radio-field">
                            <input type="radio"
                                   id="radWebsiteDeveloper"
                                   name="radPosition"
                                   value="WebsiteDeveloper"
                                   tabindex="596"
                                <?php if ($position == "WebsiteDeveloper") print ' checked="checked" '; ?>>
                            Website Developer</label>
                    </p>
                </fieldset>

                <fieldset class="buttons">
                    <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Apply" >
                </fieldset>
            </form>


            <?php
        }
        ?>
</article>
<?php
    include ("footer.php");
?>
