<?php


/*

Hotel payment report by Badi Jones

This is one report that is part of a hotel payment reconciliation system.

The report includes the number of reservations per hotel and whether or not the hotel has paid 10% commission.

Gives % pending and % paid, as well as reasons why payments weren't made.

for internal use only

*/

//authenticate and connect
include('../config.php');

if($_POST['arrivaldate']){


$get_arrivalDate = $_POST['arrivaldate'];
$get_departureDate = $_POST['departuredate'];

$get_created1 = $_POST['created1'];
$get_created2 = $_POST['created2'];

$hg_id = $_POST['hg_id'];
$compid = $_POST['compid'];
$owner = $_POST['owner'];
$chain_code = $_POST['chain_code'];

$hotelcity = $_POST['hotelcity'];
$hotelstate = $_POST['hotelstate'];
$orderby = "Orig Res";
$hotelzas = $_POST['hotelzas'];





if($_POST['hotelzas']){

$hotelcity_query = "AND orders.associateid = '$hotelzas'";
$orderby = "Start";

}

if($_POST['hotelcity']){

$hotelcity_query = "AND reservations.hotelcity = '$hotelcity'";
$orderby = "Start";

}


if($_POST['hotelstate']){

$hotelstate_query = "AND reservations.hotelstate = '$hotelstate'";
$searchedby = true;

}


if($_POST['compid']){

$compid_query = "AND reservations.compid = '$compid'";
$searchedby = true;

}




if($_POST['owner'] ==='3'){

$owner_query = "";

}else if($_POST['owner']){

$owner_query = "AND hotel_chains.owner = '$owner'";
$searchedby = true;

}


if($_POST['hg_id']){

$hg_id_query = "AND reservations.propertycode2 = '$hg_id'";
$searchedby = true;

}

if($_POST['chain_code']){

$chain_query = "AND substr(reservations.propertycode,1,2)= '$chain_code'";
$searchedby = true;

}

if($_POST['arrivaldate'] && $_POST['arrivaldate']){

$whereDeparture = "AND (reservations.departuredate >= '$get_arrivalDate' AND reservations.departuredate <= '$get_departureDate')";
$startChkio = strtotime($get_arrivalDate);
$endChkio = strtotime($get_departureDate);
$diffChkio = $endChkio - $startChkio;


if( floor($diffChkio / (60 * 60 * 24))  < 370)
$searchedby = true;

}

if($_POST['created1'] && $_POST['created2']){

$wherecreated = "AND (collections.created >= '$get_created1' AND collections.created <= '$get_created2')";

$startCreated = strtotime($get_created1);
$endCreated = strtotime($get_created2);
$diffCreated = $endCreated - $startCreated;

if( floor($diffCreated / (60 * 60 * 24))  < 370)
$searchedby = true;


}

#confirmed Statuses
$confirmed = "SUM(IF( reservations.status='CONFIRMED'||  reservations.status='PHONED' || collections.status LIKE 'P%' , 1, 0))  - SUM(IF(  (reservations.status='CONFIRMED'||  reservations.status='PHONED' ) AND (collections.status NOT like 'P%' )   , 1, 0))";
$confirmedNew = "SUM(IF(  (reservations.status='CONFIRMED'||  reservations.status='PHONED') && collections.status LIKE 'P%' , 1, 0))";
$confirmedStatus = "(reservations.status='CONFIRMED'||  reservations.status='PHONED') && collections.status LIKE 'P%'";
$confirmedNotPaidStatus = "(( reservations.status='CONFIRMED'||  reservations.status='PHONED') && collections.status NOT LIKE 'P%')";
$pending = "SUM(IF($confirmed) , 1, 0))) - SUM(IF(collections.status LIKE 'P%', 1, 0)";
$originalRes = " reservations.status='CONFIRMED'|| reservations.status='NO SHOW' || reservations.status='NOT HONORED' || reservations.status='PHONED' || reservations.status='CANCELED' || reservations.status='TEST'";



$query ="SELECT 

	reservations.propertycode2 as 'HG ID',
	reservations.propertycode as 'WS ID',

	
CASE  
	WHEN (hg_hotels_new.commented = '0') THEN '1'
	WHEN (hg_hotels_new.commented IS NULL) THEN '0'
	WHEN (TRIM(REPLACE(hg_hotels_new.commented ,'<!--','')) = 'bh') THEN '2'
	WHEN (TRIM(REPLACE(hg_hotels_new.commented ,'<!--','')) = 'np') THEN '3'
    ELSE TRIM(REPLACE(hg_hotels_new.commented ,'<!--',''))
END AS Active,
	

	
	hg_hotels_new.name as 'Hotel',
    IF(reservations.hotelname != hg_hotels_new.name, reservations.hotelname, '') as 'Name on Res if Different',
	reservations.hotelcity as City,
	reservations.hotelstate as State,
	reservations.hotelphone as Phone,
    SUM(IF(  $originalRes, 1, 0)) as 'OrigRes',
    SUM(IF( collections.status IN('P','PM','PL'), 1,0)) as 'PML',
    SUM(IF(collections.status ='C' || (reservations.status = 'CANCELED' && collections.status IS NULL), 1, 0)) as C,
    SUM(IF( collections.status ='NS', 1, 0)) as 'NS',
    SUM(IF(collections.status ='NC', 1, 0)) as NC,
    SUM(IF( collections.status ='UC', 1, 0)) as UC,
    SUM(IF( collections.status ='CS', 1, 0)) as CS,
    SUM(IF(collections.status ='DP', 1, 0)) as DP,
    SUM(IF(collections.status ='NF', 1, 0)) as NF,
    SUM(IF(collections.status ='NR', 1, 0)) as NR,
    SUM(IF(collections.status ='Z', 1, 0)) as Z,
    SUM(IF(collections.status ='6', 1, 0)) as '6',
    SUM(IF(collections.status NOT IN('P','PM','PL','C','NS','NC','UC','CS','DP','NF','NR','Z','6') AND collections.status IS NOT NULL, 1, 0)) as Other,
  
  
    SUM(IF(collections.status IS NOT NULL, 1, 0)) as 'Non Pending',
    ( SUM(IF(  reservations.status='CONFIRMED'|| reservations.status='NO SHOW' || reservations.status='NOT HONORED' || reservations.status='PHONED' || (reservations.status = 'CANCELED' && collections.status IS NOT NULL) || reservations.status='TEST', 1, 0)) - SUM(IF(collections.status IS NOT NULL, 1, 0)) ) as 'Pending',
    
     ROUND((   ( SUM(IF(  $originalRes, 1, 0)) - SUM(IF(collections.status IS NOT NULL, 1, 0)) )  /  SUM(IF(  $originalRes, 1, 0))  )*100)     as '% Pending',
     	
     ROUND((   SUM(IF( collections.status IN('P','PM','PL'), 1,0))  /  SUM(IF(  $originalRes, 1, 0))  )*100)     as '% Paid',
    IF(hg_hotels_new.contactName = '', ' - ', hg_hotels_new.contactName) as 'Acct Contact',
    IF(hg_hotels_new.contactEmail = '', ' - ', hg_hotels_new.contactEmail) as 'Acct Email',
    IF(hg_hotels_new.acctPhone = '', ' - ', hg_hotels_new.acctPhone) as 'Acct Phone',
    IF(hg_hotels_new.acctFax = '', ' - ', hg_hotels_new.acctFax) as 'Acct Fax',
    IF(hg_hotels_new.monthlyInvoice = '', ' - ', hg_hotels_new.monthlyInvoice) as 'Monthly Invoice',
    IF(hg_hotels_new.service = '', ' - ', hg_hotels_new.service) as 'Service',
    IF(hg_hotels_new.invoice1 = \"0000-00-00 00:00:00\", ' - ', DATE_FORMAT(hg_hotels_new.invoice1,'%Y-%m-%d')) as 'Invoice 1',
    IF(hg_hotels_new.invoice2 = \"0000-00-00 00:00:00\", ' - ', DATE_FORMAT(hg_hotels_new.invoice2,'%Y-%m-%d')) as 'Invoice 2',
    IF(hg_hotels_new.invoice3 = \"0000-00-00 00:00:00\", ' - ', DATE_FORMAT(hg_hotels_new.invoice3,'%Y-%m-%d')) as 'Invoice 3',
    IF(hg_hotels_new.notes = \"\", ' - ', hg_hotels_new.notes) as 'Notes'
 

FROM reservations 
LEFT JOIN collections on collections.confirmation_id = reservations.confirmationnumber 
LEFT JOIN orders on orders.orderid = reservations.orderid
LEFT JOIN hg_hotels_new on hg_hotels_new.hg_id = reservations.propertycode2 
LEFT JOIN hotel_chains ON  substr(propertycode,1,2)= hotel_chains.code  

WHERE  reservations.propertycode2 != ''

AND reservations.confirmationnumber !=''


$hg_id_query
$compid_query
$owner_query
$chain_query
$hotelcity_query
$hotelstate_query

#AND reservations.`status` NOT IN('MULTI-ROOM','TEST','FAILED','SENT')
#AND (reservations.status='CONFIRMED' OR  reservations.status='PHONED') 
AND ($originalRes)

$whereDeparture
$wherecreated
GROUP BY reservations.propertycode2 ";




$starttime = microtime(true);

//Do your query and stuff here


$export = mysql_query ($query ) or die ( "Sql error : " . mysql_error( ) );

$fields = mysql_num_fields ( $export );
$endtime = microtime(true);
$duration = $endtime - $starttime; //calculates total time taken




for ( $i = 0; $i < $fields; $i++ )
{
    $header .= mysql_field_name( $export , $i ) . "\t";
}

$rowct = 1;

while( $row = mysql_fetch_row( $export ) )
{
$rowct++;
	$origline = '';
	$origmatch = '';

	$confline = '';
	$confmatch = '';
	$percentover = '';
	
	
    $line = '';
    foreach( $row as $value )
    {
        if ( ( !isset( $value ) ) || ( $value === "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        

        $line .= preg_replace('|[\r\n]|i',' - ',$value);
    }
    

	$origline = $row[11] + $row[12] + $row[13] + $row[14] + $row[15] + $row[16] + $row[17];
	$confline = $row[18] + $row[19];
	
	
	
	if($origline === $row[10]){
		$origmatch = 'true';
	}else{
		$origmatch = 'false';
	}
	
	if($confline === $row[11]){
		$confmatch = 'true';
	}else{
		$confmatch = 'false';
	}
	#percentover 18 - 22

	if( ($row[21] > 100) || ($row[22] > 100) || ($row[23] > 100) || ($row[24] > 100) || ($row[25] > 100) ){
		$percentover = 'false';
	}else{
		$percentover = 'true';
	}

/*

    if sum of l m n o doesn't equal k, make exception report
    Confirm	No Show	Cancel	NonCom = Orig Res
    $row[11] + $row[12] + $row[13] + $row[14]  
    $row[10]
    
    
	if sum of col p and q doesn't equal l, make exception
	Paid	Pending = Confirm
	
	$row[15] + $row[16]
	$row[11]

*/
    
    $data .= trim( $line ). "\n";
}


$avgct = $rowct+1;

$data .= "\"TOTALS ($duration seconds)\"	\"\"	\"\"	\"\"	\"\"	\"\"	\"\"	\"=SUM(H2:H$rowct)\"	\"=SUM(I2:I$rowct)\"	\"=SUM(J2:J$rowct)\"	\"=SUM(K2:K$rowct)\"	\"=SUM(L2:L$rowct)\"	\"=SUM(M2:M$rowct)\"	\"=SUM(N2:N$rowct)\"	\"=SUM(O2:O$rowct)\"	\"=SUM(P2:P$rowct)\"	\"=SUM(Q2:Q$rowct)\"	\"=SUM(R2:R$rowct)\"	\"=SUM(S2:S$rowct)\"	\"=SUM(T2:T$rowct)\"	\"=SUM(U2:U$rowct)\"	\"=SUM(V2:V$rowct)\"	\"=ROUND( (V$avgct/H$avgct)*100,1)\"	\"=ROUND((I$avgct/H$avgct)*100,1)\"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"	\" - \"". "\n";


$data = str_replace( "\r" , "" , $data );

if ( $data === "" )
{
    $data = "\n(0) Records Found!\n";                        
}


// Assuming today is March 10th, 2001, 5:16:18 pm, and that we are in the
// Mountain Standard Time (MST) Time Zone


$today = date("m.d.Y_g:i");                         // 03.10.01

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pay-ratings$today.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";

}else{

?>
<html>
<head>
	<link rel="stylesheet" href="../../z-res/z-js/jquery.ui.all.css">
	<script src="../../z-res/z-js/jquery-1.7.2.js"></script>
	<script src="../../z-res/z-js/jquery.ui.core.js"></script>
	<script src="../../z-res/z-js/jquery.ui.datepicker.js"></script>
	<link rel="stylesheet" href="../../z-res/z-js/demos.css">
	


</head>
<body>
<script>

	$(function() {
		$( "#from" ).datepicker({
		
			dateFormat: "yy-mm-dd",
			defaultDate: "<?= $defaultArrival; ?>"
		});
	});
	
	
	
	$(function() {
		$( "#to" ).datepicker({
		
			dateFormat: "yy-mm-dd",
			defaultDate: "<?= $defaultDeparture; ?>"

		});
	});
	
	
	$(function() {
		$( "#from1" ).datepicker({
		
			dateFormat: "yy-mm-dd",
			defaultDate: "<?= $defaultArrival; ?>"
		});
	});
	
	
	
	$(function() {
		$( "#to1" ).datepicker({
		
			dateFormat: "yy-mm-dd",
			defaultDate: "<?= $defaultDeparture; ?>"

		});
	});
	

	</script>

<form action='' method=post>
<p>
  <input type="radio" name="owner" value="1" checked="checked" /> Krista  &nbsp; &nbsp;
  <input type="radio" name="owner" value="2"> Tina 
  <input type="radio" name="owner" value="3"> All 

</p>

<p><b>Departure date between:</b><br /><input type='text' id='from' name='arrivaldate' value='2011-01-01' />  and <input type='text' id='to' name='departuredate' value='<?=$today_cal ?>' /> 

<p><b>Commissions entered between:</b><br /><input type='text' id='from1' name='created1' value='' />  and <input type='text' id='to1' name='created2' value='' /> 
<p><b>Company ID:</b><br /><input type='text' name='compid' value='' />

<p><b>HG ID:</b><br /><input type='text' name='hg_id' value='' />
<p><b>ZAS:</b><br /><input type='text' name='hotelzas' value='' />

<p><b>Hotel City:</b><br /><input type='text' name='hotelcity' value='' />
<p><b>Hotel State:</b><br />
<select name="hotelstate" size='1' class="formfield" style="width:51px">
<option value='' selected>&nbsp;</option>
<option value='AK'>AK</option>
<option value='AL'>AL</option>
<option value='AR'>AR</option>
<option value='AZ'>AZ</option>
<option value='CA'>CA</option>
<option value='CO'>CO</option>
<option value='CT'>CT</option>
<option value='DC'>DC</option>
<option value='DE'>DE</option>
<option value='FL'>FL</option>
<option value='GA'>GA</option>
<option value='HI'>HI</option>
<option value='IA'>IA</option>
<option value='ID'>ID</option>
<option value='IL'>IL</option>
<option value='IN'>IN</option>
<option value='KS'>KS</option>
<option value='KY'>KY</option>
<option value='LA'>LA</option>
<option value='MA'>MA</option>
<option value='MD'>MD</option>
<option value='ME'>ME</option>
<option value='MI'>MI</option>
<option value='MN'>MN</option>
<option value='MO'>MO</option>
<option value='MS'>MS</option>
<option value='MT'>MT</option>
<option value='NC'>NC</option>
<option value='ND'>ND</option>
<option value='NE'>NE</option>
<option value='NH'>NH</option>
<option value='NJ'>NJ</option>
<option value='NM'>NM</option>
<option value='NV'>NV</option>
<option value='NY'>NY</option>
<option value='OH'>OH</option>
<option value='OK'>OK</option>
<option value='OR'>OR</option>
<option value='PA'>PA</option>
<option value='PR'>PR</option>
<option value='RI'>RI</option>
<option value='SC'>SC</option>
<option value='SD'>SD</option>
<option value='TN'>TN</option>
<option value='TX'>TX</option>
<option value='UT'>UT</option>
<option value='VA'>VA</option>
<option value='VT'>VT</option>
<option value='WA'>WA</option>
<option value='WI'>WI</option>
<option value='WV'>WV</option>
<option value='WY'>WY</option>
</select>
<br>
<p><b>Chain Code:</b><br /><input type='text' name='chain_code' value='' />


<input type=submit value=submit>
</form>
<?php } ?>
