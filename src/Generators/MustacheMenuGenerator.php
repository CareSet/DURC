<?php
/*
	This generateds the static mustache menu for each file..

*/
namespace CareSet\DURC\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use CareSet\DURC\DURC;

class MustacheMenuGenerator extends \CareSet\DURC\DURCGenerator {


	//so that we target the same file the whole time..
	public static function getFile(){
		$file_name = base_path()."/resources/views/DURC/durc_html.mustache";
		return($file_name);
	}

	public static function start(
						$db_config,
						$squash,
						$URLroot
		){


		$file = MustacheMenuGenerator::getFile();

		//the start of our menu html...
		$header = '<!doctype html>
<html lang="en">
  <head>
    <!-- Generated from DURC/src/Generators/MustacheMenuGenerator.php  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>DURC Dashboard</title>

    <!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/css/anytime.5.2.0.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link rel="stylesheet" href="/css/simplemde.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.39.0/codemirror.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.39.0/theme/lesser-dark.css">

<style>
body {
  padding-top: 3.5rem;
}
h1 {
  padding-bottom: 9px;
  margin-bottom: 20px;
  border-bottom: 1px solid #eee;
}
.sidebar {
  position: fixed;
  top: 51px;
  bottom: 0;
  left: 0;
  z-index: 1000;
  padding: 20px 0;
  overflow-x: hidden;
  overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
  border-right: 1px solid #eee;
}
.sidebar .nav {
  margin-bottom: 20px;
}
.sidebar .nav-item {
  width: 100%;
}
.sidebar .nav-item + .nav-item {
  margin-left: 0;
}
.sidebar .nav-link {
  border-radius: 0;
}
.placeholders {
  padding-bottom: 3rem;
}
.placeholder img {
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
}
.AnyTime-pkr { z-index: 9999 }

/* 
    For loading Select2 and other possible things that will require time
    to render
 */
.loader {
    margin: auto;
    text-align: center;
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #007bff; /* Blue Bootstrap Primary color */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/*
    The select2 element needs some extra CSS to match the Bootstrap CSS
 */
.select2-selection__rendered {
    line-height: 36px !important;
}
.select2-container .select2-selection--single {
    height: 38px !important;
}
.select2-selection__arrow {
    height: 38px !important;
}
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="/js/anytime.5.2.0.js"></script>
<script src="/js/simplemde.min.js"></script>
<script src="/js/form.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.39.0/codemirror.js"></script>
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">Main</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/Zermelo/DURC/">DURC <span class="sr-only">(current)</span></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">

';


	file_put_contents($file,$header);

	}

	public static function finish(
						$db_config,
						$squash,
						$URLroot
		){

		$file = MustacheMenuGenerator::getFile();


		$trailer = '
	<div class="col-md-1"></div>
        <main role="main" class="ml-sm-auto col-md-11 pt-3">
                {{{content}}}
        </main>
      </div>
    </div>
    
    <div id="loader" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="loader"></div>
        </div>
    </div>

  </body>
</html>
';

		file_put_contents($file, $trailer, FILE_APPEND | LOCK_EX);

	}


/*
        This accepts the data for each table in the database that DURC is aware of..
        and generates a Mustache Menu
*/
        public static function run_generator($data_for_gen){

                $data_for_gen = self::_check_arguments($data_for_gen); //ensure reasonable defaults...
                extract($data_for_gen); //everything goes into the local scope... this includes all settings from the config json file...


		//we just need to add a little snippet to the route file..
		$file = MustacheMenuGenerator::getFile();

/*
		$snippet = "
            <li class='nav-item'>
              <a class='nav-link' href='$URLroot$class_name/'>$class_name</a>
            </li>
";
*/
		$snippet = ''; //ads nothing..but perhaps some day...

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);


	}//end generate function








}//end class
