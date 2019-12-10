<?php

namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/*
	This is where we put all of the functions that we want all DURC models to inherit
*/
class DURCModel extends Model{

	/**
	*	This function allows us to avoid the recursive eager loading problem by allowing a controller (etc) to specify
 	*	That only one level of eager loading will occur, starting from the object-in-focus...
	* 	But to typically avoid eager loading for index lists and such...
	*/
	public function fresh_with_relations(){
		return($this->fresh($this->DURC_selfish_with));
	}

	//overriding this can change how a card_body is calculated in the json for single data option..
	public function getCardBody(){

		
		$my_class = get_called_class();
		$name_field = $my_class::getNameField();

		$my_name = $this->$name_field;
		$my_durc_name = (new \ReflectionClass($this))->getShortName();	
		$my_id = $this->id;

		//lets find all of the urls..
		$urls_to_add = [];
		foreach($this->toArray() as $field => $value){
			if(strpos($field,'_url') !== false){
				//then this is a url..
				$urls_to_add[$field] = $value;
			}
		}
	
		$card_body = "
  <div class='card-body'>
<p class='card-text'>
$my_name
	<ul>
		<li>
	<a href='/DURC/$my_durc_name/$my_id/' target='_blank'>edit</a>
		 </li>
";
		foreach($urls_to_add as $label => $url){
			$card_body .= "<li><a target='_blank' href='$url'>$label</a></li>";
		}
$card_body .= "
	</ul>
</p>
  </div>
";

		return($card_body);

	}

	//return the field that is the best field for using to name this object for the purposes of drop-downs and select widgets etc etc..
	public static function getNameField(){
	
		$my_class = get_called_class();
		//the field 'select_name' is the top of the priority list if it exists..
		if(isset($my_class::$field_type_map['select_name'])){
			return('select_name');
		}
	
		$hell_no = [
			'id',
			'password',
			'passwd',
			'pwd',
			'updated_at',
			'created_at',
			'updated_dt',
			'created_dt',
			'updated_date',
			'created_date',
		];


		
		$label_field_stubs = [
			'name',
			'label',
			'note',
			];

		foreach($label_field_stubs as $this_stub){

			//first use a field with 'name' in the string somewhere...
			foreach($my_class::$field_type_map as $field => $field_type){
				$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];	
				if(strpos(strtolower($field),$this_stub) !== false && $input_type == 'text'){
					//then this is the first 'name' field with a varchar type. This is the winner.
					return($field);
				}
			}
		}

		//if we get here there are no fields called 'name'
		//so lets do any varchar field type...
		foreach($my_class::$field_type_map as $field => $field_type){	
			$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];
			if($input_type == 'text'){
				//then this is the first text field on the 
				return($field);
			}
		}
	
		//lets return an integer field, as long as its not an id...
		foreach($my_class::$field_type_map as $field => $field_type){	
			$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];
			if($input_type == 'number' && !in_array($field,$hell_no)){
				//then this is the first text field on the 
				return($field);
			}
		}
		
		$date_type = [
			'date',
			'datetime',
			'time',
			];

		//lets return an datetime field, as long as its not an created_at or updated_at...
		foreach($my_class::$field_type_map as $field => $field_type){	
			$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];
			if(in_array($input_type,$date_type)  && !in_array($field,$hell_no)){
				//then this is the first text field on the 
				return($field);
			}
		}
		//well if nothing else, we just return the id as the right answer...

		if(isset($my_class::$field_type_map['id'])){
			return('id');
		}else{
			//if we get here we are pretty much screwed.
			return(false);
		}
	}


	//get the value of the field that should be the name of this object..
	public function _getBestName(){

		$result = self::getNameField();

		if($result){
			return($result);
		}else{
			die("DURC Model for $this_class_name could not get any reasonable field for a name.. check your $db_table table..");
		}
	}

	//static function to get the right image field
	//return null if we cannot find anything.
	public static function getImgField(){

		//return('bob');

		$my_class = get_called_class();
		//the field 'select_img_url' is the top of the priority list if it exists..
		if(isset($my_class::$field_type_map['select_img_url'])){
			return('select_img_url');
		}
		
		$hell_no = [
			'id',
			'password',
			'passwd',
			'pwd',
			'updated_at',
			'created_at',
			'updated_dt',
			'created_dt',
			'updated_date',
			'created_date',
		];

		$my_class = get_called_class();

		
		$img_field_stubs = [
			'img_uri',
			'img_url',
			'image_uri',
			'image_url',
			];

		//simply return the first matching field.. 
		foreach($img_field_stubs as $this_stub){

			//first use a field with 'img_url' (etc) in the string somewhere...
			foreach($my_class::$field_type_map as $field => $field_type){
				$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];	
				if(strpos(strtolower($field),$this_stub) !== false && $input_type == 'text'){
					//then this is the first 'img_url' (etc) field with a varchar type. This is the winner.
					return($field);
				}
			}
		}

		//then we do not have any img fields...
		//lets return null  at  the default
		return(null);	
	

	}

	//get the value of the fields that should allow for searches on this object
	public function _getBestSearchFields(){

		$result = self::getSearchFields();

		if($result){
			return($result);
		}else{
			die("DURC Model for $this_class_name could not get any reasonable fields for searching.. check your $db_table table..");
		}
	}
	

	//static function to get good search fields
	public static function getSearchFields(){
		
		$hell_no = [
			'id',
			'password',
			'passwd',
			'pwd',
			'updated_at',
			'created_at',
			'updated_dt',
			'created_dt',
			'updated_date',
			'created_date',
		];

		$my_class = get_called_class();

		
		$label_field_stubs = [
			'name',
			'label',
			'note',
			];

		$return_me = [];
		foreach($label_field_stubs as $this_stub){

			//first use a field with 'name' in the string somewhere...
			foreach($my_class::$field_type_map as $field => $field_type){
				$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];	
				if(strpos(strtolower($field),$this_stub) !== false && $input_type == 'text'){
					//then this is the first 'name' field with a varchar type. This is the winner.
					$return_me[] = $field;
				}
			}
		}

		if(count($return_me) > 0){
			return($return_me);
		}else{
			
			//if we get here there are no fields called 'name'
			//so lets do any varchar field type...
			foreach($my_class::$field_type_map as $field => $field_type){	
				$input_type = DURC::$column_type_map[strtolower($field_type)]['input_type'];
				if($input_type == 'text'){
					//then this is the first text field on the 
					$return_me[] = $field;
				}
			}
			if(count($return_me) > 0){
				return($return_me);
			}else{
				//damn. If we get here, there is literally no text field to search.
				//lets just search for the id.
				//TODO perhaps we should return the number fields...
				return(['id']);
			}
		}
		

	}
}
