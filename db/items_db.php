<?php

class Item{
  
    // database connection and table name
	private $conn;
    public $id;
    public $ItemCodeId;
       
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
	}
    function add_item_code($item_code, $stock_id, $description, $category, $qty, $foreign=0){

	try{
		$sql = "INSERT INTO `0_item_codes`(`item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`)
         VALUES (?,?,?,?,?,?)";
			$stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $item_code);
            $stmt->bindParam(2, $stock_id);
            $stmt->bindParam(3, $description);
            $stmt->bindParam(4, $category);	
            $stmt->bindParam(5, $qty);
            $stmt->bindParam(6, $foreign);
            $stmt->execute();
			$insertedRId = $this->conn->lastInsertId();
            $response = array('Response'=>$item_code.' Has been added', 'id'=>$insertedRId);
			return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}
	
}
function add_stock_item($item_code, $stock_id, $description, $category, $qty, $foreign=0){

	try{
		$sql = "INSERT INTO `0_item_codes`(`item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`)
         VALUES (?,?,?,?,?,?)";
			$stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $item_code);
            $stmt->bindParam(2, $stock_id);
            $stmt->bindParam(3, $description);
            $stmt->bindParam(4, $category);	
            $stmt->bindParam(5, $qty);
            $stmt->bindParam(6, $foreign);
            $stmt->execute();
			$insertedRId = $this->conn->lastInsertId();
			
		$sql2 = "INSERT INTO 0_stock_master (stock_id, description, long_description, category_id,
		tax_type_id, units, mb_flag, sales_account, inventory_account, cogs_account,
		adjustment_account, wip_account)
		VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
			$stmt2 = $this->conn->prepare($sql2);
			$stmt2->bindParam(1, $item_code);
			$stmt2->bindParam(2, $description);
			$stmt2->bindParam(3, $description);	
			$stmt2->bindParam(4, $category);
			$stmt2->bindValue(5, 1);
			$stmt2->bindParam(6, $qty);
			$stmt2->bindValue(7, 'D');
			$stmt2->bindValue(8, '100101');
			$stmt2->bindValue(9, '200101');
			$stmt2->bindValue(10, '1510');
			$stmt2->bindValue(11, '1510');
			$stmt2->bindValue(12, '1060');
			$stmt2->execute();
			$response = array('Response'=>$item_code.' Has been added', 'id'=>$insertedRId);
			return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}
	
}

function update_item_code($item_code, $stock_id, $description, $category, $qty, $foreign=0){
	try{
		$sql = "UPDATE 0_item_codes SET item_code =?, stock_id = ?, description = ?,
	 	category_id = ?, quantity = ?, is_foreign = ?
		WHERE item_code = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $item_code);
		$stmt->bindParam(2, $stock_id);
		$stmt->bindParam(3, $description);
		$stmt->bindParam(4, $category);	
		$stmt->bindParam(5, $qty);
		$stmt->bindParam(6, $foreign);
		$stmt->bindParam(7, $this->id);
		$stmt->execute();

		$response = array('Response'=>$item_code.' Has been Updated');
		return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}

}

function delete_item_code(){
	try{
		$sql = "DELETE FROM 0_item_codes WHERE item_code= ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$response = array('Response'=>'item with id '.$this->id.' Has been Deleted');
		return $response;
	}catch(Exception $ex){
		var_dump($ex);
	}

}

function get_item_code($id){
	
	$sql = "SELECT * FROM  0_item_codes WHERE item_code= ?";
	$items=array();
	 $stmt = $this->conn->prepare($sql);
     $stmt->bindParam(1, $id);
        // execute query
     $stmt->execute();
	 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row); 
			$items[]=array(                              
			'id'=>$row['id'],
			'ItemCode'=>$row['item_code'],
			'StockId'=>$row['stock_id'],
            'Description'=>$row['description'],
            'Category'=>$row['category_id'],
			'Quantity'=>$row['quantity'],
			'InActive'=>$row['inactive']
            );
            $this->ItemCodeId= $row['item_code'];
            return $items;
        }

	
}
function get_item_price($id){
	$sql = "SELECT * FROM  0_prices WHERE stock_id= ?";
	$price=array();
	 $stmt = $this->conn->prepare($sql);
     $stmt->bindParam(1, $id);
        // execute query
     $stmt->execute();
	 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row); 
			$price=array(                              
            'SalesTypeId'=>$row['sales_type_id'],
            'CurrencyAbbr'=>$row['curr_abrev'],
            'Price'=>$row['price']
		);
            return $price;
        }

	
}
function add_item_price($stock_id, $sales_type_id, $curr_abrev, $price){
	try{
		$sql = "INSERT INTO `0_prices`(`stock_id`, `sales_type_id`, `curr_abrev`, `price`)
        VALUES (?,?,?,?)";
			$stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $stock_id);
            $stmt->bindParam(2, $sales_type_id);
            $stmt->bindParam(3, $curr_abrev);
            $stmt->bindParam(4, $price);	
            $stmt->execute();
			$insertedRId = $this->conn->lastInsertId();
            $response = array('Response'=>$stock_id.' Price has been Set', 'id'=>$insertedRId);
			return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}
	
}
function update_item_price($stock_id, $sales_type_id, $curr_abrev, $price){
	try{
		$sql = "UPDATE 0_prices SET sales_type_id = ?, curr_abrev = ?,
	 	price = ?
		WHERE stock_id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $sales_type_id);
		$stmt->bindParam(2, $curr_abrev);
		$stmt->bindParam(3, $price);
		$stmt->bindParam(4, $stock_id);	
		$stmt->execute();

		$response = array('Response'=>$stock_id.' Has been Updated');
		return $response;

	}catch(Exception $ex){
		var_dump($ex);
	}

}
function delete_item_price($stock_id){
	try{
		$sql = "DELETE FROM 0_prices WHERE id= ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(1, $stock_id);
		$stmt->execute();
		$response = array('Response'=>'Price for Has been Deleted');
		return $response;
	}catch(Exception $ex){
		var_dump($ex);
	}

}




}
