<?php
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

chdir(dirname(__FILE__));
include_once('centralDB.php');

// Default flags needed for logic. 
$sql = "SELECT * FROM ";
$users = 'N';
$sessions = 'N';
$plays = 'N';
$scouting_reports = 'N';
$search_field_parameters = array();

// Pull dowm the form data submitted. 
$formData = mysqli_escape_string($dbConnection, $_REQUEST['formData']);
$formElements = explode("&", $formData);

// Loop through each form element passed down.
foreach ($formElements as $single_element) {

	// Break apart the form data to see if there is a value provided. 
	$formElementValues = explode("=", $single_element);
	
	// Only add conditions to the query if there is a value passed in. 
	if($formElementValues[1] != ''){
		
		$current_value = $formElementValues[1];
		
		switch($formElementValues[0]){
			case 'selectSport':

				if($current_value == 'Baseball') { $current_value = '0'; }
				if($current_value == 'Softball') { $current_value = '1'; }

				array_push($search_field_parameters,"GameType = '$current_value'");
				$users = 'Y';
				break;
			case 'selectState':
				array_push($search_field_parameters,"State = '$current_value'");
				$facilities = 'Y';
				break;
			case 'selectHeight':
				array_push($search_field_parameters,"Height = '$current_value'");
				$users = 'Y';
				break;
			case 'selectGraduation':
				array_push($search_field_parameters,"GraduationYear = '$current_value'");
				$users = 'Y';
				break;
			case 'selectPosition':

				if($current_value == 'C') { $current_value = '2'; }
				if($current_value == '1B') { $current_value = '3'; }
				if($current_value == '2B') { $current_value = '4'; }
				if($current_value == 'SS') { $current_value = '6'; }
				if($current_value == '3B') { $current_value = '5'; }
				if($current_value == 'OF') { $current_value = '7'; }
				if($current_value == 'P') { $current_value = '1'; }

				array_push($search_field_parameters,"Position = '$current_value'");
				$users = 'Y';
				break;
			case 'selectBats':
			
				if($current_value == 'L') { $current_value = '2'; }
				if($current_value == 'R') { $current_value = '1'; }
				if($current_value == 'S') { $current_value = '3'; }
				
				array_push($search_field_parameters,"Bats = '$current_value'");
				$users = 'Y';
				break;
			case 'selectThrows':

				if($current_value == 'L') { $current_value = '2'; }
				if($current_value == 'R') { $current_value = '1'; }

				array_push($search_field_parameters,"Throws = '$current_value'");
				$users = 'Y';
				break;				
		}
	}
}

// ********************************************************************************************
// Identify all the tables that will be needed for this query (based on flags from the fields).
// ********************************************************************************************

$tables_pulled_from = array();

if($users == 'Y'){
	array_push($tables_pulled_from,"Users");
}

$from_statement = implode(",", $tables_pulled_from);
$sql .= $from_statement;

// *******************************************
// Identify all the joining conditions needed.
// *******************************************

$join_statements = array();

if($users == 'Y' && $facilities == 'Y'){
	array_push($join_statements,"Users.UId = Facilities.UId AND Users.SId = Facilities.SId AND Users.FacilityId = Facilities.FacilityId");
}

$join_statement = implode(" AND ", $join_statements);
$sql .= " WHERE ".$join_statement;

// *************************************************
// Append the search parameters to the search query.
// ************************************************* 

$where_statement = implode(" AND ", $search_field_parameters);

// If there are no joins for this query, need to add the WHERE clause. 
if(sizeof($join_statements) == 0){
	$sql .= " WHERE ".$where_statement;
}
else{
	// Otherwise just append the additional WHERE conditions. 
	$sql .= " AND ".$where_statement;
}

$sql .= " LIMIT 250";

//echo($sql);
$result = mysqli_query($dbConnection,$sql);
$overall_results = array();

// Loop through and push out their subscription dates the appropriate amount of time. 
while($row = mysqli_fetch_assoc($result)){
		// Push the values to the results array. 
		$overall_results[] = $row;
}

exit(json_encode($overall_results));
//header('Content-Type: application/json');
//echo json_encode($overall_results);
?>