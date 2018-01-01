<?php
namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use CareSet\DURC\DURC;
/*
	All of the mostache generators will need shared functions.
	Ways to generate basic html components, using bootstrap
	in repeatable ways.
	This class hosts those shared functions... 

*/
class DURCMustacheGenerator{

	//basically a router for the other functions 
	public static function _get_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$input_type = DURC::$column_type_map[strtolower($data_type)]['input_type'];

		switch($input_type){
			case 'number':
				if($is_linked_key){
					return(self::_get_select2_field_html($field_data));
				}else{
					return(self::_get_text_field_html($field_data));
				}
			break;

			case 'date':
				return(self::_get_date_field_html($field_data));
			break;
			case 'datetime':
				return(self::_get_datetime_field_html($field_data));
			break;
			case 'time':
			case 'text':
			case 'file':
				
				return(self::_get_text_field_html($field_data));
			break;


		}


	}

	//this returns both the html and the javascript required to kick start an AJAX powered select2 instance.
	public static function _get_select2_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
	Current id value: {{"."$column_name"."}} (see below for lookup value)<br>
	<select class='select2_$column_name form-control' id='$column_name' name='$column_name' form-control'>
	<option value='{{"."$column_name"."}}' selected='selected'>{{"."$column_name"."}}</option>
	</select>
    </div>
  </div>

<script type='text/javascript'>

$('.select2_$column_name').select2({
  ajax: {
    	url: '/DURCsearchjson/$foreign_table/',
    	dataType: 'json'
  }
});


</script>

";

		return($field_html);

	}

	//returns bootstrap 4 decorated mustache template for a text field
	public static function _get_datetime_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

	
		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
	Current Value: {{"."$column_name"."}}<br>
      <input type='text' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>
    </div>
  </div>
";

		return($field_html);
	}
	//returns bootstrap 4 decorated mustache template for a text field
	public static function _get_date_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

	
		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
	Current Value: {{"."$column_name"."}}
      <input type='date' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>
    </div>
  </div>
";

		return($field_html);
	}

	//returns bootstrap 4 decorated mustache template for a text field
	public static function _get_text_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

	
		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
      <input type='text' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>
    </div>
  </div>
";

		return($field_html);
	}

	public static function _get_number_field_html($field_data){

	}



}
