<?php
//https://www.codeofaninja.com/2014/06/php-object-oriented-crud-example-oop.html
// set page headers
// include database and object files
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// pass connection to objects
$product = new Product($db);
$category = new Category($db);

$page_title = "Create Product";
include_once "layout_header.php";
 
// contents will be here
echo "<div class='right-button-margin'>";
    echo "<a href='index.php' class='btn btn-default pull-right'>Read Products</a>";
echo "</div>";


 
?>
<?php 
// if the form was submitted - PHP OOP CRUD Tutorial
if(isset($_POST['CatSubmit']) ){
    // set product property values
    $product->name = htmlspecialchars(strip_tags($_POST['name']));
    $product->price = htmlspecialchars(strip_tags($_POST['price']));
    $product->description = htmlspecialchars(strip_tags($_POST['description']));
    $product->category_id = htmlspecialchars(strip_tags($_POST['category_id']));
    $image=!empty($_FILES['image']['name']) ? sha1_file($_FILES['image']['tmp_name'])."-".basename($_FILES['image']['name']) : "";
    $product->image = htmlspecialchars(strip_tags($image));
 
  	// try to upload the submitted file
 	$product->uploadPhoto();
    // create the product
    if($product->create()){
        echo "<div class='alert alert-success'>Product was created.</div>";
    }
 
    // if unable to create the product, tell the user
    else{
        echo "<div class='alert alert-danger'>Unable to create product.</div>";
    }
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Name</td>
            <td><input type='text' name='name' class='form-control' /></td>
        </tr>
 
        <tr>
            <td>Price</td>
            <td><input type='text' name='price' class='form-control' /></td>
        </tr>
 
        <tr>
            <td>Description</td>
            <td><textarea name='description' class='form-control'></textarea></td>
        </tr>
		 <tr>
		    <td>Photo</td>
		    <td><input type="file" name="image" /></td>
		</tr>
        <tr>
            <td>Category</td>
            <td>
				<?php
				// read the product categories from the database
				$stmt = $category->read();
				 
				// put them in a select drop-down
				echo "<select class='form-control' name='category_id'>";
				    echo "<option>Select category...</option>";
				 
				    while ($row_category = $stmt->fetch_assoc()){
				        extract($row_category);
				        echo "<option value='{$id}'>{$name}</option>";
				    }
				 
				echo "</select>";
				?>
            </td>
        </tr>
 
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary" name="CatSubmit">Create</button>
            </td>
        </tr>
 
    </table>
</form>

<?php
 
// footer
include_once "layout_footer.php";
?>