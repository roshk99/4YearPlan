<?php
	require('renderclasses.php'); 
?>
<div> 
	<a href="home.php"><b>Back to Home</b></a>
	<p> Make sure you save!</p>
</div>

<!--Div for all the counters-->
<div class="panel panel-default">
	<div class="panel-heading">Counters</div>
	<table class="table" style="font-weight: bold;">
		<tr>
			<td id="totalcreditstd" class="active">Total Credits: <span id="totalcredits"> <span id="totalcreditsnum">0</span>/<span id="totalcreditsden">120</span> </span> </td>
			<td id="upperleveltd" class="active">Upper Level Credits: <span id="upperlevel"> <span id="upperlevelnum">0</span>/<span id="upperlevelden">48</span> </span></td>
		</tr>
	</table>
	<table class="table" style="font-weight: bold;">
		<tr>
			<td id="dist1td" class="active">Distribution 1: <span id="dist1"> <span id="dist1num">0</span>/<span id="dist1den">12</span> </span></td>
			<td id="dist2td" class="active">Distribution 2: <span id="dist2"> <span id="dist2num">0</span>/<span id="dist2den">12</span> </span></td>
			<td id="dist3td" class="active">Distribution 3: <span id="dist3"> <span id="dist3num">0</span>/<span id="dist3den">12</span> </span></td>
			<td id="fwistd" class="active">FWIS: <span id="fwis"> <span id="fwisnum">0</span>/<span id="fwisden">3</span> </span></td>
			<td id="lpaptd" class="active">LPAP: <span id="lpap"> <span id="lpapnum">0</span>/<span id="lpapden">1</span> </span></td>
		</tr>
	</table>
	<div class="panel-body"><button id="countercalc" type="button" class="btn btn-default" onclick="update()" >Calculate Counters and Populate My Plan</button></div>
</div>

<!--Div for the selectboxes and buttons-->
<div id="semesterform" class="panel panel-default" style="float: left;">
	<div class="panel-heading">Select Classes</div>
	<div class="panel-body">
		<form id="saveclasses" method="post" action="save.php">
			<button type="submit" class="btn btn-default">Save Class Selections</button><br><br>
		<?php
			//Renders ap classes separately
			echo '<b> AP Classes </b>';
			echo '<select id = "semester0" name="semester0[]" class="selectpicker" data-live-search="true" 
				data-width="300px" multiple data-max-option="25">';
		
			foreach ($ap_classoptions as $class_name => $class_info)
			{
				renderclasses($class_name, $class_info, 0);
			}
		
			echo '</select><br></br>';

			//Renders fall classes in fall semesters and spring classes in spring semesters
			for ($i=1; $i<9; $i++)
			{
				echo '<b> Semester ' . $i . ' </b>  ';
				echo '<select id = "semester'. $i . '"name="semester' . $i . '[]" class="selectpicker" data-live-search="true" 
						data-width="300px" multiple data-max-option="7">';
	
				foreach ($classlist as $class_name => $class_info)
				{
					if ($class_info["fall"] == 1 && $i%2 == 1)
					{
						renderclasses($class_name, $class_info, $i);
					}
					elseif ($class_info["spring"] == 1 && $i%2 == 0)
					{
						renderclasses($class_name, $class_info, $i);
					}					
						 
				}

				echo '</select><br></br>';
			}
		?>
		</form>
	</div>
</div>

<!--Div for semester table-->
<div id="semesters" class="panel panel-default" style="float: right;">
	<div class="panel-heading">My Plan</div>
	<div class="panel-body">Classes that appear in blue do not have their prerequisites fulfulled</div>
	<!--Table for AP classes, will just have a comma separated list-->
	<table class="table table-bordered" style="width: 700px; min-height:100px">
		<tr>
			<td>
				<p><b> AP classes </b></p>
				<span id="apclasseslist"> </span>
			</td>
		</tr>
	</table>
	<br>
	<table class="table table-bordered table-striped" style="width: 700px;">
	<?php
		//Creates semesters 1-8 and adds 7 bullets per semester
		for ($i=1; $i<9; $i++)
			{
				//Odd semesters on the left and even semesters on the right
				if ($i % 2 == 1)
				{
					echo '<tr>';
					echo '<td>';
					echo '<b>Semester ' . $i . '</b><br></br>';
					echo '<ul id="semester' . $i . 'list" style="text-align: left">';
					for ($j=1; $j<8; $j++)
					{
						echo '<li id="semester' . $i . '-' . $j . '"></li>';
					}
					echo '</ul>';
					echo 'Total Credits: <span id=semester' . $i . 'num>0</span>'; 
					echo '</td>';		
				}
				else
				{
					echo '<td>';
					echo '<b>Semester ' . $i . '</b><br></br>';
					echo '<ul id="semester' . $i . 'list" style="text-align: left">';
					for ($j=1; $j<8; $j++)
					{
						echo '<li id="semester' . $i . '-' . $j . '"></li>';
					}
					echo '</ul>';
					echo 'Total Credits: <span id=semester' . $i . 'num>0</span>';
					echo '</td>';
					echo '</tr>';		
				}
			}
	?>
	</table>
</div>
