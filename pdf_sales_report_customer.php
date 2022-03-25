<?php
// Tentukan path yang tepat ke mPDF
$nama_dokumen='Sales Report(Customer)'; //Beri nama file PDF hasil.
require_once __DIR__ . '/vendor/autoload.php'; // Arahkan ke file mpdf.php didalam folder mpdf
$mpdf = new \Mpdf\Mpdf(); // Membuat file mpdf baru
 
$results = '';
  require_once('includes/load.php');

//Memulai proses untuk menyimpan variabel php dan html
ob_start();
?>

<?php
	$results  = customer_buy_product($start_date,$end_date);
?>

<style>
 	table{margin: auto;}
 	td,th{padding: 5px;text-align: center; width: 150px}
 	h1{text-align: center}
 	th{background-color: #95a5a6; padding: 10px;color: #fff}
 </style>
<h1>Daftar Seleksi Calon Murid</h1>
<table border="0">

	<tr>
		<th>Customer</th>
		<th>Product</th>
		<th>Code</th>
		<th>Total Qty</th>
	</tr>
	<?php foreach($results as $result): ?>
              <tr>
                  <td style="text-align: center;"><?php echo remove_junk(ucfirst($result['Customer']));?></td>
                  <td style="text-align: center;"><strong><?php echo remove_junk($result['ProductCode']);?></strong> - <?php echo remove_junk($result['Product']);?></td>
                  <td style="text-align: center;"><?php echo $result['TotalQty']; ?></td>
              </tr>
    <?php endforeach; ?>
   
</table>


<?php

$mpdf->setFooter('{PAGENO}');
//penulisan output selesai, sekarang menutup mpdf dan generate kedalam format pdf
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Disini dimulai proses convert UTF-8, kalau ingin ISO-8859-1 cukup dengan mengganti $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>