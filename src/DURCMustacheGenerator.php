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
class DURCMustacheGenerator extends DURCGenerator {

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

	public static function run_generator($data_for_gen){
        //does nothing need to comply with abstract class

	}

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


			case 'markdown':
				return(self::_get_markdown_field_html($field_data));
			break;

			case 'code':
				return(self::_get_code_field_html($field_data));
			break;

		}


	}

    protected static function _get_null_checkbox_elem($field_data)
    {
        $column_name = $field_data['column_name'];

        // This is the checkbox element
        $html = "<div class='col-sm-1'>
                    <input class='form-check-input null-checkbox' type='checkbox' data-elem='{$column_name}' name='{$column_name}Null' id='{$column_name}Null' value='{$column_name}Null' {{{$column_name}_checked}}>
                    <label class='form-check-label' for='{$column_name}Null'>null</label>
                </div>";

        return $html;
    }

	//this returns both the html and the javascript needed to kick start an AJAX powered select2 instance.
	public static function _get_select2_field_html($URLroot, $field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$maybe_disabled_html = ' disabled ';
		}else{
			$maybe_disabled_html = '';
		}

		$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
	Current id value: {{"."$column_name"."}} (see below for lookup value)<br>
	<select class='select2_$column_name form-control' id='$column_name' name='$column_name' $maybe_disabled_html >
	<option value='{{"."$column_name"."}}' selected='selected'><!-- Replaced Dynamically with Text from related model --></option>
	</select>
    </div>
  </div>

<script type='text/javascript'>
\$(document).ready(function () {
    // Show the loader/spinner
    add_to_loading_queue('{$column_name}');
    
    // Get the option element that is currently selected and it's foreign ID
    const element = $('#{$column_name} option:selected');
    const {$foreign_table}Id = element.val();
    let {$foreign_table}IdHasError = false;
    
    // Use the json API to get the text of the selected element. 
    // We do this dynamically so we don't have to figure out in the controller
    // what elements are select2 elements, and populate from related models
    \$.ajax({
        type: 'GET',
        url: '{$URLroot}json/{$foreign_table}/' + {$foreign_table}Id,
    })
    .done(function(data, textStatus, jqXHR) {
        // data.text has the same text string (formed from concatinating search fields) as
        // the elements in the select list
        element.html(data.text);
    })
    .fail(function(xhr, status, error) {
        // Display the error text
        element.html(JSON.parse(xhr.responseText));
        {$foreign_table}IdHasError = true;
    })
    .always(function (data) {
        // Create the select2 dropdown    
        $('.select2_$column_name').select2({
          width: '100%',   
          ajax: {
            url: '$URLroot" . "searchjson/$foreign_table',
            dataType: 'json',
            data: function (params) {
                  var query = {
                      q: params.term,
                      page: params.page || 1
                  }
        
                  // Query parameters will be ?search=[term]&page=[page]
                  return query;
              }
          }
        })
        .change(function() {
            // When we change the value, remove the danger class because we assume you pick a valid option.
            $('#select2-{$column_name}-container').removeClass('text-danger');
        });
        
        // Hide the loader/spinner after the select2 has been built
        remove_from_loading_queue('{$column_name}');
        if ({$foreign_table}IdHasError === true) {
            $('#select2-{$column_name}-container').addClass('text-danger');
        }
    });
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

		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$maybe_readonly_html = ' readonly ';
		}else{
			$maybe_readonly_html = '';
		}

        // This is the mustache template for checking if there are validation errors for this field
        $is_invalid = "{{#errors.$column_name.has_errors}}is-invalid{{/errors.$column_name.has_errors}}";

        $nullable_class = self::_is_nullable($field_data) ? 'nullable' : '';

        // Get our default value, if there is one, so we can put it in the placeholder,
        $default_value = self::_get_default_value($field_data);

		$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
      <input type='text' class='form-control $nullable_class $is_invalid' id='$column_name' name='$column_name' placeholder='$default_value' value='{{"."$column_name"."}}' $maybe_readonly_html>

<button type='button' class='btn btn-primary' id='$icon_id'>
<img src='/css/ic_today_black_24dp_1x.png'> 
</button>
<button type='button' class='btn btn-primary' id='$today_id'> Today and Now</button>
<div class='invalid-feedback'>
  <ul>
  {{#errors.$column_name.messages}}<li>{{.}}</li>{{/errors.$column_name.messages}}
  </ul>
</div>";

    $field_html.= "<script>
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

    </div>";
        if (self::_is_nullable($field_data)) {
            // If we have a nullable field, add the null checkbox
            $field_html .= self::_get_null_checkbox_elem($field_data);
        }
  $field_html.= "</div>";

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


		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$maybe_readonly_html = ' readonly ';
		}else{
			$maybe_readonly_html = '';
		}

        // This is the mustache template for checking if there are validation errors for this field
        $is_invalid = "{{#errors.$column_name.has_errors}}is-invalid{{/errors.$column_name.has_errors}}";

        $nullable_class = self::_is_nullable($field_data) ? 'nullable' : '';

        // Get our default value, if there is one, so we can put it in the placeholder,
        $default_value = self::_get_default_value($field_data);

		$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
      <input type='text' class='form-control $nullable_class $is_invalid' id='$column_name' name='$column_name' placeholder='$default_value' value='{{"."$column_name"."}}' $maybe_readonly_html>
        
    <button type='button' class='btn btn-primary' id='$icon_id'>
    <img src='/css/ic_today_black_24dp_1x.png'> 
    </button>
    <button type='button' class='btn btn-primary' id='$today_id'> Today </button>
    <div class='invalid-feedback'>
      <ul>
      {{#errors.$column_name.messages}}<li>{{.}}</li>{{/errors.$column_name.messages}}
      </ul>
   </div>
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

    </div>";
        if (self::_is_nullable($field_data)) {
            // If we have a nullable field, add the null checkbox
            $field_html .= self::_get_null_checkbox_elem($field_data);
        }
  $field_html .= "</div>";

		return($field_html);

	}

	//returns bootstrap 4 decorated mustache template for a code field powered by codemirror
	public static function _get_code_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$is_found_code_mode = false;
		$code_mode_cdn_html = '';
		$right_code_mode = '';
		$matching_code_mode = '';
	        foreach(DURC::$code_language_map as $this_code_stub => $this_code_mode){

              		$this_code_string = "_$this_code_stub"."_code";

             		$doesItEndWithCode = substr_compare( $column_name, $this_code_string , -strlen( $this_code_string ) ) === 0;
                      	if($doesItEndWithCode){
				$is_found_code_mode = true;
				$code_mode_cdn_html = "<script src='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.39.0/mode/$this_code_mode/$this_code_mode.js'></script>";
				$matching_code_mode = $this_code_mode;
				$right_code_mode = ", mode: '$matching_code_mode'";
                        }

                }

		if($matching_code_mode == 'sql'){
			$right_code_mode = ", mode: 'text/x-sql'"; //do we get better results passing in a specific mime type? yes we do.
		}


		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
	<pre>>{{"."$column_name"."}}</pre>
    </div>
  </div>	";


		}else{

			$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
	<textarea style='height: auto;'  id='$column_name' name='$column_name' >{{"."$column_name"."}}

</textarea>
    </div>
  </div>
	$code_mode_cdn_html
<script>

      var editor_$column_name = CodeMirror.fromTextArea(document.getElementById('$column_name'), {
        lineNumbers: true,
	viewportMargin: Infinity,
	theme: 'lesser-dark' 
	$right_code_mode
      });

</script>
";
		}


		return($field_html);
	}


	//returns bootstrap 4 decorated mustache template for a markdown field
	public static function _get_markdown_field_html($field_data){

		$column_name = $field_data['column_name'];
		$data_type = $field_data['data_type'];
		$is_foreign_key = $field_data['is_foreign_key'];
		$is_linked_key = $field_data['is_linked_key'];
		$foreign_db = $field_data['foreign_db'];
		$foreign_table = $field_data['foreign_table'];

		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			//TODO make a simple JS markdown function work here...

			$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
	<pre>{{"."$column_name"."}}</pre>
    </div>
  </div>
";

		}else{

			$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
	<textarea id='$column_name' name='$column_name'  >{{"."$column_name"."}}</textarea>
    </div>
  </div>
<script>
	new SimpleMDE({
		element: document.getElementById('$column_name'),
		spellChecker: true,
	});
</script>
";
		}

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

        // Get our default value, if there is one, so we can put it in the placeholder,
        $default_value = self::_get_default_value($field_data);

        // This is the mustache template for checking if there are validation errors for this field
        $is_invalid = "{{#errors.$column_name.has_errors}}is-invalid{{/errors.$column_name.has_errors}}";

		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$maybe_readonly_html = ' readonly ';
		}else{
			$maybe_readonly_html = '';
		}

		$nullable_class = self::_is_nullable($field_data) ? 'nullable' : '';

		$field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <label for='$column_name' class='col-sm-4 col-form-label'>$column_name</label>
    <div class='col-sm-7'>
      <input type='text' class='form-control $nullable_class $is_invalid' id='$column_name' name='$column_name' placeholder='$default_value' value='{{"."$column_name"."}}' $maybe_readonly_html>
      <div class='invalid-feedback'>
          <ul>
          {{#errors.$column_name.messages}}<li>{{.}}</li>{{/errors.$column_name.messages}}
          </ul>
       </div>
    </div>";
		if (self::_is_nullable($field_data)) {
		    // If we have a nullable field, add the null checkbox
            $field_html .= self::_get_null_checkbox_elem($field_data);
		}


        $field_html .="</div>";

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
        // This is the mustache template for checking if there are validation errors for this field
        // A checkbox field shoule never be "invalid" but just in case...
        $is_invalid = "{{#errors.$column_name.has_errors}}is-invalid{{/errors.$column_name.has_errors}}";

		$is_view_only = $field_data['is_view_only'];

		if($is_view_only){
			$maybe_readonly_html = ' readonly ';
		}else{
			$maybe_readonly_html = '';
		}

        $field_html = "
  <div class='form-group row {{"."$column_name"."_row_class}}'>
    <div class='col-sm-4'><label>$column_name</label></div>
    <div class='col-sm-7'>
        <div class='checkbox'>
            <input type='checkbox' id='$column_name' class='$is_invalid'name='$column_name' {{"."$column_name"."}} $maybe_readonly_html >
            <div class='invalid-feedback'>
                <ul>
                  {{#errors.$column_name.messages}}<li>{{.}}</li>{{/errors.$column_name.messages}}
                </ul>
            </div>
       </div> 
    </div>
  </div>
";

        return($field_html);
    }

	public static function _get_number_field_html($field_data){

	}



}
