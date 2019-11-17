
<?php
	
	include_once('config/connectdb.php');

	session_start();
	
	if($_SESSION['useremail']=="") {

		header('location:index.php');
	}

	// Changing header layout.

	if ($_SESSION['role']=="Admin") {
		
		include_once('inc/header.php');
	
	} else {
		
		include_once('inc/header_user.php');	
	}

	

	/* Change Password
  	 
  	Step 1: when user clicks on change password button, we want to get values from user and store them in variable.

	*/

  	 if (isset($_POST['btn_update'])) {
  	 	
  	 	$txt_oldpassword = $_POST['txtoldpassword'];
  	 	$txt_newpassword = $_POST['txtnewpassword'];
  	 	$txt_confirmpass = $_POST['txtconfirmpassword'];

  	 // Step 2: Get recrod using select statement according to user email.

  	 	$email = $_SESSION['useremail'];

  	 	$select = $pdo->prepare(" SELECT * FROM tbl_user WHERE useremail='$email' ");
  	 	$select->execute();

  	 	$row = $select->fetch(PDO::FETCH_ASSOC);
  	 	

  	 	$useremail_db = $row['useremail'];  // these values are coming from db
  	 	$password_db = $row['password'];

  	// Step 3: Compare values (user input and database values)


  	 	if($txt_oldpassword == $password_db) {
  	 		
  	 		if($txt_newpassword == $txt_confirmpass) {

  	 			$update = $pdo->prepare("UPDATE tbl_user SET password=:pass WHERE useremail =:email ");
  	 			
  	 			$update->bindParam(':pass', $txt_confirmpass);
  	 			$update->bindParam(':email', $email);

	  	 			if($update->execute()) {
	  	 			
	  	 				echo '<script type="text/javascript">
	  	 						jQuery(function validation(){
	  	 							swal({
	  	 								title:"Success",
	  	 								text: "Password Changed.",
	  	 								icon: "success",
	  	 								button: "Ok",

	  	 								});
	  	 							});

	  	 					</script>';
	  	 			} else {
	  	 				echo 'Password not changed. Try again.';
	  	 			}

  	 		} else {

  	 			echo '<script type="text/javascript">
	  	 						jQuery(function validation(){
	  	 							swal({
	  	 								title:"Error",
	  	 								text: "Passwords Do Not Match.",
	  	 								icon: "error",
	  	 								button: "Enter Again",

	  	 								});
	  	 							});

	  	 					</script>';
  	 		}

  	 	} else {
  	 		echo '<script type="text/javascript">
	  	 						jQuery(function validation(){
	  	 							swal({
	  	 								title:"Error",
	  	 								text: "Old Password Does not Match",
	  	 								icon: "error",
	  	 								button: "Enter Again",

	  	 								});
	  	 							});

	  	 					</script>';
  	 	}


  	 }

  	
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Change Password
        <!-- <small>You can manage all administration tasks from here.</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

         <form action="" method="POST">
              <div class="box-body">
                
                <div class="form-group">
                  <label for="oldpassword">Old Password</label>
                  <input type="text" class="form-control" name="txtoldpassword" id="txtoldpassword" placeholder="Enter Old Password" required>
                </div>

                <div class="form-group">
                  <label for="newpassword">New Password</label>
                  <input type="password" class="form-control" name="txtnewpassword" id="txtnewpassword" placeholder="Enter New Password" required>
                </div>

                 <div class="form-group">
                  <label for="confirmedpassword">Confirm New Password</label>
                  <input type="password" class="form-control" name="txtconfirmpassword" id="txtconfirmpassword" placeholder="Confirm New Password" required>
                </div>
               
                
              </div>
              <!-- /.box-body -->

              <div class="form-group">
                <button type="submit" class="btn btn-primary" name="btn_update">Change Password</button>
              </div>
            </form>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
	
	include_once('inc/footer.php');
?>