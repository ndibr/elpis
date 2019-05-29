<?php ?>
<div class="register_form">
	<form class="register" action="index.php" method="post" enctype="multipart/form-data" id="form_register">
		<label>Name *</label><input type="text" id="name" name="name" value="<?php echo isset_or($_REQUEST['name']);?>" required> <br>
		<label>Login *</label><input type="text" id="login" name="login" value="<?php echo isset_or($_REQUEST['login']);?>" required> <br>
		<label>E-mail *</label><input type="email" id="email" name="email" value="<?php echo isset_or($_REQUEST['email']);?>" required> <br>
		<label>Gender *</label>
			<select id="gender_id" name="gender_id" value="<?php echo isset_or($_REQUEST['login']);?>" required>
				<?php foreach($genders as $index => $row) { ?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['name']; ?></option>
				<?php } ?>
			</select><br>
		<label>Password *</label><input type="password" id="password1" name="password1" required> <br>
		<label>Repeat Password *</label><input type="password" id="password2" name="password2" required> <br>
		<label>Birthday *</label><input type="text" id="birthday" name="birthday" value="<?php echo isset_or($_REQUEST['birthday']);?>" required> <br>
		<label>Phone</label><input type="text" id="phone" name="phone" value="<?php echo isset_or($_REQUEST['phone']);?>"> <br>
		<label>Address</label><input type="text" id="phone" name="address" value="<?php echo isset_or($_REQUEST['address']);?>"> <br>
		<!-- <label>Выберите фото:<input type="file" id="picture" name="picture"></label><br> -->
		<a class="btn btn_register" href="#">Register</a>
		<br /><?php echo isset_or($error_message); ?>
		<input type="hidden" id="op" readonly="readonly" value="register" name="op"/>
	</form>
</div>
	<script>
		$(document).ready( function() {
			$( "#birthday" ).datepicker({
				changeMonth: true,
				changeYear: true,
				minDate: "-99Y", maxDate: +1,
				yearRange: "1919:2019"
			});
     		$( "#birthday" ).datepicker( "option", "dateFormat", "dd.mm.yy");	

			$(".btn_register").on("click",function(e){
				e.preventDefault();
				if (! $('#form_register')[0].checkValidity()) alert("Fill all required fields!");			
      			else $('#form_register').submit();
       			//if ($("#form_register").valid()) 
				// $('#form_register').submit();
				 //  else alert("Please fill all required fields");
   			 });

		} );
	</script>

