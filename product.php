<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <!-- <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Add New</a>
         </div>
        </div> -->
        <div class="panel-body">
          <table class="table table-responsive-sm">
            <thead>
              <tr>
                <th class="text-center" >#</th>
                <th> Product Name</th>
                <th> Product Code </th>
                <th class="text-center">Searah</th>
                <th class="text-center">Category</th>
                <th class="text-center">Cost Price</th>
                <th class="text-center">Sell Price</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td> <?php echo remove_junk($product['product_name']); ?></td>
                <td> <?php echo remove_junk($product['product_code']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['searah_name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['category_name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['product_cost_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['product_sell_price']); ?></td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  

  <?php include("layouts/footer.php"); ?>