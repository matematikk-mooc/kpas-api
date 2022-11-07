<?php

echo "Copied file<br>";
echo file_get_contents( "/lticonfigs/test.txt" ); 

echo "<br><br>LTI Store<br>";
echo file_get_contents( "/var/www/html/database/test.txt" ); 