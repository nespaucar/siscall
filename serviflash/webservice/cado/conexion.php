<?php
 	class cado {
  	// Conexion con MYSQL 
	 	function conectar(){
	   		try {
	    		$db = new PDO('mysql:host=martinampuero.com;dbname=mampuero_sispwperu', 'mampuero_pwperu', 'A1savZ4Deq');
		 		return $db;
		 	} catch (PDOException $e) {
	       		echo $e->getMessage();
          	}
	  	}
	  	
	  	function ejecutar($isql){
	    	$ejecutar=$this->conectar()->prepare($isql);
		 	$ejecutar->execute();
		 	return $ejecutar;
	  	}	  
   	}
?>