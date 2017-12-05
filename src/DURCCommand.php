<?php
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

	$databases = $this->option('DB');


	$db_struct = DURC::getDBStruct($databases);

	var_export($db_struct);

    }
}
