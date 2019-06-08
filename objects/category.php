<?php
class Category{

	// Database Connection and table name
	private $conn;
	private $table_name = "categories";

	// Object properties
	public $id;
	public $name;

	public function __construct($db){
		$this->conn = $db;
	}

	// used by select drop-down list
	function read()
	{
		 $query = "SELECT id,name FROM ".$this->table_name." ORDER BY name";

		$stmt = $this->conn->query($query);
		return $stmt;
	}


	// used to read category name by its ID
	function readName()
	{
		$query = "SELECT name FROM ".$this->table_name." WHERE id = ".$this->id;
		$stmt = $this->conn->query($query);

		$fh = $stmt->fetch_assoc();
		return $this->name = $fh['name'];
	}
}
?>