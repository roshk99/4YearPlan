<?php

function renderclasses($class_name, $class_info, $sem_num)
{
	$mystr = $class_name;

	if ($class_info["dist"] == 1)
	{
		$dist = "I";
	}
	elseif ($class_info["dist"] == 2)
	{
		$dist = "II";
	}
	elseif ($class_info["dist"] == 3)
	{
		$dist = "III";
	}
	
	if (isset($dist))
	{
		$mystr = $mystr . " (" . $dist . ") ";
	}

	if (isset($class_info["prereqs"]))
	{
		$mystr = $mystr . ' {' . implode(",", $class_info["prereqs"]) . '} ';
		
	}

	if (isset($class_info["selected"]))
	{
		if ($class_info["selected"] == $sem_num)
		{
			echo '<option selected>' . $mystr . ' - ' . $class_info["credits"] . '</option>';
		}
		else
		{
			echo '<option>' . $mystr . ' - ' . $class_info["credits"] . '</option>';
		}
	}
	else
	{
		echo '<option>' . $mystr . ' - ' . $class_info["credits"] . '</option>';
	}
} 

?>
