<?php

  include_once('config/connectdb.php');
  
  session_start();

  if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){
    header('location:index.php');
  }

	 include_once('inc/header.php');

   // Insert Data into database (Save Button)

   if(isset($_POST['btnsave'])) {

      $category = $_POST['txtcategory'];
      
      if(empty($category)) {

        $error = '<script type="text/javascript">
          jQuery(function validation(){
            swal({
              title:"Error",
              text: "Category name is required.",
              icon: "error",
              button: "Try Again",

              });
            });

        </script>';

        echo $error;

      } 

      //if(!isset($error)) {
      else {
        $insert = $pdo->prepare("INSERT INTO tbl_category(category) VALUES (:category)");
        $insert->bindParam(':category', $category);
        $result = $insert->execute();
        if ($result) {
          echo '<script type="text/javascript">
                 jQuery(function validation(){
                  swal({
                     title:"Success",
                     text: "New Category Added",
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
                     text: "Check Again",
                     icon: "error",
                     button: "Try Again",
                     });
                  });
                </script>';
        }
      } 
  } // Code for Save Button Ends Here

  // Start of Update Button to INSERT data into Database.

  if (isset($_POST['btnupdate'])) {
    $category = $_POST['txtcategory'];
    $id = $_POST['txtid'];
      
      if(empty($category)) {

        $error_update = '<script type="text/javascript">
          jQuery(function validation(){
            swal({
              title:"Error",
              text: "Category name is required.",
              icon: "error",
              button: "Try Again",

              });
            });

        </script>';

        echo $error_update;

      }

      if(!isset($error_update))  {

        $update = $pdo->prepare("UPDATE tbl_category SET category= :category WHERE catid =" .$id);
        $update->bindParam(':category', $category);
        //$update->bindParam(':id', $id);
        
        if($update->execute()) {

          echo '<script type="text/javascript">
                  jQuery(function validation(){
                    swal({
                      title:"Updated",
                      text: "Category name is Updated.",
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
                    text: "Someting went wrong.",
                    icon: "error",
                    button: "Try Again",
                  });
                });
              </script>';
        }
    } 
  } // btnupdate ends here. 

  // Start code for Delete Button

  if (isset($_POST['btndelete'])) {
     $delete = $pdo->prepare("DELETE FROM tbl_category WHERE catid=".$_POST['btndelete']);
     $result = $delete->execute();
     if ($result) {
       echo '<script type="text/javascript">
                jQuery(function validation(){
                  swal({
                    title:"Deleted",
                    text: "Record Deleted.",
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
                    text: "Someting went wrong.",
                    icon: "error",
                    button: "Try Again",
                  });
                });
              </script>';

     }

   } // end of delete button code

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Categories.
        <small>Add / Remove / Edit Categories.</small>
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
            
          <div class="box-body">
              <form action="" method="POST">
                <?php 
                  
                  // Code for for Edit Button
                  if(isset($_POST['btnedit'])) {

                    $select = $pdo->prepare(" SELECT *
                                              FROM tbl_category
                                              WHERE catid=".$_POST['btnedit']);
                    
                    $select->execute();
                    
                    if($select) {
                      $record = $select->fetch(PDO::FETCH_OBJ);
                       echo '
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Category:</label>
                              <input type="hidden" class="form-control" value="'.$record->catid.'"name="txtid">
                              <input type="text" class="form-control" value="'.$record->category.'" name="txtcategory" placeholder="Enter Category Name">
                            </div>
                          <div class="box-footer">
                              <button type="submit" name="btnupdate" class="btn btn-primary">Update Category</button>
                          </div>  
                          ';
                    }

                  } else {

                    echo '
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Category:</label>
                              <input type="text" class="form-control" name="txtcategory" placeholder="Enter Category Name">
                            </div>
                          <div class="box-footer">
                              <button type="submit" name="btnsave" class="btn btn-primary">Add Category</button>
                          </div>  
                          ';
                    }
                ?>
                
                </div>
                <div class="col-md-8">
                  <h3>Existing Categories</h3>
                  
                  <table id="table_category" class="table table-stripped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Category</th>
                        <th class="text-center">Edit Record</th>
                        <th class="text-center">Delete Record</th>
                      </tr>
                    </thead>

                    <tbody>
                      
                      <?php 
                      
                        $select = $pdo->prepare("SELECT * FROM tbl_category");
                        $select->execute();
                          
                          while($row = $select->fetch(PDO::FETCH_OBJ)) {
                            
                            echo'<tr>
                                  
                                  <td>'.$row->catid.'</td>
                                  <td>'.$row->category.'</td>
                                  <td>
                                    <button type = "submit" class="btn btn-success btn-sm" value = "'.$row->catid.'" name="btnedit" >Edit</button>
                                  </td> 

                                  <td>
                                    <button type="submit" class = "btn btn-danger btn-sm" value = "'.$row->catid.'" name="btndelete">Delete</button>
                                  </td>        
                                
                                </tr>';
                            }
                      ?> 

                    </tbody>
                  </table> 

              </div>
            </form>
          </div>
              <!-- /.box-body -->
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <!-- Call Single Function for DataTable -->  

   <script>

      $(document).ready(function () {

        $('#table_category').DataTable()
      });

    </script>

 <?php
	include_once('inc/footer.php');
?>