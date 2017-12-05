<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class MustacheViewGenerator extends DURCGenerator {


	public static $suggested_output_dir = 'resources/views/';

	public static function run_generator($class_name,$database,$table,$fields){

		$parent_file_name = "$class_name.mustache";	


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

		
		$return_me = [
			'mostache_template' => [
                                'full_file_name' => $my_path.$parent_file_name,
				'file_name' 	=> $parent_file_name,
				'file_text'	=> $template_text,
				],
		];

                foreach($return_me as $file_details){
                        file_put_contents($file_details['full_file_name'],$file_details['file_text']);
                }

		return(true);

	}//end generate function







}//end class
