<?php
	function checkMECH()
	{
		$simple = ["CHEM 121", "CHEM 122", "MATH 101", "MATH 102", "MATH 211", "MATH 212", "MSNE 301", "PHYS 101", "PHYS 102"];

		$one_of = [ ["req" => 1, "classes" => ["STAT 305", "STAT 310", "STAT 331"] ], ["req" => 2, "classes" => ["MECH 400", "MECH 403", "MECH 411", "MECH 417", "MECH 454", "MECH 594", "MSNE 402", "MECH 474", "MECH 555", "MECH 488", "MECH 498" ] ] ];

		$special = [ ["req" => 1, "levels" => ["300", "400", "500"], "depts" => ["BIOE", "CHBE", "CEVE", "CAAM", "COMP", "ELEC", "MSNE", "MECH", "STAT"], "not" => $simple ] ];

		 return ["simple" => $simple, "one_of" => $one_of, "special" => $special];

	}
?>
