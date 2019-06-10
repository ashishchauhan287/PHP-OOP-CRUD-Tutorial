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
	public $image;
	public $timestamp;


	public function __construct($db){
		$this->conn = $db;
	}

	// create product
	function create()
	{
		$this->timestamp = date('Y-m-d H:i:s');
		$query = "INSERT INTO `products`( `name`, `description`, `price`, `category_id`,`image`,`created`) VALUES ('{$this->name}','{$this->description}','{$this->price}','{$this->category_id}','{$this->image}','{$this->timestamp}')"; 
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
		$query = "SELECT name, price, description, category_id, image FROM ".$this->table_name." WHERE id = ".$this->id." LIMIT 0,1";

		$stmt = $this->conn->query($query);
		$row = $stmt->fetch_assoc();

		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
		$this->image = $row['image'];
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

	// will upload image file to server
	function uploadPhoto(){
		$result_message = "";

		// now, if image is not empty, try to upload the image
		if($this->image){

			// sha1_file() function is used to make a unique file name
			$target_directory = "uploads/";
			$target_file = $target_directory . $this->image;
			$file_type = pathinfo($target_file , PATHINFO_EXTENSION);

			// ERROR message is empty
			$file_upload_error_messages = "";

			// make sure that file is a real image
			$check = getimagesize($_FILES['image']['tmp_name']);
			if($check!==false){
				// Submitted file is an image
			}else{
				$file_upload_error_messages.="<div> Submitted file is not an image.</div>";
			}

			// make sure certain file types are allowed
			$allowed_file_types = array("jpg","jpeg","png","gif");
			if(!in_array($file_type, $allowed_file_types)){
				$file_upload_error_messages."<div>Only JPG, JPEG, PNG, GIF files are allowed";
			}

			// make sure file does not exist
			if(file_exists($target_file)){
				$file_upload_error_messages.="<div>Image already exists. Try to change file name.</div>";
			}

			// make sure submittted file is not too large, can't be larger then 1 MB
			if($_FILES['image']['size'] > (1024000)){
				$file_upload_error_messages.="<div>Image must be less than 1 MB in size.</div>";
			}

			// make sure the 'uploads' folder exists
			// if not, create it
			if(!is_dir($target_directory)){
			    mkdir($target_directory, 0777, true);
			}


			// if $file_upload_error_messages is still empty
			if(empty($file_upload_error_messages)){
			    // it means there are no errors, so try to upload the file
			    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
			        // it means photo was uploaded
			    }else{
			        $result_message.="<div class='alert alert-danger'>";
			            $result_message.="<div>Unable to upload photo.</div>";
			            $result_message.="<div>Update the record to upload photo.</div>";
			        $result_message.="</div>";
			    }
			}
			 
			// if $file_upload_error_messages is NOT empty
			else{
			    // it means there are some errors, so show them to user
			    $result_message.="<div class='alert alert-danger'>";
			        $result_message.="{$file_upload_error_messages}";
			        $result_message.="<div>Update the record to upload photo.</div>";
			    $result_message.="</div>";
			}
		}

		return $result_message;
	}

}

?>