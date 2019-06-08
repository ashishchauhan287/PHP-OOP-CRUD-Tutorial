<?php
class Product{

	// databse connection and table name
	private $conn;
	private $table_name = "products";

	// object properties
	public $id;
	public $name;
	public $price;
	public $description;
	public $category_id;
	public $timestamp;


	public function __construct($db){
		$this->conn = $db;
	}

	// create product
	function create()
	{
		$this->timestamp = date('Y-m-d H:i:s');
		$query = "INSERT INTO `products`( `name`, `description`, `price`, `category_id`, `created`) VALUES ('{$this->name}','{$this->description}','{$this->price}','{$this->category_id}','{$this->timestamp}')"; 
		if($this->conn->query($query))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// Read Data
	function readAll($from_record_num, $records_per_page)
	{
		$query = "SELECT id,name,description,price,category_id FROM ".$this->table_name." ORDER BY name ASC LIMIT {$from_record_num},{$records_per_page}";

		$stmt  = $this->conn->query($query);
		return $stmt;
	}


	// used for paging products
	public function countAll(){
	 
	    $query = "SELECT id FROM " . $this->table_name . "";
	 
	    $stmt = $this->conn->query( $query );
	 
	    $num = $stmt->num_rows;
	 
	    return $num;
	}

	// Read one product data
	function readone()
	{
		$query = "SELECT name, price, description, category_id FROM ".$this->table_name." WHERE id = ".$this->id." LIMIT 0,1";

		$stmt = $this->conn->query($query);
		$row = $stmt->fetch_assoc();

		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
	}

	// Update Product Data
	function update(){

	    // posted values
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->price=htmlspecialchars(strip_tags($this->price));
	    $this->description=htmlspecialchars(strip_tags($this->description));
	    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
	    $this->id=htmlspecialchars(strip_tags($this->id));
	 
	    $query = "UPDATE
	                " . $this->table_name . "
	            SET
	                name = '".$this->name."',
	                price = '".$this->price."',
	                description = '".$this->description."',
	                category_id  = '".$this->category_id."'
	            WHERE
	                id = ".$this->id;
	 
	    $stmt = $this->conn->query($query);
	 	  
	    // execute the query
	    if($stmt){
	        return true;
	    }
	 
	    return false;
	     
	}


	// delete the product
	function delete(){
	 
	    $query = "DELETE FROM " . $this->table_name . " WHERE id = ".$this->id;
	     
	    $stmt = $this->conn->query($query);	 
	    if($stmt){
	        return true;
	    }else{
	        return false;
	    }
	}


	// read products by search term
	public function search($search_term, $from_record_num, $records_per_page){

		$search_term = "%{$search_term}%";
		// select Query
		$query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE '".$search_term."' OR p.description LIKE '".$search_term."'
            ORDER BY
                p.name ASC
            LIMIT
                ".$from_record_num.", ".$records_per_page;

              $stmt = $this->conn->query($query); 

		    // return values from database
		    return $stmt;

	}


	public function countAll_BySearch($search_term){

		$search_term = "%{$search_term}%";
		//select query
		$query = "SELECT
                COUNT(*) as total_rows
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE '".$search_term."' OR p.description LIKE '".$search_term."'" ;

        $stmt = $this->conn->query($query);
        $row = $stmt->fetch_assoc();
        return $row['total_rows'];
	}

}

?>