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

/*
        This accepts the data for each table in the database that DURC is aware of..
	Zermelo report of underlying data table..
*/
        public static function run_generator($data_for_gen){

                $data_for_gen = $this->_check_arguments($data_for_gen); //ensure reasonable defaults...
                extract($data_for_gen); //everything goes into the local scope... this includes all settings from the config json file...

		$is_debug = true;

		if($is_debug){
			$structure_comment = "/*\n";
			$structure_comment .= "\n//fields:\n";
			$structure_comment .= var_export($fields,true);
			$structure_comment .= "\n//has_many\n";
			$structure_comment .= var_export($has_many,true);
			$structure_comment .= "\n//has_one\n";
			$structure_comment .= var_export($has_one,true);
			$structure_comment .= "\n//belongs_to\n";
			$structure_comment .= var_export($belongs_to,true);
			$structure_comment .= "\n//many_many\n";
			$structure_comment .= var_export($many_many,true);
			$structure_comment .= "\n//many_through\n";
			$structure_comment .= var_export($many_through,true);
			$structure_comment .= "*/\n";	
		}else{
			$structure_comment = '';
		}

		$select_us = [];
		$joins = [];
		$pre_sql_php = '';
		$decoration_php = '';


		$pre_sql_php = "
	//get the local image field for this report... null if not found..
	\$img_field_name = \App\\$class_name::getImgField();
	if(isset(\$\$img_field_name)){
		\$img_field = \$\$img_field_name;
	}else{
		\$img_field = null;
	}

	\$joined_select_field_sql  = '';


";

	$decoration_php .= "

	if(isset(\$\$img_field_name)){  //is it set
		if(strlen(\$img_field) > 0){ //and it is it really a url..
			\$row[\$img_field_name] = \"<img width='300' src='\$img_field'>\";
		}
	}


";




		//to start, we presume that we will simply select for every data field in the array
		foreach($fields as $field_index => $field_data){	
			$select_us[$field_data['column_name']] = ['field' => "$table.".$field_data['column_name'],  'as' => $field_data['column_name']];
		}
		
		$i = -1;
		$letters = range('A','Z');
		if(!is_null($belongs_to)){
			$add_back = [];
			foreach($belongs_to as $other_table => $other_table_contents){
				$i++; //for getting new letters..
				//this is what we have for every table that our interneal something_id fields link to...
				$prefix = $other_table_contents['prefix'];
				if(is_null($prefix)){
					$col_prefix = '';
				}else{
					$col_prefix = "$prefix"."_";
				}

				$type = $other_table_contents['type'];
				$to_table = $other_table_contents['to_table'];
				$to_table_alias = $letters[$i] ."_$to_table"; //we need reliable table aliases, even if we join to the same table again and again
				$to_db = $other_table_contents['to_db'];
				$local_key = $other_table_contents['local_key'];
				$other_columns  = $other_table_contents['other_columns'];
				
				$pre_sql_php .= "
	\$$to_table"."_field = \App\\$type::getNameField();	
	\$joined_select_field_sql .= \"
, $to_table_alias.\$$to_table"."_field  AS $col_prefix\$$to_table"."_field
\"; 
	\$$to_table"."_img_field = \App\\$type::getImgField();
	if(!is_null(\$$to_table"."_img_field)){
		if(\$is_debug){echo \"$to_table has an image field of: |\$$to_table"."_img_field|\n\";}
		\$joined_select_field_sql .= \"
, $to_table_alias.\$$to_table"."_img_field  AS $col_prefix\$$to_table"."_img_field
\"; 
	}
";	
				
				$decoration_php .= "
\$$other_table"."_tmp = '$col_prefix'.\$$to_table"."_field;
if(isset(\$row[\$$other_table"."_tmp])){
	\$$other_table"."_data = \$row[\$$other_table"."_tmp];
	\$row[\$$other_table"."_tmp] = \"<a target='_blank' href='/Zermelo/DURC_$to_table/\$$local_key'>\$$other_table"."_data</a>\";
}

\$$other_table"."_img_tmp = '$col_prefix'.\$$to_table"."_img_field;
if(isset(\$row[\$$other_table"."_img_tmp]) && strlen(\$$other_table"."_img_tmp) > 0){
	\$$other_table"."_img_data = \$row[\$$other_table"."_img_tmp];
	\$row[\$$other_table"."_img_tmp] = \"<img width='200px' src='\$$other_table"."_img_data'>\";
}
";


				//now we calculate how to modify the select_us fields to account for the fact that we would prefer
				//to display the name fields of the objects on the other side of the links...
				//we now do this is along the way... TODO erase this code if it works
/*
				$add_back[$local_key] = $select_us[$local_key]; //we still need to have the id!!! but at the end of the report!!
				$select_us[$local_key] = [
						'field' => "$to_table_alias.\$$to_table"."_field",
						'as' => "$col_prefix\$$to_table"."_field",
					];
*/

				$joins[] = "
LEFT JOIN $to_db.$to_table AS $to_table_alias ON 
	$to_table_alias.id =
	$table.$local_key
";

			}
			//fold the id_fields (i.e. tag_id and person_id etc etc)  back into the repoort (because we need them for decorations)
			foreach($add_back as $local_key => $to_add ){
				$select_us[] = $to_add;
			}
		}
	
		//we manually add id so that it is always at the beginnig..
		//so lets remove it here..
		unset($select_us['id']);	

		//now we are doing the specific aliases for the SELECT portion of the SQL
		$select_sql = '';
		$c = ''; //TODO remove this later...
		foreach($select_us as $select_index => $select_data){
			$field = $select_data['field'];
			$as = $select_data['as'];
			$select_sql .= ", $field AS $as\n";
			$c = ',';
		}
		
		//now we add all of the joins..
		$join_sql = '';
		foreach($joins as $this_join){
			$join_sql .= $this_join;
		}



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
        \$desc = \"View the $class_name data
			<br>
			<a href='/DURC/$class_name/create'>Add new $class_name</a>
\";
        return(\$desc);
    }

    //  returns the SQL for the report.  This is the workhorse of the report.
    public function GetSQL()
    {

        \$is_debug = false; //lots of debugging echos will be show instead of the report

        \$index = \$this->getCode();

$pre_sql_php

        if(is_null(\$index)){

                \$sql = \"
SELECT
$table.id
\$joined_select_field_sql 
$select_sql
FROM $database.$table
$join_sql
\";

        }else{

                \$sql = \"
SELECT
$table.id 
\$joined_select_field_sql
$select_sql 
FROM $database.$table 
$join_sql
WHERE $table.id = \$index
\";

        }

        if(\$is_debug){
                echo \"<pre>\$sql\";
                exit();
        }

        return \$sql;
    }

    //decorate the results of the query with useful results
    public function MapRow(array \$row, int \$row_number) :array
    {

	\$is_debug = false;
	
	//we think it is safe to extract here because we are getting this from the DB and not a user directly..
        extract(\$row);

$pre_sql_php


        //link this row to its DURC editor
        \$row['id'] = \"<a href='/DURC/$class_name/\$id'>\$id</a>\";

$decoration_php


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

$structure_comment

?>";
	
	$signed_zermelo_report = Signature::sign_phpfile_string($report_php_code);

	$report_path = base_path().'/app/Reports/'; //how do we support alternate paths??
	$report_file_location = $report_path . "$report_class_name.php";
	
	if(file_exists($report_file_location)){
		//do nothing for now..
                        $current_file_contents = file_get_contents($report_file_location);
                        $has_file_changed = Signature::has_signed_file_changed($current_file_contents);
                        if($has_file_changed){
                                //if a signed file has changed, we never never overwrite it.
                                //it contains manually created code that needs to be protected.
                                //so we do nothing here...
                                echo "Not overwriting $report_file_location it has manual modifications\n";
                        }else{
                                //if the file has not been modified, then it is still in its "autogenerated" state.
                                //files in that state are always overridden by newer autogenerated files..
				file_put_contents($report_file_location,$signed_zermelo_report);
                        }

	}else{
		//this is initial file creation
		file_put_contents($report_file_location,$signed_zermelo_report);

	}


	}//end generate function




}//end class
