<div class="cal_list">
<a class="new_calendar btn" href="index.php?op=form_calendar">Create New Calendar</a><br/>&nbsp;

<div id="confirm_message" style="display:none;">
	<div class="delete_message">
	Are you sure?
	<br/>
	<a class="btn confirm_yes" href="#">Yes</a>
	<a class="btn confirm_no" href="#">No</a>
	</div>
</div>
<?php
if (count($result) >0) {     
    //var_dump($result); 
?>
        <table class="user_cal">    
            <?php foreach($result as $index => $row) { ?>
                <tr>
                    <!-- <td>
                        <?php echo $row['id'];?>
                    </td> -->
                    <td width="150px">
                        <?php echo $row['title']; ?>
                    </td>
					 <!-- <td>
                        <?php echo $row['status_name']; ?>
                    </td> -->
                    <td>
                    <a class="delete_calendar" href="#" calendar_id="<?php echo $row['id'] ?>"></a>
                    <a class="update_calendar" href="#" calendar_id="<?php echo $row['id'] ?>"></a>
                    <!-- <a class="share_calendar" href="#" calendar_id="<?php echo $row['id'] ?>">[s]</a> -->
                    </td>
                </tr>
            <?php } ?>    
        </table>
  </div>  
    <?php
        } else {
            echo "ti durak?";
        }
?>

<script>
$(document).ready( function() {
	var calendar_id = 0;

	$(".update_calendar").on("click",function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "index.php?op=update_calendar",
			cache: false,
			data: {'id':$(this).attr("calendar_id")},
			success: function(data){
				$("#cover_content").html(data);
				$("#cover").fadeIn(400);
			},
			error: function(rq, status, err){}
		});
			});

	$(".delete_calendar").on("click",function(e){
		e.preventDefault();
		calendar_id = $(this).attr("calendar_id");
		$("#cover_content").html($("#confirm_message").html());
		$(".confirm_yes").on("click",function(e){
			e.preventDefault();
			document.location.href="http://elpis.blackwing.lv/index.php?op=delete_calendar&calendar_id="+calendar_id;
		});

		$(".confirm_no").on("click",function(e){
			e.preventDefault();
			$('#cover').fadeOut(400);
		});
		
		$("#cover").fadeIn(400);
	});
            
    // $(".share_calendar").on("click",function(e){
	// 	e.preventDefault();
    //     alert("todo");
    // });


	$("#cover_close").on("click",function(e){
		$('#cover').fadeOut(400);
	});
} );
</script>
