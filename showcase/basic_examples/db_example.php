<?php

/*
 * 
 * Hi, this example is related to the usage of the db on  X7CLOUD FRAMEWORK.
 * thanks, @jamesjara
 * 
 * Remember to run this as  /db_example.php?v=1&m=1 to get the X7 API
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
	$x7cloud->setLogging(true);
	
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
	$module_users	=	$x7cloud->addModule(true);
	$module_users		->setHint('Module to handle users');

	//  3) CREATE THE VERBS OF THE PARENT MODULE

		$verb_User_List	= 	$module_users->addVerb(true);
		$verb_User_List		->setHint(' List Users in XML format ');
		$verb_User_List		->SetFunction = function () use( $x7cloud ) {	
			//If is logged continue else return error string
			if ( $x7cloud->isLogged() ) {
				//Query data from db
				$data['response']['success']= true;
				$data['data']	  ['users']  = $x7cloud->CloudQuery(" EXEC list_users  ");
				//Output the data to the client
				$x7cloud->CloudResponse( $data  , JSON );		
			} else {
				//Output error if is not logged
				$data['response']['success']= false;
				$data['response']['message']= 'Error , You must be logged to continue';
				$x7cloud->CloudResponse( $data , JSON , JSON );	
			}		
		};
		
		$verb_User_Add	= 	$module_users->addVerb(true);
		$verb_User_Add		->setHint(' Add new User ');
		$verb_User_Add		->SetFunction = function () use( $x7cloud ) {					
			//If is logged continue else return error string
			if ( $x7cloud->isLogged() ) {
				//Set the requerid values
				$getName = $x7cloud->setNewValue( 'name' , true , true , FILTER_SANITIZE_STRING );
				//validate and sanitize values to continue
				if ( $x7cloud->isValid() ) {
					//Execute a store procedure that required the name of the user
					if ( $x7cloud->command(sprintf(" EXEC add_user  %s" , $getName ))){		
						$data['response']['success']= true;
						$data['message']  			 = 'The User have been added successfully';
						$x7cloud->CloudResponse( $data  , JSON );
					} else {
						$data['response']['success']= false;
						$data['message']  			= 'Error creating the User';						
					}				
				} else {
					//Set error message if is not valid 
					$data['message'] = 'your request is invalid, all params are required';
					$x7cloud->CloudResponse( $data , JSON   );
				}
			} else {
				//Output error if is not logged 
				$array['response']['success']= false;
				$array['response']['message']= 'Error , You must be logged to continue';
				$x7cloud->CloudResponse( $array , JSON );
			}
		};

		$verb_User_Upd	= 	$module_users->addVerb(true);
		$verb_User_Upd		->setHint(' Update User ');
		$verb_User_Upd		->SetFunction = function () use( $x7cloud ) {					
			//If is logged continue else return error string
			if ( $x7cloud->isLogged() ) {
				//Set the requerid values
				$getId 	= $x7cloud->setNewValue( 'id' , true , true , FILTER_SANITIZE_INTEGER );
				$getName= $x7cloud->setNewValue( 'name' , true , true , FILTER_SANITIZE_STRING );
				//validate and sanitize values to continue
				if ( $x7cloud->isValid() ) {
					//Execute a store procedure that required the name and id of the user
					if ( $x7cloud->command(sprintf(" EXEC update_user  %s , %s" , $getId, $getName ))){		
						$data['response']['success']= true;
						$data['message']  			 = 'The User have been updated successfully';
						$x7cloud->CloudResponse( $data  , JSON );
					} else {
						$data['response']['success']= false;
						$data['message']  			= 'Error updating the User';						
					}				
				} else {
					//Set error message if is not valid 
					$data['message'] = 'your request is invalid, all params are required';
					$x7cloud->CloudResponse( $data , JSON   );
				}
			} else {
				//Output error if is not logged 
				$array['response']['success']= false;
				$array['response']['message']= 'Error , You must be logged to continue';
				$x7cloud->CloudResponse( $array , JSON );
			}
		};	
		
		$verb_User_Del	= 	$module_users->addVerb(true);
		$verb_User_Del		->setHint(' Delete User ');
		$verb_User_Del		->SetFunction = function () use( $x7cloud ) {					
			//If is logged continue else return error string
			if ( $x7cloud->isLogged() ) {
				//Set the requerid values
				$getId 	= $x7cloud->setNewValue( 'id' , true , true , FILTER_SANITIZE_INTEGER );
				//validate and sanitize values to continue
				if ( $x7cloud->isValid() ) {
					//Execute a store procedure that required id of the user
					if ( $x7cloud->command(sprintf(" EXEC delete_user %s" , $getId ))){		
						$data['response']['success']= true;
						$data['message']  			 = 'The User have been deleted successfully';
						$x7cloud->CloudResponse( $data  , JSON );
					} else {
						$data['response']['success']= false;
						$data['message']  			= 'Error deleting the User';						
					}				
				} else {
					//Set error message if is not valid 
					$data['message'] = 'your request is invalid, all params are required';
					$x7cloud->CloudResponse( $data , JSON   );
				}
			} else {
				//Output error if is not logged 
				$array['response']['success']= false;
				$array['response']['message']= 'Error , You must be logged to continue';
				$x7cloud->CloudResponse( $array , JSON );
			}
		};
		
	$x7cloud->main();
	
}