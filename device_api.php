<?php
require_once ('connectvars.php');
function AddDevice($did, $status) {
	if (! empty ( $did )) {
		$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
		
		$query = "SELECT * FROM users, device WHERE user = '$did' OR did = '$did'";
		$data = mysqli_query ( $dbc, $query ) or die ( 'Error to qurey database' );
		if (mysqli_num_rows ( $data ) == 0) {
			$query = "SELECT attr FROM configsavp";
			$data = mysqli_query ( $dbc, $query );
			$row = mysqli_fetch_array ( $data );
			$domain = $row ['attr'];
			
			$query = "INSERT INTO users (user, domain, realm)
    			VALUES ('$did', '$domain', '$domain')";
			mysqli_query ( $dbc, $query ) or die ( 'Error to query database' );
			
			$query = "INSERT INTO device (did, status, addDate) VALUES ('$did', '$status', NOW())";
			mysqli_query ( $dbc, $query ) or die ( 'Error to query database' );
		} else {
			mysqli_close ( $dbc );
			exit ( 'An account already exists for this username. Please use a different account.' );
		}
		mysqli_close ( $dbc );
	} else {
		mysqli_close ( $dbc );
		exit ( 'User Account and Password should be setting' );
	}
}
function DelDevice($did) {
	if (! empty ( $did )) {
		$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
		
		$query = "SELECT id FROM users WHERE user = '$did'";
		$data = mysqli_query ( $dbc, $query ) or die ( 'Error to query database' );
		if (mysqli_num_rows ( $data ) == 1) {
			$row = mysqli_fetch_array ( $data );
			$id = $row ['id'];
			$query = "DELETE FROM users WHERE id = $id LIMIT 1";
			mysqli_query ( $dbc, $query ) or die ( 'Error to query database' );
		}
		mysqli_close ( $dbc );
	}
}
function ListDevice($keyword, $column, $sort) {
	$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
	
	$query = "SELECT * FROM device";
	
	if ( (isset ( $keyword )) && (!empty ( $column ))) {
		if ( ($column == 'did') || ($column == 'status')) {
		    $query .= " WHERE $column LIKE '%$keyword%'";
		}
		if ( $column == 'addDate' || ($column == 'expireDate')) {
			$time = explode("&", $keyword);
			$query .= " WHERE DATE(createDate) BETWEEN '$time[0]' AND '$time[1]'";
		}
	}
	
	switch ($sort) {
		case 1 :
			$query .= " ORDER BY did";
			break;
		case 2 :
			$query .= " ORDER BY did DESC";
			break;
		case 3 :
			$query .= " ORDER BY addDate";
			break;
		case 4 :
			$query .= " ORDER BY addDate DESC";
			break;
		case 5 :
			$query .= " ORDER BY status";
			break;
		case 6 :
			$query .= " ORDER BY status DESC";
			break;
		default :
			$query .= " ORDER BY did";
			break;
	}
	
	$data = mysqli_query ( $dbc, $query );
	if (($row_cnt = mysqli_num_rows ( $data )) > 0) {
		$result = array ();
		for($i = 0; $i < $row_cnt; $i ++) {
			$row = mysqli_fetch_array ( $data );
			$result [$i] = array (
					$row ['did'],
					$row ['status'],
					$row ['addDate'],
					$row ['expireDate'] 
			);
		}
		return $result;
	}
}
function UpdateDevice() {
}

?>