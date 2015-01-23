<?php

/*
 * 
 * Hi, this example is related to the usage of the db on  X7CLOUD FRAMEWORK.
 * thanks, @jamesjara
 * 
 * Remember to run this as  /streaming_example.php?v=1&m=1 to get the X7 API
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
	DEFINE('PLAIN'	,'PLAIN');
	DEFINE('PDF'	,'FILE');
	DEFINE('IMAGE'	,'FILE');
	DEFINE('AUDIO'	,'FILE');

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
	$module_streaming	=	$x7cloud->addModule(true);
	$module_streaming		->setHint('Module to manipulate different file formats');

	//  3) CREATE THE VERBS OF THE PARENT MODULE
	$verb_stream_pdf	= 	$module_streaming->addVerb(true);
	$verb_stream_pdf	->setHint('  How to stream a PDF File ');
	$verb_stream_pdf	->SetFunction = function () use( $x7cloud ) {			
		$path = __DIR__.'/res_x7cloud.pdf';
		//Set extra headers
		if ( ! $x7cloud->CloudResponse( $path , PDF  ) ) $x7cloud->CloudResponse( 'pdf not found' , PLAIN );
	};

	$verb_stream_image	= 	$module_streaming->addVerb(true);
	$verb_stream_image	->setHint(' How to stream a IMAGE File ');
	$verb_stream_image	->SetFunction = function () use( $x7cloud ) {
		$path = __DIR__.'/res_x7cloud.png';
		if ( ! $x7cloud->CloudResponse( $path , IMAGE  ) ) $x7cloud->CloudResponse( 'pdf not found' , PLAIN);
	};	

	$verb_stream_image	= 	$module_streaming->addVerb(true);
	$verb_stream_image	->setHint(' How force download IMAGE File  ');
	$verb_stream_image	->SetFunction = function () use( $x7cloud ) {
		$path = __DIR__.'/res_x7cloud.png';
		//Set extra headers
		$extra_headers['Content-Disposition']  = sprintf('attachment; filename="%s"', basename($path));
		if ( ! $x7cloud->CloudResponse( $path , IMAGE , null , $extra_headers ) ) $x7cloud->CloudResponse( 'pdf not found' , PLAIN);
	};

	$verb_stream_mp3	= 	$module_streaming->addVerb(true);
	$verb_stream_mp3	->setHint('  How to force download  a AUDIO File ');
	$verb_stream_mp3	->SetFunction = function () use( $x7cloud ) {
		$path = __DIR__.'/res_x7cloud.mp3';
		//Set extra headers
		$extra_headers['Content-Disposition'] = sprintf('attachment; filename="%s"', basename($path));
		if ( ! $x7cloud->CloudResponse( $path , AUDIO , null , $extra_headers ) ) $x7cloud->CloudResponse( 'mp3 not found' , PLAIN  );
	};
	
	$verb_stream_mp3	= 	$module_streaming->addVerb(true);
	$verb_stream_mp3	->setHint('  How to stream a AUDIO File ');
	$verb_stream_mp3	->SetFunction = function () use( $x7cloud ) {
		$path = __DIR__.'/res_x7cloud.mp3';
		//Set extra headers
		$extra_headers['X-Pad']  				= 'avoid browser bug';		
		$extra_headers['Content-Disposition']  	= sprintf('inline; filename="%s"', basename($path));
		if ( ! $x7cloud->CloudResponse( $path , AUDIO , null, $extra_headers ) ) $x7cloud->CloudResponse( 'mp3 not found' , PLAIN  );
	};
	
	$x7cloud->main();

}