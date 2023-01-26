<?php
/*
==================================================
== Category Page
==================================================
*/

ob_start(); // output Buffering Start

session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

	if ($do == 'Manage') {
		
		$sort = 'ASC';

		$sort_array = array('ASC', 'DESC');

		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
			$sort = $_GET['sort'];
		}

		$stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
		$stmt2->execute();
		$cats = $stmt2->fetchAll();

		if(! empty($cats)) {

 ?>

		<h1 class="text-center">Manage Categories</h1>
		<div class="container categories">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-edit"></i> Manage Categories
					<div class="option pull-right">
						<i class="fa fa-sort"></i> Ordering: [
						<a class="<?php if($sort == 'ASC') { echo 'active';} ?>" href="?sort=ASC">ASC</a> |
						<a class="<?php if($sort == 'DESC') { echo 'active';} ?>" href="?sort=DESC">DESC</a> ]
						<i class="fa fa-eye"></i> View: [
						<span class="active" data-view='full'>Full</span> |
						<span data-view='classic'>Classic</span> ]
					</div>
				</div>
				<div class="panel-body">
					<?php
						foreach ($cats as $cat) {
							echo "<div class='cat'>";
								echo "<div class='hidden-button'>";
									echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
									echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
								echo "</div>";
								echo "<h3>" . $cat['Name'] 	. '</h3>';
								echo "<div class='full-view'>";
									echo "<p>"; if($cat['Description'] == '') { echo 'This Category Has No Descripition';} else{ echo $cat['Description'];} echo "</p>";
									 if($cat['Visabilty'] == 1) { echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
									 if($cat['Allow_Comment'] == 1) { echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
									 if($cat['Allow_Ads'] == 1) { echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
								 echo "</div>";
							echo "</div>";
							echo "<hr>";
						}
					?>		
				</div>
			</div>
			<a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add Categroy</a>
		</div>

	<?php

		} else {
			echo "<div class='container'>";
				echo '<div class="nice-message">There\'s No Categories To Show </div>';
				echo '<a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add Categroy</a>';
			echo "</div>";
		}

	 }elseif ($do == 'Add') { ?>
			<h1 class="text-center">Add New Categroy</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Insert" method="POST">
	    			<!-- Start Name Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Name</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category">
	    				</div>
	    			</div>
	    			<!-- End Name Field -->
	    			<!-- Start Description Field -->
 	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Description</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="description" class=" form-control" placeholder="Descripe The Category">
	    				</div>
	    			</div>
	    			<!-- End Description Field -->
	    			<!-- Start Ordering Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Ordering</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
	    				</div>
	    			</div>
	    			<!-- End Ordering Field -->
	    			<!-- Start Visibility Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Visible</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="vis-yes" type="radio" name="visibility" value="0" checked>
	    						<label for="vis-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="vis-no" type="radio" name="visibility" value="1">
	    						<label for="vis-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Visibility Field -->
	    			<!-- Start Commenting Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Allow Commenting</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="com-yes" type="radio" name="commenting" value="0" checked>
	    						<label for="com-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="com-no" type="radio" name="commenting" value="1">
	    						<label for="com-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Commenting Field -->
	    			<!-- Start Ads Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Allow Ads</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="ads-yes" type="radio" name="ads" value="0" checked>
	    						<label for="ads-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="ads-no" type="radio" name="ads" value="1">
	    						<label for="ads-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Ads Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Add Category" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form>
	    	</div>

		<?php
	}elseif ($do == 'Insert') {

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		        echo "<h1 class='text-center'>Insrt Categories</h1>";
		    	echo "<div class='container'>";

	    		// Get Variables From The Form

	    		$name 		= $_POST['name'];
	    		$desc		= $_POST['description'];
	    		$order 		= $_POST['ordering'];
	    		$visible 	= $_POST['visibility'];
	    		$comment	= $_POST['commenting'];
	    		$ads 	    = $_POST['ads'];

	    		// Check If Category Exist In Database

    			$check = checkItem("name", "Categories", $name);

    			if ($check == 1) {
    				$theMsg = '<div class="alert alert-danger">Sorry This Categroy Is Exist</div>';
    				redirectHome($theMsg, 'back');
    			} else {
    			
		    		// Insert Category Info In Database

	    			$stmt = $con->prepare("INSERT INTO 
	    										categories(Name,  Description, Ordering, Visabilty , Allow_Comment, Allow_Ads)
	    									    VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads) ");
	    			$stmt->execute(array(

	    				'zname' 	=> $name,
	    				'zdesc' 	=> $desc,
	    				'zorder'	=> $order,
	    				'zvisible' 	=> $visible,
	    				'zcomment' 	=> $comment,
	    				'zads' 		=> $ads
	    			));

		    		// Echo Success Message 

		    		 $theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Inserted</div>';
		    		 redirectHome($theMsg, 'back');
			    }
	    		
	    	}else {
	    		echo "<div class='container'>";
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

	    		redirectHome($theMsg, 'back');
	    		echo "</div>";
	    	}

    	echo "</div>";	

	}elseif ($do == 'Edit') {

    	// Check If Get Request catid Is Numeric & Get The Integer Value Of It

    	$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0;

    	// Select All Data Depend On This ID

	    $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

	    // Execute  Query

	    $stmt->execute(array($catid));

	    // Fetch Query 

	    $cat  = $stmt->fetch();

	    // The Row Count 

	    $count = $stmt->rowCount();

	    // If Ther's Such ID Show The Form

	    if ($count > 0) { ?>


			<h1 class="text-center">Edit Categroy</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Update" method="POST">
	    			<input type="hidden" name="catid" value="<?php echo $catid ?>">
	    			<!-- Start Name Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Name</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name" class="form-control" required="required" value="<?php echo $cat['Name'] ?>">
	    				</div>
	    			</div>
	    			<!-- End Name Field -->
	    			<!-- Start Description Field -->
 	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Description</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="description" class=" form-control" value="<?php echo $cat['Description'] ?>">
	    				</div>
	    			</div>
	    			<!-- End Description Field -->
	    			<!-- Start Ordering Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Ordering</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'] ?>">
	    				</div>
	    			</div>
	    			<!-- End Ordering Field -->
	    			<!-- Start Visibility Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Visible</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visabilty'] == 0) {echo 'checked';}?> />
	    						<label for="vis-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visabilty'] == 1) {echo 'checked';}?>>
	    						<label for="vis-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Visibility Field -->
	    			<!-- Start Commenting Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Allow Commenting</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) {echo 'checked';}?> />
	    						<label for="com-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) {echo 'checked';}?> />
	    						<label for="com-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Commenting Field -->
	    			<!-- Start Ads Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Allow Ads</label>
	    				<div class="col-sm-10 col-md-6">
	    					<div>
	    						<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) {echo 'checked';}?>>
	    						<label for="ads-yes">Yes</label> 
	    					</div>
	    					<div>
	    						<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) {echo 'checked';}?>>
	    						<label for="ads-no">No</label> 
	    					</div>
	    				</div>
	    			</div>
	    			<!-- End Ads Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Save Category" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form>
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
    	echo "<h1 class='text-center'>Update Categroy</h1>";
    	echo "<div class='container'>";

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	    		// Get Variables From The Form

	    		$id 		= $_POST['catid'];
	    		$name 		= $_POST['name'];
	    		$desc 		= $_POST['description'];
	    		$order 		= $_POST['ordering'];

	    		$visible 	= $_POST['visibility'];
	    		$comment 	= $_POST['commenting'];
	    		$ads 		= $_POST['ads'];
	    			
	    		// Update The Database With This Info

	    		 $stmt = $con->prepare("UPDATE 
	    		 							categories  
	    		 						SET 
	    		 							Name = ?, 
	    		 							Description = ?, 
	    		 							Ordering = ?, 
	    		 							Visabilty = ?,
	    		 							Allow_Comment = ?, 
	    		 							Allow_Ads = ?
	    		 						WHERE 
	    		 							ID = ?");
	    		 $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));
	 
	    		 // Echo Success Message 

	    		$theMsg =  '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Update</div>';
	    		redirectHome($theMsg, 'back');
	    		
	    	}else {
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
	    		redirectHome($theMsg);
	    	}

    	echo "</div>";
	}elseif ($do == 'Delete') {

    	echo "<h1 class='text-center'>Delete Category</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request Catid Is Numeric & Get The Integer Value Of It

			    	$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("ID", "categories", $catid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
				    	$stmt->bindparam(":zid", $catid);

				    	$stmt->execute();

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Deleted</div>';
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