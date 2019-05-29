<?php 
include 'calendar_view.php';

echo '<div class="time_nav"><a class="btn left" href="index.php?op=calendar_week&dt='.date("d.m.Y", strtotime("-7 days",$datetime1)).'">Previous</a>';
echo "Week ".strftime("%W", strtotime($dt));
echo '<a class="btn right" href="index.php?op=calendar_week&dt='.date("d.m.Y", strtotime("+7 days",$datetime1)).'">Next</a></div>';

$dtw = strftime("%W", strtotime($dt));
$today = ($dtw == strftime("%W", strtotime(date('d.m.Y'))) ? date('N') : -1);
$current_day = $first_day;
echo '<table class="week">';

	echo '<tr>';
	 	for ($column=0; $column<7; $column++) { 
			 echo '<th><a href="index.php?op=calendar_day&dt='.$current_day.'">'.$current_day.'</a></th>';
			 $current_day = date("d.m.Y", strtotime("+1 days",strtotime($current_day)));
		 }
	echo '</tr>';
	echo '<tr>';
		$current_day = $first_day;
		for ($column=0; $column<7; $column++) { 
			echo '<td class="'.($today == $column+1 ? 'week_today' : '').'">';
				$i=date("j", strtotime($current_day));
				if (count(isset_or($result[$i])) > 0) foreach($result[$i] as $index => $row2){
					echo $row2['time_from'].
						' <a class="open_event" href="#" get_event="'.$row2['id'].'">'.$row2['title'].'</a>'.
						'<a class="delete_event" href="#" event_id="'.$row2['id'].'"></a>'.
						'<a class="update_event" href="#" event_id="'.$row2['id'].'"></a>'.
   						'<br/>';
				 }
				$current_day = date("d.m.Y", strtotime("+1 days",strtotime($current_day)));
			echo '</td>';
		}
	echo '</tr>';
 echo '</table>';

//cvar_dump($result);
?>

<script>
$(document).ready( function() {
	var event_id = 0;
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

	$(".update_event").on("click",function(e){
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: "index.php?op=update_event",
					cache: false,
					data: {'id':$(this).attr("event_id")},
					success: function(data){
						$("#cover_content").html(data);
						$("#cover").fadeIn(400);
					},
					error: function(rq, status, err){}
				});
			});

			$(".delete_event").on("click",function(e){
				e.preventDefault();
				event_id = $(this).attr("event_id");
				$("#cover_content").html($("#confirm_message").html());
				$(".confirm_yes").on("click",function(e){
					e.preventDefault();
					document.location.href="http://elpis.blackwing.lv/index.php?op=delete_event&event_id="+event_id;
				});

				$(".confirm_no").on("click",function(e){
					e.preventDefault();
					$('#cover').fadeOut(400);
				});
				
				$("#cover").fadeIn(400);
			});

	$("#cover_close").on("click",function(e){
		$('#cover').fadeOut(400);
	});
} );
</script>
