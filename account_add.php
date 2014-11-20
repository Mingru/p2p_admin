<?php
$page_title = 'Add Account';
require_once ('header.php');
require_once ('menu.php');
require_once ('account_api.php');
require_once ('device_api.php');
require_once ('bind_api.php');

if (isset ( $_GET ['action'] )) {
	if ($_GET ['action'] == 'edit') {
		$page_title = 'Edit Account';
		$account = test_input ( $_GET ['account'] );
		$status = $_GET ['status'];
		$bind_list = $_GET ['bind_list'];
	}
}

if (isset ( $_POST ['submit'] ) || isset ( $_POST ['update'] )) {
	$accountErr = $passwordErr = $statusErr = "";
	$err_flag = 0;
	
	if (empty ( $_POST ['account'] )) {
		$accountErr = "Account required";
		$err_flag = 1;
	} else {
		$account = test_input ( $_POST ['account'] );
		$result = array ();
		$result = ListAccount ( $account, aid, 1 );
		if (count ( $result ) != 0) {
			$accountErr = "This account has been registered";
			$err_flag = 1;
		}
	}
	
	if (empty ( $_POST ['password'] )) {
		$passwordErr = "Password required";
		$err_flag = 1;
	} else {
		$password = test_input ( $_POST ['password'] );
	}
	$status = $_POST ['status'];
	
	if ($err_flag == 0) {
		AddAccount ( $account, $password, $status );
		if (! empty ( $_POST ['bind_list'] )) {
			foreach ( $_POST ['bind_list'] as $device ) {
				AddBind ( $account, $device );
			}
		}
		// header( 'Location: http://localhost/p2p/account.php' ) ;
	}
}
function test_input($data) {
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars ( $data );
	return $data;
}
?>
<div class=main>
    <?php
				
echo '<h2>' . $page_title . '</h2>';
				if ((isset ( $_POST ['submit'] )) && ($err_flag == 0)) {
					$statusStr = ($status == 1) ? 'Activate' : 'Deactivate';
					echo '<p><em>Added: </em>' . $account . ' --- ' . $statusStr . '</p>';
				}
				?>
    <form enctype="multipart/form-data" method="post"
		action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" cellspacing="2" cellpadding="0">
			<tr>
				<td align="right" valign="middle">Account:</td>
				<td align="left" valign="middle"><input type="text" name="account"
					size="18" value="<?php if( !empty($account)) echo $account;?>"
					<?php if ( $_GET['action'] == 'edit') echo "readonly"?> /> <span
					class="error"><?php echo $accountErr;?></span></td>
			</tr>

			<tr>
				<td align="right" valign="middle">Password:</td>
				<td align="left" valign="middle"><input type="password"
					name="password" size="18" /> <span class="error"><?php echo $passwordErr;?></span></td>
			</tr>

			<tr>
				<td align="right" valign="middle">Status:</td>
				<td><input type="radio" name="status"
					<?php if ( (isset($status) && $status == 1) || (!isset($status))) echo "checked"; ?>
					value="1"> <span>Activate</span> <input type="radio" name="status"
					<?php if ( isset($status) && $status == 2) echo "checked"; ?>
					value="0"> <span>Deactivate</span> <span class="error"><?php echo $statusErr;?></span>
				</td>
			</tr>

			<tr>
				<td align="right" valign="middle">Bind Devices:</td>
				<td>
					<table>
  			           <?php
																$data = array ();
																$dev = array ();
																$dev_cnt = 0;
																$data = ListDevice ( NULL, NULL, 1 );
																$dev_cnt = count ( $data );
																
																for($i = 0; $i < $dev_cnt; $i ++) {
																	$input = '<input type="checkbox" name="bind_list[]" value="' . $data [$i] [0] . '"';
																	
																	echo '<tr>';
																	echo '<td>';
																	if ($_GET ['action'] == 'edit') {
																		if (isset ( $bind_list )) {
																			foreach ( $bind_list as $device ) {
																				if (! strcmp ( $data [$i] [0], $device )) {
																					$input .= ' checked';
																				}
																			}
																		}
																	}
																	$input .= '><label>' . $data [$i] [0] . '</label>';
																	echo $input;
																	echo '</td>';
																	echo '</tr>';
																}
																?>
  			       </table>
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