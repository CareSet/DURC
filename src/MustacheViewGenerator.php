<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class MustacheViewGenerator {


	public static $suggested_output_dir = 'view/resources/';

	public static function generate($class_name,$database,$table,$fields){

		$parent_file_name = "$parent_class_name.mustache";	


		$template_text = "
<h1>$class_name form </h1>
<ul>
";
		foreach($fields as $this_field => $field_data){
			$template_text .= "<li> $this_field  <ul> ";
			foreach($field_data as $key => $value){
				$template_text .= "<li> $key -> $value </li>";
			}
			$template_text .= "</ul> </li>";
		}

		$template_text .= "</ul>";

		
		$return_me = [
			'mostache_template' => [
                                'full_file_name' => base_path().$parent_file_name,
				'file_name' 	=> $parent_file_name,
				'file_text'	=> $text_template_text,
				],
		];


		return($return_me);


	}//end generate function







}//end class
