<?php
session_start();
include 'inc/config.php';
include 'inc/utils.php';
include 'inc/functions.php';

//var_dump($_REQUEST);
//check, if user is logged in
if ( ( isset_or($_SESSION['id'],0) > 0 ) || ( isset_or($_REQUEST['op']) == 'login') 
	|| ( isset_or($_REQUEST['op']) == 'form_register') || ( isset_or($_REQUEST['op']) == 'register')) {

	if (!isset($_REQUEST['op'])) $_REQUEST['op'] = 'calendar_month';
} else  {
	$include_template = 'templates/login.php'; 
	include 'templates/structure.php'; 
	die();
}

//var_dump($_SESSION); 

// Весенний дождик
// Роняя, дорога петляет..
// Потеряв в коде

// и вечером никто не ждет,
// и делать можно все, что хочется
// и как это называется,
// свобода или одиночество?

$oDB = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
$oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ToDo: Add this settings to php.ini
date_default_timezone_set('UTC');

switch (isset_or($_REQUEST['op'])) {

	case 'login':
		$login = isset_or($_REQUEST['login']);
		$password = isset_or($_REQUEST['password']);
		$password = md5(PWD_SALT.$password);

		$statement = $oDB->prepare("SELECT *
			FROM user
			WHERE login = :login");
		$statement->bindValue(":login", $login);
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_ASSOC); 
		if (count($result) >0) {
			if ($password == $result[0]["password"]) {
				$_SESSION['id'] = $result[0]["id"];
				$_SESSION['calendar_id'] = "all";
				//tut budet babah echo "";
				header('Location: http://elpis.blackwing.lv/index.php');
			} else {
				$error_message = "Login or password not found!";
				$include_template = 'templates/login.php'; 
				include 'templates/structure.php';
				die();
			}
		} else {
			$error_message = "Login or password not found!";
			$include_template = 'templates/login.php'; 
			include 'templates/structure.php';
			die();
		}

		break;
	
	case 'logout':
		$_SESSION['id'] = 0;
		$include_template = 'templates/login.php';
		include 'templates/structure.php';
		die();
		break;
		
	case 'register':
		if ( (isset_or($_REQUEST['password1']) == isset_or($_REQUEST['password2'])) && (isset_or($_REQUEST['password1']) != '')) { //check password and password confirm
			
			$statement = $oDB->prepare("SELECT login
				FROM user
				WHERE login=:login"); 
			$statement->bindValue(":login", $_REQUEST['login']); 
			$statement->execute(); 
			$result = $statement->fetchAll(PDO::FETCH_ASSOC); 
			if (count($result) == 0) { 
				$statement = $oDB->prepare("INSERT INTO user (name, login, email, gender, password, birthday, address, picture) 
					VALUES (:name, :login, :email, :gender, :password, :birthday, :address, :picture);");
				$birthday = date("Y-m-d", strtotime(isset_or($_REQUEST['birthday'],date('d.m.Y'))));
				$statement->bindValue(":name", $_REQUEST['name']);
				$statement->bindValue(":login", $_REQUEST['login']);
				$statement->bindValue(":email", $_REQUEST['email']);
				$statement->bindValue(":gender", $_REQUEST['gender_id']);
				$statement->bindValue(":password", md5(PWD_SALT.isset_or($_REQUEST['password1'])));
				$statement->bindValue(":birthday", $birthday);
				$statement->bindValue(":address", $_REQUEST['address']);
				$statement->bindValue(":picture", ''); // $_REQUEST['picture']  
				$statement->execute();
				$user_id = $oDB->lastInsertId();
				$_SESSION['id'] = $user_id;
				$_SESSION['calendar_id'] = "all";

				// create default calendar
				$statement = $oDB->prepare("INSERT INTO `calendar` (`owner_id`, `title`, `status`)
					VALUES (:owner_id, 'My Calendar', 1);");
				$statement->bindValue(":owner_id", $user_id);
				$statement->execute(); 
				header('Location: http://elpis.blackwing.lv/index.php');
				die();

				break;
			} else {
				$error_message = "Login already used!";
			}
		} else {
			$error_message = "Password and confirm password fields must be same!";
		}
		
	case 'form_register':	
		$statement = $oDB->prepare("SELECT * FROM gender");
		$statement->execute();
		$genders = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$include_template = 'templates/form_register.php'; 
		include 'templates/structure.php';
		die();
		break;

		
	case 'form_calendar':
		$include_template = 'templates/form_calendar.php'; 
		include 'templates/structure.php';
		die();
		break;
		
	case 'create_calendar':
        $statement = $oDB->prepare("INSERT INTO `calendar` (`owner_id`, `title`, `status`)
		VALUES (:owner_id, :title, 1);");
        $statement->bindValue(":owner_id", $_SESSION['id']);
        $statement->bindValue(":title", $_REQUEST['title']);
		$statement->execute(); 
		
		// $statement = $oDB->prepare("SELECT c.*, sc.status as status_name FROM calendar c
		// 	LEFT JOIN calendar_status sc
		// 	ON sc.id = c.status
		// 	where owner_id=:id;");
        // $statement->bindValue(":id", $_SESSION['id']); 
        // $statement->execute();    
        // $result = $statement->fetchAll(PDO::FETCH_ASSOC);
		// $include_template = 'templates/user_calendars.php';
		//break;
 
	case 'user_calendars':
		$statement = $oDB->prepare("SELECT c.*, sc.status as status_name FROM calendar c
			LEFT JOIN calendar_status sc
				ON sc.id = c.status
			WHERE owner_id=:id AND c.status=1;");
		$statement->bindValue(":id", $_SESSION['id']); 
		$statement->execute();    
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		$include_template = 'templates/user_calendars.php';

		//include 'templates/user_calendars.php';
		break;

	case 'update_calendar':
		$statement = $oDB->prepare("SELECT *
			FROM calendar
			WHERE id=:id AND owner_id=:owner_id");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->bindValue(":id", $_REQUEST['id']);
		$statement->execute();
		$calendar = $statement->fetch(PDO::FETCH_ASSOC);

		include 'templates/update_calendar.php';
		die();
		break;


	case 'save_updated_calendar':
		$statement = $oDB->prepare("UPDATE calendar
			SET title=:title
			WHERE id=:id AND owner_id=:owner_id;");
		$statement->bindValue(":title", $_REQUEST['title']);
		$statement->bindValue(":id", $_REQUEST['calendar_id']);
		$statement->bindValue(":owner_id", $_SESSION['id']);   
		$statement->execute();	
		header('Location: http://elpis.blackwing.lv/index.php?op=user_calendars');
		die();
		break;

	case 'delete_calendar':
		$statement = $oDB->prepare("UPDATE calendar
			SET status=2
			WHERE id=:id AND owner_id=:owner_id;");
		$statement->bindValue(":id", $_REQUEST['calendar_id']);
		$statement->bindValue(":owner_id", $_SESSION['id']); 
		$statement->execute();	
		header('Location: http://elpis.blackwing.lv/index.php?op=user_calendars');
		die();
		break;


	case 'form_event':
		$statement = $oDB->prepare("SELECT id, title
			FROM calendar
			WHERE owner_id=:owner_id AND status=1
			ORDER BY is_default desc, title");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->execute();    
		$calendars = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = $oDB->prepare("SELECT id, name FROM priority");
		$statement->execute();    
		$priority = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$include_template = 'templates/form_event.php'; 
		include 'templates/structure.php';
		die();
		break;


	case 'create_event':
		$datetime_from = date("Y-m-d", strtotime(isset_or($_REQUEST['datetime_from'],date('d.m.Y'))));
		$datetime_till = ($_REQUEST['datetime_till']=="" ? $datetime_from : date("Y-m-d", strtotime(isset_or($_REQUEST['datetime_till'],date('d.m.Y')))));
		
		$time_from = isset_or($_REQUEST['time_from'],'');
		$time_till = isset_or($_REQUEST['time_till'],'');
		if ($time_till == "") {
			$time_till = $time_from;
		}

		// if ($time_from == $time_till) {
		// 	$time_till = 
		// }

		$datetime_from .= ' '.$time_from;
		$datetime_till .= ' '.$time_till;

		$statement = $oDB->prepare("INSERT INTO event (calendar_id, owner_id, title, datetime_from, datetime_till, place, priority_id, description)
			VALUES (:calendar_id, :owner_id, :title, :datetime_from, :datetime_till, :place, :priority_id, :description);");
		$statement->bindValue(":calendar_id", $_REQUEST['calendar_id']);
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->bindValue(":title", $_REQUEST['title']);
		$statement->bindValue(":datetime_from", $datetime_from);
		$statement->bindValue(":datetime_till", $datetime_till);
		$statement->bindValue(":place", $_REQUEST['place']);
		$statement->bindValue(":priority_id", $_REQUEST['priority_id']);
		$statement->bindValue(":description", $_REQUEST['description']);   
		$statement->execute();	
		header('Location: http://elpis.blackwing.lv/index.php');
		die();
		break;
		
	case 'calendar_month':
		$calendars = getUserCalendars();
		$dt = isset_or($_REQUEST['dt'],date('d.m.Y'));
		$_SESSION['calendar_view'] = $_REQUEST['op'];
		$_SESSION['date'] = $dt;
		if(isset($_REQUEST['calendar_id'])){
			$_SESSION['calendar_id'] = $_REQUEST['calendar_id'];
		}
		$datetime1 = strtotime($dt);
		var_dump($datetime1);
		$d1 = date('Y-m-01',$datetime1);
		$d2 = date('Y-m-t',$datetime1);
		$w1 = strftime("%W", strtotime($d1));
		$w2 = strftime("%W", strtotime($d2));

		$wd = strftime("%u",strtotime($d1));
		$ld = strftime("%d",strtotime($d2))+1;
	
		$statement = $oDB->prepare("SELECT *, day(datetime_from) as day_from
			FROM event
			WHERE owner_id=:owner_id ".
				(($_SESSION['calendar_id'] == 'all')?"":"AND calendar_id = :calendar_id").
				" AND date(datetime_from) BETWEEN :date_from AND :date_till
				AND status is null;");
		$statement->bindValue(":owner_id", $_SESSION['id']); 
		if ($_SESSION['calendar_id'] != 'all') $statement->bindValue(":calendar_id", $_SESSION['calendar_id']);
		$statement->bindValue(":date_from", strftime("%Y-%m-%d",strtotime($d1)));
		$statement->bindValue(":date_till", strftime("%Y-%m-%d",strtotime($d2)));
		$statement->execute();    
		$result = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$result[$row['day_from']][] = $row;
		}
		$include_template = 'templates/calendar_month.php';
		break;
		
	case 'calendar_week':
		$calendars = getUserCalendars();
		$dt = isset_or($_REQUEST['dt'],date('d.m.Y'));
		$_SESSION['calendar_view'] = $_REQUEST['op'];
		$_SESSION['date'] = $dt;
		if(isset($_REQUEST['calendar_id'])){
			$_SESSION['calendar_id'] = $_REQUEST['calendar_id'];
		}
		$datetime1 = strtotime($dt);

		$wd = strftime("%u",strtotime($dt));
		$first_day = date("d.m.Y", strtotime("-".($wd-1)." days",$datetime1));
		$last_day = date("d.m.Y", strtotime("+".(7-$wd)." days",$datetime1));
	
		$statement = $oDB->prepare("SELECT *, day(datetime_from) as day_from, date_format(datetime_from, '%H:%i') as time_from
			FROM event
			WHERE owner_id=:owner_id ".
				(($_SESSION['calendar_id'] == 'all')?"":"AND calendar_id = :calendar_id").
				" AND date(datetime_from) BETWEEN :date_from AND :date_till
				AND status is null;");
		$statement->bindValue(":owner_id", $_SESSION['id']); 
		if ($_SESSION['calendar_id'] != 'all') $statement->bindValue(":calendar_id", $_SESSION['calendar_id']);
		$statement->bindValue(":date_from", strftime("%Y-%m-%d",strtotime($first_day)));
		$statement->bindValue(":date_till", strftime("%Y-%m-%d",strtotime($last_day)));
        	$statement->execute();    
		$result = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$result[$row['day_from']][] = $row;
		}
		$include_template = 'templates/calendar_week.php';
		break;	
		
// заходишь в соленое море по грудь,
// и чувствуешь, сколько царапин на теле.
// а если бы душу в него окунуть?
// мы сдохли б от боли, на самом-то деле.

	case 'calendar_day';
		$calendars = getUserCalendars();
		$dt = isset_or($_REQUEST['dt'],date('d.m.Y'));
		$_SESSION['calendar_view'] = $_REQUEST['op'];
		$_SESSION['date'] = $dt;
		if(isset($_REQUEST['calendar_id'])){
			$_SESSION['calendar_id'] = $_REQUEST['calendar_id'];
		}
		$datetime1 = strtotime($dt);

		$statement = $oDB->prepare("SELECT *,
                		if(date(datetime_from) = :date_from,hour(datetime_from),0) as hour_from,
                		if(date(datetime_till) <> :date_from,23,hour(datetime_till)) as hour_till,
						date(datetime_from) as date_from,
						date(datetime_till) as date_till
                	FROM event
                	WHERE ((date(datetime_from) = :date_from or date(datetime_till) = :date_from)
						OR (date(datetime_from) <= :date_from and date(datetime_till) >= :date_from)) ".
						(($_SESSION['calendar_id'] == 'all')?"":"AND calendar_id = :calendar_id").
                		" AND owner_id = :owner_id
				AND status is null;");
			$statement->bindValue(":owner_id", $_SESSION['id']); 
		if ($_SESSION['calendar_id'] != 'all') $statement->bindValue(":calendar_id", $_SESSION['calendar_id']);
		$statement->bindValue(":date_from", strftime("%Y-%m-%d",strtotime($dt)));
		$statement->execute(); 
		$result = array(0 => array());
		$k = 0;
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			if ( ($row['date_from'] == $row['date_till']) && ($row['hour_from'] > $row['hour_till']) ) {
				$a = $row['hour_from'];
				$row['hour_from'] = $row['hour_till'];
				$row['hour_till'] = $a;
			}
			foreach ($result as $i => $col) {
				$f = true;
				for ($j = $row['hour_from']; $j <= $row['hour_till']; $j++) {
					if (isset($result[$i][$j])) $f = false;
				}
				if ($f) break;
			}

			if ($f)
				for ($q = $row['hour_from']; $q <= $row['hour_till']; $q++) {
					$result[$i][$q] = $row; 
				}
			else {
				$k++;
				for ($q = $row['hour_from']; $q <= $row['hour_till']; $q++) {
					$result[$k][$q] = $row;
				}
			}
		}
		$include_template = 'templates/calendar_day.php';
		break;	

		// а ведь глаза прохожих не горят,
		// иду по улицам намеренно вслепую,
		// я не ищу похожих на тебя,
		// ведь я уверен - их не существует
		
	case 'get_event':
		$statement = $oDB->prepare("SELECT e.*,c.title AS calendar_name, p.name AS priority_name
			FROM event e
			LEFT JOIN calendar c
				ON e.calendar_id = c.id
			LEFT JOIN priority p
				ON e.priority_id = p.id
			WHERE e.id=:id AND e.owner_id=:owner_id;");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->bindValue(":id", $_REQUEST['id']);
		$statement->execute();
		$event = $statement->fetch(PDO::FETCH_ASSOC);
		include 'templates/event.php';
		die();
		break;

	case 'update_event': 
		$statement = $oDB->prepare("SELECT e.*,c.title AS calendar_name, p.name AS priority_name,
				date_format(e.datetime_from, '%H:%i') as time_from,
				date_format(e.datetime_till, '%H:%i') as time_till,
				date_format(e.datetime_from, '%d.%m.%Y') as dtime_from,
				date_format(e.datetime_till, '%d.%m.%Y') as dtime_till
		 	FROM event e
			LEFT JOIN calendar c
				ON e.calendar_id = c.id
			LEFT JOIN priority p
				ON e.priority_id = p.id
			WHERE e.id=:id AND e.owner_id=:owner_id;");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->bindValue(":id", $_REQUEST['id']);
		$statement->execute();
		$event = $statement->fetch(PDO::FETCH_ASSOC);

		$statement = $oDB->prepare("SELECT id, title
			FROM calendar
			WHERE owner_id=:owner_id AND status=1
			ORDER BY is_default desc, title");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->execute();    
		$calendars = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = $oDB->prepare("SELECT id, name FROM priority");
		$statement->execute();    
		$priority = $statement->fetchAll(PDO::FETCH_ASSOC);

		include 'templates/update_event.php';
		die();
		break;

	case 'save_updated_event':
		$datetime_from = date("Y-m-d", strtotime(isset_or($_REQUEST['datetime_from'],date('d.m.Y'))));
		$datetime_till = ($_REQUEST['datetime_till']=="" ? $datetime_from : date("Y-m-d", strtotime(isset_or($_REQUEST['datetime_till'],date('d.m.Y')))));
		
		$datetime_from .= ' '.isset_or($_REQUEST['time_from'],'');
		$datetime_till .= ' '.isset_or($_REQUEST['time_till'],'');

		$statement = $oDB->prepare("UPDATE event
			SET calendar_id=:calendar_id,
				title=:title,
				datetime_from=:datetime_from,
				datetime_till=:datetime_till,
				place=:place,
				priority_id=:priority_id,
				description=:description
			WHERE id=:id AND owner_id=:owner_id;");
		$statement->bindValue(":calendar_id", $_REQUEST['calendar_id']);
		$statement->bindValue(":id", $_REQUEST['event_id']);
		$statement->bindValue(":title", $_REQUEST['title']);
		$statement->bindValue(":datetime_from", $datetime_from);
		$statement->bindValue(":datetime_till", $datetime_till);
		$statement->bindValue(":place", $_REQUEST['place']);
		$statement->bindValue(":priority_id", $_REQUEST['priority_id']);
		$statement->bindValue(":description", $_REQUEST['description']);   
		$statement->bindValue(":owner_id", $_SESSION['id']);   
		$statement->execute();	
		header('Location: http://elpis.blackwing.lv/index.php?op='.$_SESSION['calendar_view'].'&dt='.$_SESSION['date']);
		die();
		break;

	case 'delete_event':
		$statement = $oDB->prepare("UPDATE event
			SET status='D'
			WHERE id=:id AND owner_id=:owner_id;");
		$statement->bindValue(":id", $_REQUEST['event_id']);
		$statement->bindValue(":owner_id", $_SESSION['id']); 
		$statement->execute();	
		header('Location: http://elpis.blackwing.lv/index.php?op='.$_SESSION['calendar_view'].'&dt='.$_SESSION['date']);
		die();
		break;

    default:
        echo "ti durak?";
		//include 'templates/select.php';
		break;
}

include 'templates/structure.php';
