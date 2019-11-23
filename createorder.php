<?php
  
  include_once('config/connectdb.php');
  session_start();

	

  function fill_product($pdo) {

    $output = '';

    // Query to select/retrieve data from database.
    $select = $pdo->prepare("SELECT * FROM tbl_product ORDER BY pname ASC");
    
    $select->execute();

    $result = $select->fetchAll();

     foreach($result as $record) {
      //Pay attention to below line. $output.= is concatinating
       $output.='<option value="'.$record["pid"].'">'.$record["pname"].'</option>';
      
     }

       return $output;

  } // End of fill_product function

  // Code to save data
  // First if Save button is clicked....
    
    if(isset($_POST['btnsaveorder'])){
    
    $customer_name = $_POST['txtcustomer'];
    $order_date = date('Y-m-d', strtotime($_POST['orderdate']));
    $subtotal = $_POST["txtsubtotal"];
    $tax = $_POST['txttax'];
    $discount=$_POST['txtdiscount'];
    $total=$_POST['txttotal'];
    $paid=$_POST['txtpaid'];
    $due=$_POST['txtdue'];
    $payment_type=$_POST['rb'];
    
    // SINCE we are storing each line in array, we store data in array //
    
   $arr_productid = $_POST['productid'];
   $arr_productname = $_POST['productname'];
   $arr_stock = $_POST['stock'];
   $arr_qty = $_POST['qty'];
   $arr_price = $_POST['price'];
   $arr_total = $_POST['total'];
    
    // 1st query, insert data into tbl_invoice
    $insert = $pdo->prepare("INSERT INTO tbl_invoice(customer_name, order_date, subtotal, tax, discount, total_amount, paid_amount, due_amount, payment_type) 
                            VALUES(:cust, :orderdate, :stotal, :tax, :disc, :total, :paid, :due, :ptype) ");
    
     $insert->bindParam(':cust', $customer_name);
     $insert->bindParam(':orderdate', $order_date);
     $insert->bindParam(':stotal', $subtotal);
     $insert->bindParam(':tax', $tax);
     $insert->bindParam(':disc', $discount);
     $insert->bindParam(':total', $total);
     $insert->bindParam(':paid', $paid);
     $insert->bindParam(':due', $due);
     $insert->bindParam(':ptype', $payment_type);
    
    $insert->execute();
    
    //2nd  insert query for tbl_invoice_details
    
    $invoice_id = $pdo->lastInsertId(); // Calling built-in function lastInsertId()
    
    if($invoice_id!=null){
    
      for($i=0 ; $i<count($arr_productid) ; $i++){
        
        $rem_qty = $arr_stock[$i]-$arr_qty[$i];
    
          if($rem_qty < 0){
        
            return"Order Is Not Complete";
          } else {
        
            $update = $pdo->prepare(" UPDATE tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");
        
            $update->execute();
          
          }
    
    
   $insert = $pdo->prepare("INSERT INTO tbl_invoice_details(invoice_id, product_id, product_name, qty, price, order_date) VALUES(:invid,:pid,:pname,:qty,:price,:orderdate)" );
    
    $insert->bindParam(':invid',$invoice_id);
    $insert->bindParam(':pid', $arr_productid[$i]);
    $insert->bindParam(':pname', $arr_productname[$i]);
    $insert->bindParam(':qty', $arr_qty[$i]);
    $insert->bindParam(':price', $arr_price[$i]);
    $insert->bindParam(':orderdate', $order_date);
    
    $insert->execute();
    
    }   // END OF FOR LOOP     
        
      //echo "success fully created order";    
   
      header('location:orderlist.php');     
        
    } // End of if if($invoice_id!=null){

  } // End of main if(isset)


 if($_SESSION['role']=="Admin"){
    
    
    include_once'inc/header.php';  

} else {
    
  include_once'inc/headeruser.php';   

}

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create New Order
        <small></small>
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
           <!-- Customer and date -->
          <div class="box-body">
            <div class="col-md-6">

                <div class="form-group">
                  <label>Customer:</label>
                  <div class="input-group customer">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="txtcustomer" id="txtcustomer" placeholder="Enter Customer Name">
                  </div>
                </div>
            </div>

            <div class="col-md-6"> 
              <div class="form-group">
                <label>Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control" id="datepicker" name="orderdate" value="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                </div>
              </div>
            </div>

           <!-- Main content -->
          <div class="box-body">
            <div class="col-md-12">
              <div style="overflow-x: auto;">
                <table class="table table-stripped" id="table_order">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Search</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th><center><button type="button" name="btnadd" id="btnadd" class="btn btn-success btn-sm btnadd">
                            <span class="glyphicon glyphicon-plus"></span></button></center></th>
                      </tr>
                    </thead>
                  </table>      
              </div>
            </div>
          </div>
               <!-- Tax and Discount -->
          <div class="box-body">
            <form>
            <div class="col-md-6">
              <div class="form-group">
                <label for="product">Subtotal:</label>
                <div class="input-group subtotal">
                    <div class="input-group-addon">
                      <i class="fa fa-euro"></i>
                    </div>  
                  
                  <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal" placeholder="subtotal" readonly>
              </div>
            </div>

              <div class="form-group">  
                <label for="product">VAT (5%):</label>
                <div class="input-group vat">
                    <div class="input-group-addon">
                      <i class="fa fa-percent"></i>
                    </div>  
                <input type="text" class="form-control" name="txttax" id="txttax" placeholder="VAT" readonly>
              </div></div>
              <div class="form-group">  
                <label for="product">Discount:</label>
                <div class="input-group vat">
                    <div class="input-group-addon">
                      <i class="fa fa-minus"></i>
                    </div>  
                <input type="text" class="form-control" name="txtdiscount" id="txtdiscount" placeholder="Discount" required>
              </div></div>
            </div>
              

            <div class="col-md-6">
              <div class="form-group">  
                <label for="total">Total:</label>
                <div class="input-group vat">
                    <div class="input-group-addon">
                      <i class="fa fa-eur"></i>
                    </div>   
                <input type="text" class="form-control" name="txttotal" id="txttotal" placeholder="Total" readonly>
              </div></div>
              <div class="form-group">  
                <label for="product">Amount Paid:</label>
                <div class="input-group paid">
                    <div class="input-group-addon">
                      <i class="fa fa-money"></i>
                    </div>  
                <input type="text" class="form-control" name="txtpaid" id="txtpaid" placeholder="Amount Paid" required>
              </div></div>
              <div class="form-group">  
                <label for="amount_due">Amount Due:</label>
                <div class="input-group paid">
                    <div class="input-group-addon">
                      <i class="fa fa-eur"></i>
                    </div>  
                <input type="text" class="form-control" name="txtdue" id="txtdue" placeholder="Amount Due" readonly>
              </div></div>

            </div>
            <div class="form-group" align="center">
              <label>Payment Method: </label>
                <label> 
                  <input type="radio" name="rb" value="cash" class="minimal-red" checked>
                   Cash
                </label>
                <label> 
                  <input type="radio" name="rb" value="credit" class="minimal-red">
                   Debit / Credit Card
                </label>

                <label> 
                  <input type="radio" name="rb" value="bank" class="minimal-red">
                   Cheque/Bank
                </label>
                
              </div>
          </div>
          <div align="center">
            <input type="submit" name="btnsaveorder" value="Save Order" class="btn btn-success">
          </div>
            
        </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script type="text/javascript">
  
  //Date picker
  $('#datepicker').datepicker({
    autoclose: true
    })


 

  //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
      })
    

   // Starting jQuery
   $(document).ready(function(){
      // jQuery code for + / x buttons
      $(document).on('click', '.btnadd', function(){
        var html = '';
          html += '<tr>';
         
          html += '<td><input type = "hidden" class = "form-control pname" name="productname[]"></td>';
          html += '<td><select class="form-control productid" name="productid[] style="width: 250px"><option value="">Choose Product</option><?php echo fill_product($pdo); ?></select></td>';
          html += '<td><input type = "text" class = "form-control stock" name="stock[]" readonly></td>';
          html += '<td><input type = "number" min="0.01" class = "form-control price" name="price[]"></td>';
          html += '<td><input type = "number" min="1" class = "form-control qty" name="qty[]"></td>';
          html += '<td><input type = "text" class = "form-control total" name="total[]" readonly></td>'; 

          // Code for x remove button
          html += '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>';
          
          $('#table_order').append(html);

          // Initialize Select2 Items.
          $('.productid').select2();

          // fetching data (stock, quantity, price) with Ajax
          
          $(".productid").on('change', function(e) {

            var productid = this.value;
              var tr=$(this).parent().parent();

              $.ajax({
                url:"getproduct.php",
                method:"GET",
                data:{id:productid},
                success:function(data){
                  
                  tr.find(".pname").val(data["pname"]);
                  tr.find(".stock").val(data["pstock"]); //pstock is coming from db
                  tr.find(".price").val(data["sales_price"]);
                  tr.find(".qty").val(1);
                  tr.find(".total").val(tr.find(".price").val() * tr.find(".qty").val());
                  calculate(0,0);
                }

              }) // end of ajax

          })

        });

      // function for remove button

      $(document).on('click', '.btnremove', function(){
        $(this).closest('tr').remove();
        calculate(0,0);
        
        $("#txtpaid").val(0);
        
        });

      // calcultion qty * price = total
      
      $("#table_order").delegate(".qty","keyup change", function(){
       
      var quantity = $(this);
      var tr = $(this).parent().parent(); 
        
      if((quantity.val()-0)>(tr.find(".stock").val()-0) ){ 
        swal("Error","Enter quantity available in stock.","warning");
        
        quantity.val(1);
        
        tr.find(".total").val(quantity.val() *  tr.find(".price").val());
        calculate(0,0);
       
       } else {
           
           tr.find(".total").val(quantity.val() *  tr.find(".price").val());
           calculate(0,0);
       }    
        
        
        
    })    
  
    // Function to calculate Subtotal, VAT, Discount etc.

    function calculate(dis,paid){
         
      var subtotal=0;
      var tax=0;
      var discount = dis;     
      var net_total=0;
      var paid_amt=paid;
      var due=0;
           
           
      $(".total").each(function(){
          
      subtotal = subtotal+($(this).val()*1);    
          
      })
         
      tax=0.05*subtotal;
      net_total=tax+subtotal;  //50+1000 =1050
      net_total=net_total-discount;   
      due=net_total-paid_amt;         
         
    
    $("#txtsubtotal").val(subtotal.toFixed(2)); 
    $("#txttax").val(tax.toFixed(2));   
    $("#txttotal").val(net_total.toFixed(2));
    $("#txtdiscount").val(discount);
    $("#txtdue").val(due.toFixed(2));
  
         
         
     } // End of function calculate
        
    $("#txtdiscount").keyup(function(){
        var discount = $(this).val();
        calculate(discount,0);
    
    });
        
    $("#txtpaid").keyup(function(){
    var paid = $(this).val();  
    var discount = $("#txtdiscount").val();
        calculate(discount,paid);
        
    });        
        


  }); // END OF jQuery   

   
   
</script>

 <?php
	
	include_once('inc/footer.php');
?>