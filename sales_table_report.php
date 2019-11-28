<?php
  
  include_once('config/connectdb.php');
  error_reporting(0);
  session_start();

	include_once('inc/header.php');


   
    // SQL Query to get sum of total sales

  $select = $pdo->prepare("
                          SELECT 
                          sum(total_amount) as total, 
                          sum(tax) as tax, 
                          count(invoice_id) as invoice 
                          FROM tbl_invoice 
                          WHERE order_date 
                          BETWEEN :fromdate AND :todate
                        ");

  $select->bindParam(':fromdate', $_POST['date_1']);
  $select->bindParam(':todate', $_POST['date_2']);
  
  $select->execute();
  $row=$select->fetch(PDO::FETCH_OBJ);

  $net_total = $row->total;
  $vat = $row->tax;
  $invoice = $row->invoice;

           



?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales Report
        <small>View Sales Reports in Tablular Form</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Up</li>
      </ol>
    </section>

    <!-- Main content -->
  <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-primary">
          
          <div class="box-header with-border">
            <h3 class="box-title"></h3>
          </div>
        <form  action="" method="post" name="">
          <div class="box-body">
            <div><label>Choose date range:</label></div>
            
            <div class="row">
              
              <div class="col-md-5">
                <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                   <input type="text" class="form-control pull-right" id="datepicker1" placeholder="From" name="date_1" data-date-format="yyyy-mm-dd" >
                            </div>
              </div>

               <div class="col-md-5">
                <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker2" placeholder="To" name="date_2" data-date-format="yyyy-mm-dd" >
                            </div>
              </div>

               <div class="col-md-2">
                <input type="submit" name="btndatefilter" value="Run Report" class="btn btn-primary">
              </div>

            </div>

           </div>

      <div class="box-body">
          
       <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            
            <span class="info-box-icon bg-orange"><i class="fa fa-hashtag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">No. of Invoices</span>
              <span class="info-box-number"><h3><?php echo '<i class="fa fa-hashtag" aria-hidden="true"></i>' . $invoice; ?></h3></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-eur"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Sales</span>
              <span class="info-box-number"><h3><?php echo '<i class="fa fa-eur" aria-hidden="true"></i>'. number_format($net_total, 2); ?></h3></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-percent"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">VAT Amount</span>
              <span class="info-box-number"><h3><?php echo '<i class="fa fa-eur" aria-hidden="true"></i>'.  number_format($vat, 2); ?></h3></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
      </div>
  </div>
 <div class="box-body">
   <div style="overflow-x: auto;">
  <table class="table table-stripped" id="sales_report_table">
    <thead>
      <tr>
        <th colspan="7">From: <?php echo $_POST['date_1']; ?> To: <?php echo $_POST['date_2']; ?></th>

      </tr>
    <tr>
      <th>Invoice #</th>
      <th>Customer Name</th>
      <th>Order Date</th>
      <th>Subtotal</th>
      <th>Total</th>
      <th>VAT</th>
      <th>Due</th>
      <th>Payment Type</th>


    </tr>
    </thead>
    <tbody>
      <!-- SQL Query to fetch data -->
      <?php 

        $select = $pdo->prepare("
                                  SELECT * 
                                  FROM tbl_invoice
                                  WHERE order_date
                                  BETWEEN :fromdate AND :todate
                                  ");
        $select->bindParam(':fromdate',$_POST['date_1']);
        $select->bindParam(':todate', $_POST['date_2']);
        
        $select->execute();
        
        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
          echo '<tr>
                <td>'.$row->invoice_id.'</td>
                <td>'.$row->customer_name.'</td>
                <td>'.$row->order_date.'</td>
                <td>'.$row->subtotal.'</td>
                <td>'.$row->total_amount.'</td>
                <td>'.$row->tax.'</td>
                <td>'.$row->due_amount.'</td>
                
              ';

               if($row->payment_type=="Cash"){
        
              echo'<td><span class="label label-success">'.$row->payment_type.'</span></td>';  
            }elseif($row->payment_type=="Card"){
                echo'<td><span class="label label-primary">'.$row->payment_type.'</span></td>';  
            }else{
                 echo'<td><span class="label label-warning">'.$row->payment_type.'</span></td>';
            }
                

                }
              ?>

      <?php '</tr>' ?>
    
    </tbody>
  </table>
</div>
</div>
</form>

</section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
  
  <script>
      //Date picker
      $('#datepicker1').datepicker({
          autoclose: true
      });

      $('#datepicker2').datepicker({
          autoclose: true
      });

  // Datatable 
    
    $(document).ready(function () {
      
      $('#sales_report_table').DataTable({

        "order":[[0,"desc"]]

      });
        
    
    } );

  </script>
 
 <?php
	
	include_once('inc/footer.php');
?>