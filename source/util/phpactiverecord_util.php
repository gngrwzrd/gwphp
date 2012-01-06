<?php

/**
 * Creates JSON for a flat array of records from php-activerecord.
 */
function jsonize_flat_records_array($buffer, $records_array) {
	$c = 0;
	$count = count($records_array);
	foreach($records_array as $record) {
		$c++;
		$buffer .= $record->to_json();
		if($c < $count) $buffer .= ",";
	}
}

?>
