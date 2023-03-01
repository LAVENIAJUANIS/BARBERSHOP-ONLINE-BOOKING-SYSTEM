<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'msms_db');
//OOP
$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}



if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$product_id = $_GET["id"];
			
			$sql = "SELECT * FROM hairproduct_list WHERE p_id = '$product_id'";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			$pid = "pid".$row["p_id"];
			
			$itemArray = array(
				$pid=>array ('name'=>$row["p_name"], 'img'=>$row["p_img"], 'prodID'=>$row["p_id"], 'quantity'=>$_POST["quantity"], 'price'=>$row["p_price"]));
								
			if(!empty($_SESSION["cart_item"])) {				
				if(in_array($pid,array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($pid == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}								
							}
					}
				} else {					
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);					
				}
			} else {				
				$_SESSION["cart_item"] = $itemArray;
			}						
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if("pid".$_GET["prodID"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>

<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
	
	#service-list tbody tr{
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <dl>
                <dt class="text-muted">Full Name</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($fullname) ? $fullname : '' ?></dd>
                <dt class="text-muted">Email</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($email) ? $email : '' ?></dd>
                <dt class="text-muted">Contact</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($contact) ? $contact : '' ?></dd>
				<dt class="text-muted">Your Barber</dt>
                <dd class='pl-4 fs-4 fw-bold'><?= isset($barber) ? $barber : '' ?></dd>
            </dl>
        </div>
        <div class="col-md-6">
            <dl>
                <dt class="text-muted">Schedule</dt>
                <dd class='pl-4 text-dark'>
                    <p class=""><small><?= isset($schedule) ? date("M d, Y",strtotime($schedule)) : '' ?></small></p>
                </dd>
				<dt class="text-muted">Time</dt>
				<dd class='pl-4 text-dark'>
				<p class=""><small><?= isset($time) ? date("h:i A", strtotime($time)) : '' ?></small></p>
				</dd>

                <dt class="text-muted">Status</dt>
                <dd class='pl-4 text-dark'>
                    <?php
                    if(isset($status)):
                        switch($status){
                            case '1':
                                echo "<span class='badge badge-primary badge-pill'>Verified</span>";
                                break;
                            case '2':
                                echo "<span class='badge badge-success bg-primary badge-pill'>Done</span>";
                                break;
                            case '0':
                                echo "<span class='badge badge-light badge-pill text-dark'>Pending</span>";
                                break;
                        }
                    endif;
                    ?>
                </dd>
            </dl>
        </div>
    </div>
	
<div class="row">
<p style="margin: 15px;"><i class="fa fa-shopping-cart" style="font-size:24px"></i> / My Cart</p>

<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table cellpadding="10" cellspacing="1" id="carttable" width="60%" style="margin: 0 10px 0 10px;">
<tbody>
<tr id="carttable tr">
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #909090; color: white;">Item</th>
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;">Code</th>
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;" width="15%">Quantity</th>
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;" width="15%">Unit Price (RM)</th>
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;" width="15%">Price (RM)</th>
	<th style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;" width="15%">Actions</th>
</tr>	

<?php		
foreach ($_SESSION["cart_item"] as $item){
	$item_price = $item["quantity"]*$item["price"];
	?>
	<tr>
	<td id="#carttable td" style="text-align:left;"><?php echo $item["name"];?></td>				
	<td id="#carttable td" style="text-align:center;"><?php echo $item["prodID"]; ?></td>
	<td id="#carttable td" style="text-align:center;"><?php echo $item["quantity"]; ?></td>
	<td id="#carttable td" style="text-align:center;"><?php echo $item["price"]; ?></td>
	<td id="#carttable td" style="text-align:center;"><?php echo number_format($item_price,2); ?></td>
	<td id="#carttable td" style="text-align:center;"><a href="cart_action.php?action=remove&prodID=<?php echo $item["prodID"]; ?>"><i class="fa fa-times-circle" ></i> Remove</a></td>
	
	</tr>
	<?php
	$total_quantity += $item["quantity"];
	$total_price += ($item["price"]*$item["quantity"]);
	}
	?>

<tr>
<td colspan="2" align="right"><b>Total:</b></td>
<td style="text-align:center;"><?php echo $total_quantity; ?></td>
<td style="text-align:center;" colspan="2"><strong><?php echo "RM ".number_format($total_price, 2); ?></strong></td>
<td style="text-align:center;"><a href="COD.php" class="btn btn-lg btn-block btn-primary"><button type="submit">Checkout</button></a></td>
</tr>

</tbody>
</table>	
	
<p style="margin: 15px;"><a href="cod.php?action=empty"><i class="fa fa-trash" style="font-size:24px"></i> Empty Cart</a></p>
<p style="margin: 15px;"><a href="hairproduct.php?action=empty"><i  style="font-size:24px"></i> Continue Shopping</a></p>
<?php
} else {
?>
<p style="margin: 15px;">Your Cart is Empty</p>
<?php 
}
?>

	
</div>
    <div class="col-12 text-right">
        <button class="btn btn-flat btn-sm btn-dark" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>