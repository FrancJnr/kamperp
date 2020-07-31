<?php
class Invoice{
  
    // database connection and table name
	private $conn;
	public $customer_id;
       
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
	}
	function add_sales_order($order_no, $type, $debtor_no, $trans_type, $branch_code, $customer_ref, $reference, $comments,
	$ord_date, $order_type, $ship_via, $deliver_to, $delivery_address, $contact_phone,$freight_cost, $from_stk_loc, $delivery_date, $payment_terms, 
	$total, $prep_amount){
		try{
		

			$sql = "INSERT INTO 0_sales_orders (order_no, type, debtor_no, trans_type, branch_code, customer_ref, reference, comments,
			ord_date, order_type, ship_via, deliver_to, delivery_address, contact_phone,
			freight_cost, from_stk_loc, delivery_date, payment_terms, total, prep_amount)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(1, $order_no);
				$stmt->bindParam(2, $type);
				$stmt->bindParam(3, $debtor_no);
				$stmt->bindParam(4, $trans_type);	
				$stmt->bindParam(5, $branch_code);
				$stmt->bindParam(6, $customer_ref);
				$stmt->bindParam(7, $reference);
				$stmt->bindParam(8, $comments);
				$stmt->bindParam(9, $ord_date);
				$stmt->bindParam(10, $order_type);
				$stmt->bindParam(11, $ship_via);
				$stmt->bindParam(12, $deliver_to);
				$stmt->bindParam(13, $delivery_address);
				$stmt->bindParam(14, $contact_phone);
				$stmt->bindParam(15, $freight_cost);
				$stmt->bindParam(16, $from_stk_loc);
				$stmt->bindParam(17, $delivery_date);
				$stmt->bindParam(18, $payment_terms);
				$stmt->bindParam(19, $total);
				$stmt->bindParam(20, $prep_amount);

				$stmt->execute();
				$insertedRId = $this->conn->lastInsertId();
			
					
					echo("We are here  now");


		}catch(Exception $ex){
			$response = array('Error'=>$ex);
			var_dump($response);

	}
	}
	function add_items($order_no, $trans_type, $stk_code, $description, $unit_price, $quantity, $discount_percent){
		try {
			$sql2 = "INSERT INTO 0_sales_order_details (order_no, trans_type, stk_code, description, unit_price, quantity) 
			VALUES (?, ?,?,?,?,?)";
			$stmt2 = $this->conn->prepare($sql2);
			$stmt2->bindParam(1, $order_no);
			$stmt2->bindParam(2, $trans_type);
			$stmt2->bindParam(3, $stk_code);
			$stmt2->bindParam(4, $description);
			$stmt2->bindParam(5, $unit_price);
			$stmt2->bindParam(6, $quantity);
			$stmt2->execute();
			$response = array('Response'=>'Invoice created');
		} catch (Exception $ex) {
			var_dump($ex);
		}
	
	}	
function delete_sales_order($order_no){
	try{
			$sql = "DELETE FROM 0_sales_orders WHERE order_no=?";

			$stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $order_no);
            $stmt->execute();
			

			$sql2 = $sql = "DELETE FROM 0_sales_order_details WHERE order_no = ?";
			$stmt2 = $this->conn->prepare($sql2);
            $stmt2->bindParam(1, $order_no);
			$stmt2->execute();
			return "Deleted";

	}catch(Exception $ex){
		var_dump($ex);
	}
}
	
function update_sales_order($order_no, $type, $debtor_no, $trans_type, $branch_code, $customer_ref, $reference, $comments,
$ord_date, $order_type, $ship_via, $deliver_to, $delivery_address, $contact_phone,$freight_cost, $from_stk_loc, $delivery_date, $payment_terms, 
$total, $prep_amount){
try {
	$sql = "UPDATE 0_sales_orders SET type = ?,
		debtor_no =?, trans_type=?, branch_code = ?, customer_ref = ?, reference = ?, comments = ?,
		ord_date = ?, order_type = ?, ship_via = ?, deliver_to = ?, delivery_address = ?,
		contact_phone = ?, freight_cost = ?, from_stk_loc = ?, delivery_date = ?,
		payment_terms = ?, total = ?, prep_amount = ?
	 WHERE order_no=?";
		$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(1, $type);
				$stmt->bindParam(2, $debtor_no);
				$stmt->bindParam(3, $trans_type);	
				$stmt->bindParam(4, $branch_code);
				$stmt->bindParam(5, $customer_ref);
				$stmt->bindParam(6, $reference);
				$stmt->bindParam(7, $comments);
				$stmt->bindParam(8, $ord_date);
				$stmt->bindParam(9, $order_type);
				$stmt->bindParam(10, $ship_via);
				$stmt->bindParam(11, $deliver_to);
				$stmt->bindParam(12, $delivery_address);
				$stmt->bindParam(13, $contact_phone);
				$stmt->bindParam(14, $freight_cost);
				$stmt->bindParam(15, $from_stk_loc);
				$stmt->bindParam(16, $delivery_date);
				$stmt->bindParam(17, $payment_terms);
				$stmt->bindParam(18, $total);
				$stmt->bindParam(19, $prep_amount);
				$stmt->bindParam(20, $order_no);
				$stmt->execute();
		$stmt->execute();
		return "Updated";
	} catch (Exception $ex) {
		return "Invoice details updated";
	}

}


	function get_sales_order($order_no){
		
		try {
			$sql = "SELECT `order_no`, `trans_type`, 
		`type`, `debtor_no`, `branch_code`, `reference`, `customer_ref`, `comments`,
		 `ord_date`, `order_type`, `ship_via`, `delivery_address`, `contact_phone`, 
		 `contact_email`, `deliver_to`, `freight_cost`, `from_stk_loc`, `delivery_date`, 
		 `payment_terms`, `total`, `prep_amount`, `alloc` 
		 FROM `0_sales_orders` 
		 WHERE order_no = ?";
		$stmt = $this->conn->prepare($sql);
		$invoice=array();	
		$stmt->bindParam(1, $order_no);
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row); 
				$invoice[]=array(                              
				'id'=>$row['order_no'],
				'TransType'=>$row['trans_type'],
				'Type'=>$row['type'],
				'CustomerId'=>$row['debtor_no'],
				'BranchCode'=>$row['branch_code'],
				'Reference'=>$row['reference'],
				'CustomerRef'=>$row['customer_ref'],
				'Comments'=>$row['comments'],
				'OrderDate'=>$row['ord_date'],
				'OrderType'=>$row['order_type'],
				'DeliveryAddress'=>$row['delivery_address'],
				'ContactPhone'=>$row['contact_phone'],
				'ContactEmail'=>$row['contact_email'],
				'DeliverTo'=>$row['deliver_to'],
				'FleightCost'=>$row['freight_cost'],
				'DeliveryDate'=>$row['delivery_date'],
				'Total'=>$row['total'],
				'PrepAmount'=>$row['prep_amount']
				);
				// var_dump($invoice);
				return $invoice;
			}
			
		
		} catch (Exception $ex) {
			var_dump($ex);
		}
		
	}

}

?>