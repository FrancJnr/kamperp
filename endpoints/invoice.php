<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$path_to_root = "";
include($path_to_root . "../db/invoices_db.php");
include($path_to_root . "../db/customers_db.php");

include("../db/connect_db.php");
$action = isset($_GET['action']) ? $_GET['action']: die();
// return get_customer($customer_id);
$db = new Database();
$companyid = isset($_GET['company-id']) ? $_GET['company-id'] : '';
if($companyid=='KAMP'){
    $db->db_name='techsava_kamperp';
    $conn = $db->getConnection();
}elseif($companyid=='KPM'){
    $db->db_name='techsava_kamerp';
    $conn = $db->getConnection();
}elseif($companyid=='PRISK'){
    $db->db_name='techsava_prisk';
    $conn = $db->getConnection();
}else{
    echo json_encode("Missing Company ID");
    die();
}
$authenticated;
if(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_PW']){
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $authenticated= $db->authenticate($username, $password);
}else{
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode("Missing username or password");
    exit;
}
if($authenticated!= -1){
$invoice = new Invoice($conn);

if($action=='get-invoice'){
    $id = isset($_GET['order-no']) ? $_GET['order-no']: die();
    $results = $invoice->get_sales_order($id);
    echo json_encode($results);

}
if($action=='add-invoice'){
    $customer = new Customer($conn);
    $json = file_get_contents('php://input');
    $data = json_decode($json);   
    foreach($data as $invoiceObj){
       
           $response = $invoice->add_sales_order(
            $invoiceObj->InvoiceNo, 
            0,
            $response= $customer->get_customer_by_ref($invoiceObj->CustId),
            3,
            3,
            $invoiceObj->RefNo, 
            '',
            $invoiceObj->comments,
            $invoiceObj->OrderDate, 
            1,
            1,
            $invoiceObj->DeliverTo, 
            $invoiceObj->DeliveryAddress,
            $invoiceObj->ContactPhoneNo,
            $invoiceObj->DeliveryCost,
            '',
            $invoiceObj->DeliveryDate, 
            1, 
            $invoiceObj->InvoiceTotal, 
            0
           );
                $itemsArray = $invoiceObj->items;
                foreach ($itemsArray as $item) {
                    $invoice->add_items($invoiceObj->InvoiceNo, 30, $item->Item, $item->Description, $item->UnitPrice, $item->Quantity);
                }

             echo json_encode($response);

        //    if($companyid='KAMP'){
        //     echo json_encode(array("status"=>"Invoice added", "amount"=>$invoiceObj->InvoiceTotal));

        //    }else if($companyid='PRISK'){
        //     echo json_encode(array("status"=>"Invoice added", "amount"=>($invoiceObj->InvoiceTotal))*21/100);

        //    }else{
        //     echo json_encode(array("status"=>"Invoice added", "amount"=>($invoiceObj->InvoiceTotal))*21/100);

        //    }
    
    }
    
}
if($action=='update-invoice'){
    // $id = isset($_GET['order-id']) ? $_GET['order-id']: die();
    $json = file_get_contents('php://input');
    $data = json_decode($json);   
    foreach($data as $invoiceObj){
        $response = $invoice->update_sales_order(
         $invoiceObj->order_no, 
         $invoiceObj->type,
         $invoiceObj->debtor_no,
         $invoiceObj->trans_type,
         $invoiceObj->branch_code, 
         $invoiceObj->customer_ref,
         $invoiceObj->reference, 
         $invoiceObj->comments,
         $invoiceObj->ord_date, 
         $invoiceObj->order_type,
         $invoiceObj->ship_via, 
         $invoiceObj->deliver_to, 
         $invoiceObj->delivery_address,
         $invoiceObj->contact_phone,
         $invoiceObj->freight_cost,
         $invoiceObj->from_stk_loc,
         $invoiceObj->delivery_date, 
         $invoiceObj->payment_terms, 
         $invoiceObj->total, 
         $invoiceObj->prep_amount
        ); 
           
    }
    echo json_encode($response);
}
if($action=='delete-invoice'){
    $id = isset($_GET['order-id']) ? $_GET['order-id']: die();
    $response = $invoice->delete_sales_order($id);
    echo json_encode($response);
}
}

?>
