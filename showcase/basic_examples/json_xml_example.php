<?php

/*
 * 
 * Hi, this example is related to the usage of xml and json on  X7CLOUD FRAMEWORK.
 * thanks, @jamesjara
 * 
 * Remember to run this as  /json_xml_example.php?m=1&v=1 to get the X7 API
 * 
 * In this example:
 * 
 * TODO , add here API 
 * 
 * */

error_reporting(E_ALL); 
ini_set('display_errors', '1');
date_default_timezone_set('America/Los_Angeles');

exit( main() );

function main( ) {	

	include('x7cloud.php');
	DEFINE('JSON'	,'JSON');
	DEFINE('XML' 	,'XML');
	DEFINE('PLAIN' 	,'PLAIN');
	
	//  1) BASIC CONFIGURATION
	$x7cloud = 	new ws_x7cloud_034875();
	$x7cloud->setDebug	(false);
	$x7cloud->isGet		(true);
	$x7cloud->setModuleVar('m');
	$x7cloud->setVerbVar('v');
	$x7cloud->setLogPath( __DIR__.'/../../logs/' );
	$x7cloud->isLogging(true);
	
	//  2) CREATE MODULES AND VERBS
	$module_core	= $x7cloud->addModule(true);
	$module_core	->setHint('Module to handle X7Cloud API');
		$show_api		= 	$module_core->addVerb(true);
		$show_api		->setHint(' Show the API of your custom x7cloud webservice ');
		$show_api		->SetFunction = function () use( $x7cloud ) {
			echo '<PRE>';
			$x7cloud->showAPI();
			echo '</PRE>';
		};
	
	$module_JSON 	=	$x7cloud->addModule(true);
	$module_JSON	->setHint('Module to output on JSON related format');	
		//Output data with json header but plain structure
		$json_example1 = $module_JSON->addVerb(true);
		$json_example1 ->setHint(' Show hello world in json format ');
		$json_example1 ->SetFunction = function () use( $x7cloud ) {	
			$json_sample =  '{"menu": {
					   "id": "file",
					   "value": "File",
					   "popup": {
						 "menuitem": [
						   {"value": "New", "onclick": "CreateNewDoc()"},
						   {"value": "Open", "onclick": "OpenDoc()"},
						   {"value": "Close", "onclick": "CloseDoc()"}
						 ]
					   }
					 }}';
			$x7cloud->CloudResponse( $json_sample , PLAIN , JSON );
			//Notes that this is a HACK , because we are passing a string with json contents,
			//so we need to setup the template type as a PLAIN because is a PLAIN String,
			//but in the third parameter we set the Content-Type as an json format.
		}; 	
		//Output data with json header but json structure
		$json_example2 = $module_JSON->addVerb(true);
		$json_example2 ->setHint(' Show hello world in json format ');
		$json_example2 ->SetFunction = function () use( $x7cloud ) {	
			$json_sample['message'] = 'hello world';
			$x7cloud->CloudResponse( $json_sample , JSON  );					
		}; 
	
	$module_XML 	=	$x7cloud->addModule(true);
	$module_XML	->setHint('Module to output on XML related format');	
		//Output data with json header but plain structure
		$xml_example1 = $module_XML->addVerb(true);
		$xml_example1 ->setHint(' Show hello world in xml format ');
		$xml_example1 ->SetFunction = function () use( $x7cloud ) {	
			$xml_sample =  '<menu id="file" value="File"><popup>
						<menuitem value="New" onclick="CreateNewDoc()" />
						<menuitem value="Open" onclick="OpenDoc()" />
						<menuitem value="Close" onclick="CloseDoc()" />
					  </popup></menu>';
			$x7cloud->CloudResponse( $xml_sample , PLAIN , XML );
			//Notes that this is a HACK , because we are passing a string with xml contents,
			//so we need to setup the template type as a PLAIN because is a PLAIN String,
			//but in the third parameter we set the Content-Type as an xml format.
		}; 	
		//Output data with xml header but xml structure
		$xml_example2 = $module_XML->addVerb(true);
		$xml_example2 ->setHint(' Show hello world in xml format ');
		$xml_example2 ->SetFunction = function () use( $x7cloud ) {		
			$xml_sample['message'] = 'hello world';
			$x7cloud->CloudResponse( $xml_sample , XML );						
		}; 
		
	$x7cloud->main();
	
}