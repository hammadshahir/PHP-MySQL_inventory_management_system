<?php
	include_once('config/connectdb.php'); 
  if($_SESSION['useremail']=="" or $_SESSION['role']=="User") {
    header('location:index.php');
  } else {
    include_once('inc/header.php');
  }
  

  $id = $_POST['pidd'];

  
  $delete = $pdo->prepare("DELETE FROM tbl_product WHERE pid=$id");
  $result = $delete->execute();
  if($result) {


  } else {

    echo '

      <script type="text/javascript">
          jQuery(function validation(){
            swal({
              title: "Error",
              text: "Something went wrong",
              icon: "error",
              button: "Go Back",
            });

          });

          </script>

    ';
  }

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-primary">
          
          <div class="box-header with-border">
            
          </div>

          <div class="box-body">
            
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
	
	include_once('inc/footer.php');
?>