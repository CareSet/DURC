<?php
/*
	This is the place where the actual command is orchestrated.
	it ends up being our "main()"


*/
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DURCMineCommand extends Command{

    protected $signature = 'DURC:mine {--squash} {--DB=*}';
    protected $description = 'DURC:mine generates a json representation of your database structure and relationships, by mining your DB directly.';

    public function handle(){
	//what does this do?

	$databases = $this->option('DB');

	$db_struct = DURC::getDBStruct($databases);


	var_exporT($db_structure);
	exit();	

    }


}
