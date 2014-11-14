<?php
	require('getdata.php');
	
	$majors = query('SELECT major_name FROM majors');

	foreach ($majors as &$major)
	{
		echo $major['major_name'];
	}
?>
