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
	                		$tables[$column->TABLE_NAME][$column->COLUMN_NAME] = [
						'column_name' => $column->COLUMN_NAME,
						'data_type' => $column->DATA_TYPE,
                        'is_primary_key' => ( $column->COLUMN_KEY === 'PRI' ) ? true : false,
						'is_foreign_key' => false,
						'is_linked_key' => false,
						'foreign_db' => null,
						'foreign_table' => null,
                        'is_nullable' => ($column->IS_NULLABLE === 'NO') ? false : true,
                        'default_value' => $column->COLUMN_DEFAULT,
                        'is_auto_increment' => (strpos(strtolower($column->EXTRA),'auto_increment') !== false)
						];
				}
	            	}
			ksort($tables);

			//need to log the problems here... TODO
			foreach($bad_tables as $table => $problem){
				echo "Rejected $table because $problem\n";
			}

			//now lets see about the foreign keys...
			$foreign_key_array = [];
			$foreign_keys = DB::select('SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND COLUMN_NAME != "id" ', [$db_name]);

			if(is_array($foreign_keys) && !empty($foreign_keys)){

				foreach($foreign_keys as $this_row){
					$table_name = $this_row->TABLE_NAME;
					$column_name = $this_row->COLUMN_NAME;
					$referenced_db = $this_row->REFERENCED_TABLE_SCHEMA;
					$referenced_table = $this_row->REFERENCED_TABLE_NAME;			

					$tables[$table_name][$column_name]['is_foreign_key'] = true;
					$tables[$table_name][$column_name]['is_linked_key'] = true;
					$tables[$table_name][$column_name]['foreign_db'] = $referenced_db;
					$tables[$table_name][$column_name]['foreign_table'] = $referenced_table;
				
				}
			}

			//we are working with json, so we want an JS array of columns and not a JS object
			$new_tables = [];
			foreach($tables as $table_name => $column_data){
				foreach($column_data as $dropping_this_column_name => $column_array){
					$new_tables[$table_name][] = $column_array;
				}
			}
			return($new_tables);
	        } else {
	     		return false;
	     	}

	}//end getTables

	//using the users durc configuration..
	//return a list of the create form  urls for the list view
	public static function getDURCUrlArray(){		
		$user_edit_file_name = base_path()."/config/DURC_config.edit_me.json";
		$user_config = json_decode(file_get_contents($user_edit_file_name),true);

		$return_me = [];
		foreach($user_config as $db => $durc_array){
			foreach($durc_array as $durc_string => $durc_details){
				$create_url = "/DURC/$durc_string/create";
				$durc_listing = new \stdClass;			
				$durc_listing->label = $durc_string;
				$durc_listing->create_url = $create_url;
				$list_url = "/Zermelo/DURC_$durc_string";
				$durc_listing->list_url = $list_url;
				$return_me[$durc_string] = $durc_listing;
			}
		}

		//put them in alpha order
		ksort($return_me);

		//but use numeric keys in the end...
		$return_me = array_values($return_me);

		return($return_me);
	}


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

	public static function get_gen_string(){
		return "DURC Generated At: ".date('l jS \of F Y h:i:s A');
	}

    /***
     * @param $data_type
     * @param string $column_name
     * @return string
     *
     * Map an SQL data type to a HTML input type and use column name for hinting if provided
     */
	public static function mapColumnDataTypeToInputType( $data_type, $column_name = '' )
    {

	$l_data_type = strtolower($data_type);


	$char_types = [
		'char',
		'varchar',
		'tinytext',
		'mediumtext',
		'text',
		'longtext',
		];


        $input_type = '';
        if ( ( 		$l_data_type == 'tinyint' ||
                	$l_data_type == 'boolean' ) 
		&&
            		( strpos( $column_name, 'has_', 0 ) === 0 ||
            		strpos( $column_name, 'is_', 0 ) === 0 ) ) {
            // It's a boolean type if is starts with has_ or is_ and is a
            // tinyint or boolean type
            $input_type = 'boolean';
        } else {
		//lets see if this is markdown.. it is if its a character type,
		//and if it ends in '_markdown' or some other equviilent string
		if(in_array($l_data_type,$char_types)){

			$its_markdown = false;	
			$markdown_strings = [ '_markdown', '_markdn' ];
			foreach($markdown_strings as $this_markdown_string){
				//this "does it end with" logic comes from
				//https://stackoverflow.com/a/36569425/144364
				$doesItEndWithMarkdown = substr_compare( $column_name, $this_markdown_string , -strlen( $this_markdown_string ) ) === 0;
				if($doesItEndWithMarkdown){
//					echo "Note:$column_name looks like a markdown column  ";
					return('markdown');
				}
			}

			if(strpos($column_name,'_code') !== false){ //then this loop is worth doing!!
			//now we do the same thing with all of the code strings
				foreach(self::$code_language_map as $this_code_stub => $this_code_mode){
					$this_code_string = "_$this_code_stub"."_code";

					$doesItEndWithCode = substr_compare( $column_name, $this_code_string , -strlen( $this_code_string ) ) === 0;
					if($doesItEndWithCode){
//						echo "Note:$column_name looks like a code column  ";
						return('code');
					}

				}
			}

            		return(self::$column_type_map[strtolower($data_type)]['input_type']);
		}else{
			//if we get here then we are not using a special syntax to mark the field
			//we are just using the standard mapping at the end of this file
            		return(self::$column_type_map[strtolower($data_type)]['input_type']);
		}
        }

        return $input_type;
    }

    /**
     * @param $field_name
     * @param $value
     * @return mixed
     *
     * Use rules to format a form input value for storage into the database
     */
    public static function formatForStorage( $field_name, $field_type, $value )
    {
        $formattedValue = $value;
        if ( self::mapColumnDataTypeToInputType( $field_type, $field_name ) == 'boolean' ) {
            if ( $value == 'on' ||
                $value > 0 ) {
                $formattedValue = 1;
            } else {
                $formattedValue = 0;
            }
        } else if ( $field_type == 'datetime' ) {
            // Convert to SQL format for storage
            if ( !empty($value) ) {
                $formattedValue = date( 'Y-m-d h:i:s', strtotime( $value ) );
            }
        }

        return $formattedValue;
    }

    public static function formatForDisplay( $field_type, $field_name, $field_value, $is_list = false )
    {
        $formatted_value = '';

        if ( DURC::mapColumnDataTypeToInputType( $field_type, $field_name, $field_value ) == 'boolean' ) {
            if ( $field_value > 0 ) {
                if ( $is_list ) {
                    $formatted_value = '✔'; // If we are in the list view, display a checkmark
                } else {
                    $formatted_value = 'checked';
                }
            }
        } else if ( $field_type == 'datetime' ) {
            if ( !empty($field_value) ) {
                $formatted_value = date( config( 'durc.date_format' ) . ' ' . config( 'durc.time_format' ), strtotime( $field_value ) );
            } else {
                $formatted_value= '';
            }
        } else {
            $formatted_value = $field_value;
        }

        return $formatted_value;

    }

        public static   $column_type_map = [
                        //integer types
                        'int' => [ 'input_type' => 'number', ],
                        'tinyint' => [ 'input_type' => 'number', ],
                        'bigint' => [ 'input_type' => 'number', ],
                        'mediumint' => [ 'input_type' => 'number', ],
                        'smallint' => [ 'input_type' => 'number', ],

                        //floats
                        'float' => [ 'input_type' => 'number', ],
                        'decimal' => [ 'input_type' => 'number', ],
                        'double' => [ 'input_type' => 'number', ],
                        'real' => [ 'input_type' => 'number', ],


                        'date' => [ 'input_type' => 'date', ],
                        'datetime' => [ 'input_type' => 'datetime', ],
                        'timestamp' => [ 'input_type' => 'datetime', ],
                        'time' => [ 'input_type' => 'time', ],
                        'year' => [ 'input_type' => 'year', ],


                        //text
                        'char' => [ 'input_type' => 'text', ],
                        'varchar' => [ 'input_type' => 'text', ],
                        'tinytext' => [ 'input_type' => 'text', ],
                        'mediumtext' => [ 'input_type' => 'text', ],
                        'text' => [ 'input_type' => 'text', ],
                        'longtext' => [ 'input_type' => 'text', ],

                        //blob
                        'tinyblob' => [ 'input_type' => 'file', ],
                        'mediumblob' => [ 'input_type' => 'file', ],
                        'blob' => [ 'input_type' => 'file', ],
                        'longblob' => [ 'input_type' => 'file', ],
                        'binary' => [ 'input_type' => 'file', ],
                        'varbinary' => [ 'input_type' => 'file', ],

                        ];

	//this listing is specifically borrowed from the /mode/ subdirectory of the CodeMirror 
	//we actually use CodeMirror over Ace because of how its directory names clearly map to its modes
	//however, there are some things that we need to change in order to support 
	//decent naming conventions. so we will map 'r' to rlang etc so that we can avoid strangeness
	//we have also removed any mode with a '-' in it.. because that does not work..
	//sorry for 'haskel-literate' et al
	//also, html is mapped to htmlmixed because that is problably what most people will want...


	public static $code_language_map = [
  'apl' => 'apl',
  'asciiarmor' => 'asciiarmor',
  'asterisk' => 'asterisk',
  'clike' => 'clike',
  'clojure' => 'clojure',
  'cmake' => 'cmake',
  'cobol' => 'cobol',
  'coffeescript' => 'coffeescript',
  'commonlisp' => 'commonlisp',
  'crystal' => 'crystal',
  'css' => 'css',
  'cypher' => 'cypher',
  'dlang' => 'd',
  'dart' => 'dart',
  'diff' => 'diff',
  'django' => 'django',
  'dockerfile' => 'dockerfile',
  'dtd' => 'dtd',
  'dylan' => 'dylan',
  'ebnf' => 'ebnf',
  'ecl' => 'ecl',
  'eiffel' => 'eiffel',
  'elm' => 'elm',
  'erlang' => 'erlang',
  'factor' => 'factor',
  'fcl' => 'fcl',
  'forth' => 'forth',
  'fortran' => 'fortran',
  'gas' => 'gas',
  'gfm' => 'gfm',
  'gherkin' => 'gherkin',
  'go' => 'go',
  'groovy' => 'groovy',
  'haml' => 'haml',
  'handlebars' => 'handlebars',
  'haskell' => 'haskell',
  'haxe' => 'haxe',
  'htmlembedded' => 'htmlembedded',
  'htmlmixed' => 'htmlmixed',
  'http' => 'http',
  'idl' => 'idl',
  'javascript' => 'javascript',
  'json' => 'javascript',
  'jinja2' => 'jinja2',
  'jsx' => 'jsx',
  'julia' => 'julia',
  'livescript' => 'livescript',
  'lua' => 'lua',
  'markdown' => 'markdown',
  'mathematica' => 'mathematica',
  'mbox' => 'mbox',
  'mirc' => 'mirc',
  'mllike' => 'mllike',
  'modelica' => 'modelica',
  'mscgen' => 'mscgen',
  'mumps' => 'mumps',
  'nginx' => 'nginx',
  'nsis' => 'nsis',
  'ntriples' => 'ntriples',
  'octave' => 'octave',
  'oz' => 'oz',
  'pascal' => 'pascal',
  'pegjs' => 'pegjs',
  'perl' => 'perl',
  'php' => 'php',
  'pig' => 'pig',
  'powershell' => 'powershell',
  'properties' => 'properties',
  'protobuf' => 'protobuf',
  'pug' => 'pug',
  'puppet' => 'puppet',
  'python' => 'python',
  'qlang' => 'q',
  'rlang' => 'r',
  'rpm' => 'rpm',
  'rst' => 'rst',
  'ruby' => 'ruby',
  'rust' => 'rust',
  'sas' => 'sas',
  'sass' => 'sass',
  'scheme' => 'scheme',
  'shell' => 'shell',
  'sieve' => 'sieve',
  'slim' => 'slim',
  'smalltalk' => 'smalltalk',
  'smarty' => 'smarty',
  'solr' => 'solr',
  'soy' => 'soy',
  'sparql' => 'sparql',
  'spreadsheet' => 'spreadsheet',
  'sql' => 'sql',
  'stex' => 'stex',
  'stylus' => 'stylus',
  'swift' => 'swift',
  'tcl' => 'tcl',
  'textile' => 'textile',
  'tiddlywiki' => 'tiddlywiki',
  'tiki' => 'tiki',
  'toml' => 'toml',
  'tornado' => 'tornado',
  'troff' => 'troff',
  'ttcn' => 'ttcn',
  'turtle' => 'turtle',
  'twig' => 'twig',
  'vb' => 'vb',
  'vbscript' => 'vbscript',
  'velocity' => 'velocity',
  'verilog' => 'verilog',
  'vhdl' => 'vhdl',
  'vue' => 'vue',
  'webidl' => 'webidl',
  'xml' => 'xml',
  'xquery' => 'xquery',
  'yacas' => 'yacas',
  'yaml' => 'yaml',
  'z80' => 'z80',
	];


}
