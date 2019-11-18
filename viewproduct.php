<?php
  include_once('config/connectdb.php');
  session_start();
	include_once('inc/header.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Product.
        <small>View Product Details.</small>
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

            <?php 
              
              $id = $_GET['id'];
              $select = $pdo->prepare(" SELECT * FROM tbl_product WHERE pid=".$id);
                $select->execute();
                while($record = $select->fetch(PDO::FETCH_OBJ)) {
                  echo'
                    <div class="col-md-6">
                    <p align="center" class="list-group-item list-group-item-info"><strong>Product Details</strong></p>
                     <ul class="list-group">
                        <li class="list-group-item">Product ID: <span class="label label-success pull-right">'.$record->pid.'</span></li>
                        <li class="list-group-item">Product Name: <span class="label label-success pull-right">'.$record->pname.'</span></li>
                        <li class="list-group-item">Product Model: <span class="label label-success pull-right">'.$record->pmodel.'</span></li>
                        <li class="list-group-item">Product Category: <span class="label label-success pull-right">'.$record->pcategory.'</span></li>
                        <li class="list-group-item">Sales Price: <span class="label label-success pull-right">'.$record->sales_price.'</span></li>
                        <li class="list-group-item">Purchase Price: <span class="label label-success pull-right">'.$record->purchase_price.'</span></li>
                        <li class="list-group-item">Expected Profit <span class="label label-success pull-right">'.($record->sales_price - $record->purchase_price ).'</span></li>
                        <li class="list-group-item">Stock in Hand: <span class="label label-success pull-right">'.$record->pstock.'</span></li>
                        <li class="list-group-item"><strong>Description:</strong> <span class="">'.$record->pdescription.'</span></li>
                      </ul>
                    </div>

                    <div class="col-md-6">
                    <p align="center" class="list-group-item list-group-item-info"><strong>Product Image</strong></p>
                      <ul class="list-group text-center">
                        <li class="list-group-item center"><img src="productimages/'.$record->pimage.'"class=img-responsive"></li>
                        
                      </ul>
                    </div>

                  '; 

                }
            ?>
            
          </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
	
	include_once('inc/footer.php');
?>