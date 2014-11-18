<?php
	require('checkMECH.php');
	require('checkBUSI.php');

	function checkreqs($classlist, $major)
	{
		if (function_exists('check' . $major) == false)
		{
			return ["No data found"];
		}
		$array = call_user_func('check' . $major);
		$simple = $array["simple"];
		$one_of = $array["one_of"];
		$special = $array["special"];


		$missing = [];
		$missing["simple"] = [];
		foreach ($simple as $class)
		{
			if (array_search($class, $classlist) == false)
			{
				$missing["simple"][count($missing["simple"])] = $class;
			}
		}

		$missing["one_of"] = [];
		foreach($one_of as $info)
		{
			$numer = 0;
			foreach($info["classes"] as $class)
			{
				if (array_search($class, $classlist) == true)
				{
					$numer++;
				}
			}

			if ($numer < $info["req"])
			{
				$missing["one_of"][count($missing["one_of"])] = $info;
			}
		}

		$missing["special"] = [];
		foreach($special as $info)
		{
			$numer = 0;
			foreach ($classlist as $class)
			{	
				$level = $class[5] . "00";
				$dept = substr($class, 0, 4);
				
				if (array_search($level, $info["levels"]) == true && array_search($dept, $info["depts"]) == true && array_search($class, $info["not"]) == false)
				{
					$numer++;
				}
			}

			if ($numer < $info["req"])
			{
				$missing["special"][count($missing["special"])] = $info;
			}
		}
		
		return $missing;
	}	

?>
