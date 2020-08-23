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
    //possible deleted_at field names...
    //in reverse order of priority. we pick the last one.
    protected static $valid_deleted_at_fields = [
        'deleted_date',
        'delete_at_date',
        'deleted_date',
        'deleted_at_date',
        'deleted_at',
        'deletedAt', //Sequlize style
        'deleted_at',
    ];

// this is helper function for run_generator that sets the arguments to reasonable default...
// preparese for the first extract() call call in run_generateor
	private function _check_arguments($data_for_gen){

                $defaults = [
                        'has_many' => null,
                        'has_one' => null,
                        'belongs_to' => null,
                        'many_many' => null,
                        'many_through' => null,
                        'squash' => false,
                        'URLroot' => '/DURC/',
                        'create_table_sql' => null,
                ];

                foreach($defaults as $var_name => $default_value){
                        if(!isset($data_for_gen[$var_name])){
                                //then this variable did not get provided inside the $data_for_gen array..
                                $data_for_gen[$var_name] = $default_value; //set it to the default...
                        }
                }

		return($data_for_gen); //but now with defaults properly set!!

	}


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
	abstract public static function run_generator( $data_for_gen) ;

    /**
     * @param array $fields
     * @param $column_name
     * @return bool
     *
     * Given an array of fields (see config/DURC_config.autogen.json for field attributes)
     * check to see if the array contains the String column name passed, in the $column_name
     * parameter. Return true if the array $fields contains $coulmn_name, false OW
     */
    protected static function has_field( $fields, $column_name )
    {
        $found = false;
        foreach ( $fields as $field ) {
            if ( $field['column_name'] == $column_name ) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * @param array $valid_fields
     * @param array $possible_fields
     * @return bool|mixed
     *
     * Given an array of fields (see config/DURC_config.autogen.json for field attributes)
     * and an array of possible fields, check to see if any of the column names in the
     * array of valid fields exist in the array of possible fields. If it exists, return
     * the field, otherwise return false.
     */
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

    /**
     * @param array $fields
     * @return bool|mixed
     *
     * Given an array of fields, find the possible field representing a "created at" attribute.
     * If found, return the field, return false otherwise.
     */
    protected static function get_possible_created_at( $fields )
    {
        return self::get_possible_field( self::$valid_created_at_fields, $fields );
    }

    /**
     * @param array $fields
     * @return bool|mixed
     *
     * Given an array of fields, find the possible field representing a "updated at" attribute.
     * If found, return the field, return false otherwise.
     */
    protected static function get_possible_updated_at( $fields )
    {
        return self::get_possible_field( self::$valid_updated_at_fields, $fields );
    }

    /**
     * @param array $fields
     * @return bool|mixed
     *
     * Given an array of fields, find the possible field representing a "deleted at" attribute.
     * If found, return the field, return false otherwise.
     */
    protected static function get_possible_deleted_at( $fields )
    {
        return self::get_possible_field( self::$valid_deleted_at_fields, $fields );
    }

    /**
     * @param $field_data
     * @return mixed|null
     *
     * DURC mines the default value for a field from the database schema.
     * This function checks to see if there is a default value set for this field in the database.
     * If there is a default value, return it, otherwise return null
     */
    protected static function _get_default_value($field_data)
    {
        $default_value = null;
        if ((isset($field_data['default_value']) &&
            $field_data['default_value'] !== null)) {
            $default_value = $field_data['default_value'];
        }

        return $default_value;
    }

    /**
     * @param $field_data
     * @return bool|mixed
     *
     * DURC mines the NULLABLE metadata from the database schema. This function
     * checks to see if this field is allowed to be NULL based on the schema.
     * If the field can be NULL, return true, othewise return false.
     */
    protected static function _is_nullable($field_data)
    {
        $is_nullable = false;
        if ((isset($field_data['is_nullable']) &&
            $field_data['is_nullable'] !== null)) {
            $is_nullable = $field_data['is_nullable'];
        }

        return $is_nullable;
    }

    /**
     * @param $field_data
     * @return bool
     *
     * DURC mines the AUTO_INCREMENT attribute from the database schema. This function
     * checks to see if this field is an auto-increment field. If this field is an AI field,
     * return true, otherwise return false.
     */
    protected static function _is_auto_increment($field_data)
    {
        $auto = false;
        if (isset($field_data['is_auto_increment']) &&
            $field_data['is_auto_increment'] === true) {
            $auto = true;
        }
        return $auto;
    }

    /**
     * @param $string
     * @param $field_data
     * @return bool
     *
     * Return true if field data column name begins with string,
     * return false otherwise
     */
    protected static function _begins_with($string, $field_data)
    {
        $doesItBeginWithString = (substr(
            $field_data['column_name'],
            0, strlen($string)) === $string);

        return $doesItBeginWithString;
    }

    /**
     * @param $string
     * @param $field_data
     * @return bool
     *
     * Return true if field data column name ends with string,
     * return false otherwise
     */
    protected static function _ends_with($string, $field_data)
    {
	    $doesItEndWithString = substr_compare(
		    strtolower($field_data['column_name']),
            $string,
		    -strlen($string)) === 0;

	    return $doesItEndWithString;
    }

    /**
     * @param $field_data
     * @return bool
     *
     * The field under validation is required, meaning that it can't be null
     * or an empty string. 0 is allowed for integer values
     */
    protected static function _is_required($field_data)
    {
        $required = false;
        if (self::_is_nullable($field_data) === false &&
            self::_get_default_value($field_data) === null) {
            $required = true;
        }

        // If the field name actually has "required" in the name, let's make it required
        if (self::_ends_with('_required', $field_data)) {
            $required =  true;
        }

        // Make an exception for auto-increment fields, because they populate automatically
        if (self::_is_auto_increment($field_data)) {
            $required = false;
        }

        return $required;
    }
}
