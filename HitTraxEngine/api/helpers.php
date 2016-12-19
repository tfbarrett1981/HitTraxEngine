<?php
// Helping Functions 2013 MKProgramming

// cleanData - return escaped data based on name
function cleanData($n, $db){
	return(mysqli_real_escape_string($db, $n ));
}

// pData - just shorten the get method
function pData($n){
	if(!empty($_POST)) $data = $_POST[$n];
	else $data = $_GET[$n];
	return $data;
}


// check to see if there is a user that exists with the password / email combination

function vd_db_user_check($email, $password){
	include('centralDB.php');

	$sql = "SELECT `MasterID`, `Role` FROM `Users` WHERE Email='" . mysqli_real_escape_string($dbConnection, $email) ."' AND Hash='" . mysqli_real_escape_string($dbConnection, md5($password)) ."'";
	
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	return mysqli_fetch_assoc($result);				
	
}

//check with a hash
function vd_db_user_check_md5($email, $password){
	include('centralDB.php');

	$sql = "SELECT `MasterID`, `Role` FROM `Users` WHERE Email='" . mysqli_real_escape_string($dbConnection, $email) ."' AND Hash='" . mysqli_real_escape_string($dbConnection, $password) ."'";
	
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	return mysqli_fetch_assoc($result);				
}

// get just the user id from master key
// this will basically separate the first part of MasterID 
// $mid = 1234:12 this function will return just 1234
function vd_db_get_user_id($mid){
	return substr($mid, 0, strpos($mid, ':'));
}

// return the firstname / lastname in a nice format from central db
function vd_db_get_user_nicename($wpuser){
	$uid = $wpuser->ID;
	$mid = get_user_meta( $uid, 'MasterID', true );

	//connect to db now
	include('centralDB.php');

	$sql = "SELECT `FirstName`, `LastName` FROM `Users` WHERE MasterID='" . mysqli_real_escape_string($dbConnection, $mid) . "' LIMIT 1";
	
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else $row = mysqli_fetch_assoc($result);

	return 'Hello there, ' . $row['FirstName'] . ' ' . $row['LastName'];
}

// check if the user is a paid subscriber, otherwise return false
function vd_db_is_subscriber($wpuser){
	$uid = $wpuser->ID;
	$mid = get_user_meta( $uid, 'MasterID', true );

	//connect to db now
	include('centralDB.php');

	$sql = "SELECT `Subscription`, `SubscriptionType` FROM `Users` WHERE MasterID='" . mysqli_real_escape_string($dbConnection, $mid) . "' LIMIT 1";

	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else {
		$row = mysqli_fetch_assoc($result);
		if(is_null($row['Subscription']) || $row['Subscription'] === '0000-00-00 00:00:00' ) return false; 

		// check to see if the subscription is current based on the date. IE: If it's past that date, connect to firstdata and see if it's still current

		if(!vd_is_current($mid, $row['Subscription'], $row['SubscriptionType'])) return false;
		return $row['Subscription'];
	}
}

// check if the user is a paid subscriber, otherwise return false
function vd_db_is_subscriberLevel($wpuser){
	$uid = $wpuser->ID;
	$mid = get_user_meta( $uid, 'MasterID', true );

	//connect to db now
	include('centralDB.php');

	$sql = "SELECT `Subscription`, `SubscriptionType` , `SubscriptionLevel` FROM `Users` WHERE MasterID='" . mysqli_real_escape_string($dbConnection, $mid) . "' LIMIT 1";

	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else {
		$row = mysqli_fetch_assoc($result);
		if(is_null($row['Subscription']) || $row['Subscription'] === '0000-00-00 00:00:00' ) return false; 

		// check to see if the subscription is current based on the date. IE: If it's past that date, connect to firstdata and see if it's still current

		if(!vd_is_current($mid, $row['Subscription'], $row['SubscriptionType'])) return false;
		return $row['SubscriptionLevel'];
	}
}

function vd_is_current($mid, $endDate, $subType){

	//check date first
	$today = date("Y-m-d H:i:s");
	if($endDate > $today) return true; // it's not expired yet so let's just return true and keep going
	
	$username = 'michaeldonfranscesco';
	$password = 'HitTrax23';

    $ch = curl_init();
    // change the query based on type
    if($subType == 1) curl_setopt($ch, CURLOPT_URL,"https://api.globalgatewaye4.firstdata.com/transaction/search?status=A&start_date=" . Date('Y-m-d', strtotime('-1 month'))); 

    else if($subType == 2) curl_setopt($ch, CURLOPT_URL,"https://api.globalgatewaye4.firstdata.com/transaction/search?status=A&start_date=" . Date('Y-m-d', strtotime('-1 year'))); 

   
	//curl_setopt($ch, CURLOPT_POST, 1);
	//authenticate
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_PORT, 443);

    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	
	$server_output = curl_exec ($ch);

    curl_close ($ch);

    if($server_output == 'Unauthorized' ) return false;
    if($server_output == '') return false;
	
	$lines = explode ("\n", $server_output);
	$data = array();

	foreach($lines as $line) $data[] = str_getcsv($line);

	foreach($data as $row){
		//echo $row[0] . ' ' . $row[1] . ' ' . $row[5] . ' '  . $row[12] . ' ' . $row[9] . ' ' . $row[7] . "<br />";

		if($row[12] == $mid && $row[7] == 'Approved'){ // check if Reference_3 is the same as the transacation
			// basically we pass the user id with the transaction so we have a unique identifier to search for later !
			// we're looking for an approved transaction within the last 4 weeks (monthly) 
			// if we find one, let's update the user table with next months date
			
			//connect to db now
			include('centralDB.php');

			if($subType == 1){
				$theTime = strtotime('+1 month', strtotime($endDate));
				$newEndDate = date("Y-m-d H:i:s", $theTime);
			}

			else if ($subType == 2){
				$theTime = strtotime('+1 year', strtotime($endDate));
				$newEndDate = date("Y-m-d H:i:s", $theTime);
			}

			$sql = 'UPDATE `Users` SET `Subscription`="' . $newEndDate . '" WHERE `MasterID`="' . mysqli_real_escape_string($dbConnection, $mid) . '"';

			//try to execute mysql statement
			$result = mysqli_query($dbConnection,$sql);
			
			# if($result === false) { /* failed to update but they're still current */ } 
			# else { /* updated with the new month since they're current */ }

			return true;
		}

		else if($row[12] == $mid && $row[7] == 'Declined') return false;
		else if($row[12] == $mid && $row[7] == 'Error') return false;
	}
	return false;
}



// return the user info for specified user
function vd_db_get_user_data($wpuser){
	$uid = $wpuser->ID;
	$mid = get_user_meta( $uid, 'MasterID', true );

	//connect to db now
	include('centralDB.php');

	$sql = "SELECT * FROM `Users` WHERE MasterID='" . mysqli_real_escape_string($dbConnection, $mid) . "' LIMIT 1";
	
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else $row = mysqli_fetch_assoc($result);

	return $row;
}

// get the last session data so we can display it on the website
// access the centralDB and return an array of information to use :)
function vd_db_get_last_session($uid, $unitId){
	include('centralDB.php');

	$sql = "SELECT * FROM `Session` WHERE UsId='" . mysqli_real_escape_string($dbConnection, $uid) . "' AND UsUId='" . mysqli_real_escape_string($dbConnection, $unitId) . "' AND `Session`.`Actv`=1 ORDER BY TS DESC LIMIT 1";
	
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else $row = mysqli_fetch_assoc($result);
	
	return $row;
}

// convert CompanyId to the facility name
function vd_facility_converted($cid){
	include('centralDB.php');
	$sql = 'SELECT `CompanyName` FROM `Facilities` WHERE `CompanyId` = ' . $cid . ' LIMIT 1';

	// Try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else $row = mysqli_fetch_assoc($result);
		
	return $row['CompanyName'];
}

// convert CompanyId to entire facility row
function vd_entire_facility_converted($cid){
	include('centralDB.php');
	$sql = 'SELECT * FROM `Facilities` WHERE `CompanyId` = ' . $cid . ' LIMIT 1';

	// Try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	if($result === false) return false;
	else $row = mysqli_fetch_assoc($result);
	return $row;
}

// convert stadium number to a string 
function vd_stadium_converted($n){
	$name = '';

	switch($n){
		case 0 : $name = 'Boston'; break;
		case 2 : $name = 'Williamsport'; break;
		case 3 : $name = 'New York'; break;
		case 4 : $name = 'Baltimore'; break;
		case 5 : $name = 'St. Louis'; break;
		case 6 : $name = 'Arizona'; break;
		case 7 : $name = 'Minnesota'; break;
		case 8 : $name = 'Philadelphia'; break;
		case 9 : $name = 'Pittsburgh'; break;
		case 10 : $name = 'Arlington'; break;
		case 11 : $name = 'Penn Park'; break;
		case 12 : $name = 'Houston'; break;
		case 13 : $name = 'Los Angeles'; break;
	}

	return $name;
}

// skill query

function skill_query($type, $skill){

	$year = 'TIMESTAMPDIFF(YEAR, `Users`.`BirthDate`, NOW())';
	$skill = (int) preg_replace("~[^0-9]~", "", $skill);
	$query = $year;

	switch ($skill) {
		# --U Age Groups
		case 8:		$query .= ' < 9'; break;
		case 10:	$query .= ' >= 9 AND ' . $year . ' < 11'; break;
		case 12:	$query .= ' >= 11 AND ' . $year . ' < 13'; break;
		case 13:	$query .= ' = 13'; break;
		case 15:	$query .= ' >= 14 AND ' . $year . ' < 16'; break;
		case 16:	$query .= ' >= 16 AND ' . $year . ' < 17'; break;
		case 18:	$query .= ' >= 17 AND ' . $year . ' < 19'; break;
		case 20:	$query .= ' >= 19 AND ' . $year . ' < 21'; break;
		case 23:	$query .= ' >= 21 AND ' . $year . ' < 24'; break;
		# --+ Age Groups
		case 24: $query .= ' >= 24 AND ' . $year . ' < 30'; break;
		case 30: $query .= ' >= 30 AND ' . $year . ' < 40'; break;
		case 40: $query .= ' >= 40'; break;
	}

	return $query;	
}



// convert skill level number to string
function vd_skill_type_converted($skill, $type){

	$info = '';
	
	switch($type){
	
		case 0 :
		
			switch($skill){
				case 0 : 
					$info = array('skill' => 'Little League', 'game' => 'Baseball');
					break;
				case 1 :
					$info = array('skill' => 'U15', 'game' => 'Baseball');
					break;
					
				case 2 :
					$info = array('skill' => 'High School', 'game' => 'Baseball');
					break;
					
				case 3 :
					$info = array('skill' => 'College', 'game' => 'Baseball');
					break;
					
				case 4 :
					$info = array('skill' => 'Professional', 'game' => 'Baseball');
					break;
					
				case 5 :
					$info = array('skill' => 'U13', 'game' => 'Baseball');
					break;
			}
			break;
		
		case 1 :
			
			switch($skill){
			
				case 0 : 
					$info = array('skill' => '12U', 'game' => 'Softball');
					break;
				case 1 :
					$info = array('skill' => 'High School', 'game' => 'Softball');
					break;
					
				case 2 :
					$info = array('skill' => 'College', 'game' => 'Softball');
					break;
					
				case 3 :
					$info = array('skill' => 'Mens', 'game' => 'Softball');
					break;

			}
			break;
	}
	
	return $info;
}

// Convert meters per second to miles per hour
function vd_meters_to_mph( $meters ){
	//formula ? (25*1*60*60)/(1609*1*1)
	return round((floatval($meters)*1*60*60)/(1609*1*1));
} 

function vd_meters_to_feet($m){
	return round(floatval($m) * 3.2808);
}

// generate leaderboard
// return an array of stats from the database or cache (if already generated)  based off the game type (baseball) bracket (highschool) and variable (Max Exit Velocity)
function vd_db_get_hit_leaders($gameType, $skill, $rankVar, $site, $region){
	
	$siteQuery = '';
	if($site != '' && $site != undefined && $site != -1)
		$siteQuery = " `Users`.`SId`=" . $site ." AND ";
	else $site = 'all';

	// lets first check to see if a cache file exists based on variables
	
	$cHours = 1; // number of hours until it expires
	
	$location = getcwd() . '/cache/leaders/';
	$filename = 'site-' . $site . '_gt-' . $gameType . '_sl-' . $skill . '_rv-' . $rankVar . '_r-' . $region . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) ){
	
		// cached resource exisits, so lets just return the array without using the database ! 
		// and look at that, it's less than a few hours old
		
		//echo 'File Time: ' . date('m/d/Y h:m:s', filemtime($location . $filename));
		//echo '<br /> ' . $cHours .' Hours Ago: ' . date('m/d/Y h:m:s', strtotime('now -' . $cHours .' hours'));
		//echo 'Last Update: ' . date('m/d/Y h:m:s', filemtime($location . $filename));
		//echo '<br /><br />';
		
		// let's unserilze the results and return them
		
		return unserialize(file_get_contents($location . $filename));

	
	} else {
	
		// there isn't a cached version, so let's get the info from the central db
		
		include('centralDB.php');
		
		$daysago = date("Y-m-d H:i:s", strtotime(' -90 days', time()));

		$sql = "SELECT MAX(`Session`.`" . $rankVar . "`) AS " . $rankVar . ", `Users`.`FirstName`, `Users`.`LastName`, `Facilities`.`Country`, `Facilities`.`State`, `Facilities`.`Region`, `Facilities`.`CompanyName` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` INNER JOIN `Facilities` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Session`.`TS` > '" . $daysago . "' ";
    
		if ($region != 'All')
			$sql = $sql . " AND `Facilities`.`Region`='" . $region . "' ";
    
		$sql = $sql . " AND `Users`.`Active`=1 AND `Users`.`Disable`=0 AND `Users`.`Certified`=1 AND  " . skill_query($gameType, $skill) . " AND `Users`.`GameType`=" . $gameType . " AND ". $siteQuery . " `Session`.`Actv`=1 AND `Session`.`Type`<>2 AND `Session`.`Type`<>6 AND `Session`.`VB`=0 GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar ." DESC LIMIT 25";


		//		$sql = "SELECT MAX(`Session`.`" . $rankVar . "`) AS " . $rankVar . ", `Users`.`FirstName`, `Users`.`LastName`, `Facilities`.`Country`, `Facilities`.`State`, `Facilities`.`CompanyName` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` INNER JOIN `Facilities` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Users`.`Certified`=1 AND `Users`.`Disable`=0 AND  " . skill_query($gameType, $skill) . " AND `Session`.`Actv`=1 AND `Session`.`VB`=0 GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar ." DESC LIMIT 25";
			
		//try to execute mysql statement
		$result = mysqli_query($dbConnection,$sql);
	
		if($result === false){	
	
			return $sql;

			//couldn't find the database results - let's show the old cache data (if there is any)
			if(file_exists($location . $filename )) return unserialize(file_get_contents($location . $filename));
			else return false;
		
		} else {
	
			$results = array();
			
			while($row = mysqli_fetch_assoc($result))
				$results[] = $row;
			
			if(mysqli_num_rows($result) > 0)
				// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
				file_put_contents($location . $filename, serialize($results));
			
			return $results; 
		}
	}
}




// generate leaderboard
// return an array of stats from the database or cache (if already generated)  based off the game type (baseball) bracket (highschool) and variable (Max Exit Velocity)
function vd_db_get_hit_leaders_all($gameType, $skill, $rankVar, $site, $region){
	
	$siteQuery = '';
	if($site != '' && $site != undefined && $site != -1)
		$siteQuery = " `Users`.`SId`=" . $site ." AND ";
	else $site = 'all';

	// lets first check to see if a cache file exists based on variables
	
	$cHours = 1; // number of hours until it expires
	
	$location = getcwd() . '/cache/leaders/';
	$filename = 'site-' . $site . '_all_gt-' . $gameType . '_sl-' . $skill . '_rv-' . $rankVar . '_r-' . $region . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) ){
	
		// cached resource exisits, so lets just return the array without using the database ! 
		// and look at that, it's less than a few hours old
		
		//echo 'File Time: ' . date('m/d/Y h:m:s', filemtime($location . $filename));
		//echo '<br /> ' . $cHours .' Hours Ago: ' . date('m/d/Y h:m:s', strtotime('now -' . $cHours .' hours'));
		//echo 'Last Update: ' . date('m/d/Y h:m:s', filemtime($location . $filename));
		//echo '<br /><br />';
		
		// let's unserilze the results and return them
		
		return unserialize(file_get_contents($location . $filename));

	
	} else {
	
		// there isn't a cached version, so let's get the info from the central db
		
		include('centralDB.php');
		
		$daysago = date("Y-m-d H:i:s", strtotime(' -90 days', time()));

		$sql = "SELECT MAX(`Session`.`" . $rankVar . "`) AS " . $rankVar . ", `Users`.`Id`, `Users`.`UId`, `Users`.`SId`, `Users`.`FirstName`, `Users`.`LastName`, `Facilities`.`Country`, `Facilities`.`State`, `Facilities`.`Region`, `Facilities`.`CompanyName` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` INNER JOIN `Facilities` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Session`.`TS` > '" . $daysago . "' ";

		if ($region != 'All')
			$sql = $sql . " AND `Facilities`.`Region`='" . $region . "' ";

		$sql = $sql . " AND `Users`.`Active`=1 AND `Users`.`Disable`=0 AND `Users`.`Certified`=1 AND  " . skill_query($gameType, $skill) . " AND `Users`.`GameType`=" . $gameType . " AND ". $siteQuery . " `Session`.`Actv`=1 AND `Session`.`Type`<>2 AND `Session`.`Type`<>6 AND `Session`.`VB`=0 GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar ." DESC";
			
		//try to execute mysql statement
		$result = mysqli_query($dbConnection,$sql);
	
		if($result === false){	
	
			return $sql;

			//couldn't find the database results - let's show the old cache data (if there is any)
			if(file_exists($location . $filename ))
				return unserialize(file_get_contents($location . $filename));
			else return false;
		
		} else {
	
			$results = array();
			
			while($row = mysqli_fetch_assoc($result))
				$results[] = $row;
			
			if(mysqli_num_rows($result) > 0)
				// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
				file_put_contents($location . $filename, serialize($results));
			
			return $results; 
			
		}
	}
}

// Generate pitch leaderboard
// Return an array of stats from the database or cache (if already generated)  based off the game type (baseball) bracket (highschool) and variable (pitch)
function vd_db_get_pitch_leaders($gameType, $skill, $rankVar, $site, $region){
	
	$siteQuery = '';
	if($site != '' && $site != undefined && $site != -1)
		$siteQuery = " `Users`.`SId`=" . $site ." AND ";
	else $site = 'all';

	// lets first check to see if a cache file exists based on variables
	
	$cHours = 1; // number of hours until it expires
	
	$location = getcwd() . '/cache/leaders/';
	$filename = 'site-' . $site . '_gt-' . $gameType . '_sl-' . $skill . '_rv-' . $rankVar . '_r-' . $region . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) ){
	
		// cached resource exisits, so lets just return the array without using the database ! 
		// and look at that, it's less than a few hours old
		
		// let's unserilze the results and return them
		
		return unserialize(file_get_contents($location . $filename));

	} else {
	
		// there isn't a cached version, so let's get the info from the central db
		
		include('centralDB.php');
		
		$daysago = date("Y-m-d H:i:s", strtotime('-90 days', time()));

		/* have to do something special for percent strikes */
		if($rankVar == 'SP') {
			$sql = "SELECT MAX(`Session`.`Strk` / `Session`.`PC` * 100) AS `SP`, `Users`.`FirstName`, `Users`.`LastName`, `Facilities`.`Country`, `Facilities`.`State`, `Facilities`.`Region`, `Facilities`.`CompanyName` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` INNER JOIN `Facilities` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Session`.`TS` > '" . $daysago . "' AND `Users`.`Active`=1 AND `Users`.`Disable`=0 ";

			if ($region != 'All')
				$sql = $sql . " AND `Facilities`.`Region`='" . $region . "' ";

			$sql = $sql . " AND `Users`.`Certified`=1 AND " . skill_query($gameType, $skill) . " AND `Users`.`GameType`=" . $gameType . " AND ". $siteQuery . "  `Session`.`Actv`=1 AND `Session`.`Type`=2 GROUP BY `Users`.`MasterId` ORDER BY `SP` DESC LIMIT 25";
		}
    
    	else {
			$sql = "SELECT MAX(`Session`.`" . $rankVar . "`) AS " . $rankVar . ", `Users`.`FirstName`, `Users`.`LastName`, `Facilities`.`Country`, `Facilities`.`State`, `Facilities`.`Region`, `Facilities`.`CompanyName` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` INNER JOIN `Facilities` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Session`.`TS` > '" . $daysago . "' AND `Users`.`Active`=1 AND `Users`.`Disable`=0 ";

			if ($region != 'All')
				$sql = $sql . " AND `Facilities`.`Region`='" . $region . "' ";

			$sql = $sql . "AND `Users`.`Certified`=1 AND  " . skill_query($gameType, $skill) . " AND `Users`.`GameType`=" . $gameType . " AND ". $siteQuery . "  `Session`.`Actv`=1 AND `Session`.`Type`=2 GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar ." DESC LIMIT 25";
		}
    
		//try to execute mysql statement
		$result = mysqli_query($dbConnection,$sql);
	
		if($result === false){	
	
			//couldn't find the database results - let's show the old cache data (if there is any)
			if(file_exists($location . $filename ))
				return unserialize(file_get_contents($location . $filename));
			else return false;
		
		} else {
	
			$results = array();
			
			while($row = mysqli_fetch_assoc($result))
				$results[] = $row;
						
			if(mysqli_num_rows($result) > 0)
				// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
				file_put_contents($location . $filename, serialize($results));

			return $results; 
			
		}
	}
}

// Generate the home page leaderboard (just exit ball velocity in mph)
function vd_db_get_home_leaders($rankVar){
	
	// Lets first check to see if a cache file exists based on variables
	$cHours = 1; // number of hours until it expires
	
	$location = getcwd() . '/api/cache/leaders/';
	$filename = 'home-' . $rankVar . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) ){
	
		// cached resource exisits, so lets just return the array without using the database ! 
		// and look at that, it's less than a few hours old
		
		// let's unserilze the results and return them
		
		return unserialize(file_get_contents($location . $filename));
	
	} else {
	
		// there isn't a cached version, so let's get the info from the central db
		
		include('centralDB.php');
		
		$daysago = date("Y-m-d H:i:s", strtotime('-90 days', time()));

		$bbsql = "SELECT MAX(`Session`.`" . $rankVar ."`) AS " . $rankVar . ", `Users`.`FirstName`, `Users`.`LastName`, `Users`.`GameType`, `Users`.`SkillLevel`, `Users`.`MasterID` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` WHERE `Session`.`TS` > '" . $daysago . "' AND `Session`.`Actv`=1 AND `Session`.`VB`=0 AND `Users`.`GameType` = 0 AND `Users`.`Active`=1 AND `Users`.`Disable`=0 AND `Users`.`Certified`=1 AND (`Users`.`SkillLevel` = 5 OR `Users`.`SkillLevel` = 3 OR `Users`.`SkillLevel` = 2) GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar . " DESC LIMIT 3";

		$sbsql = "SELECT MAX(`Session`.`" . $rankVar ."`) AS " . $rankVar . ", `Users`.`FirstName`, `Users`.`LastName`, `Users`.`GameType`, `Users`.`SkillLevel`, `Users`.`MasterID` FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` WHERE `Session`.`TS` > '" . $daysago . "' AND `Session`.`Actv`=1 AND `Session`.`VB`=0 AND `Users`.`GameType` = 1 AND `Users`.`Active`=1 AND `Users`.`Disable`=0 AND `Users`.`Certified`=1 AND (`Users`.`SkillLevel` = 2 OR `Users`.`SkillLevel` = 1) GROUP BY `Users`.`MasterId` ORDER BY " . $rankVar . " DESC LIMIT 2";

		//try to execute mysql statement
		$bbresult = mysqli_query($dbConnection,$bbsql);
		$sbresult = mysqli_query($dbConnection,$sbsql);

		//echo mysqli_error($dbConnection);
	
		if($bbresult === false && $sbresult === false){	

			//couldn't find the database results - let's show the old cache data (if there is any)
			if(file_exists($location . $filename ))
				return unserialize(file_get_contents($location . $filename));
			else return false;
		
		}else{
	
			$resultsArr = array();
			
			while($row = mysqli_fetch_assoc($bbresult))
				$resultsArr[] = $row;

			while($row = mysqli_fetch_assoc($sbresult))
				$resultsArr[] = $row;
						
			// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
			if(mysqli_num_rows($bbresult) > 0 || mysqli_num_rows($sbresult) > 0){
				file_put_contents($location . $filename, serialize($resultsArr));
			}
			return $resultsArr; 
			
		}
	}
}

// Generate the game display.
function vd_db_get_games($facilityID, $startDate, $endDate){
	
	// Lets first check to see if a cache file exists based on variables
	$cHours = 1; // number of hours until it expires
	$facilityID = 133;
	
	$location = getcwd() . '/api/cache/games/';
	$filename = 'game-' . $rankVar . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) ){
	
		// cached resource exisits, so lets just return the array without using the database ! 
		// and look at that, it's less than a few hours old
		
		// let's unserilze the results and return them
		
		return unserialize(file_get_contents($location . $filename));
	
	} else {
	
		// there isn't a cached version, so let's get the info from the central db
		
		include('centralDB.php');

		$game_sql = "select * from Games where CompanyId = '$facilityID' and State=2 and StartDate >= '$startDate' AND StartDate < '$endDate' ORDER BY  StartDate ASC";

		//try to execute mysql statement
		$game_result = mysqli_query($dbConnection,$game_sql);
	
		if($game_result === false){	
			//couldn't find the database results - let's show the old cache data (if there is any)
			if(file_exists($location . $filename ))
				return unserialize(file_get_contents($location . $filename));
			else return false;
		
		} else {
	
			$resultsArr = array();
			while($row = mysqli_fetch_assoc($game_result))
				$resultsArr[] = $row;
						
			// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
			if(mysqli_num_rows($game_result) > 0) file_put_contents($location . $filename, serialize($resultsArr));
			return $resultsArr; 
			
		}
	}
}

// Generate a specific homepage statistic from cache or central db
function vd_db_get_home_stat($n, $text='Y'){
	include('centralDB.php');
	$v = 0;
	//check cache
	$cHours = 3;
	// don't update all at once, too slow sometimes
	switch($n){
		case "pitches":	$cHours = 17; break;
		case "swings":	$cHours = 14; break;
		case "doubles":	$cHours = 18; break;
		case "on base":	$cHours = 23; break;
		case "users":	$cHours = 1; break;
	}

  $location = getcwd() . '/api/cache/stats/';
	$filename = $n . '.txt';
	
	if(file_exists($location . $filename ) && ( filemtime($location . $filename) > strtotime('now -' . $cHours .' hours') ) )
		$v = file_get_contents($location . $filename);

	else {	
		//we'll have to pull from the database because there was nothing in the cache (or it was too old)
    	// changed to count(*), and 2 queries instead of a union, much, much faster.
		$sql1 = '';
		$sql2 = '';

		switch($n){
			case "pitches" :
				$sql1 = "SELECT COUNT(*) FROM (SELECT PV FROM `AtBatPlays` WHERE `PV` > 0) as PV";
				$sql2 = "SELECT COUNT(*) FROM (SELECT PV FROM `Plays` WHERE `PV` > 0) as PV";
			break;
			case "swings" :
				$sql1 = "SELECT COUNT(*) FROM (SELECT Dist FROM `AtBatPlays` WHERE `Dist` > 0) as Dist";
				$sql2 = "SELECT COUNT(*) FROM (SELECT Dist FROM `Plays` WHERE `Dist` > 0) as Dist";
			break;
			case "doubles" :
				$sql1 = "SELECT COUNT(*) FROM (SELECT Res FROM `AtBatPlays` WHERE `Res` = 2) as Res";
				$sql2 = "SELECT COUNT(*) FROM (SELECT Res FROM `Plays` WHERE `Res` = 2) as Res";
			break;
			case "on base" :
				$sql1 = "SELECT COUNT(*) FROM (SELECT Res FROM `AtBatPlays` WHERE `Res` > 0) as Res";
				$sql2 = "SELECT COUNT(*) FROM (SELECT Res FROM `Plays` WHERE `Res` > 0) as Res";
			break;
			case "users" :
				$sql1 = "SELECT COUNT(*) FROM `Users`";
			break;
		}
		
		$result = mysqli_query($dbConnection,$sql1);
		
		if($result === false){	
			//error connecting, let's try to use the last cache value
			if(file_exists($location . $filename ))
				$v = file_get_contents($location . $filename);
			else $v = '0';
		} else {
   	
			$n1 = 0; $n2 = 0;
      
			if($row = mysqli_fetch_assoc($result)) $n1 = intval($row['COUNT(*)']);

			if ($sql2 != '') {
	    		$result = mysqli_query($dbConnection,$sql2);

		        if($result != false) {	
					if($row = mysqli_fetch_assoc($result))
						$n2 = intval($row['COUNT(*)']);
		        }
			}
			
			$v = number_format($n1 + $n2);
						
			// let's cache this so we don't have to run the query constantly, maybe every 3 hours or something		
			file_put_contents($location . $filename, $v);
		}
	}
	
	if($text == 'Y') return '<div class="fact-box">' . $v . '<br />Total ' . $n . '</div>';
	else return $v;
}

// Used for the color coding for the batting averages. 
function return_color_coding($current_value){
	if(floatval($current_value) == -1) return "novalue";

	else if(floatval($current_value) < .225) return "color1";

	else if(floatval($current_value) >= .225 && floatval($current_value) < .275) return "color2";

	else if(floatval($current_value) >= .275 && floatval($current_value) < .300) return "color0";

	else if(floatval($current_value) >= .300 && floatval($current_value) < .350) return "color3";

	else if(floatval($current_value) > .350) return "color4";
}

// Used for the standardizing the display over averages in the heat map. 
function return_clean_average($current_value){
	if($current_value == -1) return "N/A";
	else if(floatval($current_value) < 1) return ltrim(number_format($current_value, 3),"0");
	else if($current_value == 1) return "1.000";
}

/* facility functions */
/* return an array based on a row in the central datbase according to the site id associated with $mid */

function vd_get_facility_info($mid){

	include('centralDB.php');
			
	$sql = "SELECT `Facilities`.* FROM `Facilities` INNER JOIN `Users` ON `Facilities`.`SId` = `Users`.`SId` WHERE `Users`.`MasterID`='" . $mid . "' LIMIT 1";
						
	//try to execute mysql statement
	$result = mysqli_query($dbConnection,$sql);

	$results = array();
			
	while($row = mysqli_fetch_assoc($result)){
		$results[] = $row;
	}

	return $results;
}

/* facility stats */
//return various sets of information based on the SId
function vd_get_facility_stats($sid, $subEnd){
	// curring billing cycle is $subEnd - 1 month
	$curBillingStart = date("Y-m-d H:i:s", strtotime('-1 month', strtotime($subEnd)));
	$lastBillingStart = date("Y-m-d H:i:s", strtotime('-2 months', strtotime($subEnd)));

	$curBillingStart = date('Y-m-d', strtotime('first day of this month', time()));
	$curBillingEnd = date('Y-m-d', strtotime('last day of this month', time()));
	$lastBillingStart = date('Y-m-d', strtotime('-1 month', strtotime($curBillingStart)));
	$lastBillingEnd = date('Y-m-d', strtotime('-1 month', strtotime($curBillingEnd)));
  
	//GamesPlayed
	//Subscriptions
	//Active

	$data = array();

	include('centralDB.php');

	// GamesPlayed & GamesPlayedLast
	// get the # of games played durring current billing cycle and last

	$sql = "SELECT `Id` FROM `Games` WHERE `SId`='" . $sid . "' AND `State`=2 AND `StartDate`<='" . $curBillingEnd . "' AND `StartDate`>='" . $curBillingStart . "'";	
	$result = mysqli_query($dbConnection,$sql);
	$data['GamesPlayed'] = mysqli_num_rows($result);

	$sql = "SELECT `Id` FROM `Games` WHERE `SId`='" . $sid . "' AND `State`=2 AND `StartDate`<='" . $lastBillingEnd . "' AND `StartDate`>='" . $lastBillingStart . "'";	
	$result = mysqli_query($dbConnection,$sql);
	$data['GamesPlayedLast'] = mysqli_num_rows($result);

	//subscriptions

	$sql = "SELECT `Id` FROM `Users` WHERE `Role`=0 AND `SId`='" . $sid . "' AND `SubscriptionStart`<='" . $curBillingEnd . "' AND `SubscriptionStart`>='" . $curBillingStart . "'";	
	$result = mysqli_query($dbConnection,$sql);
	$data['Subscriptions'] = mysqli_num_rows($result);

	$sql = "SELECT `Id` FROM `Users` WHERE `Role`=0 AND `SId`='" . $sid . "' AND `SubscriptionStart`<='" . $lastBillingEnd . "' AND `SubscriptionStart`>='" . $lastBillingStart . "'";	
	$result = mysqli_query($dbConnection,$sql);
	$data['SubscriptionsLast'] = mysqli_num_rows($result);

	// active
	$sql = "SELECT MAX(`Session`.`TS`) AS theTS FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` WHERE `Session`.`SId`='" . $sid . "' AND `Session`.`TS`<='" . $curBillingEnd . "' AND `Session`.`TS`>='" . $curBillingStart . "' GROUP BY `Users`.`Id` ORDER BY `Session`.`TS` DESC";	
	$result = mysqli_query($dbConnection,$sql);
	$data['Active'] = mysqli_num_rows($result);

	$sql = "SELECT MAX(`Session`.`TS`) AS theTS FROM `Session` INNER JOIN `Users` ON CONCAT(`Session`.`UsId`, ':', `Session`.`UId`) = `Users`.`MasterId` WHERE `Session`.`SId`='" . $sid . "' AND `Session`.`TS`<='" . $lastBillingEnd . "' AND `Session`.`TS`>='" . $lastBillingStart . "' GROUP BY `Users`.`Id` ORDER BY `Session`.`TS` DESC";	
	$result = mysqli_query($dbConnection,$sql);
	$data['ActiveLast'] = mysqli_num_rows($result);

	return $data; 
}

// Change a data variable like MEV to max exit velocity
function vd_niceName($n){
	if($n == 'MEV') return 'Max Exit Velocity';
	else if($n == 'MD') return 'Max Distance';
	else if($n == 'MGD') return 'Max Distance';
	else if($n == 'MPV') return 'Max Pitch Velocity';
	else if($n == 'SP') return '% Strikes';
	else if($n == 'AEV') return 'Exit Velocity';
	else return $n;
}

?>