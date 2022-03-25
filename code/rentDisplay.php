<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
<link rel="stylesheet" href="components/mystyle.css">
</head>
<body>
<button onclick="window.location.href='indexWorker.php'" class="button backButton">Go Back</button><br>
<div class="deleteHeader"><h1> Rents </h1></div>





 
<?php
 require('connectDatabase.php');
 $sql = "SELECT distinct rents.ClientAccount_clientID,clientaccount.firstName,clientaccount.lastName,rentedproducts.Product_productID,brand.brandName,
  model.modelName,cathegory.cathegoryName, type.typeName, atributevalue.value, timeslot.rentDate, timeslot.dueDate
   from product,model,brand,cathegory,atributevalue,rentsdetails,rental.clientaccount,rents,timeslot,type,rentedproducts 
   where rentsdetails.Product_productID=product.productID 
   and rents.rentID=rentsdetails.rents_rentID 
   and rentedproducts.Product_productID=product.productID 
   and rentedproducts.Shop_shopID=2
   and clientaccount.clientID=rents.ClientAccount_clientID 
   and product.model_modelID=model.modelID 
   and model.cathegoryID=cathegory.cathegoryID 
   and model.brand_brandID=brand.brandID 
   and model.Type_typeID=type.typeID 
   and atributevalue.Prooduct_productID=rentsdetails.Product_productID 
   and rentsdetails.timeSlot_timeSlotID=timeslot.timeSlotID 
   and rents.ClientAccount_clientID=clientaccount.clientID;";

 $result = mysqli_query($mysqli,$sql);
if($_SERVER['REQUEST_METHOD'] != 'POST'){
 if ($result->num_rows > 0) {
     ?>
<div class="container">
  <ul class="list-group">
   

            </ul>
         
            <?php
              while ($row = $result->fetch_assoc()) 
              {
                echo '<li class="list-group-item">  '.$row['firstName'].' '.$row['lastName'].':  '.$row['Product_productID'].'
                 '.$row['brandName'].' '.$row['modelName'].' '.$row['cathegoryName'].' '.$row['typeName'].' 
                 '.$row['value'].' '.$row['rentDate'].' '.$row['dueDate'].'</li>';
              }
           ?>
       
            </ul>
            </div>
       
     <?php
     mysqli_close($mysqli); 
 } else {
  ?> 
             <div style="text-align:center"><h1> No results </h1> </div><?php
 }
?>

<form method="post" class="deleteForm" >

<label for="deleteForm">From date: </label>
<input type="date" id="rentstart" name="rentstart"
       value="2022-01-22"
       min="2000-01-01" max="2023-12-31"><br>
<label for="deleteForm">To date: </label>
<input type="date" id="rentend" name="rentend"
       value="2022-01-22"
       min="2000-01-01" max="2023-12-31"><br>
       <input id="filterButton" type="Submit" value="Filter" ></input>

</form> 
<?php
}else{
  $begin =isset($_POST['rentstart'])?$_POST['rentstart']:'';
    $end = isset($_POST['rentend'])?$_POST['rentend']:'';
  $sql2 = "SELECT distinct rents.ClientAccount_clientID,clientaccount.firstName,clientaccount.lastName,rentedproducts.Product_productID,brand.brandName,
  model.modelName,cathegory.cathegoryName, type.typeName, atributevalue.value, timeslot.rentDate, timeslot.dueDate
   from product,model,brand,cathegory,atributevalue,rentsdetails,rental.clientaccount,rents,timeslot,type,rentedproducts 
   where rentsdetails.Product_productID=product.productID 
   and rents.rentID=rentsdetails.rents_rentID 
   and rentedproducts.Product_productID=product.productID 
   and rentedproducts.Shop_shopID=2
   and clientaccount.clientID=rents.ClientAccount_clientID 
   and product.model_modelID=model.modelID 
   and model.cathegoryID=cathegory.cathegoryID 
   and model.brand_brandID=brand.brandID 
   and model.Type_typeID=type.typeID 
   and atributevalue.Prooduct_productID=rentsdetails.Product_productID 
   and rentsdetails.timeSlot_timeSlotID=timeslot.timeSlotID 
   and rents.ClientAccount_clientID=clientaccount.clientID
   and timeslot.rentDate >= '$begin'
   and timeslot.dueDate <= '$end';";

   $result2 = mysqli_query($mysqli,$sql2);
   if ($result2->num_rows > 0) {
    ?>
    <div class="container">
    <ul class="list-group">
  

           </ul>
        
           <?php
             while ($row2 = $result2->fetch_assoc()) 
             {
               echo '<li class="list-group-item">  '.$row2['firstName'].' '.$row2['lastName'].':  '.$row2['Product_productID'].'
                '.$row2['brandName'].' '.$row2['modelName'].' '.$row2['cathegoryName'].' '.$row2['typeName'].' 
                '.$row2['value'].' '.$row2['rentDate'].' '.$row2['dueDate'].'</li>';
             }
          ?>
      
           </ul>
           </div>
      
    <?php
    mysqli_close($mysqli);



            }else {
              ?> 
             <div style="text-align:center"><h1> No results </h1> </div><?php
              }


}
   
?>





<?php require('components/footer.inc.php'); ?>