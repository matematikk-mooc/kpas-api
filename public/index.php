<?php

echo "PHP HEADERS\n";


foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}
