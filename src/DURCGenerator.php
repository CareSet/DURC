<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

/*
        This is where all of the work is done...
*/
abstract class DURCGenerator{

    //possible created_at field names...
    //in reverse order of priority. we pick the last one.
    protected static $valid_created_at_fields = [
        'creation_date',
        'created_at_date',
        'createdAt', //Sequlize style
        'created_at'
    ];

    //possible updated_at field names...
    //in reverse order of priority. we pick the last one.
    protected static $valid_updated_at_fields = [
        'update_date',
        'update_at_date',
        'updated_date',
        'updated_at_date',
        'updated_at',
        'updatedAt', //Sequlize style
        'update_at',
    ];


    //Run only once at the beginning of generation
	abstract public static function start(
							$db_config,
							$squash,
							$URLroot);

	//Run only once at the end of generation
	abstract public static function finish(
							$db_config,
							$squash,
							$URLroot);

	//Run for every database and table combination
	abstract public static function run_generator(	
							$class_name,
							$database,
							$table,
							$fields,
							$has_many,
							$belongs_to,
							$many_many, 
							$many_through, 
							$squash,
							$URLroot);


    protected static function has_field( $fields, $column_name ){
        $found = false;
        foreach ( $fields as $field ) {
            if ( $field['column_name'] == $column_name ) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    protected static function get_possible_created_at( $fields ) {
        $created_at_field = false;
        foreach(self::$valid_created_at_fields as $this_possible_created_at) {
            if ( self::has_field( $fields, $this_possible_created_at ) === true ) {
                //we found our created_at variable..
                $created_at_field = $this_possible_created_at;
                break;
            }
        }

        return $created_at_field;
    }

    protected static function get_possible_updated_at( $fields ) {
        $updated_at_field = false;
        foreach(self::$valid_updated_at_fields as $this_possible_updated_at){
            if ( self::has_field( $fields, $this_possible_updated_at ) === true){
                //we found our updated_at variable..
                $updated_at_field = $this_possible_updated_at;
                break;
            }
        }

        return $updated_at_field;
    }

}
