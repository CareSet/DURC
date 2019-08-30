<?php
/*
	This is the new Zermelo index, which is better in every way than the current mustache index

*/
namespace CareSet\DURC\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use CareSet\DURC\DURC;
use CareSet\DURC\Signature;

class ZermeloIndexGenerator extends \CareSet\DURC\DURCGenerator {


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

        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$has_one = null, $belongs_to = null, $many_many = null, $many_through = null, $squash = false, $URLroot = '/DURC/',$create_table_sql){

		$report_class_name = "DURC_$class_name";

	
		$report_php_code = "<?php
namespace App\Reports;
use CareSet\Zermelo\Reports\Tabular\AbstractTabularReport;

class $report_class_name extends AbstractTabularReport
{

    //returns the name of the report
    public function GetReportName(): string {
        \$report_name = \"$class_name Report\";
        return(\$report_name);
    }

    //returns the description of the report. HTML is allowed here.
    public function GetReportDescription(): ?string {
        \$desc = \"View the $class_name data\";
        return(\$desc);
    }

    //  returns the SQL for the report.  This is the workhorse of the report.
    public function GetSQL()
    {

        \$index = \$this->getCode();

        if(is_null(\$index)){

                \$sql = \"
SELECT * FROM $database.$table
\";

        }else{

                \$sql = \"
SELECT * FROM $database.$table WHERE id = \$index
\";

        }

        \$is_debug = false;
        if(\$is_debug){
                echo \"<pre>\$sql\";
                exit();
        }

        return \$sql;
    }

    //decorate the results of the query with useful results
    public function MapRow(array \$row, int \$row_number) :array
    {

        extract(\$row);

        //link this row to its DURC editor
        \$row['id'] = \"<a href='/DURC/object_name/\$id'>\$id</a>\";

        return \$row;
    }

    //see Zermelo documentation to understand following functions:
    //https://github.com/CareSet/Zermelo

    public \$NUMBER     = ['ROWS','AVG','LENGTH','DATA_FREE'];
    public \$CURRENCY = [];
    public \$SUGGEST_NO_SUMMARY = ['ID'];
    public \$REPORT_VIEW = null;

    public function OverrideHeader(array &\$format, array &\$tags): void
    {
    }

    public function GetIndexSQL(): ?array {
                return(null);
    }

        //turns on the cache, should be off for development and small databases or simple queries
   public function isCacheEnabled(){
        return(false);
   }

        //only matters if the cache is on
   public function howLongToCacheInSeconds(){
        return(1200); //twenty minutes by default
   }

}
?>";
	
	$signed_zermelo_report = Signature::sign_phpfile_string($report_php_code);

	$report_path = base_path().'/app/Reports/'; //how do we support alternate paths??
	$report_file_location = $report_path . $report_class_name;
	
	if(file_exists($report_file_location)){
		//do nothing for now..

	}else{
		//this is initial file creation
		file_put_contents($report_file_location,$signed_zermelo_report);

	}


	}//end generate function




}//end class
