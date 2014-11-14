<?php
	require('config.php');

	//Clears all entries for semesters
	$query = query('UPDATE users SET semester0="",semester1="",semester2="",semester3="",semester4="",semester5="",semester6="",semester7="",semester8="" WHERE userid=?', $_SESSION["id"]);

	$majors = query('SELECT major_name FROM majors');

	if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
		
		if (empty($_POST["selectedmajors"]))
		{
			apologize("Please select at least one major");
		}
		else
		{
			$selected_majors = $_POST["selectedmajors"];
			$included_majors = $_POST["includedmajors"];

			$query = query('UPDATE users SET selectedmajors=? WHERE userid=?', implode(",", $selected_majors), $_SESSION["id"]);
			$query = query('UPDATE users SET includedmajors=? WHERE userid=?', implode(",", $included_majors), $_SESSION["id"]);

			redirect('home.php');  
		}
	}
	
	else
	{
		render('choosemajor_form.php', ["majors" => $majors]);
	}
?>
