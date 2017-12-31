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


       if(!is_null($has_many)){
		$template_text .= "
<div class='card'>
  <div class='card-header'>
    Has Many Relationships
  </div>
<div class='card-body'>
";
		//this section will add the tables of other-side data to the edit view for a given object.
                foreach($has_many as $other_table_name => $relate_details){

                        $prefix = $relate_details['prefix'];
                        $type = $relate_details['type'];
                        $from_table = $relate_details['from_table'];
                        $from_db = $relate_details['from_db'];
                        $from_column = $relate_details['from_column'];
			$other_columns = $relate_details['other_columns'];

			$template_text .= "
<div class='card'>
  <div class='card-header'>
    $other_table_name
{{^$type}}
(no values)
{{/$type}}
  </div>
<div class='card-body'>

<table id='table_$other_table_name' class='table table-bordered table-hover table-responsive table-sm'>
<thead>
<tr>
";
		
			//generates the headers to the table
			foreach($other_columns as $this_item){
				$column_name = $this_item['column_name'];
				$template_text .= "\t\t\t<th> $column_name </th>\n";
			}

$template_text .= "
</tr>
</thead>
<tbody>
{{#$type}}
	<tr>
		{{#.}}
";

			//generates the looping rows of data using mostache tags...
			foreach($other_columns as $this_item){
				$column_name = $this_item['column_name'];
				if($column_name == 'id'){	
					//this is the link back to the edit view for this data item..
					$template_text .= "\t\t\t<td><a href='/DURC/$type/{{"."$column_name"."}}'>{{"."$column_name"."}}</a></td>\n";	
				}else{
					$last_three = substr($column_name,-3);
					if($last_three == '_id'){
						//then this is an ID and perhaps there is a _DURClabel that goes with it!!
						$template_text .= "\t\t\t<td>{{"."$column_name"."_DURClabel}} ({{"."$column_name"."}}) </td>";
					}else{
						//normal data no link
						$template_text .= "\t\t\t<td>{{"."$column_name"."}}</td>\n";
					}
				}
			}		

			$template_text .= "		
		{{/.}}
	</tr>
{{/$type}}
</tbody>
</table>
</div></div> <!-- end $other_table_name card-->
<br>
";

		}//end foreach has_many

	$template_text .= "</div></div> <!--end has many card-->";
	}//end if null has_many

       if(!is_null($belongs_to)){
		$template_text .= "
<br>
<div class='card'>
  <div class='card-header'>
    Belongs To Relationships
  </div>
<div class='card-body'>
";
		//this section will add the tables of other-side data to the edit view for a given object.
                foreach($belongs_to as $other_table_name => $relate_details){

                        $prefix = $relate_details['prefix'];
                        $type = $relate_details['type'];
                        $to_table = $relate_details['to_table'];
                        $to_db = $relate_details['to_db'];
                        $to_column = 'id'; // the simple rule that powers the whole system
			$other_columns = $relate_details['other_columns'];


			$template_text .= "
<div class='card'>
  <div class='card-header'>
    $other_table_name
{{^$type}}
(no values)
{{/$type}}
  </div>
<div class='card-body'>
<table id='table_$other_table_name' class='table table-bordered table-hover table-responsive table-sm'>
<thead>
<tr>
";
		
			//generates the headers to the table
			foreach($other_columns as $this_item){
				$column_name = $this_item['column_name'];
				$template_text .= "\t\t\t<th> $column_name </th>\n";
			}

$template_text .= "
</tr>
</thead>
<tbody>
{{#$type}}
	<tr>
		{{#.}}
";

			//generates the looping rows of data using mostache tags...
			foreach($other_columns as $this_item){
				$column_name = $this_item['column_name'];
				if($column_name == 'id'){	
					//this is the link back to the edit view for this data item..
					$template_text .= "\t\t\t<td><a href='/DURC/$type/{{"."$column_name"."}}'>{{"."$column_name"."}}</a></td>\n";	
				}else{
					$last_three = substr($column_name,-3);
					if($last_three == '_id'){
						//then this is an ID and perhaps there is a _DURClabel that goes with it!!
						$template_text .= "\t\t\t<td>{{"."$column_name"."_DURClabel}} ({{"."$column_name"."}}) </td>";
					}else{
						//normal data no link
						$template_text .= "\t\t\t<td>{{"."$column_name"."}}</td>\n";
					}
					//normal data no link
				}
			}		

			$template_text .= "		
		{{/.}}
	</tr>
{{/$type}}
</tbody>
</table>
</div></div> <!-- end $other_table_name card-->
";


		}//end foreach belongs_to
		$template_text .= "</div></div> <!--end belongs to card-->";
	}//end if null belongs_to
	



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
