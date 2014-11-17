<div id="centeralign">
	Hello, <strong><?=$_SESSION["username"]?></strong>.
</div>

<br>

<div>
	<ul id="navbar"> 
		<li class="link-li">
			<a class="link-a" href="main.php"><b>Go to 4 Year Plan</b></a>
		</li>
		<li class="link-li">
			<a class="link-a" href="choosemajor.php"><b>Change Majors (Deletes all saved values)</b></a>
		</li>
		<li class="link-li">
			<a class="link-a" href="logout.php"><b>Log Out</b></a>
		</li>
	</ul>
</div>
<br>


<table class="table">
	<tr>
		<th> Selected majors </th>
		<th> Reqs needed </th>
	</tr>
	<?php
		foreach($majors as $major)
		{
			echo '<tr>';
			echo '<td><b>' . $major . '</b></td>';
			echo '<td>';

			if ($missing_classes[$major]["simple"] != [] || $missing_classes[$major]["one_of"] != [] || $missing_classes[$major]["special"] != [])
			{
				if ($missing_classes[$major] != ["No data found"])
				{
					echo '<ul style="text-align: left">';

					//Simple
					if ($missing_classes[$major]["simple"] != [])
					{
						foreach ($missing_classes[$major]["simple"] as $classname)
						{
							echo '<li>' . $classname . '</li>';
						}
					}

					//One_of
					if ($missing_classes[$major]["one_of"] != [])
					{
						foreach ($missing_classes[$major]["one_of"] as $info)
						{
							echo '<li>Need ' . $info["req"] . ' of ' . implode(", ", $info["classes"]) . '</li>';
						}
					}

					//Special
					if ($missing_classes[$major]["special"] != [])
					{
						foreach ($missing_classes[$major]["special"] as $info)
						{
							echo '<li> Need ' . $info["req"] . ' of level (' . implode(", ", $info["levels"]) . ') out of depts (' . implode(", ", $info["depts"]) . ') that are not (' . implode(", ", $info["not"]) . ')</li>';
						}
					}

					echo '</ul>';
				}
				else
				{
					echo '<span style="color: red">No data found</span>';
				}	
			}
			else
			{
				echo '<span style="color: green">All reqs fulfulled!</span>';
			}

			/*//If there are classes missing for that major
			if ($missing[$major] != [])
			{
				echo '<ul style="text-align: left">';
				//For each missing class
				foreach ($missing[$major] as $classes)
				{
					//If the required number is 1 and the number of classes is 1, just display the class
					if ($classes["req_number"] == 1 && count($classes["classes"]) == 1)
					{
						echo '<li>' . implode(",", $classes["classes"]) . '</li>';
					}
					//Otherwise, display in the form "1 classes from: (list of classes here)"
					else
					{
						echo '<li>Need ' . $classes["req_number"] . ' classes from: ' . implode(", ", $classes["classes"]) . '</li>';
					}
					
				}
				echo '</ul>';
			}
			//Otherwise, say that all reqs are fulfilled
			else
			{
				echo '<span style="color: green">All reqs fulfulled!</span>';
			}*/

			echo '</td>';
			echo '</tr>';
		}
	?>
</table>

<!-- Display a list of the included majors at the bottom-->
<div style="font-size: 110%;">
	Classes also included from majors:
	<?php
		if ($includedmajors != null)
		{
			echo '<b>' . implode(",", $includedmajors);
		}
	?>
</div>
