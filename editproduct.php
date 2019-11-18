<?php
  include_once('config/connectdb.php');
  session_start();
  
  if($_SESSION['useremail']=="" or $_SESSION['role']=="User") {
    header('location:index.php');
  }

	include_once('inc/header.php');

  // Code to Get ID and load database data into variables to display in html form fields.
  $id = $_GET['id'];

  $select = $pdo->prepare("SELECT * FROM tbl_product WHERE pid = ".$id);
  $select->execute();

  $record = $select->fetch(PDO::FETCH_ASSOC);

  $id_db = $record['pid'];
  $productname_db = $record['pname'];
  $productmodel_db = $record['pmodel'];
  $category_db = $record['pcategory'];
  $purchaseprice_db = $record['purchase_price'];
  $salesprice_db = $record['sales_price'];
  $stock_db = $record['pstock'];
  $description_db = $record['pdescription'];
  $productimage_db = $record['pimage'];

  // Code for Update Button

  if(isset($_POST['btnaupdate'])) {

    $productname_txt = $_POST['txtproductname'];
    $productmodel_txt = $_POST['txtproductmodel'];
    $category_txt = $_POST['txtselect_option'];
    $purchaseprice_txt = $_POST['txtppurchaseprice'];
    $salesprice_txt = $_POST['txtpsalesprice'];
    $stock_txt = $_POST['txtstock'];
    $pdescription_txt = $_POST['txtdescription'];

    // Code to add image

    $f_name= $_FILES['myfile']['name'];  

      if(!empty($f_name)) {

        $f_tmp = $_FILES['myfile']['tmp_name'];
        $f_size =  $_FILES['myfile']['size'];
        $f_extension = explode('.',$f_name);
        $f_extension= strtolower(end($f_extension));
        $f_newfile =  uniqid().'.'. $f_extension;   
        $store = "productimages/".$f_newfile;
    
        if($f_extension=='jpg' || $f_extension=='jpeg' ||  $f_extension=='png' || $f_extension=='gif'){
        
            if($f_size>=1000000 ){
        
           
            $error = '<script type="text/javascript">
              jQuery(function validation(){


              swal({
                title: "Error!",
                text: "Max file should be 1MB or less",
                icon: "warning",
                button: "Choose Image Again",
              });

            });

            </script>';
           
          echo $error;      
        
            } else {
              
               if(move_uploaded_file($f_tmp,$store)){
                   
                   $f_newfile;
                    if(!isset($error)){
             
               $update = $pdo->prepare(" UPDATE tbl_product SET 
                                              pname = :pname,
                                              pmodel = :pmodel, 
                                              pcategory = :pcategory, 
                                              purchase_price = :pprice,
                                              sales_price = :sprice, 
                                              pstock = :pstock, 
                                              pdescription = :pdescription,
                                              pimage =:pimage 
                                              WHERE pid = $id ");
                
                // Bind Parameters
                $update->bindParam(':pname',$productname_txt);
                $update->bindParam(':pmodel',$productmodel_txt);
                $update->bindParam(':pcategory',$category_txt);
                $update->bindParam(':pprice',$purchaseprice_txt);
                $update->bindParam(':sprice',$salesprice_txt);
                $update->bindParam(':pstock',$stock_txt);
                $update->bindParam(':pdescription',$pdescription_txt);
                $update->bindParam(':pimage',$f_newfile);
     
     
                  if($update->execute()){
                  
                      echo'<script type="text/javascript">
                      jQuery(function validation(){


                        swal({
                          title: "Success",
                          text: "Product Updated",
                          icon: "success",
                          button: "Ok",
                        });

                    });

                  </script>';
                  
                  } else {
                      
                     echo'<script type="text/javascript">
                        jQuery(function validation(){

                        swal({
                          title: "ERROR!",
                          text: "Something went wrong.",
                          icon: "error",
                          button: "Try Again",
                        });


                        });

                      </script>';  
                  }     
                 
                }    
                     }  
                      
                  }   
                  
                  
                  
                } else {  
                      $error = '<script type="text/javascript">
                        jQuery(function validation(){
                          swal({
                            title: "Warning!",
                            text: "only jpg ,jpeg, png and gif can be upload!",
                            icon: "error",
                            button: "Change File Type",
                          });

                        });

                        </script>';
                       
                      echo $error;
                  }    
                     
   } else {

        $update = $pdo->prepare(" UPDATE tbl_product SET 
                                  pname = :pname,
                                  pmodel = :pmodel, 
                                  pcategory = :pcategory, 
                                  purchase_price = :pprice,
                                  sales_price = :sprice, 
                                  pstock = :pstock, 
                                  pdescription = :pdescription,
                                  pimage =:pimage 
                                  WHERE pid = $id ");
        
        // Bind Parameters
        $update->bindParam(':pname',$productname_txt);
        $update->bindParam(':pmodel',$productmodel_txt);
        $update->bindParam(':pcategory',$category_txt);
        $update->bindParam(':pprice',$purchaseprice_txt);
        $update->bindParam(':sprice',$salesprice_txt);
        $update->bindParam(':pstock',$stock_txt);
        $update->bindParam(':pdescription',$pdescription_txt);
        $update->bindParam(':pimage',$productimage_db);
        
        if($update->execute()) {
           echo '<script type="text/javascript">
                  jQuery(function validation(){


                    swal({
                      title: "Success",
                      text: "Product Updated",
                      icon: "success",
                      button: "Close",
                    });

                  });

              </script>';
          } else {

             echo'<script type="text/javascript">
                  jQuery(function validation(){


                    swal({
                      title: "Error",
                      text: "Something went wrong, Check again.",
                      icon: "error",
                      button: "Try again",
                    });

                  });

              </script>';
              }


      }

   }// end of main if

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Product.
        <small>Edit product details.</small>
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
              <a href="productlist.php" class="btn btn-success btn-sm" role ="button"><span class="glyphicon glyphicon-align-justify" title="Product List"></span> Back to Products List</a>
              
            </div>
              
            <div class="box-body">
              <p>All fields are required. </p>
              <form action="" method="POST" name="form_product" enctype = "multipart/form-data">
                <div class="col-md-6">
                
                <div class="form-group">  
                      <label for="product">Product Name:</label>
                      <input type="text" class="form-control" name="txtproductname" value="<?php echo $productname_db ?>" id="full_name" placeholder="Enter Product Name" required>
                    </div>
                    <div class="form-group">
                      <label for="userpassword">Product Model:</label>
                      <input type="text" class="form-control" name="txtproductmodel" value="<?php echo $productmodel_db ?>" id="txtprice" placeholder="Enter Product Model">
                  </div>
                  <div class="form-group">
                      <label for="category">Choose Product Category:</label>
                      <select class="form-control" name="txtselect_option" required>
                        <option value="">Select Category</option>
                        <?php
                          
                          $select = $pdo->prepare("SELECT * FROM tbl_category ORDER BY catid ASC");
                          $select->execute();
                          
                          while ($record = $select->fetch(PDO::FETCH_ASSOC)) {
                            extract($record);
                          
                        ?>
                          <option <?php if($record['category']==$category_db) { ?>

                            selected="selected"

                             <?php } ?>

                            ?> <?php echo $record['category']; ?></option>
                        
                        <?php
                          
                          }
                        ?>
                      </select>                
                  </div>
                   
                  <div class="form-group">
                      <label for="userpassword">Product Purchase Price:</label>
                      <input type="number" min="1"  class="form-control" name="txtppurchaseprice" value="<?php echo $purchaseprice_db  ?>" id="txtproductprice" placeholder="Enter Purchase Price" required>
                  </div>
                   <div class="form-group">
                      <label for="userpassword">Sales Price:</label>
                      <input type="number" min="1" class="form-control" name="txtpsalesprice" value="<?php echo $salesprice_db ?>" id="txtsaleprice" placeholder="Enter Sales Price" required>
                  </div>
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label for="userpassword">Quantity:</label>
                        <input type="number" min="1" step="1" class="form-control" name="txtstock" value="<?php echo $stock_db ?>" id="txtsaleprice" placeholder="Enter Product Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="userpassword">Product Description:</label>
                        <textarea class="form-control" name="txtdescription" id="txtdescription" placeholder="Enter Product Description" rows="4"><?php echo $description_db ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="userpassword">Image</label>
                        <img src="productimages/<?php echo $productimage_db ?>" class="img-responsive" width="50px" height="50px">
                    </div>
                    <div class="form-group">
                        <label for="userpassword">Choose Image:</label>
                        <input type="file" class="input-group" name="myfile">
                    </div>
                </div>  
                 <div class="box-footer" align="center">
              
                
                <button type="submit" name="btnaupdate" class="btn btn-warning btn-sm" ><span class="glyphicon glyphicon-plus" title="Create "></span> Update Product</button>
              
            </div>
              </form>

            </div>
           
        

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
	
	include_once('inc/footer.php');
?>