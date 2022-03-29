<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
    global $db;
    $sql  =" SELECT p.id, p.product_name, p.product_code, s.searah_name, p.product_cost_price, p.product_sell_price, p.product_type, c.category_name";
    $sql  .=" FROM tbl_product p";
    $sql  .=" JOIN tbl_category c ON c.id = p.category_id";
    $sql  .=" JOIN tbl_searah s ON s.id = p.searah_id";
    $sql  .=" GROUP BY p.id, p.searah_id ";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT product_name FROM tbl_product WHERE product_name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM tbl_product ";
    $sql .= " WHERE product_name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id, p.product_name, p.product_sell_price, c.category_name, se.searah_name";
   $sql  .= " FROM tbl_product p";
   $sql  .= " LEFT JOIN tbl_category c ON c.id = p.category_id";
   $sql  .= " LEFT JOIN tbl_searah se ON p.searah_id = se.id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.product_name, COUNT(s.product_id) AS totalSold, s.invoice_item_qty AS totalQty";
   $sql .= " FROM tbl_sales_invoice_item s";
   $sql .= " JOIN tbl_product p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY s.invoice_item_qty DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT ss.invoice_code, ss.invoice_date, c.customer_store_name, e.employee_name, p.product_name, p.product_code, s.invoice_item_qty, ss.invoice_grand_total, ss.created_on";
  $sql .= " FROM tbl_sales_invoice_item s";
  $sql .= " LEFT JOIN tbl_sales_invoice ss ON ss.id = s.invoice_id";
  $sql .= " LEFT JOIN tbl_customer c ON c.id = ss.customer_id";
  $sql .= " LEFT JOIN tbl_employee e ON e.id = ss.salesman_id";
  $sql .= " LEFT JOIN tbl_product p ON s.product_id = p.id";
  $sql .= " GROUP BY ss.invoice_date DESC ";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id, s.invoice_item_qty, ss.invoice_grand_total, ss.created_on, p.product_name, ss.invoice_date";
  $sql .= " FROM tbl_sales_invoice_item s";
  $sql .= " LEFT JOIN tbl_product p ON s.product_id = p.id";
  $sql .= " LEFT JOIN tbl_sales_invoice ss ON ss.id = s.invoice_id";
  $sql .= " ORDER BY ss.invoice_date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  
  $sql = 
  
  "SELECT s.invoice_code AS invoice, s.created_on AS invoiceCreate, c.customer_store_name AS customer, e.employee_name AS salesman, p.product_code AS productCode, p.product_name AS productName, s.invoice_grand_total, s.invoice_date AS invoiceDate, ss.invoice_item_qty AS quantityTotal

  FROM tbl_sales_invoice s
  LEFT JOIN tbl_sales_invoice_item ss ON s.id = ss.invoice_id
  LEFT JOIN tbl_product p ON ss.product_id = p.id
  LEFT JOIN tbl_employee e ON s.salesman_id = e.id
  LEFT JOIN tbl_customer c ON s.customer_id = c.id
  WHERE s.created_on BETWEEN '{$start_date}' AND '{$end_date}'
  GROUP BY invoiceCreate";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql = "SELECT ss.invoice_item_qty AS Qty, DATE_FORMAT(s.created_on, '%Y-%m-%e') AS tanggal, p.product_name AS product, (p.product_sell_price * ss.invoice_item_qty) AS totalSell, p.product_code AS kode
  
  FROM tbl_sales_invoice s
  LEFT JOIN tbl_Sales_invoice_item ss ON s.id = ss.invoice_id
  LEFT JOIN tbl_product p ON p.id = ss.Product_id
  WHERE DATE_FORMAT(s.created_on, '%Y-%m' ) = '{$year}-{$month}'
  GROUP BY DATE_FORMAT(s.created_on,  '%e' ), ss.product_id";

  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql = "SELECT ss.invoice_item_qty AS Qty, s.created_on as tanggal, p.product_name AS product, (p.product_sell_price * ss.invoice_item_qty) AS totalSell
  FROM tbl_sales_invoice s
  LEFT JOIN tbl_sales_invoice_item ss ON s.id = ss.invoice_id
  LEFT JOIN tbl_product p ON p.id = ss.product_id
  WHERE DATE_FORMAT(s.created_on, '%Y' ) = '{$year}'
  GROUP BY DATE_FORMAT(s.created_on,  '%c' ), p.product_name ASC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate  finance report
/*--------------------------------------------------------------*/
function find_finance_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  
  $sql = 
  
  "SELECT py.payment_code AS payCode, py.payment_date AS payDate, s.invoice_code AS invoice, c.customer_store_name AS customerPay, py.payment_exchange_rate AS ratePay, py.payment_total_amount totalPay, s.invoice_payment_status AS statusPayment, s.invoice_status AS invoiceStatus

  FROM tbl_sales_payment py
  
  LEFT JOIN tbl_sales_payment_detail pyt ON py.id = pyt.payment_id
  LEFT JOIN tbl_sales_invoice s ON s.id = py.invoice_id
  LEFT JOIN tbl_customer c ON c.id = py.customer_id
  WHERE py.payment_date BETWEEN '{$start_date}' AND '{$end_date}'
  GROUP BY py.payment_date, py.payment_code
  ORDER BY py.payment_date ASC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Customer sales report
/*--------------------------------------------------------------*/
function  customer_buy_product($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  
  $sql = "SELECT c.customer_store_name AS Customer, p.product_name AS Product, ss.invoice_item_qty AS TotalQty, p.product_code AS ProductCode

  FROM tbl_sales_invoice_item ss
  LEFT JOIN tbl_sales_invoice s ON ss.invoice_id = s.id
  LEFT JOIN tbl_product p ON ss.product_id = p.id
  LEFT JOIN tbl_customer c ON s.customer_id = c.id
  LEFT JOIN tbl_searah se ON p.searah_id = se.id
  WHERE s.created_on BETWEEN '{$start_date}' AND '{$end_date}'
  GROUP BY c.customer_store_name, p.product_name
  ORDER BY TotalQty ASC";
  return find_by_sql($sql);
}
?>
