<?php
/*
	Generates a mysql dump of the schema for each table..
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

/*
        This accepts the data for each table in the database that DURC is aware of..
	and makes a mysql dump of the schema of the underlying table..
*/
        public static function run_generator($data_for_gen){

                $data_for_gen = self::_check_arguments($data_for_gen); //ensure reasonable defaults...
                extract($data_for_gen); //everything goes into the local scope... this includes all settings from the config json file...


		$user = \Config::get('database.connections.mysql.username');
		$password = \Config::get('database.connections.mysql.password');

		if($user && $password){
			$path = base_path("app/DURC/SQL_schemas");	
			if(!is_dir($path)){	//if the directory does not exist..
				$old = umask(0);
				mkdir($path,0744); //make it...
				umask($old);
			}
			$file_path = "$path/$database.$table.sql";
		
			//sed command removes autoincriment.. which will constantly result in new file structure as data is added...
			//from https://stackoverflow.com/a/26328331/144364
			if(`which mysqldump`){
				$mysqldump_command = "mysqldump -u $user -p$password --no-data --compact $database $table | sed 's/ AUTO_INCREMENT=[0-9]*//g' > $file_path";
				exec($mysqldump_command);
			}else{
				echo "Error: for SQL schema backups to work, you have to have mysqldump installed and findable with `which mysqldump`\n";
			}

		}else{
			echo "Error: could not load username and password to use mysqldump\n";
		}

		return(true);
		

	}//end generate function




}//end class
