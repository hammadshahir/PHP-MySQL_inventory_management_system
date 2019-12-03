<?php

  include_once('config/connectdb.php');
  
  session_start();

  if($_SESSION['useremail']=="" OR $_SESSION['role']=="User") {
    
    header('location:index.php')  ; 
  } 
    include_once('inc/header.php');  

  if(isset($_POST['btnsave'])) {

    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $userpass = $_POST['txtpassword'];
    $userrole = $_POST['txtselect_option'];

    if(isset($_POST['txtemail'])) {

      $select = $pdo->prepare("SELECT useremail FROM tbl_user WHERE useremail = '$useremail' ");
      $select->execute();
      if($select->rowCount() > 0) {
        echo '<script type="text/javascript">
                    jQuery(function validation(){
                      swal({
                        title:"Error",
                        text: "User Already Exists.",
                        icon: "error",
                        button: "Try Again",

                        });
                      });

                </script>';

        } else {

          $insert = $pdo->prepare("INSERT INTO tbl_user(username, useremail, password, role) VALUES (:name, :email, :pass, :role)");

          $insert->bindParam(':name', $username);
          $insert->bindParam(':email', $useremail);
          $insert->bindParam(':pass', $userpass);
          $insert->bindParam(':role', $userrole);

            if($insert->execute()) {
              
              echo '<script type="text/javascript">
                        jQuery(function validation(){
                          swal({
                            title:"Success",
                            text: "New User Registered Successfully",
                            icon: "success",
                            button: "Close",

                            });
                          });

                    </script>';
          
            } else {

              echo '<script type="text/javascript">
                        jQuery(function validation(){
                          swal({
                            title:"Error",
                            text: "Something went wrong.",
                            icon: "error",
                            button: "Try Again",

                            });
                          });

                    </script>';
              }
          } 
        }
    } // end of main if-isset

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Management.
        <small>Add or Delete User in the Portal.</small>
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

         <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">All fields are required. </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="POST">
              <div class="box-body">
                <div class="col-md-4">

                  <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" class="form-control" name="txtname" id="full_name" placeholder="Enter Full Name" required>
                  </div>
                  <div class="form-group">
                    <label for="useremail">Email Address:</label>
                    <input type="email" class="form-control" name="txtemail" id="useremail" placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <label for="userpassword">Password:</label>
                    <input type="password" class="form-control" name="txtpassword" id="userpassword" placeholder="Enter Password">
                </div>

                <div class="form-group">
                  <label>Choose User Type (By Default: User)</label>
                  <select class="form-control" name = "txtselect_option">
                    <option>User</option>
                    <option>Admin</option>
                  </select>
                </div>
              
                <div class="box-footer">
                    <button type="submit" name="btnsave" class="btn btn-primary">Register New User</button>
                </div>  
              
              </div>
              <div class="col-md-8">
                  <h3>Existing Users</h3>
                  <table class="table table-stripped">
                    <thead>
                      
                      <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>User Role</th>
                        <th>Action</th>
                      </tr>

                    </thead>
                    <tbody>
                        <?php

                          $select = $pdo->prepare("SELECT * FROM tbl_user ORDER BY userid ASC ");
                          
                          $select->execute();
                          
                          while($row = $select->fetch(PDO::FETCH_OBJ)) {

                            echo' <tr>
                                    <td>'.$row->userid.'</td>
                                    <td>'.$row->username.'</td>
                                    <td>'.$row->useremail.'</td>
                                    <td>'.$row->role.'</td>
                                   <td><a href="registeration.php?id='.$row->userid.'" class="btn btn-danger btn-sm" role ="button" name="deleteButton"><span class="glyphicon glyphicon-trash" title="delete"></span></a></td>
                                  </tr> 
                                ';
                            }
                        ?>
                    </tbody>
                  </table>

                </div>
                 </div>
              <!-- /.box-body -->

              
            </form>
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
	
	 include_once('inc/footer.php');

?>