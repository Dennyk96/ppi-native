<?php
  $page_title = 'All sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sales = find_all_sale();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>All Sales</span>
          </strong>
          <!-- <div class="pull-right">
            <a href="add_sale.php" class="btn btn-primary">Add sale</a>
          </div> -->
        </div>
        <div class="panel-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Invoice Date</th>
                <th>Customer</th>
                <th>Salesman</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
                <td><?php echo remove_junk($sale['invoice_code']); ?></td>
                <td><?php echo $sale['invoice_date']; ?></td>
                <td><?php echo remove_junk($sale['customer_store_name']); ?></td>
                <td><?php echo remove_junk($sale['employee_name']); ?></td>
                <td><?php echo remove_junk($sale['product_code']); ?> | <?php echo remove_junk($sale['product_name']); ?></td>
                <td><?php echo $sale['invoice_item_qty']; ?></td>
                <td><?php echo remove_junk($sale['invoice_grand_total']); ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>
