<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;


class MustacheEditViewGenerator extends \CareSet\DURC\DURCGenerator {



        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){


		$column_type_map = [
			//integer types
			'int' => [ 'input_type' => 'number', ],
			'tinyint' => [ 'input_type' => 'number', ],
			'bigint' => [ 'input_type' => 'number', ],
			'mediumint' => [ 'input_type' => 'number', ],
			'smallint' => [ 'input_type' => 'number', ],

			//floats
			'float' => [ 'input_type' => 'number', ],
			'decimal' => [ 'input_type' => 'number', ],
			'double' => [ 'input_type' => 'number', ],
			'real' => [ 'input_type' => 'number', ],
			

			'date' => [ 'input_type' => 'date', ],
			'datetime' => [ 'input_type' => 'date', ],
			'timestamp' => [ 'input_type' => 'date', ],
			

			//text
			'char' => [ 'input_type' => 'text', ],
			'varchar' => [ 'input_type' => 'text', ],
			'tinytext' => [ 'input_type' => 'text', ],
			'mediumtext' => [ 'input_type' => 'text', ],
			'text' => [ 'input_type' => 'text', ],
			'longtext' => [ 'input_type' => 'text', ],

			//blob
			'tinyblob' => [ 'input_type' => 'file', ],
			'mediumblob' => [ 'input_type' => 'file', ],
			'blob' => [ 'input_type' => 'file', ],
			'longblob' => [ 'input_type' => 'file', ],
			'binary' => [ 'input_type' => 'file', ],
			'varbinary' => [ 'input_type' => 'file', ],


			];

		
		$parent_file_names = [
			 "edit.mustache",
			];


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
  <fieldset>
    <legend>Edit $class_name {{durc_instance_name}}  </legend>
	<input type='hidden' name='_token' value='{{csrf_token}}'>
	";

		foreach($fields as $i => $field_data){

			$column_name = $field_data['column_name'];
			$column_type = $field_data['data_type'];
			$input_type = $column_type_map[strtolower($column_type)]['input_type'];

			$template_text .= "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
      <input type='text' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>
    </div>
  </div>
";

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
