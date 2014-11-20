<?php
$page_title = 'Add Device';
require_once ('header.php');
require_once ('menu.php');
require_once ('device_api.php');

if (isset ( $_GET ['action'] )) {
	if ($_GET ['action'] == 'edit') {
		$page_title = 'Edit Device';
		$account = test_input ( $_GET ['did'] );
		$status = $_GET ['status'];
	}
}

if (isset ( $_POST ['submit'] )) {
	$didErr = $statusErr = "";
	$err_flag = 0;
	
	if (empty ( $_POST ['did'] )) {
		$didErr = "DID required";
		$err_flag = 1;
	} else {
		$did = test_input ( $_POST ['did'] );
		$result = array ();
		$result = ListDevice ( $did, did, 1 );
		if (count ( $result ) != 0) {
			$accountErr = "This device has been registered";
			$err_flag = 1;
		}
		$fdid = $did . generateRandomString ( 10 );
	}
	
	$status = $_POST ['status'];
	
	if ($err_flag == 0) {
		AddDevice ( $fdid, $status );
		// header( 'Location: http://localhost/p2p/account.php' ) ;
	}
}
function test_input($data) {
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars ( $data );
	return $data;
}
function generateRandomString($length) {
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for($i = 0; $i < $length; $i ++) {
		$randomString .= $characters [rand ( 0, strlen ( $characters ) - 1 )];
	}
	return $randomString;
}
?>
<div class=main>
    <?php
				
echo '<h2>' . $page_title . '</h2>';
				if ((isset ( $_POST ['submit'] )) && ($err_flag == 0)) {
					$statusStr = ($status == 1) ? 'Activate' : 'Deactivate';
					echo '<p><em>Added: </em>' . $fdid . ' --- ' . $statusStr . '</p>';
				}
				?>
    <form enctype="multipart/form-data" method="post"
		action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" cellspacing="2" cellpadding="0">
			<tr>
				<td align="right" valign="middle">DID:</td>
				<td align="left" valign="middle"><input type="text" name="did"
					size="18" value="<?php if( !empty($account)) echo $account;?>" /> <span
					class="error"><?php echo $didErr;?></span></td>
			</tr>

			<tr>
				<td align="right" valign="middle">Status:</td>
				<td><input type="radio" name="status"
					<?php if ( (isset($status) && $status == 1) || (!isset($status))) echo "checked"; ?>
					value=1> <span>Activate</span> <input type="radio" name="status"
					<?php if ( isset($status) && $status == 2) echo "checked"; ?>
					value=0> <span>Deactivate</span> <span class="error"><?php echo $statusErr;?></span>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right" valign="middle"><input type="reset"
					value="Cancel" />
               <?php if ($_GET['action'] == 'edit') {?>
                   <input type="submit" name="update" value="Save" />
               <?php } else {?>
                   <input type="submit" name="submit" value="Save" />
               <?php }?>    
               </td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>
