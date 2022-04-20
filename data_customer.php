<?php 

    header("Content-Type:application/json");

    try {
            $database_name     = 'ppi';
            $database_user     = 'ppi_report';
            $database_password = 'Denki@05121996';
            $database_host     = '192.168.0.119';

            $pdo = new PDO('mysql:host=' . $database_host . '; dbname=' . $database_name, $database_user, $database_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);          

            $sql = 'select
                    tbl_customer.customer_store_name, tbl_product.product_name, tbl_sales_invoice_item.invoice_item_qty, tbl_sales_invoice.invoice_date
                    from tbl_sales_invoice
                    join tbl_sales_invoice_item on tbl_sales_invoice.id = tbl_sales_invoice_item.invoice_id
                    join tbl_product on tbl_sales_invoice_item.product_id = tbl_product.id
                    join tbl_customer on tbl_sales_invoice.customer_id = tbl_customer.id
                    join tbl_employee on tbl_sales_invoice.salesman_id = tbl_employee.id
                    group by tbl_sales_invoice_item.invoice_item_qty ASC
                    ';           

            // DD($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
           
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {          
                 $data[] = $row;  
            } 

           $response         = [];
           $response['data'] =  $data;

           echo json_encode($response, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'Database error. ' . $e->getMessage();
        }        