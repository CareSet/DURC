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

    protected static $valid_deleted_at_fields = [
        'deleted_date',
        'delete_at_date',
        'deleted_date',
        'deleted_at_date',
        'deleted_at',
        'deletedAt', //Sequlize style
        'deleted_at',
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
							$has_one,
							$belongs_to,
							$many_many, 
							$many_through, 
							$squash,
							$URLroot,
							$create_table_sql);


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

    private static function get_possible_field( $valid_fields, $possible_fields )
    {
        $found_field = false;
        foreach($valid_fields as $this_possible) {
            if ( self::has_field( $possible_fields, $this_possible ) === true ) {
                //we found our field.
                $found_field = $this_possible;
                break;
            }
        }

        return $found_field;
    }

    protected static function get_possible_created_at( $fields ) {
        return self::get_possible_field( self::$valid_created_at_fields, $fields );
    }

    protected static function get_possible_updated_at( $fields ) {
        return self::get_possible_field( self::$valid_updated_at_fields, $fields );
    }

    protected static function get_possible_deleted_at( $fields ) {
        return self::get_possible_field( self::$valid_deleted_at_fields, $fields );
    }
}
