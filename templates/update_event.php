<?php  ?>
<div class="event_edit">
    <form id="form_update" action="index.php" method="post" autocomplete="off"><input type="hidden" autocomplete="false"/>
		<label>Calendar</label>
		<select id="calendar_id" name="calendar_id">
			<?php foreach($calendars as $index => $row) {?>
				<option value="<?php echo $row['id'];?>" <?php echo ($row['id'] == $event['calendar_id'])?'selected="selected"':''?> ><?php echo $row['title']; ?></option>
			<?php } ?>
		</select> <br/>
		<label>Title</label><input type="text" id="title" name="title" value="<?php echo $event['title']?>"> <br/>
		<label>Date From</label><input type="text" id="datetime_from" name="datetime_from" value="<?php echo $event['dtime_from']?>">
		<label>Time From</label><input type="text" id="time_from" name="time_from" placeholder="hh:mm" value="<?php echo $event['time_from']?>">  <br/>
		<label>Date Till</label><input type="text" id="datetime_till" name="datetime_till" value="<?php echo $event['dtime_till']?>">
		<label>Time Till</label><input type="text" id="time_till" name="time_till" placeholder="hh:mm" value="<?php echo $event['time_till']?>"> <br/>
		<label>Place</label><input type="text" id="place" name="place" value="<?php echo $event['place']?>"> <br/>
		<label>Priority</label>
		<select id="priority_id" name="priority_id"  value="<?php echo $event['priority_id']?>">
			<?php foreach($priority as $index => $row) { ?>
			<option value="<?php echo $row['id'];?>"<?php echo ($row['id'] == $event['priority_id'])?'selected="selected"':''?>><?php echo $row['name']; ?></option>
			<?php } ?>
		</select><br/>
		<label>Description</label><textarea id="description" name="description" rows="1" cols="50"  value="<?php echo $event['description']?>"></textarea> <br/>
		<a class="btn btn_update" href="#">Update</a>
		<br /><?php echo isset_or($error_message); ?>
		<input type="hidden" id="op" readonly="readonly" value="save_updated_event" name="op"/>
		<input type="hidden" id="event_id" readonly="readonly" value="<?php echo $event['id'] ?>" name="event_id"/>
	</form>
<div>
	<!-- <script>
		$(document).ready( function() {
			$( "#datetime_from" ).datepicker();
      $( "#datetime_from" ).datepicker( "option", "dateFormat", "dd.mm.yy");		
			$( "#datetime_till" ).datepicker();
      $( "#datetime_till" ).datepicker( "option", "dateFormat", "dd.mm.yy");		
			// $( "#datetime_till" ).datetimepicker({inline:true});
		} );
	</script> -->

	<script>
$(document).ready( function() {
    $(".btn_update").on("click",function(e){
        $('#form_update').submit();
    });
} );
</script>
