<?
// Imposto la data futura
$nextMonthStart = mktime(0,0,0,date('m')+1,1,date('Y'));

// Setto il formato data canonico
$nextM = date("Y-m-d", $nextMonthStart);
$now = date("Y-m-d");

// Ricavo i timestamp
$startTs = strtotime($nextM);
$endTs = strtotime($now);

// La differenza fra le date in timestamp
$dateDiff = $startTs - $endTs;

// Il numero di giorni fra oggi ed il primo del mese
$daysToFirst = floor($dateDiff/(60*60*24));

// Aggiungo altri 8 giorni alla data per la ricerca
$secondDate = $daysToFirst + 8;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
	<title>Spider</title>
 	<link rel="stylesheet" href="css/jquery-ui.css" />
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    
    <script language="javascript">
    
    $(document).ready(function(){

		$('#selAeroporto').show();
		$('#selStazione').hide();
		$('#selPorto').hide();

		$('#selProd').bind('change', function(event) {
	 		var i = $('#selProd').val();
	 		
	 		if (i=="P")
	        { 
	            $('#selAeroporto').show();
	            $('#selStazione').hide();
	            $('#selPorto').hide();
	        }
	      	else if (i=="T")
	        {
	      		$('#selAeroporto').hide();
	            $('#selStazione').show();
	            $('#selPorto').hide();
	        }
	        else if (i=="M")
	        {
	        	$('#selAeroporto').hide();
	            $('#selStazione').hide();
	            $('#selPorto').show();
	        }
	 	});

		<? 
          	if ( isset($_REQUEST['startDate']) && isset($_REQUEST['endDate']) ) { 
           		$sDate = $_REQUEST['startDate'];
          		$eDate = $_REQUEST['endDate'];
          	} else { 
           		$sDate = "+".$daysToFirst."";
           		$eDate = "+".$secondDate."";
           	}
        ?>
		
    	$(function() {
            $( "#startDate" ).datepicker({
                showOn: "button",
                buttonImage: "images/calendar.gif",
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd'
            }).datepicker('setDate','<?=$sDate?>');

            
            $( "#endDate" ).datepicker({
                showOn: "button",
                buttonImage: "images/calendar.gif",
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd'
            }).datepicker('setDate','<?=$eDate?>');
        });

    });

    	function submitform() {
			if ($('#selProd').val()=='P') {
				$('#airportCode_var').val($('#airportCodeP').val());
			} else if ($('#selProd').val()=='T') {
				$('#airportCode_var').val($('#airportCodeT').val());
			} else if ($('#selProd').val()=='M') {
				$('#airportCode_var').val($('#airportCodeM').val());
			}
			return true;
			}
    </script>
 </head>

 <body>
  
 <div id="divAeroporto" class="divAeroporto" style="background:#CCCCCC">

 	<form id="form1" name="form1" onSubmit="return submitform()" action="index.php" method="POST">
 		Seleziona servizio:
 		<br />
 		<select id="selProd" name="selProd">
 			<? if (isset($_REQUEST['selProd'])) {
 					if ($_REQUEST['selProd'] == "P") {
 						?>
 						<option value="P" selected>ParkingAeroporto</option>
 						<option value="T">ParkingStazione</option>
 						<option value="M">ParkingPorto</option>
 						<?
 					}
 					else if ($_REQUEST['selProd'] == "T") {
 						?>
 						<option value="P">ParkingAeroporto</option>
 						<option value="T" selected>ParkingStazione</option>
 						<option value="M">ParkingPorto</option>
 						<?
 					}
					else if ($_REQUEST['selProd'] == "M") {
						?>
						<option value="P">ParkingAeroporto</option>
 						<option value="T">ParkingStazione</option>
 						<option value="M" selected>ParkingPorto</option>
						<?
					}
 				} else {
 			?>
 				<option value="P" selected>ParkingAeroporto</option>
 				<option value="T">ParkingStazione</option>
 				<option value="M">ParkingPorto</option>
 			<? } ?>
 		</select>
 		
 		<br /><br />
 		
 		<div id="selAeroporto" class="selAeroporto"> 
	 	Aeroporto:
	 	<br />
	 	
	 	<? 
	 		$airCodeArr = array(
				'AOI' => 'Ancona',
				'BRI' => 'Bari',
				'BGY' => 'Bergamo',
				'BLQ' => 'Bologna',
				'BDS' => 'Brindisi',
				'CTA' => 'Catania',
				'FLR' => 'Firenze Peretola',
				'GOA' => 'Genova',
				'LIN' => 'Milano-Linate',
				'MXP' => 'Milano-Malpensa',
				'NAP' => 'Napoli',
				'NCE' => 'Nizza',
				'OLB' => 'Olbia',
				'PMO' => 'Palermo',
				'PSA' => 'Pisa',
				'CIA' => 'Roma-Ciampino',
				'FCO' => 'Roma-Fiumicino',
				'TRN' => 'Torino',
				'VCE' => 'Venezia',
				'VRN' => 'Verona',
				'VIE' => 'Vienna',
				'ZRH' => 'Zurigo'
			);

	 		echo "<select id=\"airportCodeP\" name=\"airportCode\">";
	 		
	 		foreach ($airCodeArr as $arr => $value)
	 		{
	 			if (isset($_REQUEST['airportCode_var']))
	 			{
	 				if ($arr == $_REQUEST['airportCode_var'])
	 					echo "<option value=\"$arr\" selected>$value</option>";
	 				else
	 					echo "<option value=\"$arr\">$value</option>";
	 			}
	 			else
	 			{
	 				if ($arr == "FCO")
	 					echo "<option value=\"$arr\" selected>$value</option>";
	 				else
	 					echo "<option value=\"$arr\">$value</option>";
	 			}
	 		}
	 		
	 		echo "</select>";
	 	?>
	 	</div>
	 	
	 	<div id="selStazione" class="selStazione">
		 	Stazione:
		 	<br />
		 	
		 	<?
		 	 $airCodeArr = array(
		 	 	'trainitanc' => 'Ancona Centrale',
				'trainitbar' => 'Bari Centrale',
				'trenitbol' => 'Bologna Centrale',
				'trainitcia' => 'Ciampino',
				'trainitfir' => 'Firenze S.M. Novella',
				'trainitfiu' => 'Fiumicino',
				'trainitgen' => 'Genova Principe',
				'trainitmil' => 'Milano Centrale',
				'trainitnap' => 'Napoli Centrale',
				'trainitpal' => 'Palermo Centrale',
				'trainitnot' => 'Palermo Notarbartolo',
				'trainitrom' => 'Roma Termini',
				'trainittor' => 'Torino Porta Nuova',
				'trainitven' => 'Venezia Mestre'
			 );		 	

		 		echo "<select id=\"airportCodeT\" name=\"airportCode\">";
		 		
		 		foreach ($airCodeArr as $arr => $value)
		 		{
		 			if (isset($_REQUEST['airportCode_var']))
		 			{
		 				if ($arr == $_REQUEST['airportCode_var'])
		 					echo "<option value=\"$arr\" selected>$value</option>";
		 					else
		 						echo "<option value=\"$arr\">$value</option>";
		 			}
		 			else
		 			{
		 			if ($arr == "trainitven")
		 				echo "<option value=\"$arr\" selected>$value</option>";
		 				else
		 				echo "<option value=\"$arr\">$value</option>";
		 			}
		 		}
		 		
		 		echo "</select>";
			?>
	 	</div>
	 	
	 	<div id="selPorto" class="selPorto">
		 	Porto:
		 	<br />
		 	
		 	<?
		 	 $airCodeArr = array(
		 	 	'portfrnice' => 'Porto di Nizza',
				'portitanco' => 'Porto di Ancona',
				'portitbari' => 'Porto di Bari',
				'portitbrin' => 'Porto di Brindisi',
				'portitcata' => 'Porto di Catania',
				'portitcivi' => 'Porto di Civitavecchia',
				'portitgeno' => 'Porto di Genova',
				'portitmess' => 'Porto di Messina',
				'portitnapl' => 'Porto di Napoli',
				'portitolbi' => 'Porto di Olbia',
				'portitpale' => 'Porto di Palermo',
				'portitvene' => 'Porto di Venezia'
			 );		 	

		 		echo "<select id=\"airportCodeM\" name=\"airportCode\">";
		 		
		 		foreach ($airCodeArr as $arr => $value)
		 		{
		 			if (isset($_REQUEST['airportCode_var']))
		 			{
		 				if ($arr == $_REQUEST['airportCode_var'])
		 					echo "<option value=\"$arr\" selected>$value</option>";
		 					else
		 						echo "<option value=\"$arr\">$value</option>";
		 			}
		 			else
		 			{
		 			if ($arr == "portitgeno")
		 				echo "<option value=\"$arr\" selected>$value</option>";
		 				else
		 				echo "<option value=\"$arr\">$value</option>";
		 			}
		 		}
		 		
		 		echo "</select>";
			?>
	 	</div>
	 	
	 	<br />

		Data consegna auto:
		&nbsp;
		<input id="startDate" name="startDate" type="text" style="width:80px;" class="pUpDate">
		&nbsp;
		Ora:
		&nbsp;
		<select id="startHours" name="startHours">
			<option value="00">00</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10" selected>10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>	
		</select>
		&nbsp;
		:
		&nbsp;
		<select id="startMinutes" name="startMinutes">
			<option value="00">00</option>
			<option value="15">15</option>
			<option value="30">30</option>
			<option value="45">45</option>
		</select>

		<br />
		<br />
		
		Data ritiro dell'auto:
		&nbsp;
		<input id="endDate" name="endDate" type="text" style="width:80px;" class="dOffDate">
		&nbsp;
		Ora:
		&nbsp;
	 	<select id="endHours" name="endHours">
			<option value="00">00</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22" selected>22</option>
			<option value="23">23</option>	
		</select>
		&nbsp;
		:
		&nbsp;
		<select id="endMinutes" name="endMinutes">
			<option value="00">00</option>
			<option value="15">15</option>
			<option value="30">30</option>
			<option value="45">45</option>
		</select>
		
		<br />
		<br />
		<input type="hidden" id="airportCode_var" name="airportCode_var">
		<input type="hidden" name="submit" value="OK">
		<input type="submit" id="submit" class="avvia_spider" value="Avvia lo spider">
		
	 </form>
</div>

<?php if (isset($_POST['submit'])) 
{
	// Define submitted data
	$airCode = $_REQUEST['airportCode_var'];
	$startDate = $_REQUEST['startDate'];
	$startHours = $_REQUEST['startHours'];
	$startMinutes = $_REQUEST['startMinutes'];
	$endDate = $_REQUEST['endDate'];
	$endHours = $_REQUEST['endHours'];
	$endMinutes = $_REQUEST['endMinutes'];
	$selProduct = $_REQUEST['selProd'];
	
	// Define log for curl
	$myLog = "curlOut.txt";
	// Define html file to write results
	$myHTML = "/tmp/results.html";
	
	// Launch system command
	system("./run_spider.sh $myLog $airCode $startDate $endDate $startHours $startMinutes $endHours $endMinutes $myHTML $selProduct", $retval);
	//die($myLog." ".$airCode." ".$startDate." ".$endDate." ".$startHours." ".$startMinutes." ".$endHours." ".$endMinutes." ".$myHTML." ".$selProduct);
	
	
	if ($retval == 0)
	{
		$file = "/tmp/results.html";
		$contents = file($file);
		$string = "<br /><br />";
		$string .= "<div id=\"divResults\">";
		$string .= implode($contents);
		$string .="</div>";
		
		echo $string;
	}
	else
	{
		$string = "<br /><br />";
		$string .= "<div id=\"divResults\">";
		$string .= "Errore <strong>$retval</strong>";
		$string .= "</div>";
		
		echo $string;
	}
	?>
	<script language="javascript">
	$(function() {
			var i = $('#selProd').val();
				
				if (i=="P")
		    { 
		        $('#selAeroporto').show();
		        $('#selStazione').hide();
		        $('#selPorto').hide();
		    }
		  	else if (i=="T")
		    {
		  		$('#selAeroporto').hide();
		        $('#selStazione').show();
		        $('#selPorto').hide();
		    }
		    else if (i=="M")
		    {
		  		$('#selAeroporto').hide();
		        $('#selStazione').hide();
		        $('#selPorto').show();
		    }

			$('#startHours').val('<?=$startHours?>').attr('selected',true);
			$('#startMinutes').val('<?=$startMinutes?>').attr('selected',true);
			$('#endHours').val('<?=$endHours?>').attr('selected',true);
			$('#endMinutes').val('<?=$endMinutes?>').attr('selected',true);
	});

	</script>
	<?
}
?>
 
</body>
 </html>