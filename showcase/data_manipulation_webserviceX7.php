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
 * == SESSIONS ==
 * - How to validate sessions
 * - Create session
 * - Destroy session
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
	$x7cloud->setDebug	(false);
	$x7cloud->isGet		(true);
	$x7cloud->setModuleVar('m');
	$x7cloud->setVerbVar('v');

	define("PLAIN", "plain");
	define("JSON", 	"json");
	define("XML", 	"xml");
	define("SOUND", "SOUND");
	define("IMAGE", "IMAGE");
	
	//CONSTANT HEADERS
	define("RAW", "RAW");
	
	$module_output		=	$x7cloud		->addModule(true);
	$module_output		->setHint('Module to show the API of this webservice');
		// - How to get the API of your custom x7cloud webservice	
		$list_api			= 	$module_output->addVerb(true);
		$list_api			->setHint(' get the API of your custom x7cloud webservice ');
		$list_api			->SetFunction = function () use( $x7cloud ) {					
			echo '<PRE>';
			$x7cloud->showNice();
			echo '</PRE>';
		};
	
		// - How to output simple string in plain 
		$verb_output_string_plain	= $module_output->addVerb(true);
		$verb_output_string_plain	->setHint(' output simple string in plain  ');
		$verb_output_string_plain	->SetFunction = function () use( $x7cloud ) {
			$x7cloud->CloudResponse( 'Hola Mundo!' , PLAIN  );
		};
	
		// - How to output simple string in json
		$verb_output_string_json	= $module_output->addVerb(true);
		$verb_output_string_json	->setHint(' output simple string in json  ');
		$verb_output_string_json	->SetFunction = function () use( $x7cloud ) {
			$x7cloud->CloudResponse( 'Hola Mundo!' , JSON  );
		};
	
		// - How to output array in json
		$verb_output_array_json		= $module_output->addVerb(true);
		$verb_output_array_json		->setHint(' output array in json   ');
		$verb_output_array_json		->SetFunction = function () use( $x7cloud ) {
			$array['mensaje'] = 'Hola Mundo!';
			$x7cloud->CloudResponse( $array , JSON , JSON );
		};
	
		// - How to output array in xml
		$verb_output_array_xml		= $module_output->addVerb(true);
		$verb_output_array_xml		->setHint(' output array in xml   ');
		$verb_output_array_xml		->SetFunction 	= function () use( $x7cloud ) {
			$array['root'][0]['ingles']['mensaje']	= 'Hola Mundo!';
			$array['root'][0]['espanol']['mensaje']	= 'Hello World!';
			$x7cloud->CloudResponse( $array  , XML );
		};
	
		// - How to output MP3
		$verb_output_mp3			= $module_output->addVerb(true);
		$verb_output_mp3			->setHint(' output MP3   ');
		$verb_output_mp3			->SetFunction = function () use( $x7cloud ) {
			$path = __DIR__.'/piazzolla.mp3';
			if ( ! $x7cloud->CloudResponse( $path , SOUND ) ) $x7cloud->CloudResponse( 'sound not found' , PLAIN  );
		};
		
		// - How to output IMAGE
		$verb_output_image			= $module_output->addVerb(true);
		$verb_output_image			->setHint(' output IMAGE   ');
		$verb_output_image			->SetFunction = function () use( $x7cloud ) {
			$path = __DIR__.'/x7cloud.png';
			if ( ! $x7cloud->CloudResponse( $path , IMAGE ) ) $x7cloud->CloudResponse( 'image not found' , PLAIN  );
		};
	
		// - How to manipulate headers response
		$verb_output_special_headers= $module_output->addVerb(true);
		$verb_output_special_headers->setHint(' manipulate headers response   ');
		$verb_output_special_headers->SetFunction = function () use( $x7cloud ) {
			//Set extra headers
			$extra_headers['poweredby']= 'BLACKBOYS';
			$extra_headers['FOO']      = 'BAR';					
			$x7cloud->CloudResponse( 'Hola Mundo! - check headers' , PLAIN , RAW , $extra_headers );
		};
		
	$module_input		=	$x7cloud->addModule(true);
	$module_input		->setHint('Module to demostrate input manipulation');
		// - How to get data in POST
		$verb_get_post		= 	$module_input->addVerb(true);
		$verb_get_post		->setHint('  get data in POST');
		$verb_get_post		->SetFunction = function () use( $x7cloud ) {			
			//Set values
			$getIdList = $x7cloud->setNewValue( 'id' 	, false ,true , FILTER_SANITIZE_NUMBER_INT );		
			//validate and sanitize to continue
			if ( $x7cloud->isValid() ) {
				$data['id']	= $getIdList; 
				$x7cloud->CloudResponse( $data , JSON );
			} else {
				//Set error message		
				$data['message'] = 'your request is invalid, all params are required, id var by post';
				//Output data
				$x7cloud->CloudResponse( $data , JSON   );
			}			
		};

		// - How to get data in POST
		$verb_get_get		= 	$module_input->addVerb(true);
		$verb_get_get		->setHint('  get data in GET ');
		$verb_get_get		->SetFunction = function () use( $x7cloud ) {
			//Set values
			$getIdList = $x7cloud->setNewValue( 'id' , true ,true , FILTER_SANITIZE_NUMBER_INT );
			//validate and sanitize to continue
			if ( $x7cloud->isValid() ) {
				$data['id']	= $getIdList;
				$x7cloud->CloudResponse( $data , JSON );
			} else {
				//Set error message
				$data['message'] = 'your request is invalid, all params are required, id var by get';
				//Output data
				$x7cloud->CloudResponse( $data , JSON   );
			}			
		};
		
		// - How to validate data
		$verb_validate_data	= 	$module_input->addVerb(true);
		$verb_validate_data	->setHint('  validate data ');
		$verb_validate_data	->SetFunction = function () use( $x7cloud ) {
			//Set values
			$required_value 	= $x7cloud->setNewValue( 'name' 	, true ,true 	, FILTER_SANITIZE_STRING );
			$Not_requerid_value = $x7cloud->setNewValue( 'lastname' , true ,false	, FILTER_SANITIZE_STRING );
			//validate 
			if ( ! $x7cloud->isValid() ) {				
				//Set error message
				$data['message'] = 'invalid form.';
				if (!$required_value) 		$data['name'] 		= 'name is required';
				if (!$Not_requerid_value) 	$data['lastname'] 	= 'lastname is not obligatory';				
				//Output data
				$x7cloud->CloudResponse( $data , JSON   );
			} else {
				$data['message'] = 'correct form.';
				//Output data
				$x7cloud->CloudResponse( $data , JSON   );
			}			
		};

		// - How to sanitize data
		$verb_sanitize_data	= 	$module_input->addVerb(true);
		$verb_sanitize_data	->setHint('  sanitize data ');
		$verb_sanitize_data	->SetFunction = function () use( $x7cloud ) {
			//Set values
			$only_ids		= $x7cloud->setNewValue( 'name' 	, true ,true 	, FILTER_SANITIZE_NUMBER_INT );
			$only_strings 	= $x7cloud->setNewValue( 'lastname' , true ,true 	, FILTER_SANITIZE_STRING );
			$only_emails 	= $x7cloud->setNewValue( 'email' 	, true ,true	, FILTER_SANITIZE_EMAIL );
			//validate 
			$data['name'] 	 = sprintf('var (int)name result : %s',$only_ids);
			$data['lastname']= sprintf('var (string)lastname result : %s',$only_strings);
			$data['email'] 	 = sprintf('var (emails)email result : %s',$only_emails);				
			$x7cloud->CloudResponse( $data , JSON   );		
		};
	

	$module_sessions	=	$x7cloud->addModule(true);
	$module_sessions	->setHint('Module to demostrate sessions manipulation');
		// - How to validate sessions
		$verb_validate_session	= 	$module_sessions->addVerb(true);
		$verb_validate_session	->setHint(' validate sessions ');
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
		
		// - Create session
		$verb_create_session	= 	$module_sessions->addVerb(true);
		$verb_create_session	->setHint(' Create session ');
		$verb_create_session	->SetFunction = function () use( $x7cloud ) {
			//add custom values to the session
			$own_values = array ( 'username'=> "JAMESJARA" ,	'owner_id'=> "1",'custom_value'=> "hello world"	);			
			if ( $x7cloud->CreateSesion( $own_values ) ) {
				$data['message'] = 'session created.';
				$x7cloud->CloudResponse( $data , JSON   );
			} else {
				$data['message'] = 'error creating session, because you already in.';
				$x7cloud->CloudResponse( $data , JSON   );
			}	
		};
		
		// - Destroy session
		$verb_destroy_session	= 	$module_sessions->addVerb(true);
		$verb_destroy_session	->setHint(' Destroy session ');
		$verb_destroy_session	->SetFunction = function () use( $x7cloud ) {			
			if ( $x7cloud->CloseSesion(  ) ) {
				$data['message'] = 'session destropyed.';
				$x7cloud->CloudResponse( $data , JSON   );
			} else {
				$data['message'] = 'error destroying session, please try again latter..';
				$x7cloud->CloudResponse( $data , JSON   );
			}				
		};

		
	$x7cloud->main();
	
}
