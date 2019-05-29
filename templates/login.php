<?php ?>
<div class="login">
    <form id="form_login" action="index.php" method="post">
    <table>
        <tr>
            <td><label for="loginField"></label></td>
            <td><input type="text"id="loginField"name="login" placeholder="login" /></td>
        </tr>
        <tr>
            <td><label for="passwordfield"></label></td>
            <td><input type="password"id="passwordfield"name="password" placeholder="password"/></td>
        </tr>
    </table>
    <center><a class="btn btn_login" href="#">Login</a></center>
	<br /><?php echo isset_or($error_message); ?>
	<input type="hidden" id="op" readonly="readonly" value="login" name="op"/>
</form>
</div>

<script>
$(document).ready( function() {
    $(".btn_login").on("click",function(e){
        $('#form_login').submit();
    });
} );
</script>
