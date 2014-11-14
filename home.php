<?php
	require('config.php');

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
	//Get all classes 
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
		$query = query('SELECT * FROM class_major WHERE major=?', $major);
		$missing[$major] = [];
		for($i=0, $n=count($query); $i<$n; $i++)
		{
			$req_number = $query[$i]["req_number"]; 
			$numer = 0;
			$tmp = [];
			for ($j=1; $j<4; $j++)
			{
				$colnam = "class_id";
				if ($j != 1)
				{
					$colnam = $colnam . $j;
				}
				if ($query[$i][$colnam] != 0)
				{
					if (array_search($query[$i][$colnam], $classlist) == true)
					{	
						$numer++;	 
					}
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
			if ($req_number > $numer)
			{
				$missing[$major][ count($missing[$major]) ] = [ "req_number" => $req_number, "classes" => $tmp ];
			}
		}
		
	}
	render('home_form.php', ["majors" => $majors, "includedmajors" => $included, "missing" => $missing]);
?>
