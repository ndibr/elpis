<?php 
include 'calendar_view.php';

echo '<div class="time_nav"><a class="btn left" href="index.php?op=calendar_day&dt='.date("d.m.Y", strtotime("-1 days",$datetime1)).'">Previous</a>';
echo $dt;
echo '<a class="btn right" href="index.php?op=calendar_day&dt='.date("d.m.Y", strtotime("+1 days",$datetime1)).'">Next</a></div>';

//cvar_dump($result); ?>
<div class="clear">
<a class="btn btn_before" href="#" id="button_before">Before 8:00</a>
<table class="day">
	<?php 
	for ($hour=0; $hour<=23; $hour++) { ?>
	<tr class="<?php echo ( ($hour<8) ? "before" : ( ($hour>20) ? "after" : "" ) ).' '.( ($hour % 2 == 0) ? "even" : "odd" ); ?>"> 
		<td>
			<?php echo $hour; ?>:00 
		</td>
			<?php foreach($result as $i => $col){ ?>
		<td>
			<?php if (isset($result[$i][$hour]))
			echo '<a class="open_event" href="#" get_event="'.$result[$i][$hour]['id'].'">'.$result[$i][$hour]['title'].'</a><br/>'; //events
			//echo $result[$i][$hour]['title'];?>
		</td>
			<?php } ?>
	</tr>
	<?php } ?>
</table>
<a class="btn btn_after" href="#" id="button_after">After 20:00</a>
</div>

<script type="text/javascript">
//show events before 8:00
$(document).ready(function(){
	$(".before").hide();
	$("#button_before").on("click",function(e){
		e.preventDefault();
		if($(".before").is(":hidden"))
			$(".before").fadeIn(400);
		else
			$(".before").fadeOut(400);
	});
//show events after 20:00
	$(".after").hide();
	$("#button_after").on("click",function(e){
		e.preventDefault();
		if($(".after").is(":hidden"))
			$(".after").fadeIn(400);
		else	
			$(".after").fadeOut(400);
	});

});
</script>

	<script>
		$(document).ready( function() {
		//open event in new window
			$(".open_event").on("click",function(e){
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: "index.php?op=get_event",
					cache: false,
					data: {'id':$(this).attr("get_event")},
					success: function(data){
						$("#cover_content").html(data);
						$("#cover").fadeIn(400);
					},
					error: function(rq, status, err){}
				});
			});
		//close 'new window'
			$("#cover_close").on("click",function(e){
				$('#cover').fadeOut(400);
			});
		} );
	</script>

<?php

