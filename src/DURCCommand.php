<?php
/*
	This file is required because of reasons. 
	When we remove it, things go haywire.. but it does not actually do anything.
	Why cant we remove it more easily?
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
