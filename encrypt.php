<?php

function rc4($key, $str) {
	$s = array();
	for ($i = 0; $i < 256; $i++) {
		$s[$i] = $i;
	}
	$j = 0;
	for ($i = 0; $i < 256; $i++)	{

		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$z = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $z;
	}
	$i = 0;
	$j = 0;
	$ret_val = '';
	for ($y = 0; $y < strlen($str); $y++) {
		$i = ($i + 1) % 256;
		$j = ($j + $s[$i]) % 256;
		$z = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $z;
		$ret_val .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	return $ret_val;
}

function sub_multi($key, $str){
	$words = explode(" ", $str);
	foreach ($words as $value){
		$next = simple_sub($key, $value);
		$ret_val .= " ";
		$ret_val .= $next;
	}
	echo trim($ret_val);
}

function simple_sub($key, $str){

	$low = strtolower($str);
	$low_key = strtolower($key);
	if (count(explode(" ",$str)) > 1){
		return sub_multi($key, $low);
	}
	$text = str_split($low);
	$key_arr = str_split($key);
	$lookup = array();
	$ret_val = "";
	$ascii = ord('a');

	foreach ($key_arr as $value){
		 // a => x
		$lookup[chr($ascii++)] = $value;
	}

	foreach ($text as $value) {
		$letter = $lookup[$value];
		$ret_val .= $letter;
	}
	return $ret_val;
}

//echo rc4("frog", "mohamed\n");
echo simple_sub("phqgiumeaylnofdxjkrcvstzwb", "defend the east wall of the castle");
?>