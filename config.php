<?php
	ini_set("display_errors", true);
    error_reporting(E_ALL);

	session_start();

	// your database's name
    define("DATABASE", "fouryearplan");

    // your database's password
    define("PASSWORD", "rice");

    // your database's server
    define("SERVER", "localhost");

    // your database's username
    define("USERNAME", "admin");

	// require authentication for most pages
    if (!preg_match("{(?:login|logout|register)\.php$}", $_SERVER["PHP_SELF"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }	

	function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("header.php");

            // render template
            require("$template");

            // render footer
            //require("footer.php");
        }

        // else error
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

	function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

	function apologize($message)
    {
        render("apology.php", ["message" => $message]);
        exit;
    }

	function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

	function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

?>
