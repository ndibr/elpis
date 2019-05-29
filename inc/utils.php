<?php

function isset_or(&$variable, $default_value = null) {
	if (!isset($variable)) $variable = $default_value;
	return $variable;
}

function cvar_dump($array, $sub=false) {
	if (is_array($array)) {
		echo '<ul class="dump_arr'.($sub ? '_sub' : '').'">';
		foreach ($array as $k => $v) {
			echo '<li><div class="dump_key">'.$k.'</div><div class="dump_val">';
			if (is_array($v)) cvar_dump($v,true);
				else echo (is_bool($v) ? ($v?'true':'false') : $v);
			echo '</div></li>';
		}
		echo '</ul>';
	} else {
		echo "Not an array!<br/>";
		var_dump($array);
	}
}