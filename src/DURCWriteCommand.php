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

    protected $signature = 'DURC:write {--squash} {--config_file=} {--URLroot=}';
    protected $description = 'DURC:write generates Templates and Eloquent Classe, inferred from your DB structure';

    public function handle(){
	//what does this do?

	//only one code generator for now...
	$generatorClasses = [
			'Eloquent Models' => 		'CareSet\DURC\Generators\LaravelEloquentGenerator',
			'Web Controllers' => 		'CareSet\DURC\Generators\LaravelControllerGenerator',
			'Mustache Edit Views' =>	'CareSet\DURC\Generators\MustacheEditViewGenerator',
			'Mustache Index Page' => 	'CareSet\DURC\Generators\MustacheIndexViewGenerator',
			'Laravel Routes' => 		'CareSet\DURC\Generators\LaravelRouteGenerator',	
			'Test Routes' =>		'CareSet\DURC\Generators\LaravelTestRouteGenerator',	
			'Mustache Menu' => 		'CareSet\DURC\Generators\MustacheMenuGenerator',	
		];

	$config_file = $this->option('config_file');

	if(!$config_file){
		$config_file = base_path()."/config/DURC_config.edit_me.json"; //this is the user edited default config file..
	}

	$squash = $this->option('squash');

	$URLroot = $this->option('URLroot');
	if(is_null($URLroot)){
		$URLroot = '/DURC/';
	}

	if($squash){
		echo "DURC:write Beginning code generation! Squashing all previous code generation \n";	
	}else{
		echo "DURC:write Beginning code generation!\n";
	}
	$config = DURC::readDURCDesignConfigJSON($config_file);

	//each generator handles the creation of different type of file...
	foreach($generatorClasses as $generator_label => $this_generator){
		echo "Generating $generator_label...\t\t\t";
		$this_generator::start($config,$squash,$URLroot);

		foreach($config as $this_db => $db_data){
			foreach($db_data as $this_class_name => $table_data){
	
		
				$this_table = $this->_get_or_null($table_data,'table_name');


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
						$squash,
						$URLroot
					);


			}
		}

		$this_generator::finish($config,$squash,$URLroot);
		echo "done\n";
	}

	echo "DURC:write all done.\n";



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
