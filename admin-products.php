<?php  

use  \Hcode\PageAdmin;
use  \Hcode\Model\Products;
use  \Hcode\Model\User;

$app->get("/admin/products", function(){
   User::verifyLogin();

   $products = Products::listAll();


   $page = new PageAdmin();
   $page->setTpl("products",[
     "products"=>$products
   	]);

});


$app->get("/admin/products/create", function(){
   User::verifyLogin();

  $page = new PageAdmin();
  $page->setTpl("products-create");

});

$app->post("/admin/products/create", function(){
   User::verifyLogin();
   
  $products = new Products();
  $products->setData($_POST);
  $products->save();	

  header("Location: /admin/products");
  exit;  

});


?>