<?php 
function highlight_code($text) {
  $code = highlight_string('<?php ' . $text . '?>', true);
  if (substr($code, 86, 2) !== '<s') {
    return '<span style="color: #0000BB">' . substr($code, 79, -57);
  }
  return substr($code, 86, -57);
}

function anonymize_ip($ip) {
  $last_dot = strrpos($ip, '.') + 1;
  return substr($ip, 0, $last_dot)
    . str_repeat('x', strlen($ip) - $last_dot);
}

function file_ext($file) {
  $last_dot = strrpos($file, '.');
  if ($last_dot !== false) {
    return strtolower(substr($file, $last_dot + 1));
  }
  return 'file';
}


/* *************
 * Last modified: xx ago script
 * Show when a file was last modified
 * http://jalu.ch/coding/last_modified.php
 * ************* */

// ----------------
// Usage
// ----------------
// Output when this page was last modified
echo get_modified_line(); 
 // Output when some file was last modified
//echo get_modified_line("list.txt");
// You can use folders, too, just make sure to add "/." to the end
//echo get_modified_line("folder/.");


// ----------------
// Functions
// ----------------
function get_modified_line($file=null) {
    if (!$file) {
        $date_modified = filemtime($_SERVER['SCRIPT_FILENAME']);
    } else if (!file_exists($file)) {
        error_log("get_modified_line(): File $file does not exist");
        return 'file not found!';
    } else {
        $date_modified = filemtime($file);
    }
    $time_difference = time() - $date_modified;
    return "Last modified: " . time_ago($time_difference) . " ago";
}

function time_ago($diff) {
    /* Thresholds t define the number of seconds the time difference should be
     * equal to or exceed for the unit to make sense. Since there is rounding
     * of output, we already use minutes as of 59.5 seconds (avoid outputting
     * "60 seconds").
     * Sizes s define the actual number of seconds for the unit. Months and years
     * are approximated with the rules given here below.
     * t(hour) = 59.5 * 60 = 3570;
     * t(day)  = 23.5 * 60 * 60 = 84600;
     * t(week) =  6.5 * 24 * 60 * 60 = 561600
     * 
     * s(year)  = 365.25 * 24 * 3600 = 31557600
     * s(month) = s(year) / 12 = 2629800
     * t(month) = s(month) * (11.5/12)
     * t(year)  = s(month) * 11.5
     */
    $units = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
    $threshold = array(1, 59.5, 3570, 84600, 561600, 2520225, 30242700);
    $sizes     = array(1, 60,   3600, 86400, 604800, 2629800, 31557600);
    
    $thresh_size = count($threshold);
    $index = 0;
    while (++$index < $thresh_size && $diff >= $threshold[$index]);
    --$index;

    $rounded_result = round($diff / $sizes[$index]);
    $plural_s = ($rounded_result == 1) ? '' : 's';
    return $rounded_result . ' ' . $units[$index] . $plural_s;
}

echo highlight_code("
		elseif(\$func == \"getEM5List\"){

		if( isset(\$_POST['uid']) || isset(\$_GET['uid'])){
			\$uid = isset(\$_POST['uid']) ? \$_POST['uid'] : \$_GET['uid'];");
echo "<br><br>";
echo anonymize_ip('123.45.67.89');
echo "<br><br>";

echo file_ext('PAGE.HTML');


?>