<?php

namespace CareSet\DURC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/*
	A layer between DURC Controllers and Laravel Controllers
*/
class DURCController extends Controller{

	public function _getMainTemplateName(){
		//hardcoded for now.. lets get smarter!!
		return('DURC.durc_html');
	}

}
