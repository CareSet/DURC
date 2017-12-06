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
		ksort($db_struct); //has the effect of putting the aaaDurctest DB first in the routes which is helpful for testing
		return($db_struct);
	}


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



}
