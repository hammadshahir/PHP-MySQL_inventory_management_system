<?php

include_once'config/connectdb.php';

session_start();

if($_SESSION['useremail']=="" OR $_SESSION['role']=="") {
    
    header('location:index.php');
    
    }


function fill_product($pdo, $pid){
    
    $output = '';
      
    $select = $pdo->prepare("   
                                SELECT * 
                                FROM tbl_product 
                                ORDER BY pname ASC 
                            "); 
    $select->execute();
      
    $result = $select->fetchAll();
    
    foreach($result as $row){
      
        $output.='<option value="'.$row["pid"].'"';

            if($pid == $row['pid']) {

                $output.='selected';

            }
                $output.='>'.$row["pname"].'</option>';
          
    }  // End for foreach loop  
      
     return $output;   
    
  } // end of Function fill product

  // Code to fetch data from database to fill data in edit form.
  
  $id = $_GET['id'];
  // Query to select data from tbl_invoice

  $select = $pdo->prepare("
                            SELECT * 
                            FROM tbl_invoice 
                            WHERE invoice_id = :invid
                        ");
  $select->bindParam(':invid', $id);
  
  $select->execute();

  $row = $select->fetch(PDO::FETCH_ASSOC);

    $customer_name = $row['customer_name'];
    $order_date = date('Y-m-d',strtotime($row['order_date']));
    $subtotal = $row['subtotal'];
    $tax = $row['tax'];
    $discount = $row['discount'];
    $total = $row['total_amount'];
    $paid = $row['paid_amount'];
    $due = $row['due_amount'];
    $payment_type = $row['payment_type'];

    $select=$pdo->prepare("select * from tbl_invoice_details where invoice_id =$id");
    
    $select->execute();

    $row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);

    //if Update Button is clicked ....
  
  if(isset($_POST['btnupdateorder'])){

    // Step 1: get TXT values from fields and array
    
    $txt_customer_name = $_POST['txtcustomer'];
    $txt_order_date = date('Y-m-d',strtotime($_POST['orderdate']));
    $txt_subtotal = $_POST["txtsubtotal"];
    $txt_tax = $_POST['txttax'];
    $txt_discount = $_POST['txtdiscount'];
    $txt_total = $_POST['txttotal'];
    $txt_paid = $_POST['txtpaid'];
    $txt_due = $_POST['txtdue'];
    $txt_payment_type = $_POST['rb'];
    
    $arr_productid = $_POST['productid'];
    $arr_productname = $_POST['productname'];
    $arr_stock = $_POST['stock'];
    $arr_qty = $_POST['qty'];
    $arr_price = $_POST['price'];
    $arr_total = $_POST['total'];

     // STEP 2: Write update query for tbl_product

    foreach ($row_invoice_details as $item_invoice_details) {
         
         $updateproduct = $pdo->prepare("
                                        UPDATE tbl_product
                                        SET pstock = pstock+".$item_invoice_details['qty']."
                                        WHERE pid = '".$item_invoice_details['product_id']."'

                            "); // End of updateproduct query
         
         $updateproduct->execute();
     } 

     // STEP 3: Write delete query for tbl_invoice_details data where invoice_id = $id

    $delete_invoice_details = $pdo->prepare("
                                            DELETE FROM tbl_invoice_details 
                                            WHERE invoice_id = $id");
    $delete_invoice_details->execute();

    // STEP 4: Write update query for tbl_invoice
    
    $update_invoice =   $pdo->prepare(" 
                                        UPDATE tbl_invoice
                                        SET(customer_name = :cust, 
                                        order_date = :orderdate,
                                        subtotal = :stotal, 
                                        tax = :tax, 
                                        discount = :disc, 
                                        total_amount = :total, 
                                        paid_amount = :paid, 
                                        due_amount = :due, 
                                        payment_type = :ptype,
                                        WHERE invoice_id = $id
                                    ");
    
     $update_invoice->bindParam(':cust', $txt_customer_name);
     $update_invoice->bindParam(':orderdate', $txt_order_date);
     $update_invoice->bindParam(':stotal', $txt_subtotal);
     $update_invoice->bindParam(':tax', $txt_tax);
     $update_invoice->bindParam(':disc', $txt_discount);
     $update_invoice->bindParam(':total', $txt_total);
     $update_invoice->bindParam(':paid', $txt_paid);
     $update_invoice->bindParam(':due', $txt_due);
     $update_invoice->bindParam(':ptype', $txt_payment_type);

     $update_invoice->execute();
    
    //2nd  insert query for tbl_invoice_details
    
    $invoice_id = $pdo->lastInsertId();
    
    if($invoice_id != null){
    
      for($i = 0 ; $i < count($arr_productid) ; $i++){
    
    // STEP 5: write SELECT query for tbl_product to get out stock value

    $selectpdt = $pdo->prepare("
                                SELECT *
                                FROM tbl_product
                                WHERE pid = '".$arr_productid[$i]."'  
                              ");
    $selectpdt->execute();

    while ($rowpdt = $selectpdt->fetch(PDO::FETCH_OBJ)) {

        $db_stock[$i] = $rowpdt->pstock;
        
        $rem_qty = $db_stock[$i]-$arr_qty[$i];
    
        if($rem_qty < 0){
        
          return"Order Is Not Complete";
        } else {

        // STEP 6: write UPDATE query for tbl_product to update stock values
        
          $update=$pdo->prepare("   
                                    UPDATE tbl_product 
                                    SET pstock ='$rem_qty' 
                                    WHERE pid='".$arr_productid[$i]."'
                                ");
        
          $update->execute();

        }
    } // END OF WHILE LOOP
    
    
    //STEP 7: 
    
    
    $insert = $pdo->prepare("   
                                INSERT INTO tbl_invoice_details(
                                    invoice_id, product_id, product_name, qty, price, order_date) 
                                VALUES(:invid,:pid,:pname,:qty,:price,:orderdate)
                            ");
    
    $insert->bindParam(':invid', $id);
    $insert->bindParam(':pid', $arr_productid[$i]);
    $insert->bindParam(':pname', $arr_productname[$i]);
    $insert->bindParam(':qty', $arr_qty[$i]);
    $insert->bindParam(':price', $arr_price[$i]);
    $insert->bindParam(':orderdate', $txt_order_date);
     
    $insert->execute();
    
    }        
        
   //  echo"success fully created order";    
   
        header('location:orderlist.php');     
        
    }
    
  } // END OF btnupdate
    
 


if($_SESSION['role'] == "Admin") {
    
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
            Manage Order
            <small></small>
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
            <form action="" method="post" name="">

                <div class="box-header with-border">
                    <h3 class="box-title">Manage or update existing order.</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>


                                <input type="text" class="form-control" name="txtcustomer" value="<?php echo $row['customer_name']; ?>" required>
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
        <input type="text" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo $row['order_date'] ; ?>" data-date-format="yyyy-mm-dd" >
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                </div> <!-- this is for customer and date -->

                <div class="box-body">
                    <div class="col-md-12">
                 <div style="overflow-x:auto;" > 
  
                    <table class="table table-bordered" id="producttable"  >
                                        
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Search Product</th>
                              <th>Stock</th>
                              <th>Price</th>
                              <th>Enter Quantity</th>
                              <th>Total</th>
                              <th>
                  <center> <button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>

                              </th>
                          </tr>
                      </thead>

                      <?php

                      // Looping invoice data.
                      
                      foreach($row_invoice_details as $item_invoice_details) {

                            // Query to select data from tbl_product .. because items are coming product id
                            // So we are select product which is equal to invoice id
  
                          $select = $pdo->prepare(" 
                                                    SELECT * FROM tbl_product 
                                                    WHERE pid = '{$item_invoice_details['product_id']}'
                                                ");
                          
                          
                          $select->execute();

                          $row_product = $select->fetch(PDO::FETCH_ASSOC);

                      ?>

                      <tr>
                            <?php 

                    echo'<td><input type="hidden" class="form-control pname" value = "'.$row_product['pname'].'" name="productname[]" readonly></td>';
                
                    echo'<td><select class="form-control productidedit" name="productid[]" style="width: 250px";><option value="">Select Option</option>'.fill_product($pdo, $item_invoice_details['product_id'] ).'</select></td>';
                          
                    echo'<td><input type="text" class="form-control stock " name="stock[]" value = "'.$row_product['pstock'].'" readonly></td>';
                    echo'<td><input type="text" class="form-control price" name="price[]" value = "'.$row_product['sales_price'].'" ></td>';
                    echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value = "'.$item_invoice_details['qty'].'" ></td>';
                    echo'<td><input type="text" class="form-control total" name="total[]" value = "'.$row_product['sales_price']*$item_invoice_details['qty'].'" readonly></td>';
                    echo'<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>'; 

                            ?>
                      </tr>
                  <?php } // END OF FOR EACH LOOP --> foreach($row_invoice_details as $item_invoice_details)  ?>
                  </table>
                      </div>

                    </div>
                </div><!-- this for table -->

                <div class="box-body">

                    <div class="col-md-6">

                        <div class="form-group">
                            <label>SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-plus"></i>
                                </div>

        <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal" value="<?php echo $row['subtotal'] ;?>" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>VAT (5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-plus"></i>
                                </div>


        <input type="text" class="form-control" name="txttax" id="txttax" value="<?php echo $row['tax']; ?>" required readonly>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-minus"></i>
                                </div>
                 <input type="text" class="form-control" name="txtdiscount" value="<?php echo $row['discount']; ?>" id="txtdiscount" required>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Total</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-money"></i>
                                </div>

                    <input type="text" class="form-control" name="txttotal" value="<?php echo $row['total_amount']; ?>" id="txttotal" required readonly>
                            </div>
                        </div>



                        <div class="form-group">
                            <label>Paid</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>

                                <input type="text" class="form-control" value="<?php echo $row['paid_amount'] ;?>" name="txtpaid"  id="txtpaid" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-eur"></i>
                                </div>
                                <input type="text" class="form-control" value="<?php echo $row['due_amount'] ;?>" name="txtdue" id="txtdue" required readonly>
                            </div>
                        </div>

                        <!-- radio -->
                        <label>Payment Method</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Cash"<?php echo ($payment_type=='Cash')?'checked':'' ?> > Cash
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Card"<?php echo ($payment_type=='Card')?'checked':'' ?>> Debit/Credit Card
                            </label>
                            <label>
                                <input type="radio" name="rb" class="minimal-red" value="Bank"<?php echo ($payment_type=='Bank')?'checked':'' ?>>
                                Bank
                            </label>
                        </div>



                    </div>



                </div><!-- tax dis. etc -->

<hr>

                <div align="center">

                    <input type="submit" name="btnupdateorder" value="Update Order" class="btn btn-warning">

                </div>

<hr>

            </form>
        </div>




    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
   
    
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });


    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })
    
    
    
    $(document).ready(function(){

        $('.productidedit').select2()
        
     $(".productidedit").on('change' , function(e){
         
    var productid = this.value;
     var tr=$(this).parent().parent();  
       $.ajax({
           
        url:"getproduct.php",
        method:"get",
        data:{id:productid},
        success:function(data){
            
         //console.log(data); 
        tr.find(".pname").val(data["pname"]);
        tr.find(".stock").val(data["pstock"]);
        tr.find(".price").val(data["sales_price"]); 
        tr.find(".qty").val(1);
        tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
            calculate(0,0); 
            }   
      })   
    })    
        
    $(document).on('click','.btnadd', function(){
    
      var html='';
        html+='<tr>';
                
        html+='<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';
                
        html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Select Option</option><?php echo fill_product($pdo,''); ?> </select></td>';
              
        html+='<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
        html+='<td><input type="text" class="form-control price" name="price[]"></td>';
        html+='<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
        html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';
        html+='<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>'; 
              
        $('#producttable').append(html);
        
     
      //Initialize Select2 Elements for
        $('.productid').select2()
            
         $(".productid").on('change' , function(e){
             
        var productid = this.value;
         var tr=$(this).parent().parent();  
           $.ajax({
               
            url:"getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){
                
             //console.log(data); 
            tr.find(".pname").val(data["pname"]);
            tr.find(".stock").val(data["pstock"]);
            tr.find(".price").val(data["sales_price"]); 
            tr.find(".qty").val(1);
            tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val()); 
                calculate(0,0); 
                }   
          })   
        })    
       
    }) // btnadd end here    
       
        
     $(document).on('click','.btnremove',function(){
         
        $(this).closest('tr').remove(); 
         calculate(0,0);
         $("#txtpaid").val(0);
         
     }) // btnremove end here  
        
    
    $("#producttable").delegate(".qty","keyup change" ,function(){
       
      var quantity = $(this);
       var tr = $(this).parent().parent(); 
        
    if((quantity.val()-0)>(tr.find(".stock").val()-0) ){
       
       swal("WARNING!","SORRY! This much of quantity is not available","warning");
        
        quantity.val(1);
        
         tr.find(".total").val(quantity.val() *  tr.find(".price").val());
        calculate(0,0);
       }else{
           
           tr.find(".total").val(quantity.val() *  tr.find(".price").val());
           calculate(0,0);
       }    
        
    })    
      
        
     function calculate(dis, paid){
         
    var subtotal = 0;
    var tax = 0;
    var discount = dis;     
    var net_total = 0;
    var paid_amt = paid;
    var due = 0;
         
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
  
         
         
     } // function calculate end here 
        
  $("#txtdiscount").keyup(function(){
      var discount = $(this).val();
      calculate(discount,0);
      
    
    }) 
        
  $("#txtpaid").keyup(function(){
  var paid = $(this).val();  
  var discount = $("#txtdiscount").val();
      calculate(discount,paid);
    
    })        
        
        
        
  });
    
    
</script>


<?php

include_once'inc/footer.php';

?>