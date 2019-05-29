<?php ?>
<div class="form_cal">
    <form id="form_create" action="index.php" method="post">
		<label>Title</label><input type="text" id="title" name="title"> <br>
		<a class="btn btn_create btn_cal" href="#">Create</a>
		<a class="btn btn_back" href="http://elpis.blackwing.lv/index.php?op=user_calendars">Back</a>
		<br /><?php echo isset_or($error_message); ?>
		<input type="hidden" id="op" readonly="readonly" value="create_calendar" name="op"/>
	</form>
</div>
<script>
$(document).ready( function() {
    $(".btn_create").on("click",function(e){
        $('#form_create').submit();
    });
} );
</script>
