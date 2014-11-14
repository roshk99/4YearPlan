<?php
	require('config.php');
	
	//Get the selected and included majors and convert them into an array (are storead as comma separated string)
	$query = query('SELECT selectedmajors FROM users WHERE userid=?', $_SESSION["id"]);
	$majors = explode(",", $query[0]["selectedmajors"]);
	$query = query('SELECT includedmajors FROM users WHERE userid=?', $_SESSION["id"]);
	if ($query[0]["includedmajors"] != "")
	{
		$included = explode(",", $query[0]["includedmajors"]);
	}
	else
	{
		$included = null;
	}
	
	$classlist = [];
	//Get all selected classes 
	for ($i=0; $i<9; $i++)
	{
		$query = query('SELECT semester' . $i . ' FROM users WHERE userid=?', $_SESSION["id"]);
		if ($query[0]["semester" . $i] != "")
		{
			$classlist = array_merge($classlist, explode(",", $query[0]["semester" . $i]));
		}
	}

	//For each major check requirements
	$missing = [];

	foreach ($majors as $major)
	{
		//Get all required classes for the major
		$query = query('SELECT * FROM class_major WHERE major=?', $major);
		$missing[$major] = [];

		//For each required class
		for($i=0, $n=count($query); $i<$n; $i++)
		{
			//Get the required number
			$req_number = $query[$i]["req_number"]; 
			$numer = 0;
			$tmp = [];
			
			//For each column for classes in the query
			for ($j=1; $j<4; $j++)
			{
				//Generate the column name
				$colnam = "class_id";
				if ($j != 1)
				{
					$colnam = $colnam . $j;
				}

				//If the col has a 0, no class was specified there
				if ($query[$i][$colnam] != 0)
				{
					//If the required class is in the selected class, increment numer
					if (array_search($query[$i][$colnam], $classlist) == true)
					{	
						$numer++;	 
					}

					//Otherwise, get the class name for the missing class and store it in tmp
					else
					{
						$class_query = query('SELECT class_name FROM classes WHERE id=?', $query[$i][$colnam]);
						if ($class_query != [])
						{
							$tmp[count($tmp)] = $class_query[0]["class_name"];
						}
					}
				}
			}

			//If the required number of classes from the list is less than the number of classes out of the list that have been added to the 4 Year Plan
			if ($req_number > $numer)
			{
				//Add the classes and the required number of classes out of that list to the missing vector (associated with the key that is the major)
				$missing[$major][ count($missing[$major]) ] = [ "req_number" => $req_number, "classes" => $tmp ];
			}
		}
		
	}
	//Render home_form with the given variables
	render('home_form.php', ["majors" => $majors, "includedmajors" => $included, "missing" => $missing]);
?>
