<?php
	require('config.php');

	//Clears all entries for semesters
	$query = query('UPDATE users SET semester0="",semester1="",semester2="",semester3="",semester4="",semester5="",semester6="",semester7="",semester8="" WHERE userid=?', $_SESSION["id"]);

	//Gets all majors
	$majors = query('SELECT major_name FROM majors');
	
	//If form was submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
		//If no major was selected, apologize
		if (empty($_POST["selectedmajors"]))
		{
			apologize("Please select at least one major");
		}
		else
		{
			//Store selectedmajors and includedmajors in database
			$selected_majors = $_POST["selectedmajors"];
			$included_majors = $_POST["includedmajors"];

			$query = query('UPDATE users SET selectedmajors=? WHERE userid=?', implode(",", $selected_majors), $_SESSION["id"]);
			$query = query('UPDATE users SET includedmajors=? WHERE userid=?', implode(",", $included_majors), $_SESSION["id"]);
			
			//Redirect to home
			redirect('home.php');  
		}
	}
	
	else
	{
		//Render the form to choose a major
		render('choosemajor_form.php', ["majors" => $majors]);
	}
?>
