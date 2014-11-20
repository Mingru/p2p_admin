<?php
$page_title = 'Account List';
require_once('header.php');
require_once('menu.php');
require_once ('account_api.php');
require_once ('bind_api.php');

if ( isset($_GET['action'])) {
	if ($_GET['action'] == 'del') {
		if ( isset($_GET['aid'])) {
			$aid = test_input($_GET['aid']);
			DelAccount($aid);
		}
	}
}

if ( isset($_POST['submit'])) {
	$keyword = $aid = $status = $date_from = $date_to = $colnum = "";

	if ( !empty($_POST['colnum'])) {
		$colnum = $_POST['colnum'];
		
		switch ($colnum) {
			case 'aid':
				if ( !empty( $_POST['aid'])) {
					$aid = test_input($_POST['aid']);
				}
				$keyword = $aid;
				break;
			case 'status':
				$status = $_POST['status'];
				$keyword = $status;
				break;
			case 'createDate':
				$date_from = $_POST['date_from'];
				$date_to = $_POST['date_to'];
				$keyword = $date_from . "&" . $date_to;
				break;
			default:
				break;
		}
	}	
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<!DOCTYPE html>
<html>
<body onload=<?php echo "showQuery('$colnum')"?>>
    <div class="main">
    <?php echo '<h2>' . $page_title . '</h2>';?>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table>
            <tr>
                <td>Query by:</td>
                <td>
                    <select name="colnum" id="colnum" onchange="showQuery(this.value)">
                        <option value="">All</option>
                        <option value="aid" <?php if( $colnum == 'aid') echo "selected";?>>AID</option>
                        <option value="status" <?php if( $colnum == 'status') echo "selected";?>>Status</option>
                        <option value="createDate" <?php if( $colnum == 'createDate') echo "selected";?>>Create Date</option>
                    </select>
                </td>
            </tr> 
         </table>
         <table>
            <tr>
                <td>
                    <table class="query_tb" id="aid_tb" style="display: none">
                        <tr>
                            <td>Search:</td>
                            <td><input type="text" name="aid" size="18" value="<?php if( !empty($aid)) echo $aid;?>"></td>
                        </tr>
                    </table>
                    <table class="query_tb" id="status_tb" style="display: none">
                        <tr>
                            <td>Status:</td>
                            <td>
                                <input type="radio" name="status" 
                                <?php if ( isset($status) && $status == 1) echo "checked"; ?>
                                  value="1"> <span>Activate</span>
                                <input type="radio" name="status" 
                                <?php if ( isset($status) && $status == 0) echo "checked"; ?>
                                 value="0"> <span>Deactivate</span>
                           </td>
                        </tr>
                    </table>
                    <table class="query_tb" id="createDate_tb" style="display: none">
                        <tr>
                            <td>
                                <label for="date_from">From</label>
                                <input type="text" id="date_from" name="date_from" size="5">
                                <label for="date_to">to</label>
                                <input type="text" id="date_to" name="date_to" size="5">
                           </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" class="flaticon-magnifier13"></td>
            </tr>
        </table>
    </form>
    <table id="data_tb">
        <tr>
            <th>AID</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Devices</th>
            <th>Action</th>
        </tr>
 
    <?php
        $data = array();
        $data = ListAccount($keyword, $colnum, 1);
        $row_cnt = count($data);
        for ($i = 0; $i < $row_cnt; $i++) {
            echo '<tr>';
            echo '<td>' . $data[$i][0] . '</td>';
            if ($data[$i][1] == 1) {
            	echo '<td> Activate</td>';
            } else {
            	echo '<td> Deactivate</td>';
            }
            echo '<td>' . $data[$i][2] . '</td>';
            
            // List bind devices
            $bind = array();
            $bind = ListBind($data[$i][0], 'aid');
            $bind_cnt = count($bind);
            $bind_list = "";
            
            echo '<td>';
            for ($n = 0; $n < $bind_cnt; $n++ ) {
            	echo $bind[$n][1] . '</br>';
            	
            	// Pass bind devices in GET method by array
            	if ( isset($bind_list)) {
            		$bind_list .= '&bind_list[]=' . $bind[$n][1];
            	} else {
            		$bind_list = 'bind_list[]=' . $bind[$n][1];
            	}
            }
            echo '</td>';
            
            // Show edit and del button
            echo '<td>';  
            echo '<a href ="account_add.php?action=edit&amp;account=' . $data[$i][0] . '&amp;status=' . $data[$i][1] . 
                    '&amp;'. $bind_list . '" class="flaticon-pencil43"></a>';
            echo '<a href="'. $_SERVER['PHP_SELF'] . '?action=del&amp;aid=' . $data[$i][0] . '" class="flaticon-delete81"></a>';
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