<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

/*
	A good place for complex but clearly static fuinctions....
*/
class DURC{


	/*
		Gets the structure of all databases in the analysis...

		works by making lots of calls to DURC::getTables()

		pass in a $db_array which is a list of strings that are the databases..
		get back an array of the following format...

		'db' => [
				'tableA' =>
						'first_column_in_tableA' => 
								'column_name' => 'first_column_in_tableA',
								'data_type' => 'int',
						'second_column_in_tableA', 
								etc..
				'tableB' =>
						etc..

		'db2' => etc..
	*/
	public static function getDBstruct($db_array){
	
		$db_struct = [];
		foreach($db_array as $this_db){
			$db_struct[$this_db] = DURC::getTables($this_db);
		}
		ksort($db_struct); //has the effect of putting the aaaDurctest DB first in the routes which is helpful for testing
		return($db_struct);
	}

	
	/*
		gets the tables in a single database

		pass in the name of a database.
	
		get back a single stuct like so
				'tableA' => 
						'firsty_column_in_tableA' => 
                                                	'column_name' => 'first_column_in_tableA',
                                                	'data_type' => 'int',
                                                'second_column_in_tableA',
                                                                etc..


	*/
	public static function getTables($db_name){

	        $table_columns = DB::select('SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = ? ORDER BY TABLE_NAME,ORDINAL_POSITION', [$db_name]);

	        if(is_array($table_columns) && !empty($table_columns))
	        {
	            	$tables = [];
			$bad_tables = [];
	            	foreach($table_columns as $column)
	            	{
				if(strpos($column->TABLE_NAME,' ') !== false){
					//then this table has a space in it... f*!& that noise.
					$bad_tables[$column->TABLE_NAME] = 'table name as a space in it';
				}else{
	                		if(!isset($tables[$column->TABLE_NAME])){
						$tables[$column->TABLE_NAME] = [];
					}
	                		$tables[$column->TABLE_NAME][] = [
						'column_name' => $column->COLUMN_NAME,
						'data_type' => $column->DATA_TYPE,
						];
				}
	            	}
			ksort($tables);

			//need to log the problems here... TODO
			foreach($bad_tables as $table => $problem){
				echo "Rejected $table because $problem\n";
			}
			return($tables);
	        } else {
	     		return false;
	     	}

	}//end getTables

	/*
		A simple method to write the json config file
	*/
	public static function writeDURCDesignConfigJSON($config_data, $squash = false){

		$auto_gen_file_name = base_path()."/config/DURC_config.autogen.json";
		$user_edit_file_name = base_path()."/config/DURC_config.edit_me.json";

		$config_json_text =json_encode($config_data, JSON_PRETTY_PRINT);

		file_put_contents($auto_gen_file_name,$config_json_text);
		if(!file_exists($user_edit_file_name) || $squash){ //do not overwrite user configured data unless we are squashing..
			echo "Am squashing user edit config json\n";
			file_put_contents($user_edit_file_name,$config_json_text);
		}else{
			echo "User defined config json exists... leaving it alone\n";
		}

	}

	public static function readDURCDesignConfigJSON($config_file_name){
			
		if(!file_exists($config_file_name)){
			echo "Error: $config_file_name config file does not exist... drowning in confusion...\n";
			exit();
		}

		$config_json = file_get_contents($config_file_name);
		$config_data = json_decode($config_json,true); //we do want an assoc..
	
		return($config_data);
		
	}

}
