<?php


$previousConfId = false;
$consolArray = array();
$year = date("Y");



$loc_conn =    mysql_connect($loc_dbhost,$loc_dbuser,$loc_dbpass);
$remote_conn = mysql_connect($remote_dbhost,$remote_dbuser,$remote_dbpass, true);




mysql_select_db('hotel_guides_zcom', $loc_conn);
mysql_select_db('hgdb001', $remote_conn);



function getstatuscode($stinput){

$stinput = trim($stinput);

if($stinput === 1){
return "P";
}else if($stinput === 3){
return "NC";
}else if($stinput === 4){
return "C";
}else if($stinput === 5){
return "NS";
}else{
return $stinput;
}

/*
1 = P - need to convert to PL or PM if applicable
3 = NS
4 = C
5 = NC
*/

}

function specialtrim($input){

$input = '"'.str_replace('"','\"',trim($input)).'"';
return $input; 
}
$probcsv = '';
$goodcsv = '';

$failct = 0;
$successct = 0;

if($_POST){

$uploadedFileName = $_FILES["file"]["name"];
$uploadedFileType = $_FILES["file"]["type"];
$uploadedFileSize = $_FILES["file"]["size"];
$uploadedFileTmp = $_FILES["file"]["tmp_name"];
$uploadedFileError = $_FILES["file"]["error"];


	if (  ($uploadedFileType === "text/plain") ||  ($uploadedFileType === "text/csv")
	|| ($uploadedFileType === "application/octet-stream")) 
	  {
	  if ($uploadedFileError > 0)
		{
		echo "Return Code: " . $uploadedFileError . "<br />";
		}
	  else
		{

$goodcsv .= "Source,";
$goodcsv .= "period ending,";
$goodcsv .= "DB Match Status,";
$goodcsv .= "conf Id,";
$goodcsv .= "Status Code,";
$goodcsv .= "Commission Amount,";
$goodcsv .= "Guest First,";
$goodcsv .= "Guest Last,";
$goodcsv .= "Check In,";
$goodcsv .= "Check Out,";
$goodcsv .= "Nights,";
$goodcsv .= "Rooms,";
$goodcsv .= "Chain Code,";
$goodcsv .= "WS Hotel ID,";
$goodcsv .= "Hotel Name,";
$goodcsv .= "HG Name,";
$goodcsv .= "Hotel Phone,";
$goodcsv .= "WS Phone,";
$goodcsv .= "Hotel Address,";
$goodcsv .= "Hotel City,";
$goodcsv .= "Hotel State,";
$goodcsv .= "Hotel Country,";
$goodcsv .= "Hotel zip\n";
		
		
$probcsv .= "Source,";
$probcsv .= "period ending,";
$probcsv .= "DB Match Status,";
$probcsv .= "conf Id,";
$probcsv .= "Status Code,";
$probcsv .= "Commission Amount,";
$probcsv .= "Guest First,";
$probcsv .= "Guest Last,";
$probcsv .= "Check In,";
$probcsv .= "Check Out,";
$probcsv .= "Nights,";
$probcsv .= "Rooms,";
$probcsv .= "Chain Code,";
$probcsv .= "WS Hotel ID,";
$probcsv .= "Hotel Name,";
$probcsv .= "HG Name,";
$probcsv .= "Hotel Phone,";
$probcsv .= "WS Phone,";
$probcsv .= "Hotel Address,";
$probcsv .= "Hotel City,";
$probcsv .= "Hotel State,";
$probcsv .= "Hotel Country,";
$probcsv .= "Hotel zip\n";

		
		echo "Upload: " . $uploadedFileName . "<br />";
		//echo "Type: " . $uploadedFileType . "<br />";
		echo "Size: " . ($uploadedFileSize / 1024) . " Kb<br />";
		//echo "Temp file: " . $uploadedFileTmp . "<br />";
	
	
	
// CHECK FOR DUPLICATE FILES
$filename1 = '/httpdocs/pegasus/success-reports/'.$uploadedFileName.'_success.csv';
$filename2 = '/httpdocs/pegasus/exception-reports/'.$year.'/'.$uploadedFileName.'_exceptions.csv';


if (file_exists('/httpdocs/pegasus/exception-reports/'.$year.'/'.$_FILES["file"]["name"].'_exceptions.csv') || file_exists('/httpdocs/pegasus/success-reports/'.$_FILES["file"]["name"].'_success.csv')) {
    echo "<b>File has already been imported</b><br>Delete one or both of these files...<br><br>$filename1<br>$filename2<br>";
    exit;
}
	
	
		  move_uploaded_file($uploadedFileTmp, "/httpdocs/pegasus/original-reports/".$uploadedFileName);
		  echo "Stored... <br \>";

		  
		  echo "Opening ... /mnt/php/z-collections/upload/".$uploadedFileName."<br>";
		  




//Open import - begin consolidation loop:
$fp = fopen('/httpdocs/pegasus/original-reports/'.$uploadedFileName,'r') or die ("can't open file");
while ($s = fgets($fp,1024)) {

$s = preg_replace('#^([^\n|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|[^\n\|]*\|)([^\n\|/]*)/([^\n|/]*)\|[ ]*\|#', '\\1\\2|\\3|', $s);


$confields = explode('|',$s);


    
if(sizeof($confields) === 40){


$trimmed_confirmation_id = trim($confields[9]);

if(!$trimmed_confirmation_id){

		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - blank.";

		$failct++;
		
$probcsv .= "P,"; # P for Pegasus - a constant in all rows - Put P in Notes field too
$probcsv .= specialtrim($confields[4]).","; #period ending date - a constant in all rows
$probcsv .= "$thiserror,"; #error
$probcsv .= specialtrim($trimmed_confirmation_id) .","; #res confirmation ID
$probcsv .=specialtrim($confields[15]) .","; #status code - see below for status code conversion table
$probcsv .= trim($confields[26]).","; #net commissions due
$probcsv .= specialtrim($confields[14]) .","; #guest first name
$probcsv .= specialtrim($confields[13]) .","; #guest last name
$probcsv .= specialtrim($confields[17]) .","; #check-in date
$probcsv .= specialtrim($confields[18]) .","; #check-out date
$probcsv .= specialtrim($confields[19]) .","; #number of nights
$probcsv .= specialtrim($confields[20]) .","; #number of rooms
$probcsv .= specialtrim($confields[2]) .","; #chain code
$probcsv .= specialtrim($confields[2]. ";". $confields[7]) .","; #chain code;hotelID - WS hotel ID
$probcsv .= specialtrim($confields[31]) .","; #hotel name
$probcsv .= "$name,"; #HG name
$probcsv .= specialtrim($confields[38]) .","; #hotel phone number
$probcsv .= "$phone,"; #WS hotel phone number - from HG db
$probcsv .= specialtrim($confields[32]) .","; #hotel street address
$probcsv .= specialtrim($confields[34]) .","; #hotel city
$probcsv .= specialtrim($confields[35]) .","; #hotel state or province
$probcsv .= specialtrim($confields[36]) .","; #hotel country
$probcsv .= specialtrim($confields[37]) ."\n"; #hotel postal code
		
echo "Fail on confirmation #: $trimmed_confirmation_id - blank.<br>";

} else if( array_key_exists($trimmed_confirmation_id, $consolArray) && (getstatuscode($confields[15]) === "P")) {

$consolArray[$trimmed_confirmation_id][26] += number_format(trim($confields[26] *.01),2, '.', '' );

echo  getstatuscode($confields[15]) ." ".number_format(trim($confields[26] *.01),2, '.', '' )." ".$trimmed_confirmation_id."<br>";


}else{

$consolArray[$trimmed_confirmation_id][0] = $confields[0];
$consolArray[$trimmed_confirmation_id][1] = $confields[1];
$consolArray[$trimmed_confirmation_id][2] = $confields[2];
$consolArray[$trimmed_confirmation_id][3] = $confields[3];
$consolArray[$trimmed_confirmation_id][4] = $confields[4];
$consolArray[$trimmed_confirmation_id][5] = $confields[5];
$consolArray[$trimmed_confirmation_id][6] = $confields[6];
$consolArray[$trimmed_confirmation_id][7] = $confields[7];
$consolArray[$trimmed_confirmation_id][8] = $confields[8];
$consolArray[$trimmed_confirmation_id][9] = $confields[9];
$consolArray[$trimmed_confirmation_id][10] = $confields[10];
$consolArray[$trimmed_confirmation_id][11] = $confields[11];
$consolArray[$trimmed_confirmation_id][12] = $confields[12];
$consolArray[$trimmed_confirmation_id][13] = $confields[13];
$consolArray[$trimmed_confirmation_id][14] = $confields[14];
$consolArray[$trimmed_confirmation_id][15] = $confields[15];
$consolArray[$trimmed_confirmation_id][16] = $confields[16];
$consolArray[$trimmed_confirmation_id][17] = $confields[17];
$consolArray[$trimmed_confirmation_id][18] = $confields[18];
$consolArray[$trimmed_confirmation_id][19] = $confields[19];
$consolArray[$trimmed_confirmation_id][20] = $confields[20];
$consolArray[$trimmed_confirmation_id][21] = $confields[21];
$consolArray[$trimmed_confirmation_id][22] = $confields[22];
$consolArray[$trimmed_confirmation_id][23] = $confields[23];
$consolArray[$trimmed_confirmation_id][24] = $confields[24];
$consolArray[$trimmed_confirmation_id][25] = $confields[25];
$consolArray[$trimmed_confirmation_id][26] = number_format(trim($confields[26] *.01),2, '.', '' );
$consolArray[$trimmed_confirmation_id][27] = $confields[27];
$consolArray[$trimmed_confirmation_id][28] = $confields[28];
$consolArray[$trimmed_confirmation_id][29] = $confields[29];
$consolArray[$trimmed_confirmation_id][30] = $confields[30];
$consolArray[$trimmed_confirmation_id][31] = $confields[31];
$consolArray[$trimmed_confirmation_id][32] = $confields[32];
$consolArray[$trimmed_confirmation_id][33] = $confields[33];
$consolArray[$trimmed_confirmation_id][34] = $confields[34];
$consolArray[$trimmed_confirmation_id][35] = $confields[35];
$consolArray[$trimmed_confirmation_id][36] = $confields[36];
$consolArray[$trimmed_confirmation_id][37] = $confields[37];
$consolArray[$trimmed_confirmation_id][38] = $confields[38];
$consolArray[$trimmed_confirmation_id][39] = $confields[39];

}
    

    
}
}


$consline = '';


//START Main LOOP
foreach($consolArray as $fields){


$estimated = 0;
$actualcomm = 0;
$commstatus = "";


    
    if(sizeof($fields) === 40){
    









    		$thiserror = "";

    // Try to reconcile/ IMPORT

//select * from reservations where LEFT(confirmationnumber, 12) = "55024SY00018"


// CHECK FOR CONFIRMATION ID IN DB
$trimmed_confirmation_id = trim($fields[9]);
$trimmed_first = trim($fields[14]);
$trimmed_last = trim($fields[13]);
$trimmed_checkin = trim($fields[17]);
$trimmed_checkout = trim($fields[18]);


$trimmed_checkin = preg_replace('#^([0-9]{4})([0-9]{2})([0-9]{2})#', '\\1-\\2-\\3', $trimmed_checkin);
$trimmed_checkout = preg_replace('#^([0-9]{4})([0-9]{2})([0-9]{2})#', '\\1-\\2-\\3', $trimmed_checkout);
//$trimmed_checkin = eregi_replace('^([0-9]{4})([0-9]{2})([0-9]{2})','\\1-\\2-\\3',$trimmed_checkin);
//$trimmed_checkout = eregi_replace('^([0-9]{4})([0-9]{2})([0-9]{2})','\\1-\\2-\\3',$trimmed_checkout);
//20120213



$og_trimmed_confirmation_id = trim($fields[9]);
$notes = 'pegasus_auto_import';
$useHgConf = false;
$trimmed_chain_code = trim($fields[2]);
$thisconfirmation_id = '';

/*
	if($trimmed_chain_code === "CX" || $trimmed_chain_code === "RD"){
	 
	$trimmed_confirmation_id = substr($trimmed_confirmation_id, 0, -1);
	$notes = $notes. ' '.$og_trimmed_confirmation_id;
	
	}
*/

# Start  - Checking to see if conf ID is already in the DB
if( $trimmed_chain_code === "RF" /* 1 === 2 */){
	$sqlCol = "SELECT * from collections WHERE LEFT(confirmation_id, 12) = \"$trimmed_confirmation_id\""; 
	$notes = $notes. ' '.$og_trimmed_confirmation_id;
}else{
	$sqlCol = "SELECT * from collections WHERE confirmation_id = \"$trimmed_confirmation_id\""; 
}

$resultCol = mysql_query($sqlCol, $loc_conn);
$nrowsCol = mysql_num_rows($resultCol);

# Checking for bad conf IDS
	if(stristr($trimmed_confirmation_id, ',')){
	# Confirmation contains a comma
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - Contains bad characters.";
		$thiserror = "FAIL_MULTI";
		$failct++;
	
	}else if(!$trimmed_confirmation_id){
	# Confirmation ID field is empty
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - blank.";
		$thiserror = "FAIL_NOCONF";
		$failct++;
	
	}else if($nrowsCol > 0){
	# There is already a commission in the collections DB with this confirmation ID
			$duprow = mysql_fetch_assoc($resultCol);
			
			
			$paidamount = $duprow['amount'];
	
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - already exists in collections database.";
		$thiserror = "FAIL_DUPLICATE - $paidamount";
		$failct++;
	}else{
	
	
	#move forward. Conf_id is new.  Attempting to find conf_id in res DB
	
		
		if( $trimmed_chain_code === "RF" /* 1 === 2 */ ){
		
		if(strlen($trimmed_confirmation_id) === 12){
		
			$sqlRes = "SELECT * from reservations WHERE confirmationnumber LIKE \"$trimmed_confirmation_id%\"";
			$notes = $notes. ' '.$og_trimmed_confirmation_id;
			$useHgConf = true;
//echo "Red Roof2 :";
		}else{
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - redRoof.";
		$thiserror = "FAIL_RedRoof - $paidamount";
		$failct++;
//	echo " Fail RedRoof :";
		}
			

		}else if( $trimmed_chain_code === "CX" /* 1 === 2 */ ){
		
		if(strlen($trimmed_confirmation_id) === 8){
			$trimmed_confirmation_idcx = substr($trimmed_confirmation_id, 0, -1);
			$sqlRes = "SELECT * from reservations WHERE confirmationnumber LIKE \"$trimmed_confirmation_idcx%\"";
			$notes = $notes. ' '.$trimmed_confirmation_id;
			$useHgConf = true;
//echo "Red Roof2 :";
		}else{
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - CX.";
		$thiserror = "FAIL_CX - $paidamount";
		$failct++;
//	echo " Fail RedRoof :";
		}
			

		}else{
		
			$sqlRes = "SELECT * from reservations WHERE confirmationnumber = \"$trimmed_confirmation_id\"";
			
//echo "not Red Roof2 :";
		}

		
		$resultRes = mysql_query($sqlRes, $loc_conn);
		$nrowsRes = mysql_num_rows($resultRes);
		
		
		
		$lastchancein = false;
		if($nrowsRes === 0){
		
		$spec_trimmed_first = preg_replace('/[\' -]/','',$trimmed_first);
		$spec_trimmed_last = preg_replace('/[\' -]/','',$trimmed_last);
        #There are no reservations that match our comission
        #Now we check by first name/ last name/ arrival Date/ departure Date
		$lastChanceSql = "SELECT *
FROM orderaddresses
INNER JOIN reservations ON orderaddresses.orderidentity = reservations.orderid
WHERE replace(replace(replace(firstname, '\'', ''), ',', ''), '-', '') = \"$spec_trimmed_first\" AND replace(replace(replace(lastname, '\'', ''), ',', ''), '-', '') = \"$spec_trimmed_last\" AND arrivaldate = \"$trimmed_checkin\" AND departuredate = \"$trimmed_checkout\"";

		$resultLC = mysql_query($lastChanceSql, $loc_conn);
		$nrowsLC = mysql_num_rows($resultLC);


		
		if($nrowsLC > 0){
		#Found a match by first name/ last name/ arrival Date/ departure Date
		//echo "Yes<br>";
		$lastchancein = true;
		
		
		
		
		
		
		
		
		}else{
		//echo "NO $lastChanceSql<br>";
		}


		
		}
		
		
		
		
		
		
		
		
		if ($nrowsRes >0 && (trim($fields[26]) >= 0)){
		# Process commission matched by conf ID

			$resultrow = mysql_fetch_assoc($resultRes);
			$estimated = money_format('%i', $resultrow['totalrate'] * .1);
			$hgConfId = $resultrow['confirmationnumber'];
			
			$hgHgId = $resultrow['propertycode2'];
			$hgResId = $resultrow['resid'];
			
			$actualcomm = $fields[26];
			

			if(($actualcomm < $estimated) && (  getstatuscode($fields[15]) === "P")){
				
				if(($actualcomm <= 0)){
			
					$commstatus = "Z";
				}else{
					$commstatus = "PL";
					
				}
				
				
			}else if(($actualcomm > $estimated) && (  getstatuscode($fields[15]) === "P")){
				$commstatus = "PM";
			}else if($actualcomm === $estimated){
				$commstatus = "P";
			}else{
			
		$commstatus = getstatuscode($fields[15]);
			
			}
		
		

		
		
		$thisstatus = trim(strtoupper($fields[15]));
		
		if($useHgConf){
			$trimmed_confirmation_id = $hgConfId;
			$notes = $notes. ' : HG Conf=>'.$hgConfId;
		}else{
			$trimmed_confirmation_id = strtoupper($trimmed_confirmation_id);
		}
		
		$nowtime = date("Y-m-d H:i:s");
		$thisamount = trim($fields[26]);
		
		$sql = "INSERT INTO `collections` (  `confirmation_id` ,  `status` ,  `amount` , `created` , `updated`, `notes`, `hg_id`, `resid`) VALUES( '$trimmed_confirmation_id' ,  '$commstatus' ,  '$thisamount' , '$nowtime' , '$nowtime' , '$notes', '$hgHgId', '$hgResId'  ) "; 
		if($previousConfId === $trimmed_confirmation_id){
		echo "\n\n\n****** PROBLEM!! **** TALK TO BADI******\n\n\n";
		
		exit;
		}
		
		$previousConfId = $trimmed_confirmation_id;
			// INSERT QUERY ********
		mysql_query($sql,$loc_conn) or die(mysql_error()); 
		echo "$sql<br>\n";
			
		//echo "Added row.<br />"; 
		//echo "<a href='list.php'>Back To Listing</a>"; 
		
		$success[] = "Confirmation #: $trimmed_confirmation_id - successfully added!";
					$thiserror = "SUCCESS";


			
		//echo "Confirmation #: $trimmed_confirmation_id - successfully added!<br>";
		$successct++;
		}else if($lastchancein){
		
        # Process commission matched by first name/ last name/ arrival Date/ departure Date

			$resultrow = mysql_fetch_assoc($resultLC);
			$estimated = money_format('%i', $resultrow['totalrate'] * .1);
			$hgConfId = $resultrow['confirmationnumber'];
			
			$hgHgId = $resultrow['propertycode2'];
			$hgResId = $resultrow['resid'];
			
			$actualcomm = $fields[26];
			

			if(($actualcomm < $estimated) && (  getstatuscode($fields[15]) === "P")){
				
				if(($actualcomm <= 0)){
			
					$commstatus = "Z";
				}else{
					$commstatus = "PL";
					
				}
				
				
			}else if(($actualcomm > $estimated) && (  getstatuscode($fields[15]) === "P")){
				$commstatus = "PM";
			}else if($actualcomm === $estimated){
				$commstatus = "P";
			}else{
			
		$commstatus = getstatuscode($fields[15]);
			
			}
		

		
		
		$thisstatus = trim(strtoupper($fields[15]));
		
		if($useHgConf){
			$trimmed_confirmation_id = $hgConfId;
		}else{
			$trimmed_confirmation_id = $hgConfId;
		}
		
		$nowtime = date("Y-m-d H:i:s");
		$thisamount = trim($fields[26]);
		$notes = $notes. ' '.$og_trimmed_confirmation_id;

		$sql = "INSERT INTO `collections` (  `confirmation_id` ,  `status` ,  `amount` , `created` , `updated`, `notes`, `hg_id`, `resid`) VALUES( '$trimmed_confirmation_id' ,  '$commstatus' ,  '$thisamount' , '$nowtime' , '$nowtime' , '$notes - ** Matched on Name', '$hgHgId', '$hgResId'  ) "; 
		echo "$sql<br>\n";

		
		
		
		$sqlDup2 = "SELECT * from collections WHERE confirmation_id = \"$trimmed_confirmation_id\""; 
		$resultDup2 = mysql_query($sqlDup2, $loc_conn);
		$nrowsDup2 = mysql_num_rows($resultDup2);

    	
	if($nrowsDup2 > 0){
	
			$duprow2 = mysql_fetch_assoc($resultDup2);
			
			
			$paidamount2 = $duprow2['amount'];
	
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - already exists in collections database.";
		$thiserror = "FAIL_DUPLICATE_NAME_MATCH - $paidamount2";
		$failct++;
	}else{
			// INSERT QUERY ********
		 mysql_query($sql,$loc_conn) or die('Failed '.$sql.' :'. mysql_error()); 
			
			
		//echo "Added row.<br />"; 
		//echo "<a href='list.php'>Back To Listing</a>"; 
		
		$success[] = "Confirmation #: $trimmed_confirmation_id - successfully added!";
					$thiserror = "SUCCESS_NAME_MATCH";


			
		//echo "Confirmation #: $trimmed_confirmation_id - successfully added!<br>";
		$successct++;
		}
		
		
		}else{
		# No matches at all
		//echo "<span style='color:red;'>This reservation does not exist</span><br><br>"; 
		
		$errors[] = "Fail on confirmation #: $trimmed_confirmation_id - not in reservations table";
		
		if(trim($fields[26]) < 0){
		$thiserror = "FAIL_NEGATIVE";
		}else{
		$thiserror = "FAIL_NO_MATCH";
		}
		$failct++;

		//echo "Fail on confirmation #: $trimmed_confirmation_id - not in reservations table<br>";
		
		$commstatus = getstatuscode($fields[15]);
		
		if($commstatus=== 'P' && (trim($fields[26]) <= 0)){
		

			
					$commstatus = "Z";

		}
		
		}
	
	
	}

    	


if(strstr($thiserror,'FAIL')){

//GET HOTEL INFO
		$sqlInfo = "SELECT hg_hotels_dynamic.name, TP_HOTELS_NEW.phone
FROM hg_hotels_dynamic INNER JOIN TP_HOTELS_NEW ON hg_hotels_dynamic.tp_id = TP_HOTELS_NEW.id WHERE hg_hotels_dynamic.tp_id = \"".trim($fields[2]).trim($fields[7])."\""; 
		


		$resultInfo = mysql_query($sqlInfo, $remote_conn);
		$nrowsInfo = mysql_num_rows($resultInfo);
		
		
		$name = '';
		$phone = '';
		if ($nrowsInfo >0){
		
		

			$rowInfo = mysql_fetch_assoc($resultInfo);

			$name = $rowInfo['name'];
			$phone = $rowInfo['phone'];
			
		}








// IF NOT ABLE, ADD TO REPORT

$probcsv .= "P,"; # P for Pegasus - a constant in all rows - Put P in Notes field too
$probcsv .= specialtrim($fields[4]).","; #period ending date - a constant in all rows
$probcsv .= "$thiserror,"; #error
$probcsv .= specialtrim($trimmed_confirmation_id) .","; #res confirmation ID
$probcsv .= $commstatus .","; #status code - see below for status code conversion table
$probcsv .= trim($fields[26]).","; #net commissions due
$probcsv .= specialtrim($fields[14]) .","; #guest first name
$probcsv .= specialtrim($fields[13]) .","; #guest last name
$probcsv .= specialtrim($fields[17]) .","; #check-in date
$probcsv .= specialtrim($fields[18]) .","; #check-out date
$probcsv .= specialtrim($fields[19]) .","; #number of nights
$probcsv .= specialtrim($fields[20]) .","; #number of rooms
$probcsv .= specialtrim($fields[2]) .","; #chain code
$probcsv .= specialtrim($fields[2]. ";". $fields[7]) .","; #chain code;hotelID - WS hotel ID
$probcsv .= specialtrim($fields[31]) .","; #hotel name
$probcsv .= "$name,"; #HG name
$probcsv .= specialtrim($fields[38]) .","; #hotel phone number
$probcsv .= "$phone,"; #WS hotel phone number - from HG db
$probcsv .= specialtrim($fields[32]) .","; #hotel street address
$probcsv .= specialtrim($fields[34]) .","; #hotel city
$probcsv .= specialtrim($fields[35]) .","; #hotel state or province
$probcsv .= specialtrim($fields[36]) .","; #hotel country
$probcsv .= specialtrim($fields[37]) ."\n"; #hotel postal code


}else{


$goodcsv .= "P,"; # P for Pegasus - a constant in all rows - Put P in Notes field too
$goodcsv .= specialtrim($fields[4]).","; #period ending date - a constant in all rows
$goodcsv .= "$thiserror,"; #error
$goodcsv .= $trimmed_confirmation_id .","; #res confirmation ID
$goodcsv .= $commstatus .","; #status code - see below for status code conversion table
$goodcsv .= trim($fields[26]).","; #net commissions due
$goodcsv .= specialtrim($fields[14]) .","; #guest first name
$goodcsv .= specialtrim($fields[13]) .","; #guest last name
$goodcsv .= specialtrim($fields[17]) .","; #check-in date
$goodcsv .= specialtrim($fields[18]) .","; #check-out date
$goodcsv .= specialtrim($fields[19]) .","; #number of nights
$goodcsv .= specialtrim($fields[20]) .","; #number of rooms
$goodcsv .= specialtrim($fields[2]) .","; #chain code
$goodcsv .= specialtrim($fields[2]. ";". $fields[7]) .","; #chain code;hotelID - WS hotel ID
$goodcsv .= specialtrim($fields[31]) .","; #hotel name
$goodcsv .= "$name,"; #HG name
$goodcsv .= specialtrim($fields[38]) .","; #hotel phone number
$goodcsv .= "$phone,"; #WS hotel phone number - from HG db
$goodcsv .= specialtrim($fields[32]) .","; #hotel street address
$goodcsv .= specialtrim($fields[34]) .","; #hotel city
$goodcsv .= specialtrim($fields[35]) .","; #hotel state or province
$goodcsv .= specialtrim($fields[36]) .","; #hotel country
$goodcsv .= specialtrim($fields[37]) ."\n"; #hotel postal code



}

    
    }
}



fclose($fp) or die("can't close file");


echo "<br><br>";
echo "Imported: $successct records<br>";
echo "Failed: $failct records<br>";
echo "<pre>\n\n$probcsv\n</pre>";

//echo "<pre>\n\n$goodcsv\n</pre>";





$file_handle = fopen('/httpdocs/pegasus/exception-reports/'.$year.'/'.$uploadedFileName.'_exceptions.csv', 'w');
fwrite($file_handle, $probcsv);
fclose($file_handle);
// Read and write for owner, read for everybody else
chmod('/httpdocs/pegasus/exception-reports/'.$year.'/'.$uploadedFileName.'_exceptions.csv', 0666);


$file_handle2 = fopen('/httpdocs/pegasus/success-reports/'.$uploadedFileName.'_success.csv', 'w');
fwrite($file_handle2, $goodcsv);
fclose($file_handle2);





	  
		  
		  
		  
		  
		}
		
		
		
	  }
	else
	  {
	  echo "Invalid file";
	  print_r($_FILES);
	  }
  
  
  }
?>