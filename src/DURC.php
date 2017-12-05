<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

/*
	This is where all of the work is done...
*/
class DURC{


	public static function getDBstruct($db_array){
	
		$db_struct = [];
		foreach($db_array as $this_db){
			$db_struct[$this_db] = DURC::getTables($this_db);
		}

		return($db_struct);
	}


	public static function getTables($db_name){

	        $table_columns = DB::select('SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = ? ORDER BY TABLE_NAME,COLUMN_NAME', [$db_name]);
	        if(is_array($table_columns) && !empty($table_columns))
	        {
	            	$tables = [];
	            	foreach($table_columns as $column)
	            	{
	                	if(!isset($tables[$column->TABLE_NAME])){
					$tables[$column->TABLE_NAME] = [];
				}
	                	$tables[$column->TABLE_NAME][] = [
					'column_name' => $column->COLUMN_NAME,
					'data_type' => $column->DATA_TYPE,
					];
	            	}
			return($tables);
	        } else {
	     		return false;
	     	}

	}//end getTables



}
