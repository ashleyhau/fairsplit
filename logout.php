<?php

session_start();
session_destroy();

header("Location: ./"); // refresh page so it can go back into login page

?>