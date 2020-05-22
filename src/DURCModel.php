<?php

namespace CareSet\DURC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;
use CareSet\DURC\DURC;

/*
	This is where we put all of the functions that we want all DURC models to inherit
*/
class DURCModel extends Model{

    /**
     * @var array Array mapping attribute keys to their DB types,
     * overridden in child class
     */
    static $field_type_map = [];

    /**
     * @var This is set by child class to be the autoincrement column name, if any
     */
    static $auto_increment = null;

    /**
     * Error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     *
     * @var Array
     */
    protected static $rules = array();

    /**
     * Custom messages
     *
     * @var Array
     */
    protected static $messages = array();

    /**
     * Validator instance
     *
     * @var Illuminate\Validation\Validators
     */
    protected $validator;

    /**
     * @var array
     *
     * By default, all fields are nullable, but this is overriddedn in child classes
     * Depending on what is mined from the database shcema
     */
    protected $non_nullable_fields = [];

    /**
     * DURCModel constructor.
     * @param array $attributes
     *
     * Construct the model and build the validator, which will validate our models in the save() method
     *
     * Laravel (Eloquent) has a powerful built-in validation system without creating additional
     * dependencies for DURC, so we take advantage of the built-in validator as much as possible so we
     * can catch most errors before they reach the database and display them to the user in the form
     * of error messages.
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        // Build a laravel validator. We use the dependcy app make so this can possibly be overridden
        // by dependency injection in the service provider. See:
        // https://laravel.com/docs/6.x/container#the-make-method
        $this->validator = \App::make('validator');
    }


    /**
     * @param array $options
     * @return bool
     *
     * Override the Eloquent save function to perform validation
     */
    public function save(array $options = [])
    {
        // Run the formatter, which does conversion for DB storage on some field types
        $this->formatForstorage();

        // Try the validator, if it failes, throw an exception (which can be handled in controller)
        // Validation is performed using built-in laravel validators
       if ($this->validate()) {

		return parent::save($options);
/*
		//this is where our better mysql error viewer would go...
		try {
            		return parent::save($options);
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd($e);
		}
*/
       } else {
            $messsage = "DURC could not save this ".get_class($this)." model because the data is invalid.\n\n";
            $messsage .= "Here is the data you tried to store:\n";

            foreach ($this->attributes as $key => $value) {
               $messsage .= "$key => $value\n";
            }

            $messsage .= "\nHere are the issues:\n";
            foreach ($this->getErrors()->getMessages() as $key => $messages) {
               if (isset($this->attributes[$key])) {
                   $messsage .= "$key => {$this->attributes[$key]}\n";
               } else {
                   $messsage .= "$key => ?\n";
               }
               foreach ($messages as $m) {
                   $messsage .= "    * $m\n";
               }
            }
            throw new DURCInvalidDataException($messsage);
       }
    }

    /**
     * Format all model attributes for storage. This means:
     * - Convert dates to DB format (though Laravel should do this automatically if specified in model)
     * - Convert checkbox values for booleans from 'on' to 1 or 0
     */
    public function formatForStorage()
    {
        foreach ($this->attributes as $key => $value) {
            if (isset(static::$field_type_map[$key])) {
                $this->attributes[$key] = DURC::formatForStorage($key, static::$field_type_map[$key], $value, $this);
            }
        }
    }

    /**
     * Validates current attributes against rules in our model.
     * See LaravelEloquentGenerator::_generate_validation_rule_for_field()
     * for details on what validation rules are generated.
     */
    public function validate()
    {
        $v = $this->validator->make($this->attributes, static::$rules, static::$messages);

        if ($v->passes())
        {
            return true;
        }

        $this->setErrors($v->messages());

        return false;
    }

    /**
     * Set error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
	*	This function allows us to avoid the recursive eager loading problem by allowing a controller (etc) to specify
 	*	That only one level of eager loading will occur, starting from the object-in-focus...
	* 	But to typically avoid eager loading for index lists and such...
	*/
	public function fresh_with_relations(){
		return($this->fresh($this->DURC_selfish_with));
	}

	public function isFieldNullable($field)
    {
        $nullable = true;
        // See if this field name is in the array of non-nullable fields
        if (in_array($field, $this->non_nullable_fields)) {
            $nullable = false;
        }

        return $nullable;
    }

    public function getNullableFields()
    {
        $nullable = [];
        foreach($this->default_values as $key => $value) {
            if ($this->isFieldNullable($key)) {
                $nullable[]= $key;
            }
        }

        return $nullable;
    }

    /**
     * @param $field
     * @return |null
     *
     * Get the default value of the field as mined from the database schema.
     * Default values can be a literal, an expression enclosed in parentheses or
     * current_timestamp, so we handle those cases.
     */
    public function getDefautValue($field)
    {
        $default = null;
        if (array_key_exists($field, $this->default_values)) {
            if (strtoupper($this->default_values[$field]) === 'CURRENT_TIMESTAMP') {
                // If the default is current timestamp, we have to set ourselves if field is non-nullable because
                // somthing weird is going on and the database won't set the value if the field is non-nullable,
                // and set to CURRENT_TIMESTAMP as default
                // TODO: maybe figure this out
                if (!static::isFieldNullable($field)) {
                    $default = date('Y-m-d h:i:s');
                }
            } else if (substr_compare(ltrim($this->default_values[$field]), "(", 0, 1) === 0 &&
                substr_compare(rtrim($this->default_values[$field]), ")", -1) === 0) {
                // The default value specified in a DEFAULT clause can be a literal constant or an expression.
                // With one exception, enclose expression default values within parentheses to distinguish them
                // from literal constant default values.

                // If this default value starts with a ( and ends with a ) then we know it's an expression.
                $default = null;
            } else {
                $default = $this->default_values[$field];
            }
        } else if ($field == static::$auto_increment) {
            $default = null;
        }

        return $default;
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
	<ul class='list-group'>
		<li class='list-group-item'>
	<a href='/DURC/$my_durc_name/$my_id/' target='_blank'>edit</a>
		 </li>
";
		foreach($urls_to_add as $label => $url){
			$card_body .= "<li class='list-group-item'><a target='_blank' href='$url'>$label</a></li>";
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
