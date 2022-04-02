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
              <th>Invoice</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Salesman</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Sub Total</th>
              <th>Grand Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
           <tr>
              <td><?php echo remove_junk(ucfirst($result['invoice']));?> </td>
              <td><?php echo format_date($result['invoiceDate']);?> </td>
              <td><?php echo remove_junk(ucfirst($result['customer']));?> </td>
              <td><?php echo remove_junk(ucfirst($result['salesman']));?> </td>
              <td><?php echo remove_junk(ucfirst($result['productCode']));?> - <strong><?php echo remove_junk(ucfirst($result['productName']));?></strong></td>
              <td><?php echo remove_junk($result['quantityTotal']);?></td>
              <td><?php echo remove_junk($result['invoiceSubTotal']);?></td>
              <td><?php echo remove_junk($result['invoice_grand_total']);?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr>
           <td style="font-size: 14pt;"><strong>TOTAL</strong></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td><strong><?php echo number_format(total_qty($results)[0], 2);?></strong></td>
           <td><strong><?php echo price_format(sub_total($results)[0], 2);?></strong></td>
           <td><strong><?php echo price_format(grand_total($results)[0], 2);?></strong></td>
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
        <script type="text/javascript">
          var totalSubTotal;
          var totalGrand;

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
