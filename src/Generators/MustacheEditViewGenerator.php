<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;

use CareSet\DURC\DURC;

class MustacheEditViewGenerator extends \CareSet\DURC\DURCMustacheGenerator {


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



        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$has_one = null, $belongs_to = null, $many_many = null, $many_through = null, $squash = false,$URLroot = '/DURC/',$create_table_sql){

		//this should not be hard coded... but here we are..
		$model_namespace = 'App';

		$model = "\\App\\$class_name";

		$debug = false;
		if($debug){
			echo "Generating $class_name\n";
			var_export($fields);

		}
		$parent_file_names = [
			 "edit.mustache",
			];


		// Soft Delete
        $soft_delete_alert_code = null;
        $soft_delete_code = null;

        if ( self::get_possible_deleted_at( $fields ) !== false ) {

            // There is a deleted_at field, so add soft delete functionality to view
            $delete_alert_code = "<div id='delete-success-alert' class='alert alert-success' role='alert' style='display: none;'>
                You have successfully deleted this record. <a href='$URLroot$class_name/restore/{{id}}' data-id='{{id}}' data-controller='$class_name' class='do-recover btn btn-secondary'>Undo</a>
            </div>";

            $delete_code = "
{{#is_new}}
<form action='$URLroot$class_name' method='POST'>
{{/is_new}}

{{^is_new}}
<form action='$URLroot$class_name/{{id}}' method='POST'>
    <input type='hidden' name='_method' value='PUT'>
{{/is_new}}

<div class='form-group row'>
              <div class='col-sm-12 text-right'>
              <button type='button' data-id='{{id}}' data-controller='$class_name' class='do-soft-delete btn btn-outline-danger'>Delete</button>
              </div>
            </div>
</form>
";
        }else{
		//we are only supporting hard deletes
            // There is a deleted_at field, so add soft delete functionality to view
            $delete_alert_code = "<div id='delete-success-alert' class='alert alert-success' role='alert' style='display: none;'>
                You have successfully deleted this record. This was a hard delete, no going back. But you can save again if you want.. 
            </div>";

            $delete_code = "<div class='form-group row'>
              <div class='col-sm-12 text-right'>

<form action='$URLroot$class_name/{{id}}' method='POST'>
    	<input type='hidden' name='_method' value='DELETE'>
	<input type='hidden' name='_token' value='{{csrf_token}}'>

              <button type='button' data-id='{{id}}' data-controller='$class_name' class='do-soft-delete btn btn-outline-danger'>Hard Delete $class_name</button>
</form>
              </div>
            </div>";



	}

                $gen_string = DURC::get_gen_string();

		$template_text = "
{{#has_session_status}}
<div class='alert alert-info' role='alert'>
  {{session_status}}
</div>
{{/has_session_status}}

$delete_alert_code

{{#is_new}}
<form action='$URLroot$class_name' method='POST'>
{{/is_new}}

{{^is_new}}
<form action='$URLroot$class_name/{{id}}' method='POST'>
    <input type='hidden' name='_method' value='PUT'>
{{/is_new}}

	<h1>$class_name</h1>
<div class='card'>
  <div class='card-header'>
<h4> {{durc_instance_name}}</h4>
<ul>
<li> <a href='/Zermelo/DURC_$class_name/'>Return to $class_name list</a> </li>
{{^is_new}}
<li> <a href='$URLroot$class_name/create'>Create new $class_name </a> </li>
{{/is_new}}
</ul>
  </div>
<div class='card-body'>

  <fieldset>
    <legend>Edit {{durc_instance_name}}  </legend>
	<input type='hidden' name='_token' value='{{csrf_token}}'>
	";

		foreach($fields as $i => $field_data){

			$durc_eloquent_model_location = base_path() . "/app/$class_name.php";
			if(file_exists($durc_eloquent_model_location)){
				require_once($durc_eloquent_model_location);

				if(method_exists($model,'isFieldHiddenInGenericDurcEditor')){
					$is_hidden    = $model::isFieldHiddenInGenericDurcEditor($field_data['column_name']);
				}else{
					$is_hidden = false;
				}
				if(method_exists($model,'isFieldViewOnlyInGenericDurcEditor')){
					$field_data['is_view_only'] = $model::isFieldViewOnlyInGenericDurcEditor($field_data['column_name']);
				}else{
					$field_data['is_view_only'] = false;
				}
				if($field_data['is_view_only'] || $is_hidden ){
					var_export($field_data);
				}
			}else{
				$is_hidden    = false;
				$field_data['is_view_only'] = false;
			}

			if($is_hidden){
				// we do nothing here... no need to add to the form when there is nothing to add..
				//note tha hidden values will break when they are required in the underlying database...
				//and they are not already set... or set using a DB default... or allowing NULL in field... etc etc..
			}else{
				$field_html = parent::_get_field_html($URLroot,$field_data); //this is defined in ../DURCMustacheGenerator.php
				//echo $field_html;
				$template_text .= $field_html;
			}
		}

		$template_text .= "
  <div class='form-group row'>
    <div class='col-sm-8'>
      <button type='submit' class='btn btn-primary'>Save Data</button>
    </div>
  </div>
  
</fieldset>
</div></div>
</form>
<br>
  $delete_code

";


       if(!is_null($has_many) || !is_null($has_one)) {
           $template_text .= "
<br>
<div class='card'>
  <div class='card-header'>
    Relationships
  </div>
<div class='card-body'>
";
           //this section will add the tables of other-side data to the edit view for a given object.
           $has_relationships = [];
           if (!is_null($has_many)) {
               $has_relationships['Has many']= $has_many;
           }
           if ( !is_null( $has_one ) ) {
               $has_relationships['Has one'] = $has_one;
           }
           foreach ( $has_relationships as $label =>  $has_this ) {
           foreach ( $has_this as $full_relation => $relate_details ) {

               $full_relation_snake = snake_case( $full_relation ); //because this is how eloquent converts relations with eager loading..


               $prefix = $relate_details[ 'prefix' ];
               $type = $relate_details[ 'type' ];
               $from_table = $relate_details[ 'from_table' ];
               $from_db = $relate_details[ 'from_db' ];
               $from_column = $relate_details[ 'from_column' ];
               $other_columns = $relate_details[ 'other_columns' ];

               $template_text .= "
<div class='card'>
  <div class='card-header'>
    $label $full_relation_snake ( <a href='$URLroot$type/'>see all</a> )
{{^$full_relation_snake}}
(no values)
{{/$full_relation_snake}}
  </div>
<div class='card-body'>

<table id='table_$full_relation_snake' class='table table-bordered table-hover table-responsive table-sm'>
<thead>
<tr>
";

               //generates the headers to the table
               foreach ( $other_columns as $this_item ) {
                   $column_name = $this_item[ 'column_name' ];
                   $template_text .= "\t\t\t<th> $column_name </th>\n";
               }

               $template_text .= "
</tr>
</thead>
<tbody>
{{#$full_relation_snake}}
	<tr>
		{{#.}}
";

               //generates the looping rows of data using mostache tags...
               foreach ( $other_columns as $this_item ) {
                   $column_name = $this_item[ 'column_name' ];
                   if ( $this_item['is_primary_key'] ) {
                       //this is the link back to the edit view for this data item..
                       $template_text .= "\t\t\t<td><a href='$URLroot$type/{{" . "$column_name" . "}}'>{{" . "$column_name" . "}}</a></td>\n";
                   } else {
                       $last_three = substr( $column_name, -3 );
                       if ( $last_three == '_id' ) {
                           //then this is an ID and perhaps there is a _DURClabel that goes with it!!
                           $template_text .= "\t\t\t<td>{{" . "$column_name" . "_DURClabel}} ({{" . "$column_name" . "}}) </td>";
                       } else {
                           //normal data no link
                           $template_text .= "\t\t\t<td>{{" . "$column_name" . "}}</td>\n";
                       }
                   }
               }

               $template_text .= "		
		{{/.}}
	</tr>
{{/$full_relation_snake}}
</tbody>
</table>
</div></div> <!-- end $full_relation_snake card-->
<br>
";

           }//end foreach has_many
       }

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
                foreach($belongs_to as $full_relation => $relate_details){

			$full_relation_snake = snake_case($full_relation);

                        $prefix = $relate_details['prefix'];
                        $type = $relate_details['type'];
                        $to_table = $relate_details['to_table'];
                        $to_db = $relate_details['to_db'];
                        $to_column = 'id'; // the simple rule that powers the whole system
			$other_columns = $relate_details['other_columns'];


			$template_text .= "
<div class='card'>
  <div class='card-header'>
    $full_relation_snake ( <a href='$URLroot$type/'>see all</a> )
{{^$full_relation_snake}}
(no values)
{{/$full_relation_snake}}
  </div>
<div class='card-body'>
<table id='table_$full_relation_snake' class='table table-bordered table-hover table-responsive table-sm'>
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
{{#$full_relation_snake}}
	<tr>
		{{#.}}
";

			//generates the looping rows of data using mostache tags...
			foreach($other_columns as $this_item){
				$column_name = $this_item['column_name'];
				if($column_name == 'id'){
					//this is the link back to the edit view for this data item..
					$template_text .= "\t\t\t<td><a href='$URLroot$type/{{"."$column_name"."}}'>{{"."$column_name"."}}</a></td>\n";
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
{{/$full_relation_snake}}
</tbody>
</table>
</div></div> <!-- end $full_relation_snake card-->
";


		}//end foreach belongs_to
		$template_text .= "</div></div> <!--end belongs to card-->";
	}//end if null belongs_to


	$template_text .= "
<br>
    
    <script type='text/javascript'>
    // This javascript controls the null checkboxes
        $(document).ready(function() {
            
            // keep an assoc array of the last entered values
            let last_null_values = {};
            
            $('.null-checkbox').change(function(e) {

                // get the id of the element we're next to
                let id = $(this).attr('data-elem');

                // store current value, and set to null
                if ($(this).prop('checked')) {
                    last_null_values[id]= $('#'+id).val();
                    $('#'+id).val(null);
                    $('#'+id).attr('readonly', true);
                } else {
                    $('#'+id).val(last_null_values[id]);
                    $('#'+id).attr('readonly', false);
                }
            });
            
            // Trigger change on page load
            $('.null-checkbox').each(function() {
                if ($(this).prop('checked')) {
                    let id = $(this).attr('data-elem');
                    $('#'+id).attr('readonly', true);
                }
            });
        });
    </script>
";

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
