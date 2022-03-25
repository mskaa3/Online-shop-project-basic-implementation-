Alter table productsinshop drop foreign key fk_productsInShop_Product1;
Alter table productsinshop add foreign key (Product_productID) references product(productID) on DELETE CASCADE;

Alter table atributevalue drop foreign key fk_AtributeValue_Prooduct1;
alter table atributevalue add foreign key (Prooduct_productID) references product(productID) on DELETE CASCADE;

Alter table rentedproducts drop foreign key rentedproducts_ibfk_3;
alter table rentedproducts add foreign key (Product_productID) references productsinshop(Product_productID) on DELETE CASCADE;

Alter table rentedproducts drop foreign key rentedproducts_ibfk_2;
alter table rentedproducts add foreign key (Shop_shopID) references productsinshop(Shop_shopID) on DELETE CASCADE;

Alter table rentedproducts drop foreign key rentedproducts_ibfk_4;
alter table rentedproducts add foreign key (Shop_shopID) references productsinshop(Shop_shopID) on DELETE CASCADE;
