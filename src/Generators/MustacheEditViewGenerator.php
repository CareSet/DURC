<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class MustacheEditViewGenerator extends \CareSet\DURC\DURCMustacheGenerator {


	public static function start(){


	}

	public static function finish(){

	}


        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){

		$debug = false;
		if($debug){
			echo "Generating $class_name\n";
			var_export($fields);

		}
		$parent_file_names = [
			 "edit.mustache",
			];


                $gen_string = DURC::get_gen_string();

		$template_text = "
{{#has_session_status}}
<div class='alert alert-info' role='alert'>
  {{session_status}}
</div>
{{/has_session_status}}

{{#is_new}}
<form action='/DURC/$class_name/' method='POST'>
{{/is_new}}

{{^is_new}}
<form action='/DURC/$class_name/{{id}}' method='POST'>
    <input type='hidden' name='_method' value='PUT'>
{{/is_new}}

<h1> $class_name </h1>
<h4> <a href='/DURC/$class_name/'>Return to $class_name list</a> </h4>
<h6> $gen_string </h6>
  <fieldset>
    <legend>Edit $class_name {{durc_instance_name}}  </legend>
	<input type='hidden' name='_token' value='{{csrf_token}}'>
	";

		foreach($fields as $i => $field_data){

			$field_html = parent::_get_field_html($field_data); //this is defined in ../DURCMustacheGenerator.php
			//echo $field_html;
			$template_text .= $field_html;
		}

		$template_text .= "
  <div class='form-group row'>
    <div class='col-sm-10'>
      <button type='submit' class='btn btn-primary'>Save Data</button>
    </div>
  </div>

</fieldset></form>\n";

		$my_path = base_path() . "/resources/views/DURC/$class_name/";


                if(!is_dir($my_path)){
                        if (!mkdir($my_path, 0777, true)) {
                                die("MustacheEditViewGenerator: DURC needs to create the $my_path directory... but it could not.. what if it already existed? What then?");
                        }
                }


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
