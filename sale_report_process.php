<?php
$page_title = 'Sales Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_sale_by_dates($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Sales Report</title>


    <!-- datatable style -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
        <!-- bootstrap 4 css  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
            integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <!-- css tambahan  -->
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
   
   <style type="text/css">
		*{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}
body{
    font-family: Helvetica;
    -webkit-font-smoothing: antialiased;
    background: rgba( 234, 237, 237);
}
h2{
    text-align: center;
    font-size: 24px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: black;
    padding: 30px 0;
}
</style>
</head>
<body>
  <?php if($results): ?>
    
    
      
    <div class="card-body">
      <table id="sales_report" class="table table-bordered table-hover" style="width:100%">
        <thead>
          <div class="table-wrapper" style="background-color: darkgrey;">
            <h2>Sales Report <strong><?php if(isset($start_date)){ echo $start_date;}?> To <?php if(isset($end_date)){echo $end_date;}?> </strong></h2>
          </div>
          <tr>
              <th>Brand - Code</th>
              <th>Product Name</th>
              <th>Searah</th>
              <th>Sell Price</th>
              <th>Total Qty</th>
              <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
           <tr>
              <td><?php echo remove_junk(ucfirst($result['brand_name']));?> - <br><strong><?php echo remove_junk(ucfirst($result['product_code']));?></strong></td>
              <td><?php echo remove_junk(ucfirst($result['product_name']));?> </td>
              <td><?php echo remove_junk(ucfirst($result['searah_name']));?> </td>
              <td>$ <?php echo number_format($result['product_sell_price']);?></td>
              <td><?php echo remove_junk($result['total_sales']);?></td>
              <td>$ <?php echo number_format($result['total_sell_price']);?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr>
           <td colspan="4"></td>
           <td colspan="1" style="font-size: 16pt;">Grand Total</td>
           <td style="font-size: 16pt;"> $
           <?php echo number_format(total_price($results)[0], 2);?>
          </td>
         </tr>
        </tfoot>
      </table>
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no sales has been found. ");
        redirect('sales_report.php', false);
     endif;
  ?>

  <!-- jquery -->
  <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <!-- jquery datatable -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    
        <!-- script tambahan  -->
        <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js">
        </script>
    
        <!-- fungsi datatable -->
        <script>
            $(document).ready(function () {
                $('#sales_report').DataTable({
                    // script untuk membuat export data 
                    dom:
                    "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    buttons: [
                          {
                              extend: 'excelHtml5',
                              title: 'Sales Report <?php if(isset($start_date)){ echo $start_date;}?> To <?php if(isset($end_date)){echo $end_date;}?>',
                          },
                          {
                              extend: 'pdfHtml5',
                              title: 'Sales Report <?php if(isset($start_date)){ echo $start_date;}?> To <?php if(isset($end_date)){echo $end_date;}?>',
                              download: 'open',
                              pageSize: 'A4'
                          }
                      ]
                })
            });
    
        </script>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
