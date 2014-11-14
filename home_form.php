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
			if ($missing[$major] != [])
			{
				echo '<ul style="text-align: left">';
				foreach ($missing[$major] as $classes)
				{
					if ($classes["req_number"] == 1 && count($classes["classes"]) == 1)
					{
						echo '<li>' . implode(",", $classes["classes"]) . '</li>';
					}
					else
					{
						echo '<li>Need ' . $classes["req_number"] . ' classes from: ' . implode(", ", $classes["classes"]) . '</li>';
					}
					
				}
				echo '</ul>';
			}
			else
			{
				echo '<span style="color: green">All reqs fulfulled!</span>';
			}
			echo '</td>';
			echo '</tr>';
		}
	?>
</table>
<div style="font-size: 110%;">
	Classes also included from majors:
	<?php
		if ($includedmajors != null)
		{
			echo '<b>' . implode(",", $includedmajors);
		}
	?>
</div>
