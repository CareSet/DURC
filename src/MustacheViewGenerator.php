<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class MustacheViewGenerator extends DURCGenerator {


	public static $suggested_output_dir = 'resources/views/';

	public static function run_generator($class_name,$database,$table,$fields,$squash = false){

		
		$parent_file_names = [
			 "$class_name.mustache",
			 "$class_name.tpl",
			 "$class_name.handlebar",
			];


		$template_text = "
<h1>$class_name form </h1>
<ul>
";
		foreach($fields as $this_field => $field_data){
			$template_text .= "<li> $this_field  <ul>\n ";
			foreach($field_data as $key => $value){
				$template_text .= "<li> $key -> $value </li>\n";
			}
			$template_text .= "</ul> </li>\n";
		}

		$template_text .= "</ul>\n";

		$my_path = base_path() . '/'. MustacheViewGenerator::$suggested_output_dir;

		//we did this originally becuase we did not know what file extension to use.
		foreach($parent_file_names as $parent_file_name){
			//if it does not exist.. write it... if the squash argument is possed in as true.. write it..
			if(!file_exists($my_path.$parent_file_name) || $squash){
               	        	file_put_contents($my_path.$parent_file_name,$template_text);
                	}
		}

		return(true);

	}//end generate function







}//end class
