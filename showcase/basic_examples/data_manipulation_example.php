<?php

/*
 * 
 * Hi, this example is related To How to Manipulate data  X7CLOUD FRAMEWORK.
 * thanks, @jamesjara
 * 
 * Remember to run this as  /data_manipulation_example.php?v=1&m=1 to get the X7 API
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
	DEFINE('JSON','JSON');
	
	//  1) BASIC CONFIGURATION
	$x7cloud = 	new ws_x7cloud_034875();
	$x7cloud->setDebug	(false);
	$x7cloud->isGet		(true);
	$x7cloud->setModuleVar('m');
	$x7cloud->setVerbVar('v');
	$x7cloud->setLogPath(  __DIR__.'/../../logs/' );
	$x7cloud->isLogging(true);
	
	//API module
	$module_core	= $x7cloud->addModule(true);
	$module_core	->setHint('Module to handle X7Cloud API');
	$show_api		= 	$module_core->addVerb(true);
	$show_api		->setHint(' Show the API of your custom x7cloud webservice ');
	$show_api		->SetFunction = function () use( $x7cloud ) {
		echo '<PRE>';
		$x7cloud->showAPI();
		echo '</PRE>';
	};
	
	//  2) CREATE A MODULE
	$module_data	=	$x7cloud->addModule(true);
	$module_data	->setHint('Module to manipulate data');

	//  3) CREATE THE VERBS OF THE PARENT MODULE
		$verb_get_data_post	= 	$module_data->addVerb(true);
		$verb_get_data_post	->setHint('Get data from POST Method ');
		$verb_get_data_post	->SetFunction = function () use( $x7cloud ) {
			//Set values
			$getIdValue = $x7cloud->setNewValue( 'id' , true ,true , FILTER_SANITIZE_NUMBER_INT );	
			//If is valid continue else return error msj
			if ( $x7cloud->isValid() ) {
				$data['data']['message'] = sprintf(' Id Post Var is: %s',$getIdValue);
				//Output the data to the client
				$x7cloud->CloudResponse( $data  , JSON );		
			} else {
				//Output error if data is not valid
				$data['response']['success']= false;
				$data['response']['message']= 'Error , Your data is not valid,try adding &id=foo777bar to the url';
				$x7cloud->CloudResponse( $data , JSON );	
			}	
		};	
		
		$verb_get_data_get	= $module_data->addVerb(true);
		$verb_get_data_get	->setHint('Get data from GET Method  ');
		$verb_get_data_get	->SetFunction = function () use( $x7cloud ) {	
			//Set values
			$getIdValue = $x7cloud->setNewValue( 'id' , true ,true , FILTER_SANITIZE_NUMBER_INT );
			//If is valid continue else return error msj
			if ( $x7cloud->isValid() ) {
				$data['data']['message'] = sprintf(' Id GET Var is:  %s',$getIdValue);
				//Output the data to the client
				$x7cloud->CloudResponse( $data  , JSON );
			} else {
				//Output error if data is not valid
				$data['response']['success']= false;
				$data['response']['message']= 'Error , Your data is not valid,try adding &id=foo777bar to the url';
				$x7cloud->CloudResponse( $data , JSON );
			}				
		};

		$verb_data_validate	= $module_data->addVerb(true);
		$verb_data_validate	->setHint('Validate data  ');
		$verb_data_validate	->SetFunction = function () use( $x7cloud ) {	
			//Set values
			$obligatory_value 		= $x7cloud->setNewValue( 'id' 		, true ,true  , FILTER_SANITIZE_NUMBER_INT 	);
			$Not_obligatory_value 	= $x7cloud->setNewValue( 'country' 	, true ,false , FILTER_SANITIZE_STRING 		);
			//If is valid continue else return error msj
			if ( $x7cloud->isValid() ) {
				$data['data']['message1'] = sprintf(' Value required id is :  %s'			,$obligatory_value);
				$data['data']['message2'] = sprintf(' Value not required country is:  %s'	,$Not_obligatory_value);
				//Output the data to the client
				$x7cloud->CloudResponse( $data  , JSON );
			} else {
				//Output error if data is not valid
				$data['response']['success'] = false;
				$data['response']['required']= 'id';
				$data['response']['message'] = 'Error , Your data is incomplete';
				$x7cloud->CloudResponse( $data , JSON );
			}			
		};	
		
		$verb_data_sanitize	= 	$module_data->addVerb(true);
		$verb_data_sanitize	->setHint('Sanitize data ');
		$verb_data_sanitize	->SetFunction = function () use( $x7cloud ) {			
			//Set values
			$integer_value 	= $x7cloud->setNewValue( 'id' 		, true ,true  , FILTER_SANITIZE_NUMBER_INT 	);
			//var_dump( $integer_value );
			$string_value 	= $x7cloud->setNewValue( 'country' 	, true ,true  , FILTER_SANITIZE_STRING 		);
			//var_dump( $string_value );
			//If is valid continue else return error msj
			if ( $x7cloud->isValid() ) {

				if($integer_value==false) $data['data']['errors'] = ' id must be integer ';
				if($string_value==false)  $data['data']['errors'] = ' country must be string  ';
				
				$data['data']['message1'] = sprintf(' Value required id is :  %s'			,$integer_value);
				$data['data']['message2'] = sprintf(' Value not required country is:  %s'	,$string_value );
				//Output the data to the client
				$x7cloud->CloudResponse( $data  , JSON );
			} else {
				//Check each value
				if($integer_value==false)  $data['data']['error'][] = ' id must be integer ';
				if($string_value ==false)  $data['data']['error'][] = ' country must be string  ';				
				$data['response']['success'] = false;
				$data['response']['required']= 'id';
				$data['response']['message'] = 'Error , Your data is incomplete, id is required as integer, and country as string ';
				$x7cloud->CloudResponse( $data , JSON );
			}				
		};
		
	$x7cloud->main();
	
}