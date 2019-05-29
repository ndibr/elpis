<?php 
include 'calendar_view.php';

echo '<div class="time_nav"><a class="btn left" href="index.php?op=calendar_month&dt='.date("d.m.Y", strtotime("-1 months",$datetime1)).'">Previous</a>';
echo strftime("%B %Y", strtotime($d1));
echo '<a class="btn right" href="index.php?op=calendar_month&dt='.date("d.m.Y", strtotime("+1 months",$datetime1)).'">Next</a></div>';

$dte = explode('.', $dt);
$today = ($dte[1] == date('m') ? date('j') : -1);
$i = 0;
echo '<table class="month">';
 for ($row=$w1; $row<=$w2; $row++) { 
	 echo '<tr id="mtr">';
	 	for ($column=0; $column<7; $column++) { 
	 		if ( (($wd == $column+1) || ($i > 0)) && ($i<$ld) ) { //
	 		    $i++;
	 		} 
			 echo '<td class="'.($today == $i ? 'month_today' : '').'"><div class="container"><div class="date_bground">'.( ($i>0 && $i<$ld) ? $i : '').'</div><div class="month_day">';
			 if (count(isset_or($result[$i])) > 0) foreach($result[$i] as $index => $row2){ //
				 echo '<a class="open_event" href="#" get_event="'.$row2['id'].'">'.$row2['title'].'</a>'.
				//  '<a class="delete_event" href="#" event_id="'.$row2['id'].'"></a>'.
				//  '<a class="update_event" href="#" event_id="'.$row2['id'].'"></a>'.
				 '<br/>';
			 }
			// .( (count(isset_or($result[$i])) > 0) ? '*' : '')
			 echo '</div></div></td>';
		 }
	echo '</tr>';
 }
 echo '</table>';

 // cvar_dump($result);
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
