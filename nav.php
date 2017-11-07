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
            if ($path_parts['filename'] == "form") {
                print ' activePage ';
            }
            print '">';
            print '<a href="form.php">Contact</a>';
        print '</li>';
        ?>
    </ol>
</nav>
