<?php

/*
 * 
 * Hi, this example is entend to show the basic functions of X7CLOUD FRAMEWORK.
 * 
 * In this example:
 * 
 * == OUTPUT DATA ==
 * - How to get the API of your custom x7cloud webservice
 * - How to output simple string in plain 
 * - How to output simple string in json 
 * - How to output array in json 
 * - How to output array in xml
 * - How to output MP3
 * - How to output IMAGE
 * - How to manipulate headers response
 * == INPUT DATA ==
 * - How to get data in POST
 * - How to get data in GET
 * - How to validate data
 * - How to sanitize data
 * - How to: Get var only IDS
 * - How to: Get var only Letters
 * - How to: Get var only Urls
 * - How to: Get var only Date's
 * 
 * thanks, @jamesjara
 * 
 * */
error_reporting(E_ALL); 
ini_set('display_errors', '1');
date_default_timezone_set('America/Los_Angeles');

exit( main() );

function main( ) {	

	include('x7cloud.php');
	
	$x7cloud = 	new ws_x7cloud_034875();
	$x7cloud->setDebug	(true);
	$x7cloud->isGet		(true);
	$x7cloud->setModuleVar('hi');
	
	$module_showHelp	=	$x7cloud		->addModule(true);
	$module_showHelp->setHint('Module to show the API of this webservice');
	$list_api			= 	$module_showHelp->addVerb(true);
	$list_api		->setHint('Display the API');
	$list_api		->SetFunction = function () use( $x7cloud ) {					
		echo '<PRE>';
		$x7cloud->showNice();
		echo '</PRE>';
	};
	$list_api			= 	$module_showHelp->addVerb(true);
	$list_api		->setHint('Display the API');
	$list_api		->SetFunction = function () use( $x7cloud ) {
		echo '<PRE>';
		$x7cloud->showNice();
		echo '</PRE>';
	};	$list_api			= 	$module_showHelp->addVerb(true);
	$list_api		->setHint('Display the API');
	$list_api		->SetFunction = function () use( $x7cloud ) {					
		echo '<PRE>';
		$x7cloud->showNice();
		echo '</PRE>';
	};	$list_api			= 	$module_showHelp->addVerb(true);
	$list_api		->setHint('Display the API');
	$list_api		->SetFunction = function () use( $x7cloud ) {					
		echo '<PRE>';
		$x7cloud->showNice();
		echo '</PRE>';
	};
	//$x7cloud->showNice();	
	$x7cloud->main();
	
}
