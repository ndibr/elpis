<?php
//var_dump($event);
if (isset($event['id'])) {

?>
<table>
    <tr>
        <td width="150px">
        Title
        </td>
        <td width="200px">
        <?php echo $event['title']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Calendar
        </td>
        <td>
        <?php echo $event['calendar_name']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Date From
        </td>
        <td>
        <?php echo $event['datetime_from']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Date Till
        </td>
        <td>
        <?php echo $event['datetime_till']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Place
        </td>
        <td>
        <?php echo $event['place']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Priority
        </td>
        <td>
        <?php echo $event['priority_name']; ?>
        </td>
    </tr>
    <tr>
        <td>
        Description
        </td>
        <td>
        <?php echo $event['description']; ?>
        </td>
    </tr>
</table>

<a class="btn" id="update_event" href="#" get_event="<?php echo $event['id'] ?>">Update</a>
<a class="btn" id="delete_event" href="#" event_id="<?php echo $event['id'] ?>">Delete</a><br/>


<?php } else {
    echo 'Event not found';
}
?> 

<script>
$(document).ready( function() {
    $("#update_event").on("click",function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "index.php?op=update_event",
            cache: false,
            data: {'id':$(this).attr("get_event")},
            success: function(data){
                $("#cover_content").html(data);
            },
            error: function(rq, status, err){}
        });
    });

    $("#delete_event").on("click",function(e){
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
