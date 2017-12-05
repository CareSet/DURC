<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class LaravelEloquentGenerator extends DURCGenerator {


	public static $suggested_output_dir = 'app/';

	public static function run_generator($class_name,$database,$table,$fields){

		//possible created_at field names... 
		//in reverse order of priority. we pick the last one.
		$valid_created_at_fields = [
			'creation_date',
			'created_at_date',
			'createdAt', //Sequlize style
			'created_at',
			];

		//possible updated_at field names... 
		//in reverse order of priority. we pick the last one.
                $valid_updated_at_fields = [
                        'update_date',
                        'update_at_date',
                        'updated_date',
                        'updated_at_date',
			'updated_at',
			'updatedAt', //Sequlize style
                        'update_at',
                        ];


		$updated_at_code = null;	
		$created_at_code = null;	
		
		foreach($valid_created_at_fields as $this_possible_created_at){
			if(isset($fields[$this_possible_created_at])){
				//we found our created_at variable..
				$created_at_code = "const CREATED_AT = '$this_possible_created_at'"; 	
			}
		}	
		foreach($valid_updated_at_fields as $this_possible_updated_at){
			if(isset($fields[$this_possible_updated_at])){
				//we found our created_at variable..
				$updated_at_code = "const UPDATED_AT = '$this_possible_updated_at'"; 	
			}
		}	

		//we should do more logic to support dateformat laravel here...


		if(is_null($updated_at_code) || is_null($created_at_code)){ //we must have both...
			$timestamp_code = "public \$timestamps = false;";
			$updated_at_code = '//DURC NOTE: did not find updated_at and created_at fields for this model' ."\n";
			$created_at_code = '';
		}


		$parent_class_name = "DURC_$class_name";

		$parent_file_name = "$parent_class_name.php";	
		$child_file_name = "$class_name.php";	

		$parent_class_text = "<?php

namespace App;

use CareSet\DURC\DURCModel;
/*
	Note this class was auto-generated from $database,$table by DURC.
	This class will be overwritten during future auto-generation runs..
	Itjust reflects whatever is in the database..
	DO NOT EDIT THIS FILE BY HAND!!
	Your changes go in $class_name

*/
class $parent_class_name extends DURCModel
{
        // the datbase for this model
        protected \$table = '$database.$table';

	$timestamp_code
	$updated_at_code
	$created_at_code

}
";

		
		$child_class_text = "<?php

namespace App;
/*
	$class_name: controls $database.$table

This class started life as a DURC model, but itwill no longer be overwritten by the generator
this is safe to edit.

*/
class $class_name extends $parent_class_name
{

	

}
";

		$my_path = base_path() . '/'. LaravelEloquentGenerator::$suggested_output_dir;
		
		$return_me = [
			'laravel_parent_class' => [
				'full_file_name' => $my_path.$parent_file_name,
				'file_name' 	=> $parent_file_name,
				'file_text'	=> $parent_class_text,
				],
			'laravel_child_class' => [
				'full_file_name' => $my_path.$child_file_name,
				'file_name' 	=> $child_file_name,
				'file_text' 	=> $child_class_text,
				],
		];

		foreach($return_me as $file_details){
			file_put_contents($file_details['full_file_name'],$file_details['file_text']);
		}

		return(true);


	}//end generate function







}//end class
