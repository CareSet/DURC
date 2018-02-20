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
	public static function _get_field_html($URLroot,$field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$input_type = DURC::mapColumnDataTypeToInputType( $data_type, $column_name );

		switch($input_type){
            case 'boolean':
                return self::_get_checkbox_field_html( $field_data );
                break;
			case 'number':
				if($is_linked_key){
					return(self::_get_select2_field_html($URLroot,$field_data));
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
	public static function _get_select2_field_html($URLroot, $field_data){

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
    	url: '$URLroot"."searchjson/$foreign_table/',
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

	
		$icon_id = $column_name . "_FontPicker";
		$today_id = $column_name . "_TodayNow";
		$colConv = $column_name ."Conv";

		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
      <input type='text' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>

<button type='button' class='btn btn-primary' id='$icon_id'>
<img src='/images/DURC/ic_today_black_24dp_1x.png'> 
</button>
<button type='button' class='btn btn-primary' id='$today_id'> Today and Now</button>

<script>
	var $colConv = new AnyTime.Converter({format: '%Y-%m-%d %T'});
  	$('#$today_id').click( function(e) {
      		$('#$column_name').val($colConv.format(new Date())).change(); } );

    	$('#$icon_id').click(
      		function(e) {
        		$('#$column_name').AnyTime_noPicker().AnyTime_picker(
				{
    					format: '%Y-%m-%d %T',
    					formatUtcOffset: '%: (%@)'
				} 
			).focus();
        	e.preventDefault();
        	} );
</script>

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

		$icon_id = $column_name . "_FontPicker";
		$today_id = $column_name . "_TodayNow";
		$colConv = $column_name ."Conv";

		$field_html = "
  <div class='form-group row'>
    <label for='$column_name' class='col-sm-2 col-form-label'>$column_name</label>
    <div class='col-sm-10'>
      <input type='text' class='form-control' id='$column_name' name='$column_name' placeholder='' value='{{"."$column_name"."}}'>

<button type='button' class='btn btn-primary' id='$icon_id'>
<img src='/images/DURC/ic_today_black_24dp_1x.png'> 
</button>
<button type='button' class='btn btn-primary' id='$today_id'> Today and Now</button>

<script>
	var $colConv = new AnyTime.Converter({format: '%Y-%m-%d'});
  	$('#$today_id').click( function(e) {
      		$('#$column_name').val($colConv.format(new Date())).change(); } );

    	$('#$icon_id').click(
      		function(e) {
        		$('#$column_name').AnyTime_noPicker().AnyTime_picker(
				{
    					format: '%Y-%m-%d',
    					formatUtcOffset: '%: (%@)'
				} 
			).focus();
        	e.preventDefault();
        	} );
</script>

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

    //returns bootstrap 4 decorated mustache template for a text field
    public static function _get_checkbox_field_html($field_data){

        $column_name = $field_data['column_name'];
        $data_type = $field_data['data_type'];
        $is_foreign_key = $field_data['is_foreign_key'];
        $is_linked_key = $field_data['is_linked_key'];
        $foreign_db = $field_data['foreign_db'];
        $foreign_table = $field_data['foreign_table'];

        $field_html = "
  <div class='form-group row'>
    <div class='col-sm-2'><label>$column_name</label></div>
    <div class='col-sm-10'>
        <div class='checkbox'>
            <input type='checkbox' id='$column_name' name='$column_name' {{"."$column_name"."_checkbox}} >
       </div> 
    </div>
  </div>
";

        return($field_html);
    }

	public static function _get_number_field_html($field_data){

	}



}
