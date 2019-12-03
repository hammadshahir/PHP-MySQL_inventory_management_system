<!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!-- Admin LTE App -->
  <script type="text/javascript" src="dist/js/adminlte.min.js"></script>
   <!-- Sweet Alerts -->
  <script src="bower_components/sweetalert/sweetalert.js"></script>
	

<?php

	include_once('config/connectdb.php');
	session_start();
	

	if(isset($_POST['btn_login'])) {

		$useremail = $_POST['txt_email'];
		$pass = $_POST['txt_password'];

		// SQL query for login page

		$select = $pdo->prepare("SELECT *
								FROM tbl_user
								WHERE useremail = '$useremail' AND password = '$pass' ");
		
		$select->execute();
			
		$row = $select->fetch(PDO::FETCH_ASSOC);
		

		if( $row['useremail'] == $useremail AND 
			$row['password'] == $pass AND 
			$row['role']=="Admin")
		{
			$_SESSION['userid']=$row['userid'];
			$_SESSION['username']=$row['username'];
			$_SESSION['useremail']=$row['useremail'];
			$_SESSION['role']=$row['role'];
			
			header('refresh:0.5; dashboard.php'); 
		
		} else if(	$row['useremail'] == $useremail AND 
					$row['password'] == $pass AND 
					$row['role']=="User")

		{
			$_SESSION['userid']=$row['userid'];
			$_SESSION['username']=$row['username'];
			$_SESSION['useremail']=$row['useremail'];
			$_SESSION['role']=$row['role'];

			echo $success='User Login Successful';
			header('refresh:0.5; user.php'); 

		} else {

			echo '<div class="alert alert-danger" role="alert">
  					<center>Something went wrong. Check both username and password are correct.</center>
				</div>';
		
		} // end of if--else
	
	} // end of login code
	
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventory LTE | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">


  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="container">
	

<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b> Inventory </b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><strong>Account Login</strong></p>

    <form action="#" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="txt_email" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="txt_password" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <!-- <label>
              <input type="checkbox"> Remember Me
            </label> -->
            <a href="#" onclick="swal('Forgot Password?', 'Please check with your administrator to reset password.', 'error');">I forgot my password</a>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="btn_login" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

   

  </div>
</div>
</div>



<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

</body>
</html>
