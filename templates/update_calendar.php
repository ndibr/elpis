<?php ?>
<div class="cal_edit">
    <form id="update_calendar" action="index.php" method="post">
		<label>Title</label><input type="text" id="title" name="title" value="<?php echo $calendar['title']?>"> <br>
		<a class="btn btn_update" href="#">Update</a>
		<br /><?php echo isset_or($error_message); ?>
		<input type="hidden" id="op" readonly="readonly" value="save_updated_calendar" name="op"/>
		<input type="hidden" id="calendar_id" readonly="readonly" value="<?php echo $calendar['id'] ?>" name="calendar_id"/>
	</form>
</div>

<script>
$(document).ready( function() {
    $(".btn_update").on("click",function(e){
        $('#update_calendar').submit();
    });
} );
</script>
