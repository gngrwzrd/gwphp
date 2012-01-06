<?php

//http://php.net/manual/en/function.uniqid.php
function uuid() {
	$pr_bits = null;
	$fp = @fopen('/dev/urandom','rb');
	if ($fp !== false) {
		$pr_bits .= @fread($fp, 16);
		@fclose($fp);
	} else {
		$pr_bits = "";
		for($cnt=0; $cnt < 16; $cnt++){
			$pr_bits .= chr(mt_rand(0, 255));
		}
	}
	$time_low = bin2hex(substr($pr_bits,0, 4));
	$time_mid = bin2hex(substr($pr_bits,4, 2));
	$time_hi_and_version = bin2hex(substr($pr_bits,6, 2));
	$clock_seq_hi_and_reserved = bin2hex(substr($pr_bits,8, 2));
	$node = bin2hex(substr($pr_bits,10, 6));
	$time_hi_and_version = hexdec($time_hi_and_version);
	$time_hi_and_version = $time_hi_and_version >> 4;
	$time_hi_and_version = $time_hi_and_version | 0x4000;
	$clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
	$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
	$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;
	return sprintf('%08s-%04s-%04x-%04x-%012s',$time_low, $time_mid,
		$time_hi_and_version, $clock_seq_hi_and_reserved, $node);
}

?>
