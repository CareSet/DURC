<?php

namespace CareSet\DURC;

class Signature{


	public static $is_debug = true;

/*
	This function accepts an unsigned php file as a single string
	And returns a signed version of the file...

*/
	public static function sign_phpfile_string($phpfile_string){


		$signature_to_add = self::calculate_signature_from_phpfile_string($phpfile_string);

		$phpfile_by_line = explode("\n",$phpfile_string);

		$has_seen_namespace = false;
		while( ! $has_seen_namespace){
			$this_line = array_shift($phpfile_by_line);
			if(strpos('namespace ',$this_line) !== false){ //we are looking go the line like 'namespace App;' or 'namespace ThatAwesomeNamespace;' or whatever.. 
				$has_seen_namespace = true; //lets not take any more lines from $phpfile_by_line;
				$namespace_line = $this_line;
			}
		}

		$php_file_after_namespace = implode("\n",$phpfile_by_line); //we want to ignore the <?php ... up to the namespace part...

		$signed_php_file = "<?php
/*
Note: because this file was signed, everyting orignally placed before the namespace line has been replaced... with this comment ;)
FILE_SIGNATURE=$signature_to_add
*/
$namespace_line
$php_file_aftter_namespace
";

		return($signed_php_file);
		
	} 

/*
	Gets the "signature" of a php file. 
	Which is just an md5 of everything after the first "namespace" command at the top of the file..
	This allows us to put comments before the namesspace that will not be considered in the md5 math..
	Which is where we put the signature... This allows us to add the signature to the file... without changing the signature of the file
	This allows us to use the files themselves as a database of whether they have changed...
	Accepts the entire contents of a the php file as a string as an argument
	returns an md5 signature. 
*/
	public static function calculate_signature_from_phpfile_string($phpfile_string){
		$phpfile_by_line = explode("\n",$phpfile_string);

		$has_seen_namespace = false;
		while( ! $has_seen_namespace){
			$this_line = array_shift($phpfile_by_line);
			if(strlen($this_line) > 0){
				if(strpos($this_line,'namespace ') !== false){ //we are looking go the line like 'namespace App;' or 'namespace ThatAwesomeNamespace;' or whatever.. 
					$has_seen_namespace = true; //lets not take any more lines from $phpfile_by_line;
				}
			}
		}

		$php_file_to_sign = implode("\n",$phpfile_by_line); //we want to ignore the <?php ... up to the namespace part...
	
		echo "\n\n#####\n$php_file_to_sign\n####\n";


		$signature = md5($php_file_to_sign);

		return($signature);
	}	


	public static function has_signed_file_changed($signed_phpfile_string){

		$recalculated_signature = self::calculate_signature_from_phpfile_string($signed_phpfile_string);
		$in_file_signature = self::get_signature_from_signed_phpfile_string($signed_phpfile_string);

		if(self::$is_debug){
			echo "Comparing in_file_signature of $in_file_signature to the calculated signature of $recalculated_signature\n";
		}

		if($in_file_signature == $recalculated_signature){
			return(true);
		}else{
			return(false);
		}

	}


/*
	Given a signed phpfile..
	extract the signature and return it..
*/
	public static function get_signature_from_signed_phpfile_string($phpfile_string){
	
		$phpfile_by_line = explode("\n",$phpfile_string);

		$is_done_looking = false;
		while( ! $is_done_looking){
			$this_line = array_shift($phpfile_by_line);
			if(strpos('FILE_SIGNATURE=',$this_line) !== false){ //we are looking go the line like 'namespace App;' or 'namespace ThatAwesomeNamespace;' or whatever.. 
				$is_done_looking = true; //because we found the signature
				$exploded  = explode('=',trim($this_line));
				if(isset($exploded[1])){
					$possible_signature = $exploded[1];
					if(strlen($possible_signature) == 32){
						//then this is an md5, lets return it..
						$return_me = $possible_signature;
					}
				}else{
					$return_me = false;
				}
			}
			if(strpos('namespace ',$this_line) !== false){ //we are looking go the line like 'namespace App;' or 'namespace ThatAwesomeNamespace;' or whatever.. 
				$is_done_looking = true; //because there is no signature to find..
				$return_me = false;
			}
		}

		return($return_me);

	}

	


}//end class
