<?php

	$modes = file('./modelist.txt');

	$output = [];
	foreach($modes as $this_mode){
		$this_mode = trim($this_mode);
		$output[$this_mode] = $this_mode; //map everything to itself
	}

	var_export($output);

