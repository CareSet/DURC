<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class MySQLDumpGenerator extends \CareSet\DURC\DURCGenerator {


        //Run only once at the end of generation
        public static function finish(
							$db_config,
                                                        $squash,
                                                        $URLroot){
                //does nothing need to comply with abstract class
        }        



        public static function start(
							$db_config,
                                                        $squash,
                                                        $URLroot){

	}//end start

        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false, $URLroot = '/DURC/'){


		$user = env('DB_USERNAME',false);
		$password = env('DB_PASSWORD',false);

		if($user && $password){

			$path = base_path("app/DURC/SQL_schemas");	
			if(!is_dir($path)){	//if the directory does not exist..
				$old = umask(0);
				mkdir($path,0744); //make it...
				umask($old);
			}
			$file_path = "$path/$database.$table.sql";
		
			$mysqldump_command = "mysqldump -u $user -p$password --no-data --compact $database $table > $file_path";
			
			exec($mysqldump_command);

		}

		return(true);
		

	}//end generate function




}//end class
