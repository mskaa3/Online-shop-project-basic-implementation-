<?php require('components/head.inc.php'); ?>

<button onclick="window.location.href='indexWorker.php'" class="button backButton">Go Back</button><br>
<div class="deleteHeader"><h1> Delete product </h1></div>
<?php

  require('connectDatabase.php');
  $sql = "SELECT DISTINCT productsinshop.Product_productID,brand.brandName,model.modelName, atributevalue.value FROM rental.brand,rental.productsinshop,rental.model,rental.atributevalue,rental.product WHERE productsinshop.Shop_shopID=1 
  AND productsinshop.Product_productID=product.productID 
  AND product.model_modelID=model.modelID 
  AND brand.brandID=model.brand_brandID
  AND atributevalue.Prooduct_productID=productsinshop.Product_productID;";
  $result = mysqli_query($mysqli,$sql);

  if ($result->num_rows > 0) {
      ?>
 
          <form method="post" class="deleteForm" >
          <label for="deleteForm">Choose product to delete: </label>
          <select name="deleteOption">
          <option value=""> </option>
             <?php
               while ($row = $result->fetch_assoc()) 
               {
                 echo '<option value=" '.$row['Product_productID'].' ">  '.$row['Product_productID'].' '.$row['brandName'].' '.$row['modelName'].' '.$row['value'].' </option>';
               }
            ?>
          </select>
          <input type="Submit" value="Delete" ></input>
        </form>
        
      <?php
      
  } else {
  echo "no results";
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $what2look4 = $_POST['deleteOption'];

        $del = mysqli_query($mysqli,"delete from product where product.productID = '$what2look4'"); 
        
        if($del)
        {
            mysqli_close($mysqli); 
          
        }
        else
        {
          ?> 
          <div style="text-align:center"><p>Error deleting the product <?php echo mysqli_error($mysqli)?></p1> </div><?php
     
        }
    }  
?>
<?php require('components/footer.inc.php'); ?>