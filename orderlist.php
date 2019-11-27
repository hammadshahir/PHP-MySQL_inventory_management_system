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
        Orders.
        <small>View all orders here</small>
      </h1>
     <!--  <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Up</li>
      </ol> -->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-primary">
          
          <div class="box-header with-border">
            <h3 class="box-title">List of all orders.</h3>
          </div>

          <div class="box-body">

             <div style="overflow-x: auto;">
              <table class="table table-stripped" id="orderlisttable">
                      <thead>
                        
                        <tr>
                          <th>Invoice No.</th>
                          <th>Customer Name</th>
                          <th>Order Date</th>
                          <th>Total</th>
                          <th>Paid</th>
                          <th>Due</th>
                          <th>Payment Method</th>
                          <th>Print</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>

                      </thead>
                      <tbody>
                          <?php

                            $select = $pdo->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id ASC ");
                            
                            $select->execute();
                            
                            while($row = $select->fetch(PDO::FETCH_OBJ)) {

                              echo' <tr>
                                      <td>'.$row->invoice_id.'</td>
                                      <td>'.$row->customer_name.'</td>
                                      <td>'.$row->order_date.'</td>
                                      <td>'.$row->total_amount.'</td>
                                      <td>'.$row->paid_amount.'</td>
                                      <td>'.$row->due_amount.'</td>
                                      <td>'.$row->payment_type.'</td>
                                                                           
                                      <td>
                                          <a href="invoice_db.php?id='.$row->invoice_id.'" class="btn btn-success btn-sm" role ="button" name="viewButton"><span class="glyphicon glyphicon-print" style="color:#ffffff" data-toggle="tool-tip" title="Print Invoice"></span></a>
                                        </td>
                                     
                                     <td>
                                        <a href="editorder.php?id='.$row->invoice_id.'" class="btn btn-warning btn-sm" role ="button" name="btnaupdate"><span class="glyphicon glyphicon-edit" style="color:#ffffff" data-toggle="tool-tip" title="Edit Order"></span></a>
                                      </td>
                                     
                                     <td>
                                          <button id='.$row->invoice_id.' class="btn btn-danger btndelete" ><span class="glyphicon glyphicon-trash" style="color:#ffffff" data-toggle="tooltip"  title="Delete Order"></span></button>  
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
  <!-- /.content-wrapper -->
<script type="text/javascript">
    
    $(document).ready(function () {
      
      $('#orderlisttable').DataTable({

        "order":[[0,"desc"]]

      });
        
    
    } );

  </script>


    <script>
      $(document).ready( function () {
      $('[data-toggle="tooltip"]').tooltip();
      } );  

         
       $(document).ready(function() {
      $('.btndelete').click(function() {
          var tdh = $(this);
          var id = $(this).attr("id");
           swal({
      title: "Are you sure?",
      text: "Once deleted, you can't retrieve order details.",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
  .then((willDelete) => {
      if (willDelete) {
        
         $.ajax({
                  url: 'orderdelete.php',
                  type: 'post',
                  data: {
                  pidd: id
                  },
                  success: function(data) {
                  tdh.parents('tr').hide();
                  }


                });
        
        
        
      swal("Order Deleted.", {
        icon: "success",
      });
      } else {
      swal("Your order is not deleted.");
      }
    });
            
                     

        });
    });     
       
       
       
       
       
    
</script>


 <?php
	
	include_once('inc/footer.php');
?>