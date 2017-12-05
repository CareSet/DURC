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
					'column_name' => $column->COLUMN_NAME;
					'data_type' => $column->DATA_TYPE;
					

					]
	            	}
			return($tables);
	        } else {
	     		return false;
	     	}

	}

	public static function 
	        while ($row = mysql_fetch_array($result)) {
            //first pass for modeling...
        //second pass for creating actual code...
            $foreign_key = false;
            //var_export($row);
            $col_name = $row['COLUMN_NAME'];
            $all_cols[] = $col_name;
            $all_row_results[$col_name] = $row;
            $col_label = un_camelcase_string($col_name);
            if (strpos($col_name, '_id') != 0) {
                //			echo "\twe have an id tag!!\t";
                //then this is an id...
                //the col_label already has the id trimmed...
                //lets pretend case is unimportant for now...
                $col_array = explode('_', $col_name);
                $throw_away_the_id = array_pop($col_array); // we don't need _id...
                $other_table_tag = array_pop($col_array);
                $relationship = implode('_', $col_array);
                if (strlen($relationship) == 0) {
                    $relationship = $other_table_tag;
                } else {
                }
    //			echo "searching for $other_table_tag in object2table to model $relationship\t";
                if (isset($object2table[strtolower($other_table_tag)])) {
                    echo "found $other_table_tag\t";
                    //this just doesnt look like a link!! it -is- one.
                    $has_many_tmp = [
                            'prefix' => $relationship,
                            'type'   => $object_name,
                            ];
                    $key = $relationship.'_'.$object_name;
                    $has_many[$other_table_tag][$key] = $has_many_tmp;
                    $belongs_to_tmp = [
                            'prefix' => $relationship,
                            'type'   => $other_table_tag,
                            ];
                    $key = $relationship.'_'.$other_table_tag;
                    $belongs_to[$object_name][$key] = $belongs_to_tmp;
                }
    //			echo "\n";
            }
        }//done dealing with columns...
        $table_data[$this_table] = $all_cols;
        $rows_data[$this_table] = $all_row_results;
    }//moving to the next table


}
