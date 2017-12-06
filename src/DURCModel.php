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

	//get the value of the field that should be the name of this object..
	public function _getBestName(){

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


		$is_a_possible = false;
		$fields = $this->toArray();
		foreach($fields as $key => $value){
			if(strpos(strtolower($key),'name') !== false){
				return($value);
			}
			if(!$is_a_possible){
				if(!in_array($key,$hell_no)){
					//if we do not return a name... we need something...
					$is_a_possible = $value;
				}
			}
		}

		//this could be still false...
		if($is_a_possible){
			return($is_a_possible);
		}else{
			//well shit.
			if(isset($fields['id'])){
				$this_class_name = get_class($this);
				$db_table = $this->$table;
				die("DURC Model for $this_class_name could not get any reasonable field for a name.. check your $db_table table..");
			}
		}

	}



}
