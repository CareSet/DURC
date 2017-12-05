<?php
/*
	This is the place where the actual command is orchestrated.
	it ends up being our "main()"


*/
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DURCCommand extends Command{

    protected $signature = 'DURC {--DB=*}';
    protected $description = 'DURC generates Templates and Eloquent Classe, inferred from your DB structure';

    public function handle(){
	//what does this do?
	echo "You are running inside handle: You rule the world!\n";

	//only one code generator for now...
	$generatorClasses = [
			'CareSet\DURC\LaravelEloquentGenerator',
			'CareSet\DURC\MustacheViewGenerator',
		];

	$databases = $this->option('DB');

	$db_struct = DURC::getDBStruct($databases);

	//each generator handles the creation of different type of file...
	foreach($generatorClasses as $this_generator){

		foreach($db_struct as $this_db => $db_data){
			foreach($db_data as $this_table=> $table_data){
				$this_class_name = $this_table;
				$generated_results = 
					$this_generator::generate(
						$this_class_name,
						$this_db,
						$this_table,
						$table_data
					);

				var_export($generated_results);

				foreach($generated_results as $file_details){
					//now we write the generated content...
					file_put_contents($file_details['full_file_name'],$file_details['file_text']);
				}

			}
		}

	}

//	$has_many_struct = DURC::getHasMany($db_struct);
//	$belongs_to_struct = DURC::getHasMany($db_struct);
	

    }


}
