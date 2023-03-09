<?php
namespace inc\database;

class DB{

	public $db;

	function __construct(){
		$this->db = cdb();
	}

	function DB($host,$username,$password,$dbName){
		$connection = mysqli_connect($host,$username,$password);
		if($connection){
			$this->db = mysqli_select_db($this->db,$dbName);
		}
		else{
			echo "Failed to connect given database";
			return;
		}
		return true;
	}

	public function escapeStr($str){

		return $this->db->real_escape_string($str);
	}
	
	public function selectSQL($q,$twoD=false){

		$result = $this->db->query($q);

		if(!empty($result)):
	 		$rowNo  = $result->num_rows;

	 		if($rowNo > 0):
	 			if($rowNo == 1):

	 				$colNo = mysqli_num_fields($result);

	 				if($colNo==1):

	 					$dataTemp = $result->fetch_array();
	 					$data = $dataTemp[0];
						return $data;

	 				else:

	 					$arrayResult = $result->fetch_assoc();

	 				endif;
	 			else:
	 				while (($temp=$result->fetch_assoc())) {
						$arrayResult[]=$temp;
					}
	 			endif;

	 			if($twoD):
	 				if(!$this->is_multi($arrayResult))
	 					$arrayResult = array($arrayResult);
	 			endif;

	 			return $arrayResult;
	 			
	 		endif;
	 	endif;

	 	return null;
	}
	public function selectField($table,$selection,$condition){

		$selection = DB::escapeStr($selection);

		$query = "SELECT ".$selection." FROM ".$table;
		if($condition!=''){

			$query .= " WHERE ".$condition." LIMIT 1";
		}

		$temp = DB::selectSQL($query);
		return $temp;
	}

	public function updateSQL($tablename, $array){

		$updateFieldValue = '';

		foreach ($array as $key => $value) {
			$updateFieldValue .= $key."='".$value."',";
		}

		$updateFieldValue = rtrim($updateFieldValue,",");

		$id  = key($array);
		$sql = "UPDATE ".$tablename." SET ".$updateFieldValue." WHERE {$id} = '{$array[$id]}'";

		$result = $this->db->query($sql);

		if($result!==FALSE) return true;

		return false;
	}

	public function insertSQL($table,$array){

		$fields = implode("`,`",array_keys($array));

		$values = implode("\",\"",array_values($array));

		$query = "INSERT INTO ".$table." (`".$fields."`) VALUES (\"".$values."\")";

		$this->db->query($query);

		$result = $this->db->insert_id;

		if($result>0) return $result;

		return false;
	}

	public function deleteSQL($query){

		$this->db->query($query);
		$updateResult = $this->db->affected_rows;

		if($updateResult>0){
			return true;
		}
		else{
			return false;
		}
	}

	public function mysqli_iserror(){
		if($this->db->error){
			return true;
		}else{
			return false;
		}
	}

	protected function is_multi( $var ){
		///Check if 2D array
		foreach( $var as $v ) if( is_array($v) ) return TRUE;

    	return FALSE;
	}
	/**
	@parameters 
	i. return field; 
	ii. search condition (array); 
	iii. other condition(array) 
	iv. double md5 field 
	*/
	public function getInfo($tableName,$getField,$find,$other,$hashField){

		// $whereField = key($find);
		// $whereValue = $find[$whereField];
		$findCondition = '';
		$orderby = '';
		$limit = '';

		if(is_array($getField))
			$fields = implode(',', $getField);
		else
			$fields = $getField;

		if(!empty($find)):
			foreach($find as $key => $value){

				if($hashField == $key)
					$key = " MD5(MD5($hashField)) ";

				$findCondition .= $key." = '".$value."' AND ";
			}

			$findCondition = " WHERE ".rtrim($findCondition,' AND ');

		else:
			$findCondition = "";
		endif;

		

		if(!empty($other) && is_array($other)){

			if(array_key_exists('orderby',$other)){
				$orderby = " ORDER BY {$other['orderby']} {$other['orderpattern']}";
			}

			if(array_key_exists('limit',$other)){
				$limit = " LIMIT {$other['limit']} ";
			}
		}
		
		$sql    = sprintf("SELECT %s FROM %s %s %s %s",$fields,$tableName,$findCondition,$orderby,$limit);

		$result = self::selectSQL($sql);

		if($result !== false)
			return $result;

		return false;
	}

	function dbQuery($sql,&$result,&$err=null){

	 	$result= $this->db->query($sql);

	 	if ($result === false){
	 	 	return false;
	 	}

	 	return true;
	}
}
?>