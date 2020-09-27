<?php
ob_start();
session_start();
require_once("dbcontroller.php");
require 'src/Payment.php';
require 'src/Crypto.php';
require 'src/Validator.php';

$db_handle = new DBController();
if(!empty($_GET["action"])) {
	switch($_GET["action"]) {
		case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
						if($productByCode[0]["code"] == $k) {
							if(empty($_SESSION["cart_item"][$k]["quantity"])) {
								$_SESSION["cart_item"][$k]["quantity"] = 0;
							}
							$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
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
				if($_GET["code"] == $k)
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

if(isset($_POST['start_session']))
{
	
	 
	$_SESSION['total_elements']=array();
    $data = array_push($_SESSION['total_elements'], $_POST['full_name'], $_POST['email'],$_POST['contact'],$_POST['country'],$_POST['state'],$_POST['city'],$_POST['postalcode'],$_POST['address']);
	
}

?>
<HTML>
<HEAD>
	<TITLE>Paykun Payment gateway Php demo</TITLE>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="style.css" type="text/css" rel="stylesheet" />

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />

	<style type="text/css">
		
	</style>
</HEAD>
<BODY>


	
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="https://www.youtube.com/channel/UCVlSbZdK_7tTF_X91gpD48g" target="_blank">Codelone</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="https://www.youtube.com/channel/UCVlSbZdK_7tTF_X91gpD48g" target="_blank"><i class="fab fa-youtube text-danger" ></i> YouTube</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://www.instagram.com/code_lone/" target="_blank"> <i class="fab fa-instagram text-danger" ></i> Instagram</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Paykun
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="https://paykun.com/docs" target="_blank">Developer Paykun API</a>
						<a class="dropdown-item" href="https://docs.paykun.com/technical-guide/test-card-information"  target="_blank">Paykun Test Cards</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="https://dashboard.paykun.com/register"  target="_blank">Paykun Register</a>
					</div>
				</li>
				
				
			</ul>


			<form class="form-inline my-2 my-lg-0" action="search/index.php" method="get">
				<?php

					if(isset($_SESSION['total_elements']))
					{
						echo '<a class="nav-link" ><i class="fas fa-user" ></i> '.$_SESSION['total_elements'][0].'</a> '.' <a href="logout.php" class="btn-danger btn-sm" > logout?</a> ';
					}
				?>
				<a class="nav-link" href="mailto:web.dev.nav@gmail.com"><i class="fas fa-phone-alt text-default" ></i> Developer contact</a>
				<input class="form-control mr-sm-2" type="text"  name="payment-id" placeholder="Paykun payment-ID" >
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search" ></i> </button>
			</form>

		</div>
	</nav>
	
		
		
<div class="container-fluid">
<?php
	//creating object
	include_once "src/secret.php";
	
	  $link = $_SERVER['REQUEST_URI'];
      $link_array = explode('=',$link);
      $PID = end($link_array); //getting paymnet-id from the end of url.
	
	//NOTE: $PID string lenght is 23char
	if(isset($PID) && strlen($PID) == 23)
	{
	
			$response = $obj->getTransactionInfo($PID);

			if(is_array($response) && !empty($response)) {

				if($response['status'] && $response['data']['transaction']['status'] == "Success") {

			 echo '<div class="row" >
							<div class="col-md-12 offset-md-4" >
							   <a  href="#" style="font-size:28px;text-decoration:none;color:#495057;">  <img style="height: 120px;width:120px;margin:20px;" class="responsive-image img-fluid rounded mt-3 " src="https://icon-library.com/images/successful-icon/successful-icon-10.jpg"  alt="Card image cap" > Transaction Successful </a><small>#'.$response['data']['transaction']['payment_id'].'</small>
							</div>
					</div>';


				} else {
					echo'<div class="row" >
							<div class="col-md-12 offset-md-4" >
								  <a  href="#" style="font-size:28px;text-decoration:none;color:#495057;">  <img style="height: 120px;width:120px;margin:20px;" class="responsive-image img-fluid rounded mt-3 " src="https://www.interweave.in/wp-content/themes/interweave-theme/images/fail.png"  alt="Card image cap" > Transaction Failed</a><small>#'.$response['data']['transaction']['payment_id'].'</small>
							</div>
						</div>';
				}
			}
		
	}
  
?>
		
	
<div class="row">
			<div class="col-md-12 offset-md-4">
				  <a  href="index.php" >  <img style="min-height: 20px;min-width:40px;" class="responsive-image img-fluid rounded mt-3 " src="img/paykun.png"  alt="Card image cap" ></a>
			</div>
</div>
	
	
	
	
  <div class="row">

	<div class="col-md-7">
		<h5 class="mt-4 ml-3"> <i class="fas fa-box"></i>  Products</h5>
		<div class="row">
		
		<?php
		$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
		if (!empty($product_array)) { 
			foreach($product_array as $key=>$value){
				?>
				
				<div class="col-md-3 shadow-sm bg-white rounded  ml-3 mr-3 mt-2 mb-2">
					<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
						
						  <img  class="responsive-image img-fluid rounded mt-3" src="<?php echo $product_array[$key]["image"]; ?>"  alt="Card image cap">

						  <div class="card-body">
						  	<h5 class="card-title"><?php echo $product_array[$key]["name"]; ?></h5>
					        <h6 class="card-subtitle mb-2 text-muted"><?php echo "&#8377;".$product_array[$key]["price"]; ?></h6>
						    <p class="card-text">Some quick example text to build.</p>
						    <a href="#" class="card-link">
						    	<input type="text" style="border:1px solid #ddd;" placeholder="Qty" name="quantity" value="1" size="2" />
						    </a>
					        <button class="card-link btnAddAction btn-sm text-success"  type="submit" > <i class="fas fa-plus"></i> Add</button>
						
						</div>

					</form>
				</div>
			
				<?php
			}
		}
		?>

	</div>
		
	</div>

	<div class="col-md-5">

					<div id="shopping-cart">
					<h5 ><i class="fas fa-shopping-cart"></i>  Shopping Cart</h5>
					
					<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>

					<div class="table-responsive">
					<?php
					if(isset($_SESSION["cart_item"])){
						$total_quantity = 0;
						$total_price = 0;

						$total_product_count = count($_SESSION["cart_item"]);
						?>	
						<table class="tbl-cart" cellpadding="10" cellspacing="1" border="0">
							<tbody>
								<tr>
									<th style="text-align:left;">Name</th>
									<th style="text-align:left;" >Code</th>
									<th style="text-align:right;" width="5%">Quantity</th>
									<th style="text-align:right;" width="15%">Unit Price</th>
									<th style="text-align:right;" width="15%">Price</th>
									<th style="text-align:center;" width="5%">Remove</th>
								</tr>	
								<?php		
								foreach ($_SESSION["cart_item"] as $item){
									$item_price = $item["quantity"]*$item["price"];
									?>
									<tr>
										<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
										<td><?php echo $item["code"]; ?></td>
										<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
										<td  style="text-align:right;" ><?php echo "&#8377; ".$item["price"]; ?></td>
										<td  style="text-align:right;"><?php echo "&#8377; ". number_format($item_price,2); ?></td>
										<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><i class="fas fa-trash text-danger" ></i></a></td>
									</tr>
									<?php
									$total_quantity += $item["quantity"];
									$total_price += ($item["price"]*$item["quantity"]);
								}
								?>

								<tr>
									
									<td align="right" colspan="4"><strong>Qty: <?php echo $total_quantity; ?></strong></td>
									<td align="right" colspan="2"><strong>Total: </strong><strong style="color: #429e42;"><?php echo "&#8377; ".number_format($total_price, 2); ?></strong></td>
									
								</tr>

								
									
								   <form method="POST" action="submit.php">
								   	<td colspan="6" align="right">
								   		<input type="hidden" name="product_name" value="X<?php echo $total_product_count; ?> Products">
								   		<input type="hidden" name="amount" value="<?php echo $total_price; ?>">
										<button class="btn-success btn-sm "  type="submit" > <i class="fas fa-shopping-cart"></i> Checkout</button> 
									</td>
								   </form>
									
								

							</tbody>
						</table>		
						<?php
					} else {
						?>
						<div class="no-records"><img  class="responsive-image img-fluid rounded mt-3" src="img/empty-cart.png"  alt="Card image cap"></div>
						<?php 
					}
					?>
				</div>
				</div>
		
		</div>

	
</div>

	<?php 
	
		if(!$_SESSION['total_elements'])
		{
	
	?>
<!-- Modal -->
<div id= "myModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
			PayKun dummy details</h5>
     
      </div>
      <div class="modal-body">
        

		<form  id="modal_form" method="post">

         <div class="row">
         	<div class="col-md-6">
         		 <div class="form-group">
			    <label for="exampleInputEmail1">Customer full Name</label>
			   
			    <input type="text" name="full_name" class="form-control" placeholder="Enter full Name">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>

         	</div>

         	<div class="col-md-6">
         		 <div class="form-group">
			    <label for="exampleInputEmail1">E-mail</label>
			    <input type="text" name="email" class="form-control" placeholder="Enter email">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>
         </div>
			
		     

         <div class="row">
         	<div class="col-md-6">
         		 <div class="form-group">
			    <label for="exampleInputEmail1">Customer contact no</label>
			    <input type="text" maxlength="10" name="contact" class="form-control" placeholder="Enter contact no">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>

         	<div class="col-md-6">
         		     <div class="form-group">
			    <label for="exampleInputEmail1">Country</label>
			    <input type="text" name="country" value="india" class="form-control" placeholder="Enter country">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>
         </div>
         


         <div class="row">
         	<div class="col-md-6">
         		

			     <div class="form-group">
			    <label for="exampleInputEmail1">State</label>
			    <input type="text" name="state"class="form-control" placeholder="Enter state">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>

         	<div class="col-md-6">
         		     <div class="form-group">
			    <label for="exampleInputEmail1">City</label>
			    <input type="text" name="city" class="form-control" placeholder="Enter city">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>
         </div>



         <div class="row">
         	<div class="col-md-6">
         		    <div class="form-group">
			    <label for="exampleInputEmail1">Pin code</label>
			    <input type="text" name="postalcode" class="form-control" placeholder="Enter postalcode">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>

         	<div class="col-md-6">
         		  <div class="form-group">
			    <label for="exampleInputEmail1">Address</label>
			    <input type="text" name="address" class="form-control" placeholder="Enter address">
			    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			  </div>
         	</div>
         </div>   

		      
       <div class="modal-footer">
      <button type="submit" onclick="form_submit()" class="btn btn-success" id="submit" name="start_session"  > start session</button>
       </div>

		</form>


      </div>
    
    </div>
  </div>
</div>
 

	<?php	
		}
		
	?>


</div>	


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<script type="text/javascript">
	    $(window).on('load',function(){
	        $('#myModal').modal('show');
	    });
		
		function form_submit() {
		document.getElementById("modal_form").submit();
	   } 
		
	</script>
</BODY>
</HTML>