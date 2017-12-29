<?php

namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/*
	This is where we put all of the functions that we want all DURC models to inherit
*/
class DURCModel extends Model{

	/**
	*	This function allows us to avoid the recursive eager loading problem by allowing a controller (etc) to specify
 	*	That only one level of eager loading will occur, starting from the object-in-focus...
	* 	But to typically avoid eager loading for index lists and such...
	*/
	public function fresh_with_relations(){
		return($this->fresh($this->DURC_selfish_with));
	}


	public static function getNameField(){
		
		$hell_no = [
			'id',
			'password',
			'passwd',
			'pwd',
			'updated_at',
			'created_at',
			'updated_dt',
			'created_dt',
			'updated_date',
			'created_date',
		];

		$my_class = get_called_class();

		foreach($my_class::$field_type_map as $field => $field_type){
			
			if(strpos(strtolower($field),'name') !== false && $field_type == 'varchar'){
				//then this is the first 'name' field with a varchar type. This is the winner.
				return($field);
			}
		}

		//if we get here there are no fields called 'name'
		//so lets do any varchar field type...
		foreach($my_class::$field_type_map as $field => $field_type){
			
			if($field_type == 'varchar'){
				//then this is the first text field on the 
				return($field);
			}

		}
		
		//if we get here we are pretty much screwed.

		return(false);

	}


	//get the value of the field that should be the name of this object..
	public function _getBestName(){

		$result = self::getNameField();

		if($result){
			return($result);
		}else{
			die("DURC Model for $this_class_name could not get any reasonable field for a name.. check your $db_table table..");
		}
	}



}
