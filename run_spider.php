<?
	// Define submitted data
	$airCode = $_REQUEST['airportCode'];
	$startDate = $_REQUEST['startDate'];
	$startHours = $_REQUEST['startHours'];
	$startMinutes = $_REQUEST['startMinutes'];
	$endDate = $_REQUEST['endDate'];
	$endHours = $_REQUEST['endHours'];
	$endMinutes = $_REQUEST['endMinutes'];
	
	// Define log for curl
	$myLog = "curlOut.txt";
	// Define html file to write results
	$myHTML = "/tmp/results.html";
	
	// Launch system command
	system("./run_spider.sh $myLog $airCode $startDate $endDate $startHours $startMinutes $endHours $endMinutes $myHTML", $retval);
	
	if ($retval == 0) 
	{
		$file = "/tmp/results.html";
		$contents = file($file);
		$string = implode($contents);
		
		echo $string;	
	}
	else 
	{
		echo "Errore $retval";	
	}
	
?>