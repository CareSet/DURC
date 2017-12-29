<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class LaravelEloquentGenerator extends \CareSet\DURC\DURCGenerator {



	public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){

		$model_namespace = 'App';

                $gen_string = DURC::get_gen_string();

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


	//before we start the durc parent class code, we need to calculate several things, based on "has_many" and belongs_to relationships

	$has_many_with_code = "	protected \$with = [ \n"; //this will end up using both has_many and belongs_to relationships...

	$has_many_code = "//DURC HAS_MANY SECTION";
	if(!is_null($has_many)){
		foreach($has_many as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$from_table = $relate_details['from_table'];	
			$from_db = $relate_details['from_db'];	
			$from_column = $relate_details['from_column']; 

			$local_key = 'id';

			$has_many_code .= "
/**
*	get all the $type for this $parent_class_name
*/
	public function $type(){
		return \$this->hasMany('$model_namespace\\$type','$from_column','$local_key');
	}

";
	
			//now lets sort out what should go in $with
			$has_many_with_code .= "\t\t\t'$type',\n"; //the default is to automatically load has many in toArray and the $with variable forces autoload. 

		}
	}else{
		$has_many_code .= "\n\t\t\t//DURC did not detect any has_many relationships";
	}

	$has_many_with_code .= "\n];"; //not that unless there is either a has_many or belongs to, this is going to end up being an empty array

	$belongs_to_with_code = "	protected \$with = [ \n"; //this will end up using both has_many and belongs_to relationships...
	
	$belongs_to_code = "//DURC BELONGS_TO SECTION";
	if(!is_null($belongs_to)){
		foreach($belongs_to as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$to_table = $relate_details['to_table'];	
			$to_db = $relate_details['to_db'];	
			$to_column = 'id'; //our central assumption 

			$local_key = $relate_details['local_key'];

			$belongs_to_code .= "
/**
*	get all the $type for this $parent_class_name
*/
	public function $type(){
		return \$this->belongsTo('$model_namespace\\$type','$local_key','$to_column');
	}

";
	
			//now lets sort out what should go in $with
			$belongs_to_with_code .= "\t\t\t'$type',\n"; //the default is to automatically load has many in toArray and the $with variable forces autoload. 

		}
	}else{
		$belongs_to_code .= "\n\t\t\t//DURC did not detect any belongs_to relationships";
	}




	$belongs_to_with_code .= "\n];"; //not that unless there is either a has_many or belongs to, this is going to end up being an empty array



		//STARTING DURC Parent CLASS

		$parent_class_code = "<?php

namespace $model_namespace\DURC\Models;

use CareSet\DURC\DURCModel;
/*
	Note this class was auto-generated from 

$database.$table by DURC.

	This class will be overwritten during future auto-generation runs..
	Itjust reflects whatever is in the database..
	DO NOT EDIT THIS FILE BY HAND!!
	Your changes go in $class_name.php 

$gen_string
*/

class $parent_class_name extends DURCModel{

        // the datbase for this model
        protected \$table = '$database.$table';


	$timestamp_code
	$updated_at_code
	$created_at_code

	//for many functions to work, we need to be able to do a lookup on the field_type and get back the MariaDB/MySQL column type.
	static \$field_type_map = [
";
	
		
		//lets add a 'visible' array for access control at this layer''
		foreach($fields as $field_data){
			$this_field = $field_data['column_name'];
			$data_type = $field_data['data_type'];
			$parent_class_code .= "		'$this_field' => '$data_type',\n";
		}


		$parent_class_code .= "			]; //end field_type_map

		//everything is fillable by default
		protected \$guarded = [];


		$has_many_code

		$belongs_to_code

}//end of $parent_class_name";



		//STARTING CHILD CLASS

		$child_class_code = "<?php

namespace $model_namespace;
/*
	$class_name: controls $database.$table

This class started life as a DURC model, but itwill no longer be overwritten by the generator
this is safe to edit.

$gen_string

*/
class $class_name extends \\$model_namespace\DURC\Models\\$parent_class_name
{

	// DURC \$with section. This will force the eager loading of all has_many relationships.
	//be careful to not comment out both sides of the eager loading to prevent recursive autoloading..
/*
	$has_many_with_code
*/

/*
	$belongs_to_with_code
*/

	//you can uncomment fields to prevent them from being serialized into the API!
	protected  \$hidden = [
";

		//lets add a 'visible' array for access control at this layer''
		foreach($fields as $field_data){
			$this_field = $field_data['column_name'];
			$data_type = $field_data['data_type'];
			$child_class_code .= "			//'$this_field', //$data_type\n";
		}


		$child_class_code .= "		]; //end hidden array

";


	$child_class_code .= "//DURC HAS_MANY SECTION";
	if(!is_null($has_many)){
		foreach($has_many as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$from_table = $relate_details['from_table'];	
			$from_db = $relate_details['from_db'];	
			$from_column = $relate_details['from_column']; 

			$local_key = 'id';

			$child_class_code .= "
/**
*	DURC is handling the $type for this $class_name in $parent_class_name
*       but you can extend or override the defaults by editing this function...
*/
	public function $type(){
		return parent::$type();
	}

";
	
		}
	}else{
		$child_class_code .= "\t\t\t//DURC did not detect any has_many relationships";
	}


$child_class_code .= "
	//your stuff goes here..
	

}//end $class_name";

		$app_path = base_path() . '/app/'; //we put the editable file in the main app directory where all of the others live..
		$durc_app_path = base_path() . '/app/DURC/Models/'; //we put the auto-genertated parent classes in a directory above that..

		if(!is_dir($durc_app_path)){
			if (!mkdir($durc_app_path, 0777, true)) {

    				die("DURC needs to create the $durc_app_path directory... but it could not.. what if it already existed? What then?");
			}
		}

		$child_file = $app_path.$child_file_name;
		$parent_file = $durc_app_path.$parent_file_name;

		if(!file_exists($child_file) || $squash){ //overwrite if it does not exist or if we are squashing
			//we will only create this file the first time...
			//so getting here means that it does not exist, this is the first creation pass.
			file_put_contents($child_file,$child_class_code);
		}

		//but we always overwrite the parent class with the auto-generated stuff..
		file_put_contents($parent_file,$parent_class_code);

		return(true);


	}//end generate function







}//end class
