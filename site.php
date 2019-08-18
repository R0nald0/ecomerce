<?php 
use  \Hcode\Page;
use  \Hcode\Model\Products;
use \Hcode\Model\Category;

$app->get('/', function() {
   
   $products= Products::listAll();


   $page =new Page();
   $page->setTpl("index",[
   'products'=> Products::checkList($products)   
   	]);
	

});

$app->get("/categories/:idcategory",function($idcategory){

   $page=(isset($_GET['page'])) ? (int)$_GET['page'] :1;

      
   $category = new Category();
   $category->get((int)$idcategory);
   
   $pagination = $category->getProductsPage($page);
   
   $paginas=[];

   for ($i=1; $i <= $pagination['pages']; $i++) {
   	 array_push($paginas,[
         'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
         'page'=>$i     
   	 	]);
   }
   
    $page = new Page();
    $page->setTpl("category",[
       "category" =>$category->getValues(),
       "products"=>$pagination["data"],
       'paginas'=>$paginas
    ]);
  

});


$app->get("/products/:desurl",function($desurl){

   $product = new Products();
   $product->getFromURL($desurl);

   $page =new Page();
   $page->setTpl("product-detail",[
    'product'=>$product->getValues(),
    'categories'=>$product->getCategories()

    ]);
  
});

?>
