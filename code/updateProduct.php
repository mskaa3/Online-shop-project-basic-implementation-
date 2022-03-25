<?php require('components/head.inc.php'); ?>
<button onclick="window.location.href='indexWorker.php'" class="button backButton">Go Back</button><br>


<div class="deleteHeader"><h1> Update product </h1></div>
<?php
require('connectDatabase.php');
$sql = "SELECT DISTINCT productsinshop.Product_productID,brand.brandName,model.modelName FROM rental.brand,rental.productsinshop,rental.model,rental.product WHERE productsinshop.Shop_shopID=1 
AND productsinshop.Product_productID=product.productID 
AND product.model_modelID=model.modelID 
AND brand.brandID=model.brand_brandID;";
$result = mysqli_query($mysqli,$sql);
$result2 = mysqli_query($mysqli,"SELECT model.modelName,model.modelID from rental.model;"); 
$result3 = mysqli_query($mysqli,"select class.className,class.classID from rental.class;"); 
if ($result->num_rows > 0) {
    ?>
 
        <form method="post" class="deleteForm" >
        <label for="deleteForm">Choose product to update: </label>
        <select name="changeOption">
           <?php
             while ($row = $result->fetch_assoc()) 
             {
               echo '<option value=" '.$row['Product_productID'].' "> ID: '.$row['Product_productID'].' -currently: '.$row['brandName'].' '.$row['modelName'].'  </option>';
             }
          ?>
        </select>

      <?php
       
            if (($result2->num_rows > 0)) {
                if(($result3->num_rows > 0)){
                   
            ?>

</br></br>
                    <label for="deleteForm">Choose model: </label>
                    <select name="modelOption" required>>
                    <?php
                    
                        while ($row2 = $result2->fetch_assoc()) 
                        {
                        echo '<option value=" '.$row2['modelID'].' ">  '.$row2['modelName'].'  </option>';
                        }
                    ?>
                    </select></br></br>
                    <label for="deleteForm">Choose class: </label>
                    <select name="classOption"required>>
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
                    <input id="UpdateButton" type="Submit" value="Update" ></input>
                </form>
              
            <?php
            
                }
            
             }
            else {
                    echo "no model results";

             }

} else {
echo "no results";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $what2look4 = $_POST['changeOption'];
    
        

    $model =isset($_POST['modelOption'])?$_POST['modelOption']:'';
    $class = isset($_POST['classOption'])?$_POST['classOption']:'';
    $parameter1 = isset($_POST['param1'])?$_POST['param1']:'';
    $parameter2 = isset($_POST['param2'])?$_POST['param2']:'';
    $price =isset($_POST['price'])?$_POST['price']:'';

    $relationQuery=mysqli_query($mysqli,"SELECT atributetyperelation.relationID FROM rental.atributetyperelation,rental.model,rental.type
    where  model.modelID='$model'
    AND model.Type_typeID=type.typeID
    AND atributetyperelation.Type_typeID=type.typeID LIMIT 1;");

    if($relationQuery->num_rows > 0){
        $relrow = $relationQuery->fetch_assoc();
        $relID=$relrow['relationID'];
        
    }
    $relationQuery2=mysqli_query($mysqli,"SELECT atributetyperelation.relationID FROM rental.atributetyperelation,rental.model,rental.type
    where  model.modelID='$model'
    AND model.Type_typeID=type.typeID
    AND atributetyperelation.Type_typeID=type.typeID LIMIT 1,1;");

    if($relationQuery2->num_rows > 0){
        $relrow2 = $relationQuery->fetch_assoc();
        $relID2=$relrow['relationID'];
        
    } 
    $upProduct="UPDATE product
    SET dailyPrice='$price',
    Class_classID='$class',
    model_modelID='$model'
    WHERE product.productID='$what2look4';";

    $atributeval="UPDATE atributevalue
    set atributevalue.value='$parameter1'
    Where atributevalue.Prooduct_productID= '$what2look4'
    And atributevalue.atributeTypeRelation_relationID ='$relID';";

    $atributeval2="UPDATE atributevalue
    set atributevalue.value='$parameter1'
    Where atributevalue.Prooduct_productID= '$what2look4'
    And atributevalue.atributeTypeRelation_relationID ='$relID2';";

    $checkIfShoes="SELECT model.Type_typeID FROM rental.model where model.modelID='$model'";
    $checkIfShoesQuery=mysqli_query($mysqli,$checkIfShoes);
    $upQuery = mysqli_query($mysqli,$upProduct); 
    $atributequery=mysqli_query($mysqli,$atributeval);

    if($checkIfShoesQuery->num_rows > 0){
        $row4 = $checkIfShoesQuery->fetch_assoc();
        if($row4['Type_typeID']==2){
            if($parameter2='' || $parameter2<50){
                "Wrong hardness value, please try again";

            }else{
                if($upQuery){ 
                    if($atributequery){
                        if(!$atributequery2){
                            ?> 
                            <div style="text-align:center"><p>Error updating the second atribute <?php echo mysqli_error($mysqli)?></p1> </div><?php
                        }
                    }else{
                        ?> 
                        <div style="text-align:center"><p>Error updating the atribute <?php echo mysqli_error($mysqli)?></p1> </div><?php
                    }
                }else{
                    ?> 
                    <div style="text-align:center"><p>Error updating the product <?php echo mysqli_error($mysqli)?></p1> </div><?php
               
                }
            }

        }else{
            if($upQuery){ 
                if(!$atributequery){
                    ?> 
                    <div style="text-align:center"><p>Error updating the atribute <?php echo mysqli_error($mysqli)?></p1> </div><?php
                }
            }else{
                ?> 
                <div style="text-align:center"><p>Error updating the product <?php echo mysqli_error($mysqli)?></p1> </div><?php
            }


        }
    }else{
        echo "Sth went wrong , sorry";  
    }
    mysqli_close($mysqli);
}
?>

    

<?php require('components/footer.inc.php'); ?>
