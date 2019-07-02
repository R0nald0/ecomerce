<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;


class Products extends Model {
    

      public static function listAll()
      {
           
           $sql =new Sql();

            return $sql->select("SELECT *FROM tb_products ORDER BY desproduct");
    
      }

	   public function save(){
	      	$sql = new Sql();
                $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice ,:vlwidth, :vlheigth, :vllength, :vlweight, :desurl)",array(
	               ":idproduct"=> $this->getidproduct(),
	               ":desproduct"=> $this->getdesproduct(),
	               ":vlprice"=> $this->getvlprice(),
	               ":vlwidth"=> $this->getvlwidth(),
	               ":vlheigth"=> $this->getvlheigth(),
	      	       ":vllength"=> $this->getvllength(),
	      	       ":vlweight"=> $this->getvlweigth(),
	      	       ":desurl"=> $this->getdesurl()	
	      		));

	      
	      	$this->setData($results[0]);

	      }


	      public function get($idproduct){

	      	$sql = new Sql();

	      	$results=$sql->select("SELECT *FROM tb_idproducts WHERE idproduct= :idproduct",[
                ":idproduct"=>$idproduct
	      		]);
      
           $this->setData($results[0]);
	      }

	      public function delete(){

               $sql =new Sql();

               $sql->query("DELETE FROM tb_idproduct WHERE idproduct=:idproduct",[
                 ":idproduct"=>$this->getidproduct()
               	
               	]);
                 
               
	      }
		  
		  
 }

?>

