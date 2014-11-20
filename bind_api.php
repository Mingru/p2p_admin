<?php
require_once ('connectvars.php');
function AddBind($aid, $did) {
	if (! empty ( $aid ) && ! empty ( $did )) {
		$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
		
		$query = "SELECT * FROM bind WHERE aid = '$aid' AND did = '$did'";
		$data = mysqli_query ( $dbc, $query ) or die ( 'Error to qurey database' );
		if (mysqli_num_rows ( $data ) == 0) {
			$query = "INSERT INTO bind (aid, did) VALUES ('$aid', '$did')";
			mysqli_query ( $dbc, $query ) or die ( 'Error to qurey database' );
		} else {
			mysqli_close ( $dbc );
			exit ( 'The account and device already binded.' );
		}
	}
}
function DelBind($aid, $did) {
	if (! empty ( $aid ) && ! empty ( $did )) {
		$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
		
		$query = "SELECT * FROM bind WHERE aid = '$aid' AND did = '$did'";
		$data = mysqli_query ( $dbc, $query ) or die ( 'Error to qurey database' );
		if (mysqli_num_rows ( $data ) == 1) {
			$row = mysqli_fetch_array ( $data );
			$id = $row ['id'];
			$query = "DELETE FROM bind WHERE id = '$id' LIMIT 1";
			mysqli_query ( $dbc, $query ) or die ( 'Error to qurey database' );
		} else {
			mysqli_close ( $dbc );
			exit ( 'The account and device already binded.' );
		}
	}
}
function ListBind($keyword, $column) {
	$dbc = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Error to connect MySQL datadase' );
	
	$query = "SELECT * FROM bind";
	if (! empty ( $keyword ) && ! empty ( $column )) {
		$query .= " WHERE $column = '$keyword'";
	}
	
	switch ($sort) {
		case 1 :
			$query .= " ORDER BY aid";
			break;
		case 2 :
			$query .= " ORDER BY aid DESC";
			break;
		case 3 :
			$query .= " ORDER BY did";
			break;
		case 4 :
			$query .= " ORDER BY did DESC";
			break;
		default :
			$query .= " ORDER BY aid";
			break;
	}
	
	$data = mysqli_query ( $dbc, $query );
	if (($row_cnt = mysqli_num_rows ( $data )) > 0) {
		$result = array ();
		for($i = 0; $i < $row_cnt; $i ++) {
			$row = mysqli_fetch_array ( $data );
			$result [$i] = array (
					$row ['aid'],
					$row ['did'] 
			);
		}
		return $result;
	}
}
function UpdateBind() {
}
function SortBind() {
}
?>