<?php
	include_once('config/connectdb.php');
	session_start();

	if($_SESSION['useremail']=="" or $_SESSION['role']=="User") {
		header('location:index.php');
	}
	include_once('inc/header.php');

// PHP Code to Add Product

	if(isset($_POST['btnaddproduct'])) {

		$productname = $_POST['txtproductname'];
		$productmodel = $_POST['txtproductmodel'];
		$category = $_POST['txtselect_option'];
		$purchaseprice = $_POST['txtppurchaseprice'];
		$salesprice = $_POST['txtpsalesprice'];
		$stock = $_POST['txtstock'];
		$pdescription = $_POST['txtdescription'];

		// Code to add image

		$f_name= $_FILES['myfile']['name'];  
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
           
           $productimage=$f_newfile;
            if(!isset($errorr)){
     
		$insert = $pdo->prepare("
								INSERT INTO tbl_product(pname, pmodel, pcategory, purchase_price, sales_price, pstock, pdescription, pimage) 
								VALUES(:pname,:pmodel, :pcategory, :purchaseprice, :salesprice, :pstock, :pdescription, :pimage)"); 
     
	     $insert->bindParam(':pname', $productname);
	     $insert->bindParam(':pmodel', $productmodel); 
	     $insert->bindParam(':pcategory', $category);
	     $insert->bindParam(':purchaseprice', $purchaseprice);
	     $insert->bindParam(':salesprice', $salesprice);
	     $insert->bindParam(':pstock', $stock);
	     $insert->bindParam(':pdescription', $pdescription);
	     $insert->bindParam(':pimage', $productimage);
     
     
		if($insert->execute()){
    
		    echo'<script type="text/javascript">
				jQuery(function validation(){


					swal({
					  title: "Add product Successfull!",
					  text: "Product Added",
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
					  text: "Add product Fail",
					  icon: "error",
					  button: "Ok",
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
						  button: "Ok",
						});

					});

					</script>';
	       
	  		echo $error;
		}    
       
}


?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Products
        <small>Add a new product.</small>
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
		                    <input type="text" class="form-control" name="txtproductname" id="full_name" placeholder="Enter Product Name" required>
	                  	</div>
	                  	<div class="form-group">
		                    <label for="userpassword">Product Model:</label>
		                    <input type="text" class="form-control" name="txtproductmodel" id="txtprice" placeholder="Enter Product Model">
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
			                    		<option><?php echo $record['category']; ?></option>
			                    	
			                    	<?php
			                    		
			                    		}
			                    	?>
			                    </select>                
			                </div>
	                 
		                <div class="form-group">
		                    <label for="userpassword">Product Purchase Price:</label>
		                    <input type="number" min="1"  class="form-control" name="txtppurchaseprice" id="txtproductprice" placeholder="Enter Purchase Price" required>
		                </div>
		                 <div class="form-group">
		                    <label for="userpassword">Sales Price:</label>
		                    <input type="number" min="1" class="form-control" name="txtpsalesprice" id="txtsaleprice" placeholder="Enter Sales Price" required>
		                </div>
	          		</div>
	          		<div class="col-md-6">
	          			 <div class="form-group">
		                    <label for="userpassword">Quantity:</label>
		                    <input type="number" min="1" step="1" class="form-control" name="txtstock" id="txtsaleprice" placeholder="Enter Product Quantity" required>
	                	</div>
	                	<div class="form-group">
		                    <label for="userpassword">Product Description:</label>
		                    <textarea class="form-control" name="txtdescription" id="txtdescription" placeholder="Enter Product Description" rows="4"></textarea>
	                	</div>
	                	<div class="form-group">
		                    <label for="userpassword">Choose Image:</label>
		                    <input type="file" class="input-group" name="myfile">
	                	</div>
	          		</div>	
	          		 <div class="box-footer" align="center">
	          	
	          		
	          		<button type="submit" name="btnaddproduct" class="btn btn-primary btn-sm" ><span class="glyphicon glyphicon-plus" title="Create "></span> Add New Product</button>
	          	
	          </div>
	          	</form>

	          </div>
	         
        </div>	

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
	
	include_once('inc/footer.php');
?>