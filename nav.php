<nav>
    <ol>
        <?php
        print '<li class="';
            if ($path_parts['filename'] == "index") {
                print ' activePage ';
            }
            print '">';
            print '<a href="index.php">Home</a>';
        print '</li>';

        print '<li class="';
            if ($path_parts['filename'] == "staff") {
                print ' activePage ';
            }
            print '">';
        print '<a href="staff.php">Staff</a>';
        print '</li>';

        print '<li class="';
            if ($path_parts['filename'] == "events") {
                print ' activePage ';
            }
            print '">';
            print '<a href="events.php">Events</a>';
        print '</li>';

        print '<li class="';
        if ($path_parts['filename'] == "gallery") {
            print ' activePage ';
        }
        print '">';
        print '<a href="gallery.php">Gallery</a>';
        print '</li>';

        print '<li class="';
            if ($path_parts['filename'] == "jrsailing") {
                print ' activePage ';
            }
            print '">';
            print '<a href="jrsailing.php">JR.Sailing</a>';
        print '</li>';

        print '<li class="';
            if ($path_parts['filename'] == "form") {
                print ' activePage ';
            }
            print '">';
            print '<a href="form.php">Contact</a>';
        print '</li>';
        ?>
    </ol>
</nav>
