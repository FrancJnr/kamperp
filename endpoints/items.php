<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$path_to_root = "";

include($path_to_root . "../db/items_db.php");
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
$item = new Item($conn);

if($action=='get-item'){
    $id = isset($_GET['id']) ? $_GET['id']: die();
    $results = $item->get_item_code($id);
    $ItemCode = intval($item->ItemCodeId);
    // var_dump(intval($ItemCode));
    
    // $prices = $item->get_item_price($ItemCode);
    echo json_encode($results);

}
if($action=='add-item'){
    $json = file_get_contents('php://input');
    $data = json_decode($json);   
    foreach($data as $itemObj){
           $response = $item->add_stock_item(
            $itemObj->ItemCode,        
            $itemObj->StockId,
            $itemObj->Description,
            $itemObj->Category,
            $itemObj->Quantity            
        );
        if($itemObj->Price != null){
            $item->add_item_price($itemObj->ItemCode, $itemObj->SalesTypeId, $itemObj->CurrencyAbbr, $itemObj->Price);       
        }
    }
    echo json_encode($response);
}
if($action=='update-item'){
    $id = isset($_GET['id']) ? $_GET['id']: die();
    $item->id=$id;
    $json = file_get_contents('php://input');
    $data = json_decode($json);   
    foreach($data as $itemObj){
        $response = $item->update_item_code(
            $itemObj->ItemCode,        
            $itemObj->StockId,
            $itemObj->Description,
            $itemObj->Category,
            $itemObj->Quantity  
        ); 
        if($itemObj->Price != null){
            $item->update_item_price($itemObj->ItemCode, $itemObj->SalesTypeId, $itemObj->CurrencyAbbr, $itemObj->Price);       
        }       
    }
    echo json_encode($response);
}
if($action=='delete-item'){
    $id = isset($_GET['id']) ? $_GET['id']: die();
    $item->id=$id;
    $response = $item->delete_item_code();
    echo json_encode($response);
}
}

?>
