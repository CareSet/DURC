<?php
	require_once(__DIR__ . '/DURC.php');

	echo "
# Code Syntax Postfix Mappings

| Column Postfix | Invokes Sytax Mode  |
| -------------- | ------------------- |
";

	foreach(\CareSet\DURC\DURC::$code_language_map as $label => $mode){
		echo "|_$label"."_code | $mode |\n";
	}
