<?php
	require_once(__DIR__ . '/DURC.php');

	echo "
# Code Syntax Postfix Mappings

Ending a column name that DURC processes with the following stub will cause a [CodeMirror](http://codemirror.net/)
interface to be used to edit that columns data. 

You must choose which syntax to the editor should use by using one of the followihg specific postfix strings

| Column Postfix | Invokes Sytax Mode  |
| -------------- | ------------------- |
";

	foreach(\CareSet\DURC\DURC::$code_language_map as $label => $mode){
		echo "|_$label"."_code | $mode |\n";
	}
