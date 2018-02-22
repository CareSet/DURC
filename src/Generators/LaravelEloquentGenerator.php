<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class LaravelEloquentGenerator extends \CareSet\DURC\DURCGenerator {


        public static function start(
							$db_config,
                                                        $squash,
                                                        $URLroot){
		//does nothing need to comply with abstract class
	}

        //Run only once at the end of generation
        public static function finish(
							$db_config,
                                                        $squash,
                                                        $URLroot){
		//does nothing need to comply with abstract class
	}



	public static function run_generator($class_name,$database,$table,$fields,$has_many,$belongs_to, $many_many, $many_through, $squash, $URLroot){


		$model_namespace = 'App';

                $gen_string = DURC::get_gen_string();



		$updated_at_code = null;	
		$created_at_code = null;

        $this_possible_created_at = self::get_possible_created_at( $fields );
        if ( $this_possible_created_at !== false ) {
            //we found our created_at variable..
            $created_at_code = "const CREATED_AT = '$this_possible_created_at';";
        } else {
            $created_at_code = "const CREATED_AT = null;";
        }

        $this_possible_updated_at = self::get_possible_updated_at( $fields );
		if ( $this_possible_updated_at !== false ) {
            //we found our created_at variable..
            $updated_at_code = "const UPDATED_AT = '$this_possible_updated_at';";
		} else {
            $updated_at_code = "const UPDATED_AT = null;";
        }

		//we should do more logic to support dateformat laravel here...

        $timestamp_code = "public \$timestamps = true;";
		if($this_possible_updated_at === false &&
            $this_possible_created_at === false ){
		    // We don't have either timestamp
			$timestamp_code = "public \$timestamps = false;";
			$updated_at_code = '//DURC NOTE: did not find updated_at and created_at fields for this model' ."\n";
			$created_at_code = '';
		}


		$parent_class_name = "$class_name";

		$parent_file_name = "$parent_class_name.php";	
		$child_file_name = "$class_name.php";	


	//before we start the durc parent class code, we need to calculate several things, based on "has_many" and belongs_to relationships

	$used_functions = [];
	$with_array = [];

	$has_many_code = "\n//DURC HAS_MANY SECTION\n";
	if(!is_null($has_many)){
		foreach($has_many as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$from_table = $relate_details['from_table'];	
			$from_db = $relate_details['from_db'];	
			$from_column = $relate_details['from_column']; 

			$local_key = 'id';

			$used_functions[$other_table_name] = $other_table_name;

			$has_many_code .= "
/**
*	get all the $other_table_name for this $parent_class_name
*/
	public function $other_table_name(){
		return \$this->hasMany('$model_namespace\\$type','$from_column','$local_key');
	}

";
	
			//now lets sort out what should go in $with
			$with_array[$other_table_name] = 'from many';
		}
	}else{
		$has_many_code .= "\n\t\t\t//DURC did not detect any has_many relationships";
	}

	
	$belongs_to_code = "\n//DURC BELONGS_TO SECTION\n";
	if(!is_null($belongs_to)){
		foreach($belongs_to as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$to_table = $relate_details['to_table'];	
			$to_db = $relate_details['to_db'];	
			$to_column = 'id'; //our central assumption 

			$local_key = $relate_details['local_key'];

			if(!isset($used_functions[$other_table_name])){
				$belongs_to_code .= "
/**
*	get the single $other_table_name for this $parent_class_name
*/
	public function $other_table_name(){
		return \$this->belongsTo('$model_namespace\\$type','$local_key','$to_column');
	}

";
				//now lets sort out what should go in $with
				$with_array[$other_table_name] = 'belongs to';
			}else{
				$belongs_to_code .= "
\t\t//DURC would have added $other_table_name but it was already used in has_many. 
\t\t//You will have to resolve these recursive relationships in your code.";

			}

		}
	}else{
		$belongs_to_code .= "\n\t\t\t//DURC did not detect any belongs_to relationships";
	}


	$with_code = "	protected \$DURC_selfish_with = [ \n"; //this will end up using both has_many and belongs_to relationships...

	foreach($with_array as $this_with_item => $from){
			$with_code .= "\t\t\t'$this_with_item', //from $from\n"; //the default is to automatically load has many in toArray and the $with variable forces autoload. 
	}

	$with_code .= "\t\t];\n"; //not that unless there is either a has_many or belongs to, this is going to end up being an empty array

	$date_fields = [];
	//lets get the list of date fields..
	foreach($fields as $field_index => $field_data){
		$input_type = DURC::$column_type_map[strtolower($field_data['data_type'])]['input_type'];
		if($input_type == 'datetime' || $input_type == 'date'){
			$date_field[] = $field_data['column_name'];
		}
	}

	if(count($date_fields) > 0){
		$date_code = "//DURC detected the following date fields\n\t\tprotected \$dates = [\n";
	
		foreach($date_fields as $this_date_field){	
			$date_code .= "\t\t\t$this_date_field,";
		}

		$date_code .= "\t\t];";
	}else{
		$date_code = "//DURC did not detect any date fields";
	}

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

*/

class $parent_class_name extends DURCModel{

        // the datbase for this model
        protected \$table = '$database.$table';

	//DURC will dymanically copy these into the \$with variable... which prevents recursion problem: https://laracasts.com/discuss/channels/eloquent/eager-load-deep-recursion-problem?page=1
	$with_code

	$date_code

	$timestamp_code
	$updated_at_code
	$created_at_code

	//for many functions to work, we need to be able to do a lookup on the field_type and get back the MariaDB/MySQL column type.
	static \$field_type_map = [
";
	
		
		//lets add a 'visible' array for access control at this layer''
		foreach($fields as $field_index => $field_data){
			if(!isset($field_data['column_name'])){
				var_export($field_data);
				exit();
			}
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


*/
class $class_name extends \\$model_namespace\DURC\Models\\$parent_class_name
{

	//You may need to change these for 'one to very very many' relationships.
/*
	$with_code
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


	$child_class_code .= "\n//DURC HAS_MANY SECTION\n";
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
*	DURC is handling the $other_table_name for this $class_name in $parent_class_name
*       but you can extend or override the defaults by editing this function...
*/
	public function $other_table_name(){
		return parent::$other_table_name();
	}

";
	
		}
	}else{
		$child_class_code .= "\t\t\t//DURC did not detect any has_many relationships";
	}



	$child_class_code .= "\n//DURC BELONGS_TO SECTION\n";
	if(!is_null($belongs_to)){
		foreach($belongs_to as $other_table_name => $relate_details){
		
			$prefix = $relate_details['prefix'];	
			$type = $relate_details['type'];	
			$to_table = $relate_details['to_table'];	
			$to_db = $relate_details['to_db'];
			$to_column = 'id';	
			$local_key = $relate_details['local_key']; 

			if(!isset($used_functions[$other_table_name])){
				$child_class_code .= "
/**
*	DURC is handling the $other_table_name for this $class_name in $parent_class_name
*       but you can extend or override the defaults by editing this function...
*/
	public function $other_table_name(){
		return parent::$other_table_name();
	}

";
	
			}else{
				$child_class_code .= "
\t\t//DURC would have added $other_table_name but it was already used in has_many. 
\t\t//You will have to resolve these recursive relationships in your code.";
				

			}

		}
	}else{
		$child_class_code .= "\t\t\t//DURC did not detect any belongs_to relationships";
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
