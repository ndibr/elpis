<?php  ?>
<div class="event_form">
    <form id="form_event" action="index.php" method="post" autocomplete="off"><input type="hidden" autocomplete="false"/>
		<label>Calendar</label>
		<select id="calendar_id" name="calendar_id">
			<?php foreach($calendars as $index => $row) {?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['title']; ?></option>
			<?php } ?>
		</select> <br/>
		<label>Title *</label><input type="text" id="title" name="title" required> <br/>
		<label>Date From *</label><input type="text" id="datetime_from" name="datetime_from" required><label>Time From *</label><input type="text" id="time_from" name="time_from" placeholder="hh:mm" required>  <br/>
		<label>Date Till</label><input type="text" id="datetime_till" name="datetime_till"><label>Time Till</label><input type="text" id="time_till" name="time_till" placeholder="hh:mm"> <br/>
		<label>Place</label><input type="text" id="place" name="place"> <br/>
		<label>Priority</label>
		<select id="priority_id" name="priority_id">
			<?php foreach($priority as $index => $row) { ?>
			<option value="<?php echo $row['id'];?>"><?php echo $row['name']; ?></option>
			<?php } ?>
		</select><br/>
		<label>Description</label><textarea id="description" name="description" rows="10" cols="50"></textarea> <br/>
		<a class="btn btn_create btn_event" href="#">Create</a>
		<br /><?php echo isset_or($error_message); ?>
		<input type="hidden" id="op" readonly="readonly" value="create_event" name="op"/>
	</form>
	</div>
	<script>
		$(document).ready( function() {
			$( "#datetime_from" ).datepicker();
      $( "#datetime_from" ).datepicker( "option", "dateFormat", "dd.mm.yy");		
			$( "#datetime_till" ).datepicker();
      $( "#datetime_till" ).datepicker( "option", "dateFormat", "dd.mm.yy");		
			// $( "#datetime_till" ).datetimepicker({inline:true});
			$(".btn_create").on("click",function(e){
			e.preventDefault();
	if (! $('#form_event')[0].checkValidity()) alert("Fill all required fields!");			
      		else $('#form_event').submit();
   		 });


		} );
	</script>

