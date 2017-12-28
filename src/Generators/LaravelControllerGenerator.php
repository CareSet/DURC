<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


*/
namespace CareSet\DURC\Generators;


class LaravelControllerGenerator extends \CareSet\DURC\DURCGenerator {



        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$belongs_to = null, $many_many = null, $many_through = null, $squash = false){

		//possible created_at field names... 
		//in reverse order of priority. we pick the last one.
		$valid_created_at_fields = [
			'creation_date',
			'created_at_date',
			'createdAt', //Sequlize style
			'created_at',
			];

		//possible updated_at field names... 
		//in reverse order of priority. we pick the last one.
                $valid_updated_at_fields = [
                        'update_date',
                        'update_at_date',
                        'updated_date',
                        'updated_at_date',
			'updated_at',
			'updatedAt', //Sequlize style
                        'update_at',
                        ];


		$updated_at_code = null;	
		$created_at_code = null;	
		
		foreach($valid_created_at_fields as $this_possible_created_at){
			if(isset($fields[$this_possible_created_at])){
				//we found our created_at variable..
				$created_at_code = "const CREATED_AT = '$this_possible_created_at'"; 	
			}
		}	
		foreach($valid_updated_at_fields as $this_possible_updated_at){
			if(isset($fields[$this_possible_updated_at])){
				//we found our created_at variable..
				$updated_at_code = "const UPDATED_AT = '$this_possible_updated_at'"; 	
			}
		}	

		//we should do more logic to support dateformat laravel here...


		if(is_null($updated_at_code) || is_null($created_at_code)){ //we must have both...
			$timestamp_code = "public \$timestamps = false;";
			$updated_at_code = '//DURC NOTE: did not find updated_at and created_at fields for this model' ."\n";
			$created_at_code = '';
		}


		$parent_class_name = "DURC_$class_name";

		$parent_file_name = "$parent_class_name"."Controller.php";	
		$child_file_name = "$class_name"."Controller.php";	

	$field_update_from_request = '';
	foreach($fields as $field_data){
			$this_field = $field_data['column_name'];
			$data_type = $field_data['data_type'];
			$field_update_from_request .= "		\$tmp_$class_name"."->$this_field = \$request->$this_field; \n";
	}	
	$field_update_from_request .= "		\$tmp_$class_name"."->save();\n";


		$parent_class_text = "<?php

namespace App\DURC\Controllers;

use App\\$class_name;
use Illuminate\Http\Request;
use CareSet\DURC\DURCController;
use Illuminate\Support\Facades\View;


class DURC_$class_name"."Controller extends DURCController
{


	public \$view_data = [];

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request \$request){
	\$main_template_name = \$this->_getMainTemplateName();

	\$these = $class_name::paginate(100);

        foreach(\$these->toArray() as \$key => \$value){ //add the contents of the obj to the the view 
                \$this->view_data[\$key] = \$value;
        }

	//helps with logic-less templating...
	if(\$this->view_data['first_page_url'] == \$this->view_data['last_page_url']){
		\$this->view_data['is_need_paging'] = false;
	}else{
		\$this->view_data['is_need_paging'] = true;
	}

	if(\$this->view_data['current_page'] == 1){
		\$this->view_data['first_page_class'] = 'disabled';
		\$this->view_data['prev_page_class'] = 'disabled';
	}else{
		\$this->view_data['first_page_class'] = '';
		\$this->view_data['prev_page_class'] = '';
	}


	if(\$this->view_data['current_page'] == \$this->view_data['last_page']){
		\$this->view_data['next_page_class'] = 'disabled';
		\$this->view_data['last_page_class'] = 'disabled';
	}else{
		\$this->view_data['next_page_class'] = '';
		\$this->view_data['last_page_class'] = '';
	}


	if(\$request->has('debug')){
		var_export(\$this->view_data);
		exit();
	}
	\$durc_template_results = view('DURC.$class_name.index',\$this->view_data);        
	return view(\$main_template_name,['content' => \$durc_template_results]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
	// but really, we are just going to edit a new object..
	\$new_instance = new $class_name();
	\$is_new = true;
	return \$this->edit(\$new_instance,\$is_new);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
    */ 
    public function store(Request \$request){

	\$myNew$class_name = new $class_name();

	//the games we play to easily auto-generate code..
	\$tmp_$class_name = \$myNew$class_name;
	$field_update_from_request

	\$new_id = \$myNew$class_name"."->id;

	return redirect(\"/DURC/$class_name/\$new_id\")->with('status', 'Data Saved!');
    }//end store function

    /**
     * Display the specified resource.
     * @param  \App\\$$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function show($class_name \$$class_name){
	return(\$this->edit(\$$class_name));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function edit($class_name \$$class_name, \$is_new = false){

	\$main_template_name = \$this->_getMainTemplateName();

	//do we have a status message in the session? The view needs it...
	\$this->view_data['session_status'] = session('status',false);
	if(\$this->view_data['session_status']){
		\$this->view_data['has_session_status'] = true;
	}else{
		\$this->view_data['has_session_status'] = false;
	}

	\$this->view_data['csrf_token'] = csrf_token();

	if(!\$is_new){	//we will not have old data if this is a new object

		//put the contents into the view...
		foreach(\$$class_name"."->toArray() as \$key => \$value){
			\$this->view_data[\$key] = \$value;
		}

		//what is this object called?
		\$this->view_data['durc_instance_name'] = \$$class_name"."->_getBestName();
		\$this->view_data['is_new'] = false;
	}else{
		\$this->view_data['is_new'] = true;
	}
	

	\$durc_template_results = view('DURC.$class_name.edit',\$this->view_data);        
	return view(\$main_template_name,['content' => \$durc_template_results]);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, $class_name \$$class_name){

	\$tmp_$class_name = \$$class_name;
	$field_update_from_request

	\$id = \$$class_name"."->id;

	return redirect(\"/DURC/$class_name/\$id\")->with('status', 'Data Saved!');
        
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function destroy($class_name \$$class_name){
	\$main_template_name = \$this->_getMainTemplateName();
	\$durc_template_results = view('DURC.$class_name.destroy');        
	return view(\$main_template_name,['content' => \$durc_template_results]);
    }
}
";

		

		$child_class_text = "<?php

namespace App\Http\Controllers;

use App\\$class_name;
use App\DURC\Controllers\DURC_$class_name"."Controller;
use Illuminate\Http\Request;


class $class_name"."Controller extends DURC_$class_name"."Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request \$request){
        // enter your stuff here if you want...

	//anything you put into \$this->view_data will be available in the view...
	//\$this->view_data['how_cool_is_fred'] = 'very'
	//will mean that you can use {{how_cool_is_fred}} etc etc..
	//remember to look in /resources/views/DURC
	//to find the DURC generated views. Once you override those views..
	//DURC will not overwrite them anymore... same thing with this file.. you can change it and it will not
	//be overwritten by subsequent files...

	return(parent::index(\$request));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){
        // enter your stuff here if you want...
	return(parent::create());
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request){
        // enter your stuff here if you want...
	return(parent::store(\$request));
    }

    /**
     * Display the specified resource.
     * @param  \App\\$$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function show($class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::show(\$$class_name));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function edit($class_name \$$class_name, \$is_new = false){
        // enter your stuff here if you want...
	return(parent::edit(\$$class_name));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, $class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::update(\$request,\$$class_name));
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\\$class_name  \$$class_name
     * @return \Illuminate\Http\Response
     */
    public function destroy($class_name \$$class_name){
        // enter your stuff here if you want...
	return(parent::destroy(\$$class_name));
    }

}
";

		$app_path = base_path() . '/app/Http/Controllers/'; //we put the editable file in the main app directory where all of the others live..
		$durc_app_path = base_path() . '/app/DURC/Controllers/'; //we put the auto-genertated parent classes in a directory above that..

		if(!is_dir($durc_app_path)){
			if (!mkdir($durc_app_path, 0777, true)) {
    				die("DURC needs to create the $durc_app_path directory... but it could not.. what if it already existed? What then?");
			}
		}

		$child_file = $app_path.$child_file_name;
		$parent_file = $durc_app_path.$parent_file_name;

		if(!file_exists($child_file) || $squash){
			//we will only create this file the first time...
			//so getting here means that it does not exist, this is the first creation pass.
			file_put_contents($child_file,$child_class_text);
		}

		//but we always overwrite the parent class with the auto-generated stuff..
		file_put_contents($parent_file,$parent_class_text);

		return(true);


	}//end generate function







}//end class