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

    protected $signature = 'DURC {--squash} {--DB=*}';
    protected $description = 'DURC reads your DB structure (:mine) and writes corresponding Laravel code (:write)';

    public function handle(){
	//what does this do?

	$databases = $this->option('DB');

	$squash = $this->option('squash');

	//pass these options to to two other commands...
	echo "Running DURCCommand main command...\n";

	$db_struct = DURC::getDBStruct($databases);

	var_export($db_struct);



    }


}
