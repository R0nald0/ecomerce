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

$app->get("/admin/products/:idproduct",function($idproduct){

   User::verifyLogin();

   $product= new Products();
   $product->get((int)$idproduct);

   $page = new PageAdmin();
   $page->setTpl("products-update",[
     'product' =>$product->getValues()
    ]);


});


$app->post("/admin/products/:idproduct",function($idproduct){

   User::verifyLogin();

   $product= new Products();
   $product->get((int)$idproduct);
    
   $product->setData($_POST);
   $product->save();

   $product->setPhoto($_FILES["file"]);

   header('Location: /admin/products');
    exit;

});
$app->get("/admin/products/:idproduct/delete",function($idproduct){

   User::verifyLogin();

   $product= new Products();
   $product->get((int)$idproduct);

   $product->delete();

   
   header('Location: /admin/products');
    exit;
});


?>