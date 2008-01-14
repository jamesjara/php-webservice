<?php

error_reporting(E_ALL); 
ini_set('display_errors', '1');
date_default_timezone_set('America/Los_Angeles');


exit( main() );


function main( ) {	

	include('x7cloud.php');
	
	define("PLAIN", "plain");
	define("JSON", 	"json");	
	
	$webservice = new ws_x7cloud_034875();
	$webservice->setDebug(false);
	$webservice->isGet(true);
	
	$module_showHelp	=	$webservice		->addModule(true);
	$module_showHelp->setHint('Module to get the API of this webservice');
	$list_api			= 	$module_showHelp->addVerb(true);
	$list_api		->setHint('Display the API');
	$list_api		->SetFunction = function () use( $webservice ) {					
		echo '<PRE>';
		$webservice->showNice();
		echo '</PRE>';
	};

	$moduloUsers = $webservice->addModule(true);	
	$moduloUsers->setHint('modUsers');	
	
	$getuser = $moduloUsers->addVerb(true);
	$getuser->setHint('verUsuarios');	
	$getuser->SetFunction = function () use( &$webservice ) {		

		$getIdList = $webservice->setNewValue( 'id' ,true ,true , FILTER_SANITIZE_NUMBER_INT );
		//todo, continue with data validationes
		
		//If the logic is valid continue
		if ( $webservice->isValid() ) {
			
			//If is logged show data else show error				
			if ( $webservice->isLogged() ) {					
				//Generate data
				$data = sprintf('the id list is %s' , $getIdList);
				
				//Set extra headers
				$extra_headers['poweredby']    = 'sdtsdf';
				$extra_headers['charset']      = 'bbb';					
	
				//Output data
				$webservice->CloudResponse( $data , PLAIN , $extra_headers );
			} else {	
				//Set error message		
				$data = 'you need to be logged to get this data';			
				//Output data
				$webservice->CloudResponse( $data , PLAIN );
				exit;
			}	
		// if is not valid show error		
		} else {	
			//Set error message		
			$data = 'your request is invalid, all params are requerid';
			//Output data
			$webservice->CloudResponse( $data , PLAIN   );
			exit;
		}	
	};
	
	$deluser = $moduloUsers->addVerb(true);
	$deluser->setHint('deleteUsuarios');
	$deluser->SetFunction = function () use( &$webservice ) {				
			$test['name'] = 'james';
			$test['apellido'] = 'jara';
			$webservice->CloudResponse( $test  , 'json'   );
	};

	$webservice->main();
	
}
