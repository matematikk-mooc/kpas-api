<?php

$secret = getenv('PHPINFO_SECRET');
if (strlen($secret) > 10) {
    if ($_GET['s'] === $secret) {
        phpinfo();
    }
}