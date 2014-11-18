<form action="choosemajor.php" method="post" role="form">	
	<div class="form-group">
		<label for="selectmajor">Select majors/minors</label><br>
		<select id="selectmajor" name="selectedmajors[]" class="selectpicker" data-live-search="true" multiple data-max-option="3">
				<?php	
					foreach ($majors as $major)
					{
						echo '<option>' . $major['major_name'] . '</option>';  
					}
				?>
		</select>
	</div>
	<br>
	<div class="form-group">
		<label for="includemajor">Select majors/minors for which you want to include classes but not check if you met the requirements (such as electives)</label><br>
		<select id="includemajor" name="includedmajors[]" class="selectpicker" data-live-search="true" multiple data-max-option="3">
				<?php	
					foreach ($majors as $major)
					{
						echo '<option>' . $major['major_name'] . '</option>';  
					}
				?>
		</select>
	</div>
	<!--Submits to choosemajor.php-->		
	<button type="submit" class="btn btn-default">Submit</button>
</form>
