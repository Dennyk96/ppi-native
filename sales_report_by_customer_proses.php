<?php
$page_title = 'Sales By Customer';
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
      $results      = customer_buy_product($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report_by_customer.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report_by_customer.php', false);
  }
?>

<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <!-- datatable style -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
        <!-- bootstrap 4 css  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
            integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <!-- css tambahan  -->
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    </head>
    
    <body>
      <?php if($results): ?>
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <!-- membuat tabel -->
                    <table id="sales_report" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                              <th>Customer</th>
                              <th>Product</th>
                              <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php foreach($results as $result): ?>
                            <tr>
                                <td><?php echo remove_junk(ucfirst($result['Customer']));?></td>
                                <td><strong><?php echo remove_junk($result['ProductCode']);?></strong> - <?php echo remove_junk($result['Product']);?></td>
                                <td><?php echo $result['TotalQty']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                        else:
                            $session->msg("d", "Sorry no sales has been found. ");
                            redirect('sales_report.php', false);
                        endif;
                    ?>
                </div>
            </div>
    
    
        </div>
    
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
                              title: 'Sales Report(Customer)',
                          },
                          {
                              extend: 'pdfHtml5',
                              title: 'Sales Report(Customer)',
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