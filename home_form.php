<div id="centeralign">
	Hello, <strong><?=$_SESSION["username"]?></strong>.
</div>

<br>

<div class="panel panel-default">
	<ul class="nav nav-tabs nav-justified">
		 <li role="presentation">
			<a href="choosemajor.php"><b>Change Majors (Deletes all saved values)</b></a>
		</li>
		<li role="presentation">
			<a href="main.php"><b>Go to 4 Year Plan</b></a>
		</li>
		 <li role="presentation">
			<a href="logout.php"><b>Log Out</b></a>
		</li>
	</ul>
</div>

<div class="panel panel-default">
	<div class="panel-body">
    	<p>Welcome to your 4 Year Plan. In the table below, you can see which classes you are missing to fulfill your major requirements. To view and edit your planned schedule, click the "Go to 4 Year Plan" button above.</p>
		<p>To change your selected majors/minors, click the Change Majors button above. Note that this will erase all your saved classes</p>
  	</div>
	<table class="table" data-toggle="table" data-row-style="rowStyle">
		<tr>
			<th style="text-align:center;"> Selected majors </th>
			<th style="text-align:center;"> Reqs needed </th>
		</tr>
		<?php
			foreach($majors as $major)
			{
				echo '<tr>';
				echo '<td>' . $major . '</td>';
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
								echo '<li>' . $info["message"] . '</li>';
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

	<br>

	<!-- Display a list of the included majors at the bottom-->
	<div style="font-size: 110%;">
		Classes also included from majors:
		<?php
			if ($includedmajors != null)
			{
				echo implode(",", $includedmajors);
			}
		?>
	</div>
</div>
