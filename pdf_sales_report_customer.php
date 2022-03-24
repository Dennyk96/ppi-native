<?php
$page_title = 'Sales By Customer';
  require_once('includes/load.php');
?>
<?php
$results = customer_buy_product($start_date,$end_date);
?>
<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
ob_start(); 
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
 <div align="center">
  <h2 align="center">Sales Report (Customer)</h2>
  <table align="center" width="60%" border="1">
   <thead>
    <tr>
     <th>Customer</th>
     <th>Product</th>
     <th>Code</th>
     <th>Oty</th>
    </tr>
   </thead>
   <tbody>
        <?php foreach($results as $result): ?>
                <tr>
                    <td style="text-align: center;"><?php echo remove_junk(ucfirst($result['Customer']));?></td>
                    <td style="text-align: center;"><strong><?php echo remove_junk($result['Product']);?></strong> - <?php echo remove_junk($result['ProductCode']);?></td>
                    <td style="text-align: center;"><?php echo $result['TotalQty']; ?></td>
                </tr>
        <?php endforeach; ?>
   </tbody>
  </table>
 </div>
</body>
</html>
<?php
 $html = ob_get_contents(); 
 ob_end_clean();
 $mpdf->WriteHTML(utf8_encode($html));
 $mpdf->Output();
?>