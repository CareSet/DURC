<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

/*
        This is where all of the work is done...
*/
class DURCGenerator{


	public static	$column_type_map = [
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
                        'datetime' => [ 'input_type' => 'date', ],
                        'timestamp' => [ 'input_type' => 'date', ],


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


	public static function start(){
		//nothing here.. override me if you like..
	}


	public static function finish(){
		//nothing here.. override me if you like..
	}



}
