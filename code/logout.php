<?php

session_start();
session_unset();
session_destroy();
header('location: shopping.php?page=1');

?>
