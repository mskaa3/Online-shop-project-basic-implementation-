<?php require('components/head.inc.php'); ?>

<button onclick="window.location.href='indexWorker.php'" class="button backButton">Go Back</button><br>
<div class="deleteHeader"><h1> Add product </h1></div>
<?php
require('connectDatabase.php');
$sql = "select brandName,brandID FROM rental.brand;";
$result = mysqli_query($mysqli,$sql);
?>
<form method="post" class="deleteForm" >
<label for="deleteForm">Choose brand: </label>

<?php
if ($result->num_rows > 0) {
?>
<select id="brandOption" name="brandOption" required >
        <option value=" "> </option>
           <?php
             while ($row = $result->fetch_assoc()) 
             {
               echo '<option value=" '.$row['brandID'].' ">  '.$row['brandName'].'  </option>';
             }
          ?>
</select>
<?php
} else {
    echo "no results";
}
?>
<input type="Submit" value="Choose" ></input>
    </form>
      
    <?php
    

if ($_SERVER['REQUEST_METHOD'] == 'POST'&&isset($_POST['brandOption'])) {
  
      $what2look4 = $_POST['brandOption'];
      $result2 = mysqli_query($mysqli,"select distinct modelName,modelID FROM rental.model,rental.brand where model.brand_brandID=".$what2look4 .";");
      $result3 = mysqli_query($mysqli,"select distinct class.className,class.classID FROM rental.class;"); 
      if (($result2->num_rows > 0)) {
          if(($result3->num_rows > 0)){
          
      ?>
<form method="post" class="deleteForm" >
            <label for="deleteForm">Choose model: </label>
            <select name="modelOption" required>
            <?php
                while ($row2 = $result2->fetch_assoc()) 
                {
                echo '<option value=" '.$row2['modelID'].' ">  '.$row2['modelName'].'  </option>';
                }
            ?>
            </select></br></br>
            <label for="deleteForm">Choose class: </label>
            <select name="classOption" required>
            <?php
                while ($row3 = $result3->fetch_assoc()) 
                {
                echo '<option value=" '.$row3['classID'].' ">  '.$row3['className'].'  </option>';
                }
            ?>
            </select></br></br>

            <label for="param1">Enter parameter value:</label>
            <input type="text" id="param1" name="param1" required></br></br>
            <label for="param2">Enter hardness (for shoes)*:</label>
            <input type="text" id="param2" name="param2" ></br></br>
      <!-- 
            Here should be second parameter under condition -->
            <label for="price">Enter daily price:</label>
            <input type="text" id="price" name="price" required></br></br>
            <input id="AddButton" type="Submit" value="Add" ></input>
        </form>

         <?php

        }

      }else {
            echo "no model results";      
      }

}



if (isset($_POST['modelOption'])) {
    $model =isset($_POST['modelOption'])?$_POST['modelOption']:'';
    $brand = isset($_POST['brandOption'])?$_POST['brandOption']:'';
    $class = isset($_POST['classOption'])?$_POST['classOption']:'';
    $parameter1 = isset($_POST['param1'])?$_POST['param1']:'';
    $parameter2 = isset($_POST['param2'])?$_POST['param2']:'';
    $price =isset($_POST['price'])?$_POST['price']:'';

// Help queries used later on in another queries
    $numQuery=mysqli_query($mysqli,"select max(product.productID) FROM rental.product");
    if($numQuery->num_rows > 0){
        $productNumber = $numQuery->fetch_assoc();
        $pID=$productNumber['max(product.productID)'];
    }
    
    $relationQuery=mysqli_query($mysqli,"SELECT atributetyperelation.relationID FROM rental.atributetyperelation,rental.model,rental.type
    where  model.modelID='$model'
    AND model.Type_typeID=type.typeID
    AND atributetyperelation.Type_typeID=type.typeID LIMIT 1;");
  if($relationQuery->num_rows > 0){
      $relrow = $relationQuery->fetch_assoc();
       $relID=$relrow['relationID'];
      
  }


// "Real" queries
    $addProduct="INSERT INTO rental.product
    Values
    ('$pID'+1,'$price',1,'$class',
    '$model');";
    $checkIfShoes="select model.Type_typeID FROM rental.model where model.modelID='$model'";

    $atributeval="INSERT INTO rental.atributevalue
    Values('$parameter1','$relID',
    '$pID'+1);";
    $atributeval2="INSERT INTO rental.atributevalue
                              Values('$parameter2',5,
                              '$pID'+1);";
    $addtoshop="INSERT INTO rental.productsinshop
                VALUES(1,'$pID'+1);";
                      
    $checkIfShoesQuery=mysqli_query($mysqli,$checkIfShoes);
    $addQuery = mysqli_query($mysqli,$addProduct); 
    $atributequery=mysqli_query($mysqli,$atributeval);
     $shopquery=mysqli_query($mysqli,$addtoshop);
      
    
    if($checkIfShoesQuery->num_rows > 0){
        $row4 = $checkIfShoesQuery->fetch_assoc();
        if($row4['Type_typeID']==2){
            if($parameter2='' || $parameter2<50){
                ?> 
                <div style="text-align:center"><p> Incorrect value of the second parameter </p1> </div><?php
            }else{
             if($addQuery){ 
                if($atributequery){
                    $atributequery2=mysqli_query($mysqli,$atributeval2);
                    if($atributequery2){
                        if(!$shopquery){
                            ?> 
                <div style="text-align:center"><p>Error adding record to the shop <?php echo mysqli_error($mysqli)?></p1> </div><?php
                           
                        }
                    }else{
                        ?> 
                        <div style="text-align:center"><p>Error adding the second record to atribute values <?php echo mysqli_error($mysqli)?></p1> </div><?php
                   
                        $del = mysqli_query($mysqli,"delete from product where product.productID = '$pID'+1"); 
        
                        if($del)
                        {
                            mysqli_close($mysqli); 
                        }
                    }
                }else{
                    ?> 
                    <div style="text-align:center"><p>Error adding the record to atribute values <?php echo mysqli_error($mysqli)?></p1> </div><?php
               
                    $del = mysqli_query($mysqli,"delete from product where product.productID = '$pID'+1"); 
        
                        if($del)
                        {
                            mysqli_close($mysqli); 
                        }
                    
                }
            }else{
                ?> 
                <div style="text-align:center"><p>Error adding the product <?php echo mysqli_error($mysqli)?></p1> </div><?php
           
            }
           }
       }else{
        if($addQuery){ 
            if($atributequery){
                if(!$shopquery){
                    ?> 
                    <div style="text-align:center"><p>Error adding record to the shop <?php echo mysqli_error($mysqli)?></p1> </div><?php
                               
                    }
                
            }else{
                ?> 
                <div style="text-align:center"><p>Error adding the record to atribute values <?php echo mysqli_error($mysqli)?></p1> </div><?php
           
            }
        }else{
            ?> 
                <div style="text-align:center"><p>Error adding the product <?php echo mysqli_error($mysqli)?></p1> </div><?php
           
        }  
      }
    }else{
        ?> 
        <div style="text-align:center"><p>Something went wrong, sorry</p1> </div><?php
   
    }
    mysqli_close($mysqli);    
}

?>
<?php require('components/footer.inc.php'); ?>