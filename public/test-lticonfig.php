<?php

printFile("test.txt");
printFile("test2.txt");
printFile("dir1/test1.txt");
printFile("dir2/abc.txt");

function printFile($path) {
    echo "<br><br><br>---<br>database/$path <br>";
    echo file_get_contents("/var/www/html/database/$path");
}

