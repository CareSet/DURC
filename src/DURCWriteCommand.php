<?php
/*
	This is the place where the actual command is orchestrated.
	it ends up being our "main()"


*/
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DURCWriteCommand extends Command{

    protected $signature = 'DURC:write {--squash} {--config_file}';
    protected $description = 'DURC:write generates Templates and Eloquent Classe, inferred from your DB structure';

    public function handle(){
	//what does this do?
	echo "You are running inside handle: You rule the world!\n";

	//only one code generator for now...
	$generatorClasses = [
			'CareSet\DURC\Generators\LaravelEloquentGenerator',
			'CareSet\DURC\Generators\LaravelControllerGenerator',
			'CareSet\DURC\Generators\MustacheEditViewGenerator',
			'CareSet\DURC\Generators\MustacheIndexViewGenerator',
			'CareSet\DURC\Generators\LaravelRouteGenerator',	
			'CareSet\DURC\Generators\LaravelTestRouteGenerator',	
			'CareSet\DURC\Generators\MustacheMenuGenerator',	
		];

	$config_file = $this->option('config_file');

	if(!$config_file){
		$config_file = base_path()."/config/DURC_config.edit_me.json"; //this is the user edited default config file..
	}

	$squash = $this->option('squash');

	$config = DURC::readDURCDesignConfigJSON($config_file);

	//each generator handles the creation of different type of file...
	foreach($generatorClasses as $this_generator){

		$this_generator::start();

		foreach($config as $this_db => $db_data){
			foreach($db_data as $this_table => $table_data){
	
				$this_class_name = strtolower($this_table);
				//echo "Processing $this_class_name\n";
		

				$column_data = $this->_get_or_null($table_data,'column_data');
				if(is_null($column_data)){
					echo "Error: $this_db.$this_table did not have columns defined\n";
					exit();
				}

				$has_many = $this->_get_or_null($table_data,'has_many');
				$belongs_to = $this->_get_or_null($table_data,'belongs_to');
				$many_many = $this->_get_or_null($table_data,'many_many');
				$many_through = $this->_get_or_null($table_data,'many_through');

				//we need to have access to all of the information from the other table as we consider many to many relations...
				if(is_array($has_many)){
					foreach($has_many as $table_type => $has_many_data){
						$from_db = $has_many_data['from_db'];
						$from_table = $has_many_data['from_table'];
						$has_many[$table_type]['other_columns'] = $config[$from_db][strtolower($from_table)]['column_data'];
					}
				}

				//we need to have access to all of the information from the other table as we consider many to many relations...
				if(is_array($belongs_to)){
					foreach($belongs_to as $table_type => $belongs_to_data){
						$to_db = $belongs_to_data['to_db'];
						$to_table = $belongs_to_data['to_table'];
						$belongs_to[$table_type]['other_columns'] = $config[$to_db][strtolower($to_table)]['column_data'];
					}
				}

				$generated_results = $this_generator::run_generator(
						$this_class_name,
						$this_db,
						$this_table,
						$column_data,
						$has_many,
						$belongs_to,
						$many_many,
						$many_through,
						$squash
					);


			}
		}

		$this_generator::finish();

	}

//	$has_many_struct = DURC::getHasMany($db_struct);
//	$belongs_to_struct = DURC::getHasMany($db_struct);
	

    }

	private function _get_or_null($array,$key){
		if(isset($array[$key])){
			return($array[$key]);
		}else{
			return(null);
		}
	}


}
