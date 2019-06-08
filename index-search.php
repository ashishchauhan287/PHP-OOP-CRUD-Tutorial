<?php
// Core.php hold pagination variables
include_once('config/core.php');

// include database and object files
include_once('config/database.php');
include_once('objects/product.php');
include_once('objects/category.php');


// instantiated database and product object
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$page_title = "Read Products With Search";
include_once('layout_header.php');

// Query Products
$stmt = $product->readAll($from_record_num, $records_per_page);

// Specify the page where paging is used
$page_url = "index.php?";

// Count Total Rows - used for pagination
$total_rows = $product->countAll();

// read_template.php control how the product list will be rendered
include_once('read_template.php');


// layout_footer.php hold our javascript and closing html tags
include_once('layout_footer.php');

?>
