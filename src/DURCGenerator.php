<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

/*
	This abstract class details exactly how a generator is supposed to work
	Each generators compiles from the database to a different type of
	output... some create views, others creat zermelo reports
	still others make eloquent parent/child objects
*/
abstract class DURCGenerator{

    //possible created_at field names...
    //in reverse order of priority. we pick the last one.
    protected static $valid_created_at_fields = [
        'created_date',
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

    protected static function _get_default_value($field_data)
    {
        $default_value = null;
        if ((isset($field_data['default_value']) &&
            $field_data['default_value'] !== null)) {
            $default_value = $field_data['default_value'];
        }

        return $default_value;
    }

    protected static function _is_nullable($field_data)
    {
        $is_nullable = false;
        if ((isset($field_data['is_nullable']) &&
            $field_data['is_nullable'] !== null)) {
            $is_nullable = $field_data['is_nullable'];
        }

        return $is_nullable;
    }

    protected static function _is_required($field_data)
    {

	$doesItEndWithRequire = substr_compare(
					strtolower($field_data['column_name']),
					'_required',
					-strlen('_required')) === 0;

	return $doesItEndWithRequire;

    }

    protected static function _is_auto_increment($field_data)
    {
        $auto = false;
        if (isset($field_data['is_auto_increment']) &&
            $field_data['is_auto_increment'] === true) {
            $auto = true;
        }
        return $auto;
    }

    protected static function _is_present($field_data)
    {

	//the 'present' validation rule means that data field must be provided but it
	//can be 'empty' unlike 'required' which does not like to see
	// 0, null etc etc...

        $present = false;
        if (self::_is_nullable($field_data) === false &&
            self::_get_default_value($field_data) === null) {
            $present = true;
        }

        // Make an exception for auto-increment fields, because they populate automatically
        if (self::_is_auto_increment($field_data)) {
            $present = false;
        }

        return $present;
    }
}
