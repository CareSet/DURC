<?php
/*
        This is the place where the actual command is orchestrated.
        it ends up being our "main()"


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
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/css/anytime.5.2.0.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
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
</style>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
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
              <a class="nav-link" href="DURC/">DURC <span class="sr-only">(current)</span></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">

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
          </ul>
        </nav>
        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
                {{{content}}}
        </main>
      </div>
    </div>

  </body>
</html>
';

		file_put_contents($file, $trailer, FILE_APPEND | LOCK_EX);

	} 


        public static function run_generator($class_name,$database,$table,$fields,$has_many = null,$has_one = null, $belongs_to = null, $many_many = null, $many_through = null, $squash = false,$URLroot = '/DURC/',$create_table_sql){


		//we just need to add a little snippet to the route file..
		$file = MustacheMenuGenerator::getFile();


		$snippet = "
            <li class='nav-item'>
              <a class='nav-link' href='$URLroot$class_name/'>$class_name</a>
            </li>
";

		file_put_contents($file, $snippet, FILE_APPEND | LOCK_EX);

		return(true);
		

	}//end generate function








}//end class
