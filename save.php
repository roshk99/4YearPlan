<?php
	require('config.php');
	//var_dump($_POST);
	
	$id = $_SESSION["id"];


	for ($i=0; $i<9; $i++)
	{
		if (isset($_POST["semester" . $i]) && $_POST["semester" . $i] != null)
		{
			savedata($id, $i, $_POST["semester" . $i]);
		}
		else
		{
			savedata($id, $i, null);
		}
	}

	redirect('main.php');
	
	function savedata($user_id, $sem_num, $semesterdata)
	{
		if ($semesterdata != null)
		{
			$classids = [];
			foreach ($semesterdata as $class)
			{	
				$class_name = substr($class, 0, 8);
				$query = query('SELECT id FROM classes WHERE class_name=?', $class_name);
				
				$classids[count($classids)] = $query[0]["id"]; 
			}
			
			$query2 = query('UPDATE users SET semester' . $sem_num . '=? WHERE userid=?', implode(",",$classids), $user_id);
		}
		else
		{
			$query2 = query('UPDATE users SET semester' . $sem_num . '=? WHERE userid=?', "", $user_id);
		} 
	}
?>
