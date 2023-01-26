<?php
/*
==================================================
== Items Page
==================================================
*/

ob_start(); // output Buffering Start

session_start();
$pageTitle = 'Itmes';

if (isset($_SESSION['Username'])) {
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

	if ($do == 'Manage') {


    	$stmt = $con->prepare("SELECT 
    								items.*,
    								categories.Name AS cat_name,
    								users.Username AS user_name
    							From
    								items
    							INNER JOIN
    								categories
    							ON
    								categories.ID = items.Cat_ID
    							INNER JOIN
    								users
    							ON
    								users.UserID = items.Member_ID
    							ORDER BY
    								item_ID DESC");

    	// EXecute The Statement

    	$stmt->execute();

    	// Assign To Variable

    	$items = $stmt->fetchAll();
    	if(! empty($items)) {
    	?>

	    <h1 class="text-center">Manage items</h1>
	    <div class="container">
	    	<div class="table-resposive">
	    		<table class="main-table text-center table table-bordered">
	    			<tr>
	    				<td>#ID</td>
	    				<td>Name</td>
	    				<td>Description</td>
	    				<td>Price</td>
	    				<td>Adding Date</td>
	    				<td>Category</td>
	    				<td>Username</td>
	    				<td>Control</td>
	    			</tr>

	    			<?php
		    			foreach ($items as $item) {
			    				 	echo "<tr>";
			    				 		echo "<td>" . $item['item_ID'] . "</td>";
			    				 		echo "<td>" . $item['Name'] . "</td>";
			    				 		echo "<td>" . $item['Description'] . "</td>";
			    				 		echo "<td>" . $item['Price'] . "</td>";
			    				 		echo "<td>" . $item['Add_Date'] . "</td>";
			    				 		echo "<td>" . $item['cat_name'] . "</td>";
			    				 		echo "<td>" . $item['user_name'] . "</td>";
			    				 		echo "<td>";
						    				echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
						    				echo '<a href="items.php?do=Delete&itemid=' . $item['item_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
						    				if ($item['Approve'] == 0) {
												echo '<a href="items.php?do=Approve&itemid=' . $item['item_ID'] . '" class="btn btn-info "><i class="fa fa-check"></i> Approved</a>';
						    				}
			    				 		echo "</td>";
			    				 	echo "</tr>";
		    			}
	    			?>
	    		</table>
	    	</div>
	    	<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
	    </div>

	<?php

    	} else {
			echo "<div class='container'>";
				echo '<div class="nice-message">There\'s No Items To Show </div>';
				echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>';
			echo "</div>";
		}

	 }elseif ($do == 'Add') { ?>
		<h1 class="text-center">Add New Item</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Insert" method="POST">
	    			<!-- Start Name Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Name</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item">
	    				</div>
	    			</div>
	    			<!-- End Name Field -->
	    			<!-- Start Description Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Description</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item">
	    				</div>
	    			</div>
	    			<!-- End Description Field -->
	    			<!-- Start Price Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Price</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item">
	    				</div>
	    			</div>
	    			<!-- End Price Field -->
	    			<!-- Start Country Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Country</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made Item">
	    				</div>
	    			</div>
	    			<!-- End Country Field -->
	    			<!-- Start Status Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Status</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="status">
	    						<option value="0">...</option>
	    						<option value="1">New</option>
	    						<option value="2">Like New</option>
	    						<option value="3">Used</option>
	    						<option value="4">Very Old</option>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Status Field -->
	    			<!-- Start Members Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Member</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="member">
	    						<option value="0">...</option>
	    						<?php
	    							$stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 0");
	    							$stmt->execute();
	    							$users = $stmt->fetchAll();
	    							foreach ($users as $user) {
	    								echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
	    							}
	    						 ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Members Field -->
	    			<!-- Start Category Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Category</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="category">
	    						<option value="0">...</option>
	    						<?php
	    							$stmt = $con->prepare("SELECT * FROM categories");
	    							$stmt->execute();
	    							$categories = $stmt->fetchAll();
	    							foreach ($categories as $cotegory) {
	    								echo "<option value='" . $cotegory['ID'] . "'>" . $cotegory['Name'] . "</option>";
	    							}
	    						 ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Category Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Add Item" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form>
	    	</div>

	    	<?php

	}elseif ($do == 'Insert') {
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	        echo "<h1 class='text-center'>Update Itmes</h1>";
	    	echo "<div class='container'>";

    		// Get Variables From The Form

    		$name 		= $_POST['name'];
    		$desc 		= $_POST['description'];
    		$price 		= $_POST['price'];
    		$country 	= $_POST['country'];
    		$status 	= $_POST['status'];
    		$member 	= $_POST['member'];
    		$cat		= $_POST['category'];


    		// Validate The Form

    		$formErrors = array();

    		if (empty($name)) {
    			$formErrors[] = 'Name Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($desc)) {
    			$formErrors[] = 'Description Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($price)) {
    			$formErrors[] = 'Price Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($country)) {
    			$formErrors[] = 'Country Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if ($status == 0) {
    			$formErrors[] = 'You Must Choose The Status:(';
    		}

    		if ($member == 0) {
    			$formErrors[] = 'You Must Choose The Member:(';
    		}

    		if ($cat  == 0) {
    			$formErrors[] = 'You Must Choose The category:(';
    		}

    		// Loop Into Errors Array And Echo It

    		foreach ($formErrors as $error) {
    			echo '<div class="alert alert-danger"> ' . $error . '</div>';
    		}

    		// Check If Theher's No Error Proceed The Update Operation

    		if (empty($formErrors)) {

	    		// Insert User Info In Database

    			$stmt = $con->prepare("INSERT INTO 
    										items(Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID)
    									    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zmember, :zcat)");
    			$stmt->execute(array(

    				'zname' 	=> $name,
    				'zdesc' 	=> $desc,
    				'zprice' 	=> $price,
    				'zcountry' 	=> $country,
    				'zstatus' 	=> $status,
    				'zmember'	=> $member,
    				'zcat'		=> $cat
     			)); 

	    		// Echo Success Message 

	    		 $theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Inserted</div>';
	    		 redirectHome($theMsg, 'back');
    		}
    		
    	}else {
    		echo "<div class='container'>";
    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

    		redirectHome($theMsg);
    		echo "</div>";
    	}

	echo "</div>";	

	}elseif ($do == 'Edit') {
  	// Check If Get Request itemid Is Numeric & Get The Integer Value Of It

    	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;

    	// Select All Data Depend On This ID

	    $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");

	    // Execute  Query

	    $stmt->execute(array($itemid));

	    // Fetch Query 

	    $item = $stmt->fetch();

	    // The Row Count 

	    $count = $stmt->rowCount();

	    // If Ther's Such ID Show The Form

	    if ($count > 0) { ?>

		<h1 class="text-center">Edit Item</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Update" method="POST">
	    			<input type="hidden" name="itemid" value="<?php echo $itemid ?>">
	    			<!-- Start Name Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Name</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name" class="form-control" value="<?php echo $item['Name']?>">
	    				</div>
	    			</div>
	    			<!-- End Name Field -->
	    			<!-- Start Description Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Description</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="description" class="form-control" value="<?php echo $item['Description']?>">
	    				</div>
	    			</div>
	    			<!-- End Description Field -->
	    			<!-- Start Price Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Price</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="price" class="form-control" value="<?php echo $item['Price']?>">
	    				</div>
	    			</div>
	    			<!-- End Price Field -->
	    			<!-- Start Country Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Country</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="country" class="form-control" value="<?php echo $item['Country_Made']?>">
	    				</div>
	    			</div>
	    			<!-- End Country Field -->
	    			<!-- Start Status Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Status</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="status">
	    						<option value="1" <?php if($item['Status'] == 1) { echo 'selected';} ?>>New</option>
	    						<option value="2" <?php if($item['Status'] == 2) { echo 'selected';} ?>>Like New</option>
	    						<option value="3" <?php if($item['Status'] == 3) { echo 'selected';} ?>>Used</option>
	    						<option value="4" <?php if($item['Status'] == 4) { echo 'selected';} ?>>Very Old</option>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Status Field -->
	    			<!-- Start Members Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Member</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="member">
	    						<?php
	    							$allMembers = getAllFrom('*', 'users', 'where GroupID = 0', '', 'UserID');
	    							foreach ($allMembers as $user) {
	    								echo "<option value='" . $user['UserID'] . "'"; 
	    								if($item['Member_ID'] == $user['UserID']) { echo 'selected';} 
	    								echo ">" . $user['Username'] . "</option>";
	    							}
	    						 ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Members Field -->
	    			<!-- Start Category Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Category</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select name="category">
	    						<?php
	    							$allCats = getAllFrom('*', 'categories', '', '', 'ID');
	    							foreach ($allCats as $cotegory) {
	    								echo "<option value='" . $cotegory['ID'] . "'";
	    								if($item['Cat_ID'] == $cotegory['ID'] ){ echo 'selected';} 
	    								echo ">" . $cotegory['Name'] . "</option>";
	    							}
	    						 ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Category Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Save Item" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form> <?php
		    	// Select All Users Except Admin

		    	$stmt = $con->prepare("SELECT 
		    								comments.*, users.Username As Member
		    							FROM 
		    								comments
				    					INNER JOIN
		    								users
		    							ON
		    								users.UserID = comments.user_id
		    							WHERE
		    								item_id = ?");

		    	// EXecute The Statement

		    	$stmt->execute(array($itemid));

		    	// Assign To Variable

		    	$rows = $stmt->fetchAll();

		    	if(! empty($rows)) {
		    	?>

			    <h1 class="text-center">Manage [ <?php echo $item['Name']?> ] Comments</h1>
		    	<div class="table-resposive">
		    		<table class="main-table text-center table table-bordered">
		    			<tr>
		    				<td>Comment</td>
		    				<td>User Name</td>
		    				<td>Added Date</td>
		    				<td>Control</td>
		    			</tr>

		    			<?php
		    			foreach ($rows as $row) {
			    				 	echo "<tr>";
			    				 		echo "<td>" . $row['comment'] . "</td>";
			    				 		echo "<td>" . $row['Member'] . "</td>";
			    				 		echo "<td>" . $row['comment_date'] . "</td>";
			    				 		echo "<td>";
						    				echo '<a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
						    				echo '<a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
						    				if ($row['status'] == 0) {
												echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class="btn btn-info "><i class="fa fa-check"></i> Approve</a>';
						    				}
			    				 		echo "</td>";
			    				 	echo "</tr>";
		    			}	 
		    			?>
		    		</table>
		    	</div>
		    <?php }?>
	    	</div>  	

    	<?php

    	// If There's No Such ID Show Error Message

    	}else {
    		echo '<div class="container">';
    		$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
    		redirectHome($theMsg, 'back');
    		echo '</div>';
    	}

	}elseif ($do == 'Update') {
    	echo "<h1 class='text-center'>Update Item</h1>";
    	echo "<div class='container'>";

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	    		// Get Variables From The Form

	    		$id 		= $_POST['itemid'];
	    		$name 		= $_POST['name'];
	    		$desc 		= $_POST['description'];
	    		$price 		= $_POST['price'];
	    		$country 	= $_POST['country'];
	    		$status 	= $_POST['status'];
	    		$member 	= $_POST['member'];
	    		$cat 		= $_POST['category'];


	    		// Validate The Form

	    		$formErrors = array();

    		if (empty($name)) {
    			$formErrors[] = 'Name Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($desc)) {
    			$formErrors[] = 'Description Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($price)) {
    			$formErrors[] = 'Price Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if (empty($country)) {
    			$formErrors[] = 'Country Can\'t  Be <strong>Empty</strong> :(';
    		}

    		if ($status == 0) {
    			$formErrors[] = 'You Must Choose The Status:(';
    		}

    		if ($member == 0) {
    			$formErrors[] = 'You Must Choose The Member:(';
    		}

    		if ($cat  == 0) {
    			$formErrors[] = 'You Must Choose The category:(';
    		}

	    		// Loop Into Errors Array And Echo It

	    		foreach ($formErrors as $error) {
	    			echo '<div class="alert alert-danger"> ' . $error . '</div>';
	    		}

	    		// Check If Theher's No Error Proceed The Update Operation

	    		if (empty($formErrors)) {
	    			
		    		// Update The Database With This Info

		    		 $stmt = $con->prepare("UPDATE 
		    		 							items 
		    		 						SET 
		    		 							Name 			= ?, 
		    		 							Description 	= ?, 
		    		 							Price 			= ?, 
		    		 							Country_Made	= ?,
		    		 							Status 			= ?,
		    		 							Cat_ID 			= ?,
		    		 							Member_ID  		= ?
		    		 						WHERE 
		    		 							item_ID = ?");
		    		 $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $id));
		 
		    		 // Echo Success Message 

		    		$theMsg =  '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Update</div>';
		    		redirectHome($theMsg, 'back');
	    		}
	    		
	    	}else {
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
	    		redirectHome($theMsg);
	    	}

    	echo "</div>";

	}elseif ($do == 'Delete') {

    	echo "<h1 class='text-center'>Delete Items</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request itemid Is Numeric & Get The Integer Value Of It

			    	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("item_ID", "items", $itemid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
				    	$stmt->bindparam(":zid", $itemid);

				    	$stmt->execute();

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Deleted</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
 
	}elseif ($do == 'Approve') {
    	echo "<h1 class='text-center'>Approve Items</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

			    	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("item_ID", "items", $itemid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
				    	$stmt->execute(array($itemid));

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Activated</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";

	}

	include $tpl . 'footer.php';
}else {
	header('Location: index.php');
	exit();
}
ob_end_flush();

?>