<?php
	require('config.php');

	//Get selected and included majors
	$query = query('SELECT selectedmajors FROM users WHERE userid=?', $_SESSION["id"]);
	$include_query = query('SELECT includedmajors FROM users WHERE userid=?', $_SESSION["id"]);

	//If no major selected redirect to choosemajor
	if ($query[0]["selectedmajors"] == "")
	{
		redirect("choosemajor.php");
	}
	else {
	
	//Convert selectedmajors to an array and add general and ap classes	
	$majors = explode(",", $query[0]["selectedmajors"]);
	$majors[count($majors)] = "GENR";
	$majors[count($majors)] = "APCL";
	
	//If the includedmajors are not blank, convert to an array and append to the end of majors
	if ($include_query[0]["includedmajors"] != "")
	{
		$included = explode(",", $include_query[0]["includedmajors"]);
		$my_majors = array_merge($majors, $included);
	}
	//Otherwise my_majors just has majors
	else
	{
		$my_majors = $majors;
	}

	//For each semester including semester0 (ap classes)
	$semesters = [];
	for ($sem=0; $sem<9; $sem++)
	{
		//Get selected classes for the semester
		$my_sem = "semester" . $sem;
		$query = query('SELECT ' . $my_sem . ' FROM users WHERE userid=?', $_SESSION["id"]);

		//If there are any selected classes
		if ($query[0][$my_sem] != "")
		{
			//Convert to an array of class ids
			$my_class_ids = explode(",", $query[0][$my_sem]);
			$my_classes = [];
			$j = 0;

			//For each class id, get the class name and store it in my_classes
			foreach ($my_class_ids as $id)
			{
				$query = query('SELECT class_name FROM classes WHERE id=?', $id);
				$my_classes[$j] = $query[0]["class_name"];
				$j++;
			}

			//Append my_classes to semesters
			$semesters[$sem] = $my_classes;
		}
		else
		{
			$semesters[$sem] = null;
		}
	}
	
	//Create empty arrays for the classlist and for ap_classoptions
	$classlist = [];
	$ap_classoptions = [];

	//For each major
	foreach ($my_majors as $major)
	{
		//For each column (classid, classid1,.., classid3) of the class_major 
		for($count=1; $count<4; $count++)
		{

			//Get column name
			$colnam = "class_id";
			if ($count != 1)
			{
				$colnam = $colnam . $count;
			}
			
			//Get all the classes in the column of class_major where the major is the current major
			$classes = query('SELECT * FROM classes LEFT JOIN class_major ON classes.id=class_major.' . $colnam . ' WHERE class_major.major=? ', $major);
		
			//For each class
			foreach ($classes as $class)
			{	
				//If the classname is defined
				if ($class["class_name"] != null)
				{
					//Get the prereqs for the class
					$prereqs = query('SELECT * FROM prereqs WHERE class_id=?', $class["class_id"]);

					//If the class is not already added to classlist, add it with specified information
					if (!array_key_exists($class["class_name"], $classlist))
					{ 
						$classlist[ $class["class_name"] ] = [ "fall" => $class["fall"], "spring" => $class["spring"], "credits" => $class["credits"], "dist" => $class["dist"] ];
					}

					//If not adding general or ap classes
					if ($major != "GENR" && $major != "APCL")
					{
						//If majors have not yet been added to the class, add the major as an array
						if (!array_key_exists("majors", $classlist[$class["class_name"]]))
						{
							$classlist[ $class["class_name"] ]["majors"] = [$major];
						}
						//Otherwise, append it to the end of the existing major array associated with the class
						else
						{
							$classlist[ $class["class_name"] ]["majors"][count($classlist[ $class["class_name"] ]["majors"])] = $major;
						}
					}
					//Otherwise, add GENR or APCL to a new attribute, special
					else
					{
						if (!array_key_exists("special", $classlist[$class["class_name"]]))
						{
							$classlist[ $class["class_name"] ]["special"] = [$major];
						}
						else
						{
							$classlist[ $class["class_name"] ]["special"][count($classlist[ $class["class_name"] ]["special"])] = $major;
						}
					}

					//If there are prereqs
					if (count($prereqs) > 0)
					{
						//Get the prerqs in a list
						$my_prereq = [];
						for ($i=1; $i<6; $i++)
						{
							if (strlen($prereqs[0]["prereq" . $i]) > 0)
							{
								$my_prereq[$i-1] = $prereqs[0]["prereq" . $i];
							} 
						}

						//Add the prereqs to classlist
						if (count($my_prereq) > 0)
						{
							$classlist[ $class["class_name"] ]["prereqs"] = $my_prereq;
						}
					}
			
					//For each semester, add a new attribute selected and assign the semester number to it if the class saved before
					for($sem_num=0; $sem_num<9; $sem_num++)
					{
						if ($semesters[$sem_num] != null)
						{	 
							if (in_array($class["class_name"], $semesters[$sem_num]))
							{
								$classlist[ $class["class_name"] ]["selected"] = $sem_num;
							} 
						}
					}

					//If the major is APCL, add it to ap class options list 
					if ($major == "APCL")
					{
						$ap_classoptions[$class["class_name"]] = $classlist[ $class["class_name"] ];
					} 
				}
			}
		} 
	}

	/*$my_majors = [];
	foreach ($majors as $my_major)
	{
		if ($my_major != "GENR" && $my_major != "APCL")
		{
			$credits = query('SELECT credit_reqs FROM majors WHERE major_name = ?', $my_major);
			$my_majors[$my_major] = $credits[0]['credit_reqs'];
		}
	}*/

	render('interface.php', ["classlist" => $classlist, "my_majors" => $my_majors, "ap_classoptions" => $ap_classoptions]);
	} //else	
?>
