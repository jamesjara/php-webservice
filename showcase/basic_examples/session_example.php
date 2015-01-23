<?php

/*
 * 
 * Hi, this example is related to the usage of the db on  X7CLOUD FRAMEWORK.
 * thanks, @jamesjara
 * 
 * Remember to run this as  /session_example.php?v=1&m=1 to get the X7 API
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
	$x7cloud->setLogPath( __DIR__.'/../../logs/' );
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
	$module_session	=	$x7cloud->addModule(true);
	$module_session		->setHint('Module to manipulate sessions');

	//  3) CREATE THE VERBS OF THE PARENT MODULE

		$verb_validate_session	= 	$module_session->addVerb(true);
		$verb_validate_session	->setHint('  How to validate sessions ');
		$verb_validate_session	->SetFunction = function () use( $x7cloud ) {	
			//If is logged show data else show error				
			if ( $x7cloud->isLogged() ) {					
				$data['message'] = 'you are logged in.';
				$x7cloud->CloudResponse( $data , JSON   );				
			} else {	
				$data['message'] = 'please to continue please login first.';
				$x7cloud->CloudResponse( $data , JSON   );	
			}			
		};		

		$verb_create_session	= 	$module_session->addVerb(true);
		$verb_create_session	->setHint('  Creating session ');
		$verb_create_session	->SetFunction = function () use( $x7cloud ) {
			//add custom values to the session
			$own_values = array ( 'username'=> "JAMESJARA" ,'owner_id'=> "1",'custom_value'=> "hello world"	);			
			if ( $x7cloud->CreateSesion( $own_values ) ) {
				$data['message'] = 'session created.';
				$x7cloud->CloudResponse( $data , JSON   );
			} else {
				$data['message'] = 'error creating session, because you are already in.';
				$x7cloud->CloudResponse( $data , JSON   );
			}	
		};	

		$verb_destroy_session	= 	$module_session->addVerb(true);
		$verb_destroy_session	->setHint('  Destroing session ');
		$verb_destroy_session	->SetFunction = function () use( $x7cloud ) {
			if ( $x7cloud->CloseSesion(  ) ) {
				$data['message'] = 'session destroyed.';
				$x7cloud->CloudResponse( $data , JSON   );
			} else {
				$data['message'] = 'error destroing session, please try again latter..';
				$x7cloud->CloudResponse( $data , JSON   );
			}	
		};
		
	$x7cloud->main();
	
}