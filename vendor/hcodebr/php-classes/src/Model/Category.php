<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;


class Category extends Model {
    

      public static function listAll()
      {
           
           $sql =new Sql();

            return $sql->select("SELECT *FROM tb_categories ORDER BY descategory");
    
      }

	   public function save(){
	      	$sql = new Sql();
            
	      	$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)",array(
	               ":idcategory"=> $this->getidcategory(),
	               ":descategory"=> $this->getdescategory()
	      		));

	      	
	      	$this->setData($results[0]);

	      	Category::updateFile();
	      }


	      public function get($idcategory){

	      	$sql = new Sql();

	      	$results=$sql->select("SELECT *FROM tb_categories WHERE idcategory= :idcategory",[
                ":idcategory"=>$idcategory
	      		]);
      
           $this->setData($results[0]);
	      }

	      public function delete(){

               $sql =new Sql();

               $sql->query("DELETE FROM tb_categories WHERE idcategory=:idcategory",[
                 ":idcategory"=>$this->getidcategory()
               	
               	]);
                 
               Category::updateFile();
	      }
		  
		  public static function updateFile(){
			  $categories =Category::listAll();

			  $html = [];

			  foreach ($categories as $row) {
			      array_push($html,'<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a> </li>');
			  }
			  file_put_contents($_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . "views" .DIRECTORY_SEPARATOR ."categories-menu.html", implode('',$html));   
		  }
      
         
         public function getProducts($related = true)//Lista produtos relacionados e nao relacionados
         {

           $sql = new Sql();
           
           if ($related === true) {
            return	$sql->select("SELECT *FROM tb_products WHERE idproduct IN(
                   SELECT a.idproduct
                   FROM tb_products a
                   INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                   WHERE b.idcategory=:idcategory  
           		   );

           		", [
           		    ':idcategory'=>$this->getidcategory()
           		]);
           		
           }else{
              
             return $sql->select("SELECT *FROM tb_products where idproduct NOT IN(
                   SELECT a.idproduct
                   FROM tb_products a
                   INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                   where b.idcategory=:idcategory  
                  
           		);
           		",[
           		    ':idcategory'=>$this->getidcategory()
           		]);

           }

         }

          public function getProductsPage($page=1 ,$intemsParPage=6)
         {


          $start = ($page -1 ) * $intemsParPage;

          $sql = new Sql();

            $results = $sql->select("
              SELECT  SQL_CALC_FOUND_ROWS * 
              FROM tb_products a 
              INNER JOIN tb_productscategories b ON a.idproduct=b.idproduct
              INNER JOIN tb_categories c ON b.idcategory= c.idcategory
              WHERE c.idcategory=:idcategory
              LIMIT $start,$intemsParPage;
              ",[
                  ':idcategory'=>$this->getidcategory()
               ]);


             $resultadoTotal= $sql->select("SELECT FOUND_ROWS() AS nrTotal;");
             
             return [
                 'data'=>Products::checkList($results),
                 'total'=>(int)$resultadoTotal[0]["nrTotal"],
                 'pages'=>ceil($resultadoTotal[0]["nrTotal"] / $intemsParPage) 
             ];

         }
            

         public function addProduct(Products $product)
         {

          $sql = new Sql();
          
          $sql->query("INSERT INTO tb_productscategories (idcategory,idproduct) VALUES(:idcategory,:idproduct)"
                ,[':idcategory'=>$this->getidcategory(),
                  ':idproduct'=>$product->getidproduct()
                ]);

         }
       public function removeProduct(Products $product)
         {

          $sql = new Sql();
         
          $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct= :idproduct"
                ,[':idcategory'=>$this->getidcategory(),
                  ':idproduct'=>$product->getidproduct()
                ]);

         }



}

?>

