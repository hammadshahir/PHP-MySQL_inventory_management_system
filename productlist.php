<?php
  include_once('config/connectdb.php');

  session_start();

  if($_SESSION['useremail']=="" or $_SESSION['role']=="User") {
    header('location:index.php');
  } else {
    include_once('inc/header.php');
  }

	
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Products.
        <small>View / Update / Delete Products</small>
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
            <h3 class="box-title">List of all products with quantitities available. </h3>
          </div>

          <div class="box-body">
            <div style="overflow-x: auto;">
              <table class="table table-stripped" id="tableproductlist">
                      <thead>
                        
                        <tr>
                          <th>No.</th>
                          <th>Name</th>
                          <th>Model</th>
                          <th>Category</th>
                          <th>Purchase Price</th>
                          <th>Sales Price</th>
                          <th>Stock</th>
                          <th>Image</th>
                          <th>View</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>

                      </thead>
                      <tbody>
                          <?php

                            $select = $pdo->prepare("SELECT * FROM tbl_product ORDER BY pid ASC ");
                            
                            $select->execute();
                            
                            while($row = $select->fetch(PDO::FETCH_OBJ)) {

                              echo' <tr>
                                      <td>'.$row->pid.'</td>
                                      <td>'.$row->pname.'</td>
                                      <td>'.$row->pmodel.'</td>
                                      <td>'.$row->pcategory.'</td>
                                      <td>'.$row->purchase_price.'</td>
                                      <td>'.$row->sales_price.'</td>
                                      <td>'.$row->pstock.'</td>
                                      
                                      
                                      <td>
                                          <img src="productimages/'.$row->pimage.'"class=img-rounded" width="40px" height="40px">
                                        </td>
                                      
                                      <td>
                                          <a href="viewproduct.php?id='.$row->pid.'" class="btn btn-success btn-sm" role ="button" name="viewButton"><span class="glyphicon glyphicon-eye-open" style="color:#ffffff" data-toggle="tool-tip" title="View Product"></span></a>
                                        </td>
                                     
                                     <td>
                                        <a href="editproduct.php?id='.$row->pid.'" class="btn btn-warning btn-sm" role ="button" name="btnaupdate"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tool-tip" title="Edit Product"></span></a>
                                      </td>
                                     
                                     <td>
                                        <button id='.$row->pid.' class="btn btn-danger btn-sm btndanger btndelete"><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tool-tip" title="Delete Product"></span></button>
                                      </td>
                                    </tr> 
                                  ';
                              }
                          ?>
                      </tbody>
                    </table>
                </div>
          </div>

    </section>
    <!-- /.content -->
  </div>
  
  <!-- /code for data table -->

  <script type="text/javascript">
    
    $(document).ready(function () {
      
      $('#tableproductlist').DataTable({

        "order":[[0,"desc"]]

      });
        
    
    } );

  </script>

<!-- /code for tool tip -->

  <script type="text/javascript">
    
    $(document).ready(function () {
      
      $('[data-toggle="tool-tip"]').tooltip();
        
    } );

  </script>

  <!-- Delete Button Code -->
  <script type="text/javascript">
    $(document).ready(function(){

      $('.btndelete').click(function(){
        var tdh = $(this);
        var id = $(this).attr("id");


        // Sweet Alert Code

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this product data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {

                 $.ajax({
                          url:'deleteproduct.php',
                          type:'post',
                          data:{
                            pidd:id
                          },
                          success:function(data) {
                            tdh.parents('tr').hide();
                          }
                        });

              swal("Poof! Your product has been deleted.", {
                icon: "success",
              });
            } else {
              swal("Your product is not deleted.");
            }
          });


     
      });

    });
  </script>
  

 <?php
	
	include_once('inc/footer.php');
?>