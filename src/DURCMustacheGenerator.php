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

		extract($field_data);

		$input_type = DURC::$column_type_map[strtolower($data_type)]['input_type'];

		switch($input_type){
			case 'number':
			case 'date':
			case 'text':
			case 'file':
				
				return(self::_get_text_field_html($field_data));

			break;


		}


	}

	//returns bootstrap 4 decorated mustache template for a text field
	public static function _get_text_field_html($field_data){

		extract($field_data);
	
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
