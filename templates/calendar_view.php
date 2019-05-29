<?php
//var_dump($_SESSION);
?>

<select id="select_calendar" name="select_calendar">
    <option value="all">All Calendars</option>
	<?php foreach($calendars as $id => $title) { ?>
	<option value="<?php echo $id;?>" <?php echo ($id == $_SESSION['calendar_id'])?'selected="selected"':''?>><?php echo $title; ?></option>
	<?php } ?> 
</select> 

<?php
echo '<a class="btn view right" href="index.php?op=calendar_month&dt='.$dt.'">Month</a>';
echo '<a class="btn view right" href="index.php?op=calendar_week&dt='.$dt.'">Week</a>';
echo '<a class="btn view right" href="index.php?op=calendar_day&dt='.$dt.'">Day</a>'.'<br/>'; 
?>
<div id="confirm_message" style="display:none;">
	<div class="delete_message">
	Are you sure?
	<br/>
	<a class="confirm_yes btn" href="#">Yes</a>
	<a class="confirm_no btn" href="#">No</a>
	</div>
</div>
<?php
//var_dump($calendars);
?>

    <script>
		$(document).ready( function() {
			$("#select_calendar").on("change",function(e){
				e.preventDefault();
       			//if ($("#form_register").valid()) 
                document.location.href="http://elpis.blackwing.lv/index.php?calendar_id="+$("#select_calendar option:selected").val();
				 //  else alert("Please fill all required fields");
   			 });

		} );
	</script>
