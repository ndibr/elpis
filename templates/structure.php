<?php ?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>ELPIS</title>
		<link rel="stylesheet" type="text/css" href="styles/style.css">
		<link href="https://fonts.googleapis.com/css?family=Quicksand:500" rel="stylesheet">
		<!-- <link rel="icon" href="lalala.jpg"> -->
		<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<body <?php echo ((isset_or($_SESSION['id'], 0) == 0) || ($_REQUEST['op'] == 'form_event') || ($_REQUEST['op'] == 'user_calendars') ||
	($_REQUEST['op'] == 'form_calendar'))?'class="login_background"':'' ?>>
		<div id="cover"><div id="cover_content"></div><div id="cover_close"></div></div>		
			<nav <?php echo ((isset_or($_SESSION['id'], 0) == 0) || ($_REQUEST['op'] == 'form_event') || ($_REQUEST['op'] == 'user_calendars') ||
			($_REQUEST['op'] == 'form_calendar'))?'class="login_nav"':'' ?> >
				<div class="logo"><a href="index.php">Elpis</a></div>
				<ul>
				<?php if (isset_or($_SESSION['id'],0) == 0) { ?>
					<li><a href="index.php">Login</a></li>
					<li><a href="index.php?op=form_register">Register</a></li>
				<?php } else { ?>
					<li><a href="index.php?op=form_event">New Event</a></li>
					<li><a href="index.php?op=user_calendars">My Calendars</a></li>
					<li><a href="index.php?op=logout">Logout</a></li>
				<?php } ?>
				</ul>
			</nav>
			<section class="sec1">
			<?php
			//includit content suda
			if (isset($include_template)) include $include_template;
			//if (isset($include_template)) echo $include_template;
			?>
			</section>
	</body>
</html>