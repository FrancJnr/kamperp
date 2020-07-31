<?php

class Customer{
  
    // database connection and table name
	private $conn;
	public $customer_id;
       
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
	}
function add_customer($CustName, $cust_ref, $address, $tax_id, $curr_code,
	$dimension_id, $dimension2_id, $credit_status, $payment_terms, $discount, $pymt_discount, 
	$credit_limit, $sales_type, $notes)
{

	try{
		$sql = "INSERT INTO 0_debtors_master (name, debtor_ref, address, tax_id,
		dimension_id, dimension2_id, curr_code, credit_status, payment_terms, discount, 
		pymt_discount,credit_limit, sales_type, notes) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $CustName);
			$stmt->bindParam(2, $cust_ref);
			$stmt->bindParam(3, $address);
			$stmt->bindParam(4, $tax_id);	
			$stmt->bindParam(5, $dimension_id);
			$stmt->bindParam(6, $dimension2_id);
			$stmt->bindParam(7, $curr_code);
			$stmt->bindParam(8, $credit_status);
			$stmt->bindParam(9, $payment_terms);
			$stmt->bindParam(10, $discount);
			$stmt->bindParam(11, $pymt_discount);
			$stmt->bindParam(12, $credit_limit);
			$stmt->bindParam(13, $sales_type);
			$stmt->bindParam(14, $notes);
			$stmt->execute();
			$insertedRId = $this->conn->lastInsertId();
            $response = array('Response'=>$CustName.' Has been added', 'RefId'=>$insertedRId);
			return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}
	
}

function update_customer($CustName,  $address, $tax_id, $curr_code, $credit_status, $payment_terms, $discount, $pymt_discount,
	$credit_limit, $sales_type, $notes)
{
	try{
		$sql = "UPDATE 0_debtors_master SET name= ?, 
		 address= ?, tax_id=?, curr_code=?,  credit_status=?, payment_terms=?, discount=?, pymt_discount=?, credit_limit=?, 
		sales_type = ?, notes=?
		WHERE debtor_ref = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $CustName);
		$stmt->bindParam(2, $address);
		$stmt->bindParam(3, $tax_id);	
		$stmt->bindParam(4, $curr_code);
		$stmt->bindParam(5, $credit_status);
		$stmt->bindParam(6, $payment_terms);
		$stmt->bindParam(7, $discount);
		$stmt->bindParam(8, $pymt_discount);
		$stmt->bindParam(9, $credit_limit);
		$stmt->bindParam(10, $sales_type);
		$stmt->bindParam(11, $notes);
		$stmt->bindParam(12, $this->customer_id);
		$stmt->execute();

		$response = array('Response'=>$CustName.' Has been Updated');
		return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}

}

function delete_customer(){
	try{
		$sql = "DELETE FROM 0_debtors_master WHERE debtor_ref= ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $this->customer_id);
		$stmt->execute();
		$response = array('Response'=>'Customer with id '.$this->customer_id.' Has been Deleted');
		return $response;
	}catch(Exception $ex){
		var_dump($ex);
	}

}
function get_customer($customer_id){
	$sql = "SELECT * FROM  0_debtors_master WHERE debtor_ref= ?";
	$customer=array();
	 $stmt = $this->conn->prepare($sql);
     $stmt->bindParam(1, $customer_id);
        // execute query
     $stmt->execute();
	 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row); 
			$customer[]=array(                              
			'CustomerId'=>$row['debtor_ref'],
			'CustName'=>$row['name'],
			// 'CustRef'=>$row['debtor_ref'],
			'Address'=>$row['address'],
			'TaxId'=>$row['tax_id'],
			'CurrencyCode'=>$row['curr_code'],
			'SalesType'=>$row['sales_type'],
			'CreditStatus'=>$row['credit_status'],
			'PaymentTerms'=>$row['payment_terms'],
			'Discount'=>$row['discount'],
			'paymentDiscount'=>$row['pymt_discount'],
			'CreditLimit'=>$row['credit_limit'],
			'Notes'=>$row['notes'],
			'Active'=>$row['inactive']
			);
            return $customer;
        }

	
}
function get_customer_by_ref($ref){
	$sql = "SELECT * FROM  0_debtors_master WHERE debtor_ref= ?";
	 $stmt = $this->conn->prepare($sql);
     $stmt->bindParam(1, $ref);
        // execute query
     $stmt->execute();
	 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row); 
            return $row['debtor_no'];
        }	
	}
}
