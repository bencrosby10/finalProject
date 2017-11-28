<nav>
    <ol>
        <?php
        print '<li';
            if ($path_parts['filename'] == "index") {
                print ' class="activePage" ';
            }
            print '>';
            print '<a href="index.php">Home</a>';
        print '</li>';

        print '<li';
            if ($path_parts['filename'] == "staff") {
                print ' class="activePage" ';
            }
            print '>';
        print '<a href="staff.php">Staff</a>';
        print '</li>';

        print '<li';
            if ($path_parts['filename'] == "events") {
                print ' class="activePage" ';
            }
            print '>';
            print '<a href="events.php">Events</a>';
        print '</li>';

        print '<li';
            if ($path_parts['filename'] == "gallery") {
                print ' class="activePage" ';
            }
            print '>';
            print '<a href="gallery.php">Gallery</a>';
        print '</li>';

        print '<li';
            if ($path_parts['filename'] == "jrsailing") {
                print ' class="activePage" ';
            }
            print '>';
            print '<a href="jrsailing.php">JR.Sailing</a>';
        print '</li>';

        print '<li';
            if ($path_parts['filename'] == "form") {
                print ' class="activePage" ';
            }
            print '>';
            print '<a href="form.php">Contact</a>';
        print '</li>';
        ?>
    </ol>
</nav>
