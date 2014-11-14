<?php

    // configuration
    require("config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {        
        //validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must provide your password confirmation");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your password and your password confirmation must match");
        }
        
        //Check if username is taken
        $rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
        
        //Check if query was valid
        if ($rows === false || count($rows) == 0)
        {
            query("INSERT INTO users (username, password) VALUES(?, ?)", $_POST["username"], $_POST["password"]);
            
            $rows = query("SELECT LAST_INSERT_ID() AS userid");
            $id = $rows[0]["userid"];
            
            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $id;

            // redirect to portfolio
            redirect("choosemajor.php");
        }
        else
        {
            apologize("Duplicate username");
        }
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>
