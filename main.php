<?php
	require('config.php');

	$query = query('SELECT selectedmajors FROM users WHERE userid=?', $_SESSION["id"]);
	$include_query = query('SELECT includedmajors FROM users WHERE userid=?', $_SESSION["id"]);

	if ($query[0]["selectedmajors"] == "")
	{
		redirect("choosemajor.php");
	}
	else {
		
	$majors = explode(",", $query[0]["selectedmajors"]);
	$majors[count($majors)] = "GENR";
	$majors[count($majors)] = "APCL";
	
	if ($include_query[0]["includedmajors"] != "")
	{
		$included = explode(",", $include_query[0]["includedmajors"]);
		$my_majors = array_merge($majors, $included);
	}
	else
	{
		$my_majors = $majors;
	}

	$semesters = [];
	for ($sem=0; $sem<9; $sem++)
	{
		$my_sem = "semester" . $sem;
		$query = query('SELECT ' . $my_sem . ' FROM users WHERE userid=?', $_SESSION["id"]);
		if ($query[0][$my_sem] != "")
		{
			$my_class_ids = explode(",", $query[0][$my_sem]);
			$my_classes = [];
			$j = 0;
			foreach ($my_class_ids as $id)
			{
				$query = query('SELECT class_name FROM classes WHERE id=?', $id);
				$my_classes[$j] = $query[0]["class_name"];
				$j++;
			}
			$semesters[$sem] = $my_classes;
		}
		else
		{
			$semesters[$sem] = null;
		}
	}
	
	$classlist = [];
	$ap_classoptions = [];
	foreach ($my_majors as $major)
	{
		for($count=1; $count<4; $count++)
		{
			$colnam = "class_id";
			if ($count != 1)
			{
				$colnam = $colnam . $count;
			}
			$classes = query('SELECT * FROM classes LEFT JOIN class_major ON classes.id=class_major.' . $colnam . ' WHERE class_major.major=? ', $major);
		
			foreach ($classes as $class)
			{	
				if ($class["class_name"] != null)
				{
					$prereqs = query('SELECT * FROM prereqs WHERE class_id=?', $class["class_id"]);
					if (!array_key_exists($class["class_name"], $classlist))
					{ 
						$classlist[ $class["class_name"] ] = [ "fall" => $class["fall"], "spring" => $class["spring"], "credits" => $class["credits"], "dist" => $class["dist"] ];
					}
					if ($major != "GENR" && $major != "APCL")
					{
						if (!array_key_exists("majors", $classlist[$class["class_name"]]))
						{
							$classlist[ $class["class_name"] ]["majors"] = [$major];
						}
						else
						{
							$classlist[ $class["class_name"] ]["majors"][count($classlist[ $class["class_name"] ]["majors"])] = $major;
						}
					}
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

					if (count($prereqs) > 0)
					{
						$my_prereq = [];
						for ($i=1; $i<6; $i++)
						{
							if (strlen($prereqs[0]["prereq" . $i]) > 0)
							{
								$my_prereq[$i-1] = $prereqs[0]["prereq" . $i];
							} 
						}

						if (count($my_prereq) > 0)
						{
							$classlist[ $class["class_name"] ]["prereqs"] = $my_prereq;
						}
					}
			
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

					if ($major == "APCL")
					{
						$ap_classoptions[$class["class_name"]] = $classlist[ $class["class_name"] ];
					} 
				}
			}
		} 
	}

	$my_majors = [];
	foreach ($majors as $my_major)
	{
		if ($my_major != "GENR" && $my_major != "APCL")
		{
			$credits = query('SELECT credit_reqs FROM majors WHERE major_name = ?', $my_major);
			$my_majors[$my_major] = $credits[0]['credit_reqs'];
		}
	}
	render('interface.php', ["classlist" => $classlist, "my_majors" => $my_majors, "ap_classoptions" => $ap_classoptions]);
	} //else	
?>
