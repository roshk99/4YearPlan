<form action="choosemajor.php" method="post">
	<fieldset align="center">		
		<div class="form-group">
			<h3>Select majors/minors</h3>
			<select name="selectedmajors[]" class="selectpicker" data-live-search="true" multiple data-max-option="3">
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
			<h4>Select majors/minors for which you want to include classes but not check if you met the requirements (such as electives)</h4>
			<select name="includedmajors[]" class="selectpicker" data-live-search="true" multiple data-max-option="3">
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
	</fieldset>
</form>
