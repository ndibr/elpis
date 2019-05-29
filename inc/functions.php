<?php
	function getUserCalendars(){
		global $oDB;
		$statement = $oDB->prepare("SELECT id, title from calendar where owner_id=:owner_id AND status=1 order by is_default desc, title");
		$statement->bindValue(":owner_id", $_SESSION['id']);
		$statement->execute();
		$calendars = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$calendars[$row['id']] = $row['title'];
		}    
		return $calendars;
	}