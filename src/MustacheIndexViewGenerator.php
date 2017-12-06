<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC;


class MustacheIndexViewGenerator extends DURCGenerator {



	public static function run_generator($class_name,$database,$table,$fields,$squash = false){

		
		$parent_file_names = [
			 "index.mustache",
			];

		$col_list = [];
		foreach($fields as $this_field => $field_data){
			$col_list[] = $field_data['column_name'];
		}

		$template_text = "
<h1>$class_name list </h1>
<table class='table table-bordered table-hover table-responsive table-sm'>
 <caption>List of $class_name</caption>
  <thead>
    <tr>
	
";
		foreach($col_list as $column_name){
			if($column_name == 'id'){ //we want to scope this field as the row id...
				$template_text .= "<th scope='col'>#</th>";
			}else{
				$template_text .= "<th scope='col'>$column_name</th>\n"; 
			}
		}

		$template_text .= "</tr></thead><tbody>
  {{#data}}
    <tr>
	";

		foreach($col_list as $column_name){
			if($column_name == 'id'){ //we want to scope this field as the row id...
				$template_text .= "<th scope='row'>{{id}}</th>";
			}else{	
				$template_text .= "<td>{{"."$column_name"."}}</td>\n"; 
			}
		}
		$template_text .= "
    </tr>
  {{/data}}

";

		$template_text .= "<tbody></table>\n";

		$my_path = base_path() . "/resources/views/DURC/$class_name/";


                if(!is_dir($my_path)){
                        if (!mkdir($my_path, 0777, true)) {
                                die("MustacheIndexViewGenerator: DURC needs to create the $my_path directory... but it could not.. what if it already existed? What then?");
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
