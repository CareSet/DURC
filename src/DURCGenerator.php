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



}
