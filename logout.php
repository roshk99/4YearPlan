<?php

    // configuration
    require("config.php"); 

    // log out current user, if any
    logout();

    // redirect user
    redirect("login.php");

?>
