<?php

  include_once('config/connectdb.php');

  $select = $pdo->prepare("
                          SELECT sum(total_amount) as t, count(invoice_id) as inv 
                          FROM tbl_invoice
                        ");
  $select->execute();
  $row = $select->fetch(PDO::FETCH_OBJ);
  
  $total_order=$row->inv;
  $net_total=$row->t;

  $select=$pdo->prepare("
                          SELECT order_date, total_amount 
                          FROM tbl_invoice  
                          GROUP BY order_date LIMIT 30
                        ");       
  $select->execute();                
  
  $ttl=[];
  $date=[];              
            
  while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
    
    extract($row);
      $ttl[]=$total_amount;
      $date[]=$order_date;
    
  }
 
  session_start();  
  if($_SESSION['useremail'] == "" OR $_SESSION['role']=="User") {
      header('location:index.php');
      
    }

  include_once('inc/header.php');

  ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Admin Dashboard.
        <small>You can manage all your administration tasks from here.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Up</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <div class="box-body">
         
        <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_order; ?></h3>

              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo "EUR".number_format($net_total,2);?><sup style="font-size: 20px"></sup></h3>

              <p>Total Revenue</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
       <?php 
       
       $select = $pdo->prepare("
                                SELECT count(pname) AS p 
                                FROM tbl_product
                              ");
          $select->execute();
          $row = $select->fetch(PDO::FETCH_OBJ);
          $total_product = $row->p;
      ?>  
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_product; ?></h3>

              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
         <?php 
          
          $select = $pdo->prepare("
                                  SELECT count(category) AS cate 
                                  FROM tbl_category
                                ");
          $select->execute();
          $row = $select->fetch(PDO::FETCH_OBJ);
          $total_category=$row->cate;

         ?>
        
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $total_category;?></h3>

              <p>Total Categories</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div> 
         
         
  <div class="box box-warning">
   <div class="box-header with-border">
      <h3 class="box-title">Earnings (By Date)</h3>
    </div>
    
    <div class="box-body">        
      <div class="chart">
        <canvas id="earningbydate" style="height:250px"></canvas>              
      </div>
    </div>
  </div>
           
     
  <div class="row">
    <div class="col-md-6">    
    <div class="box box-info">
   <div class="box-header with-border">
      <h3 class="box-title">Best Selling Products</h3>
    </div>
 
  <div class="box-body">             
    <table id="bestsellingproductlist" class="table table-striped">
      <thead>
      <tr>
        <th>Product ID</th>
        <th>Product Name</th>   
        <th>Qty</th>
        <th>Price</th>   
        <th>Total</th>   
      </tr>    
    </thead>

    <tbody>
      <?php
        
        $select = $pdo->prepare("
                                  SELECT product_id, product_name, price, 
                                    sum(qty) as q, sum(qty*price) as total 
                                  FROM tbl_invoice_details 
                                  GROUP BY product_id 
                                  ORDER BY sum(qty) DESC LIMIT 15
                                ");
                
        $select->execute();        
        
        while($row=$select->fetch(PDO::FETCH_OBJ)  ){
        
        echo'
          <tr>

            <td>'.$row->product_id.'</td>
            <td>'.$row->product_name.'</td>
            <td><span class="label label-info">'.$row->q.'</span></td>
            <td><span class="label label-success">'."$".$row->price.'</span></td>
            <td><span class="label label-danger">'."$".$row->total.'</span></td>
          
          </tr>
         ';
        
        }          
      ?>                      
    </tbody>               
  </table>                 
  </div>
</div>    
</div>
     
  <div class="col-md-6">
     <div class="box box-info">
    
    <div class="box-header with-border">
      <h3 class="box-title">Recent Orders</h3>
    </div>
  
    <div class="box-body">        
      <table id="orderlisttable" class="table table-striped">
        
        <thead>
          <tr>
            <th>Invoice ID</th>
            <th>CustomerName</th>   
            <th>OrderDate</th>   
            <th>Total</th>   
            <th>Payment Type</th> 
          </tr>   
        </thead> 
      
      <tbody>
        
      <?php

        $select = $pdo->prepare("
                                  SELECT * 
                                  FROM tbl_invoice 
                                  ORDER BY invoice_id DESC LIMIT 15
                              ");
              
        $select->execute();
              
        while($row = $select->fetch(PDO::FETCH_OBJ)  ){
      
          echo'
              <tr>
              <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</a></td>
              <td>'.$row->customer_name.'</td>
              <td>'.$row->order_date.'</td>
              <td><span class="label label-danger">'."$".$row->total_amount.'</span></td>';
                if($row->payment_type=="Cash"){
                    echo'<td><span class="label label-warning">'.$row->payment_type.'</span></td>';
                    
                }elseif($row->payment_type=="Card"){
                     echo'<td><span class="label label-success">'.$row->payment_type.'</span></td>';
                }else{
                     echo'<td><span class="label label-primary">'.$row->payment_type.'</span></td>';
                }
        }          
      ?>        
                
    </tbody>               
    </table>      
    </div>
  </div>    
  </div>          
  </div>    
</div>
</section>
   
</div>
  
<script>
  var ctx = document.getElementById('earningbydate').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
      data: {
          labels: <?php echo json_encode($date);?>,
          datasets: [{
              label: 'Total Revenue',
          backgroundColor: 'rgb(255, 99, 132)',
              borderColor: 'rgb(255, 99, 132)',
             
              data:<?php echo json_encode($ttl);?>
          }]
      },

    // Configuration options go here
      options: {}
    });

</script>
</section>
 
  </div>
 

 <?php
	
	include_once('inc/footer.php');
?>