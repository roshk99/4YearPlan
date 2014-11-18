<?php
	function checkMECH()
	{
		$simple = ["CHEM 121", "CHEM 122", "MATH 101", "MATH 102", "MATH 211", "MATH 212", "MSNE 301", "PHYS 101", "PHYS 102", "CAAM 210", "CAAM 335", "CAAM 336", "MECH 407", "MECH 408", "MECH 331", "MECH 332", "MECH 340", "MECH 431", "MECH 200", "MECH 211", "MECH 311", "MECH 343", "MECH 371", "MECH 401", "MECH 412", "MECH 420", "MECH 472", "MECH 481"];

		$one_of = [ ["req" => 1, "classes" => ["STAT 305", "STAT 310", "STAT 331"] ], ["req" => 2, "classes" => ["MECH 400", "MECH 403", "MECH 411", "MECH 417", "MECH 454", "MECH 594", "MSNE 402", "MECH 474", "MECH 555", "MECH 488", "MECH 498" ] ] ];
		//This includes the other two clusters since they fulfull this requirements as well
		$special = [ ["req" => 3, "levels" => ["300", "400", "500"], "depts" => ["BIOE", "CHBE", "CEVE", "CAAM", "COMP", "ELEC", "MSNE", "MECH", "STAT"], "not" => $simple, "message" => "Need one 300+ class in the engineering department" ] ];

		 return ["simple" => $simple, "one_of" => $one_of, "special" => $special];

	}
?>
