<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class MustacheIndexViewGenerator extends \CareSet\DURC\DURCGenerator {


        public static function start(
							$db_config,
                                                        $squash,
                                                        $URLroot){
                //does nothing need to comply with abstract class
        }

        public static function finish(
							$db_config,
                                                        $squash,
                                                        $URLroot){
                //does nothing need to comply with abstract class
        }



        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false,$URLroot = '/DURC/'){


		$field_lookup = [];
		if(!is_null($belongs_to)){
			foreach($belongs_to as $this_belongs_to){
				$field_lookup[$this_belongs_to['local_key']] = $this_belongs_to['type'];
			}
		}


                $gen_string = DURC::get_gen_string();
		
		$parent_file_names = [
			 "index.mustache",
			];

		$col_list = [];
		foreach($fields as $this_field => $field_data){
			$col_list[] = $field_data['column_name'];
		}


	$header_row = '';
		foreach($col_list as $column_name){
			if($column_name == 'id'){ //we want to scope this field as the row id...
				$header_row .= "<th scope='col'>#</th>";
			}else{
				$header_row .= "<th scope='col'>$column_name</th>\n"; 
			}
		}

$paging_widget = "
{{^is_need_paging}}
<p>
{{total}} results. All rows shown.
</p>
{{/is_need_paging}}

{{#is_need_paging}}

<div class='dataTables_paginate paging_simple_numbers' id='table_$class_name"."_page'>
	<ul class='pagination'>
		<li class='paginate_button page-item previous {{first_page_class}}' 
			id='table_$class_name"."_previous'>
			<a href='{{first_page_url}}' aria-controls='table_$class_name' data-dt-idx='0' tabindex='0' class='page-link'>First</a>
		</li>
		<li class='paginate_button page-item {{prev_page_class}}'>
			<a href='{{prev_page_url}}' aria-controls='table_$class_name' data-dt-idx='1' tabindex='0' class='page-link'>Previous</a>
		</li>
		<li class='paginate_button page-item active'>
			<a href='#' aria-controls='table_$class_name' data-dt-idx='2' tabindex='0' class='page-link'>{{current_page}}</a>
		</li>
		<li class='paginate_button page-item {{next_page_class}} '>
			<a href='{{next_page_url}}' aria-controls='table_$class_name' data-dt-idx='3' tabindex='0' class='page-link'>Next</a>
		</li>
		<li class='paginate_button page-item {{last_page_class}}'>
			<a href='{{last_page_url}}' aria-controls='table_$class_name' data-dt-idx='4' tabindex='0' class='page-link'>Last</a>
		</li>
	</ul>
</div>
{{/is_need_paging}}
";


		$template_text = "
<h1>$class_name list </h1>
Create <a href='$URLroot$class_name/create/'>new $class_name</a><br>

$paging_widget

<table id='table_$class_name' class='table table-bordered table-hover table-responsive table-sm'>
<thead><tr>
$header_row
</tr></thead>
<tfoot><tr>
$header_row
</tr></tfoot>
<tbody>
  {{#data}}
    <tr>
	";

		foreach($col_list as $column_name){
			if($column_name == 'id'){ //we want to scope this field as the row id...
				$template_text .= "<th scope='row'><a href='$URLroot$class_name/{{id}}/'> {{id}} </a></th>";
			}else{	
				$last_three = substr($column_name,-3);
                                        if($last_three == '_id'){
						if(isset($field_lookup[$column_name])){
							$data_type = $field_lookup[$column_name];
							//then this is linkable and should have a _DURClabel	
                                                	$template_text .= "\t\t\t<td>{{"."$column_name"."_DURClabel}} <a href='$URLroot$data_type/{{"."$column_name"."}}/'> ({{"."$column_name"."}})</a> </td>";
						}
                                        }else{
                                                //normal data no link
                                                $template_text .= "\t\t\t<td>{{"."$column_name"."}}</td>\n";
                                        }
			}
		}
		$template_text .= "
    </tr>
  {{/data}}

";

		$template_text .= "<tbody></table>\n

$paging_widget

<script type='text/javascript'>
//we need a way to check to see if the page is loaded before we call DataTable()
//but we frequently do not have JQuery yet... it could be loaded at the bottom of the page...
//so we have a pure JS alternative to the ready() function..
var DURC_checkReadyState = setInterval(() => {
  if (document.readyState === 'complete') {
    clearInterval(DURC_checkReadyState);
    // document is ready
    $('#table_$class_name').DataTable(
{
        'paging':   false,
        'info':     false
}
);
  }
}, 100);


</script>

";

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
