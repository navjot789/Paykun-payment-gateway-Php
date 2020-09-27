<?php
require '../src/Payment.php';
require '../src/Crypto.php';
require '../src/Validator.php';

include_once "../src/secret.php";

$response = $obj->getTransactionInfo($_GET['payment-id']);
?>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
	<div class="container-fluid">
	<div class="list-group">
	  <a href="#" class="list-group-item list-group-item-action active text-center">
		  <h3>Payment details</h3>
	  </a>
		
		<?php
			if(is_array($response) && !empty($response)) {

					if($response['status'] && $response['data']['transaction']['status'] == "Success") {
					
						
						$i=0;
						 foreach($response['data']['transaction'] as $key=>$val)
						 {

							echo '<a href="#" class="list-group-item list-group-item-action text-center">'.$key.' : '.$val.'</a>';
							$i++;
							if($i==6){ break;
							}

						 }
						echo '<a href="../index.php" class="btn btn-info">Go back</a>';
						
							echo "<pre>";
									var_dump($response);
							echo "</pre>";
						
					} else {
						
						echo '<a href="#" class="list-group-item list-group-item-action text-center text-danger">Transaction failed or Payment ID not found!</a>';
						echo '<a href="../index.php" class="btn btn-info">Go back</a>';
					}
				}

		 
	
		?>
	  
	</div>
	</div>
</body>