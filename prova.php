<?
	
	$now = getdate();
	$nextmonth = ($now['mon'] + 1) % 13 + 1;
	$year = $now['year'];
	if($nextmonth == 1)
		$year++;
	$thefirst = gmmktime(0, 0, 0, $nextmonth, $year);
	
	$now = getdate();
	$months = array(
			31,
			28 + ($now['year'] % 4 == 0 ? 1 : 0), // Support for leap years!
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
	);
	$days = $months[$now['mon'] - 1];
	$daysleft = $days - $now['mday'];
	
	echo "daysleft => $daysleft ";
	
	$nextMonthStart = mktime(0,0,0,date('m')+1,1,date('Y'));
	$nextMonthEnd = mktime(0,0,0,date('m')+2,-1,date('Y'));
	$nextM = date("Y-m-d", $nextMonthStart);
	$now = date("Y-m-d");
	$startTs = strtotime($nextM);
	$endTs = strtotime($now);
	
	echo "Start: " . date("Y-m-d", $nextMonthStart) . "\nEnd: " . date("Y-m-d", $nextMonthEnd);
	
	
	$dateDiff = $startTs - $endTs;
	$fullDays = floor($dateDiff/(60*60*24));
	
	echo "full => $fullDays";
		
	
	
	
	
?>