<?php
$page_title = 'Device List';
require_once ('header.php');
require_once ('menu.php');
require_once ('device_api.php');

if (isset ( $_GET ['action'] )) {
	if ($_GET ['action'] == 'del') {
		if (isset ( $_GET ['did'] )) {
			$did = test_input ( $_GET ['did'] );
			DelDevice ( $did );
		}
	}
}

// if ( isset($_POST['submit'])) {
$keyword = $colnum = "";
if (! empty ( $_POST ['keyword'] )) {
	$keyword = test_input ( $_POST ['keyword'] );
}
if (! empty ( $_POST ['colnum'] )) {
	$colnum = $_POST ['colnum'];
}
// }
function test_input($data) {
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars ( $data );
	return $data;
}
?>

<div class="main">
    <?php echo '<h2>' . $page_title . '</h2>';?>
    <form enctype="multipart/form-data" method="post"
		action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" cellspacing="2" cellpadding="0">
			<tr>
				<td>Query by:</td>
				<td><select name="colnum"
					onchange="window.location='<?php echo $_SERVER['PHP_SELF']; ?>'">
						<option value=""></option>
						<option value="fdid"
							<?php if( $colnum == 'fdid') echo "selected";?>>FDID</option>
						<option value="status"
							<?php if( $colnum == 'status') echo "selected";?>>Status</option>
						<option value="addDate"
							<?php if( $colnum == 'addDate') echo "selected";?>>Add Date</option>
						<option value="expireDate"
							<?php if( $colnum == 'expireDate') echo "selected";?>>Expire Date</option>
				</select></td>
			</tr>
			<tr>
				<td>Search:</td>
				<td>
                    <?php
																				// if ( $colnum == 'fdid') {
																				$input = "<input" . " type=\"text\"" . " name=\"keyword\"" . " size=\"18\"" . " value=\"$keyword\"" . "/>";
																				// }
																				echo $input;
																				?>
                    
                    <input type="submit" name="submit" value="&crarr;" />
				</td>
			</tr>

		</table>
	</form>
	<table border="1" cellspacing="1" cellpadding="1" bgcolor="#ffffff">
		<tr>
			<th>FDID</th>
			<th>Status</th>
			<th>Add Date</th>
			<th>Expire Date</th>
			<th>Action</th>
		</tr>
 
    <?php
				$data = array ();
				$data = ListDevice ( $keyword, $colnum, 1 );
				$row_cnt = count ( $data );
				for($i = 0; $i < $row_cnt; $i ++) {
					echo '<tr>';
					echo '<td>' . $data [$i] [0] . '</td>';
					if ($data [$i] [1] == 1) {
						echo '<td> Activate</td>';
					} else {
						echo '<td> Deactivate</td>';
					}
					echo '<td>' . $data [$i] [2] . '</td>';
					echo '<td>' . $data [$i] [3] . '</td>';
					
					// Show edit and del button
					echo '<td>';
					echo '<a href="device_add.php?action=edit&amp;did=' . $data [$i] [0] . '&amp;status=' . $data [$i] [1] . '" class="flaticon-pencil43"></a>';
					echo '<a href="' . $_SERVER ['PHP_SELF'] . '?action=del&amp;did=' . $data [$i] [0] . '" class="flaticon-delete81"></a>';
					echo '</td>';
					
					echo '</tr>';
				}
				?>
	</table>
</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script>
	    function showQuery(type) {
		    var obj = document.getElementsByTagName('table');
		    var i;

		    for ( i =0; i < obj.length; i++) {
			    if ( obj[i].className == "query_tb") {
				    obj[i].style.visibility = "hidden";
				    obj[i].style.display = "none";
			    }
			}

            if (type) {
		        document.getElementById(type + "_tb").style.visibility = "visible";
			    document.getElementById(type + "_tb").style.display = "";
            }
	    }

	    $(function() {
	    	$( "#date_from" ).datepicker({ 
		    	dateFormat: 'yy-mm-dd',
	    		onClose: function( selectedDate ) {
	    		    $( "#date_to" ).datepicker( "option", "minDate", selectedDate );
	    		}                            
		    });
	    	$( "#date_to" ).datepicker({ 
		    	dateFormat: 'yy-mm-dd',
	    		onClose: function( selectedDate ) {
		            $( "#date_from" ).datepicker( "option", "manDate", selectedDate );
		        }     		    	
		    });
	    });
	</script>
</body>
</html>
