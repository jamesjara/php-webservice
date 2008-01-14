<?php

//Set namespace if is necesary

/**
 * @package    ws_x7cloud_034875
 * @notes writed in the competition 24 hours programing by JamesJara
 * @copyright  Copyright @JamesJara (c) 2010-2014 Grupo de Seguridad informatica Costa RIca. (http://gsi0.com/)
 * @license    LicenseUrl	LicenseName
 */
class ws_x7cloud_034875 {
	
	//=============Some LOG modes==================
    const log_info  = "Info";
    const log_error = "Error";
    const log_debug = "Debug";
    const log_always= "Always";
	
	//=============1.Set variables==================
	private 	static 	$instance;

	protected 	$debug 		= null, //Use to debug On,off
				$core		= null;
	
	private 	$isGet 		= true, //Used to set method post or get
				$var_module = 'm',  //Used to store the variable to get the modules
				$var_verb 	= 'v',  //Used to store the variable to get the verbs 
				$isValid	= true; //Used to store validation of variables
	
	//=============2.Set constructors==================
	/**
	 * Create a singleton 
	 *
	 * @return self object instance
	 */
	public static function getInstance(){
		if(!self::$instance){
			return self::$instance = new self();
		}
		return self::$instance; 
	} 
	
	/**
	 * Initiate the object class_prototype
	 */
	public function __construct(){ }
	
	//=============3.Set setters and getters==================
	/**
	 * Set the debug mode
	 *
	 * @param  boolean will show debug information if set to true, else will not show anything
	 */
	public function setDebug($value ){
		$this->debug = (($value==true) ? true : false);
	}
	
	/**
	 * Function to print log information
	 *
	 * @param  $msg			Message to display
	 * @param  $severity	The kind of Log 
	 */
	private function _log( $msg , $severity ) {
		if( ($this->debug==true) or ( $severity == self::log_always) ) {
			header('Content-type: text/plain');	
			echo sprintf( "[%s]\t[%s][%s] : %s \n", $severity, date('c') , memory_get_peak_usage() ,  $msg );
		}
	}
	
	/**
	 * Function to write only log errors
	 *
	 * @param  $msg		Message to write
	 */
	private function write_Log_error( $msg ){
		$error_log_path = date('y_d_m').'.log';
		if(!$this->writeToFile( $error_log_path, $msg."\n" , true )){
			echo " CRITICAL ERROR Error writing log error. \n";
			die();
		}
	}
	
	/**
	 * Function to write a msg to a specific file
	 *
	 * @param  $path		Path of the target file, using fopen(W)
	 * @param  $data		Msg to write
	 * @param  $appened		if is true will appened the data to the end of  the file
	 */
	private function writeToFile( $path , $data , $appened = false ){
		$mode = ( ($appened==true) ? 'a' : 'w' );
		if(is_writable(dirname($path))){
			$handle = fopen( $path  , $mode);
			if (!$handle){
				$this->write_Log_error("Cant open the file $path ");
				$this->_log("Cant open the file $path ", 'Error' );
				return false;
			}
			if (!fwrite($handle,$data)) {
				$this->write_Log_error("Cannot write the file $path ");
				$this->_log("Cannot write the file $path ", 'Error' );
				exit; //in this case should stop the program
			} else {
				fclose( $handle );
				return true;
			}
		}
	}
	
	

	//=============4.Set OUR basic functions here==================
	
	/**
	 * Set the mode to get the vars, from post or get
	 *
	 * @param  boolean true for GET, false for POST
	 */
	public function isGet( $value = true ){
		$this->isGet = (($value==true) ? true : false);
	} 
	
	/**
	 * Set the URL variable to catch the module
	 *
	 * @param  String
	 */
	public function setModuleVar($value){
		if(empty($value)) throw new Exception(' Module var is requered ');
		$this->var_module = $value;
	}
	
	/**
	 * Return the URL variable to catch the module
	 *
	 * @param  String
	 */
	private function getModuleVar(){
		if(empty($this->var_module)) throw new Exception(' Set Module var first ');
		return $this->var_module ;
	}

	/**
	 * Set the URL variable to catch the verb
	 *
	 * @param  String
	 */
	public function setVerbVar($value){
		if(empty($value)) throw new Exception(' Verb var is requered ');
		$this->var_verb = $value;
	}
	
	/**
	 * Return the URL variable to catch the verb
	 *
	 * @param  String
	 */
	private function getVerbVar(){
		if(empty($this->var_verb)) throw new Exception(' Set Verb var first ');
		return $this->var_verb ;
	}
	
	/**
	 * 
	 * Get a specific module from the core
	 * 
	 * @return Return a object Modulo from the core of the webservice	 
	 * @param  integer Id
	 */
	private function getCoreModule( $id ){
		return $this->core[$id];
	}

	/**
	 *
	 * Count all the modules to get the following module Id
	 *
	 * @return Return the next following automatic Id to be used in a module
	 */
	private function getModuleCount(){
		return count($this->core) + 1;
	}	

	//Creates a new module
	/**
	 * 
	 * Adds a new module to the webservice
	 * 
	 * @param Integer $id numeric id of the module
	 * @throws Exception in case of invalid ID module
	 * @return return a configured object Modulo
	 */
	public function addModule( $id ) {		
		//Ida cant be null
		if(empty($id)or(!isset($id))) throw new Exception(" Module Id is requerid ");
		//If id is true then autoincrement
		if($id===true) (int) $id = $this->getModuleCount(); else $id = (int)$id;
		//Check if exists, if exists return error
		if(isset($this->core[$id])) throw new Exception( sprintf(" Module with Id %s already exists ",$id));			
		//Create a new module
		$this->object 		= new modulo(); //we need pass the parent ID to get the module
		$this->object->id 	= $id ;
		//Add the module to the core
		$this->core[$id] =  $this->object ;
		unset($this->object);
		return $this->core[$id];    
	}
	
	public function dump(){
		var_dump($this->core);
	}	
	public function showNice(){
		//Show modules
		foreach( $this->core as $modulo ){
			echo sprintf("New Module\tId:[%s]\tHint:[%s]\n",$modulo->id ,  $modulo->hint);
			//Show verbs of the module
			foreach( $modulo->verbs as $verbo ){
				echo sprintf("\t-Verb\tId:[%s]\tVerb Hint:[%s]\tUrl:[%s]\tUrlRewrite:[]\n ",$verbo->id ,  $verbo->hint , '?'.$this->getModuleVar().'='.$modulo->id.'&'.$this->getVerbVar().'='.$verbo->id  );
			}
			echo "=================\n";
		}
	}
		
	/**
	 *
	 * Request a new value 
	 *
	 * @category utility
	 * @param String $id variable id
	 * @param Boolean $isGet set method true(GET) or false(POST) 
	 * @param Boolean $requered if is requerid or not , (checking the data is safe).
	 * @param Const $filter check here http://uk3.php.net/manual/en/filter.filters.sanitize.php , (actually adjusting the data to make it safe*)
	 * @return if is requerid and it doesnt exist return false, else will return a Mixed var(clean) 
	 */
	public function setNewValue( $id ,  $isGet = true , $requered = false ,  $sanitisation  = FILTER_UNSAFE_RAW ){
		$this->_log (' Validatin value' , self::log_debug);
		
		//Validate
		if(empty($id)) 				throw new Exception(' Id var is requered ');
		if(!is_int($sanitisation))  throw new Exception(' filter constants must be a sanitize constant ');//Todo fix this because could be passes a integer
		if(!is_bool($isGet)) 		throw new Exception(' isGet must be boolean , true/false ');
		if(!is_bool($requered)) 	throw new Exception(' requered must be boolean , true/false');
		
		//Get var from method
		@$variable = (($isGet==true) ? $_GET[$id] : $_POST[$id]);
		
		//Requerid
		if ($requered===true) if (!isset($variable)||empty($variable))  {
			$this->isValid = false;
			return false;
		}	
		
		//Sanitisation
		if ($sanitisation!==false) {
			//todo detec if is a valid $sanitisation
			$variable = filter_var( $variable , $sanitisation );			
		}
		
		return $variable;
	}
	
	/**
	 *
	 * Validate if the function can continue or not
	 *
	 * @category utility
	 * @return return true if the user is can continue or false is cant
	 */
	public function isValid(){
		$this->_log (' validating if isValid' , self::log_debug);
		if ($this->isValid==false) {
			return false;
		}
		return true;
	}

	/**
	*
	* Check if the user is logged
	* 
	* @category utility
	* @return return true on if the user is logged or false if not
	*/
	public function isLogged(){
		$this->_log (' validating if isLogged' , self::log_debug);
		@session_start();
		$ingresado = isset($_SESSION['ingresado']) ? $_SESSION['ingresado'] : null;
		//COMPRUBA SESSION
		IF ( $ingresado ) {
			/* TODO ADD HASH FEATURE
			 * IF($this->Hash_Active==TRUE){ 
				$HASH = isset($_GET[$this->Hash_Var])   ?  $_GET[$this->Hash_Var]       :  NULL;
				IF ($HASH==NULL){
					RETURN FALSE;
				} ELSE {
					//COMPRUEBA HASH
					IF ($HASH==$_SESSION['webservice_hash']){
						//COMPRUEBA SECURE HASH
						IF ( $_SESSION['secure_hash'] == $this->SE_HASH() ){
							//COMPRUBA TIMEOUT
							IF ( $this->timeout ){
								$fechaGuardada = $_SESSION['ultimo_acceso'];
								$ahora = date("Y-n-j H:i:s");
								$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
								IF ( $tiempo_transcurrido >= $this->timeout_value){
									$this->CloseSesion(true);
									RETURN FALSE;
								}ELSE {
									$_SESSION['ultimo_acceso'] = $ahora ;
								};
							} ELSE RETURN TRUE;
						} ELSE RETURN FALSE;
					} ELSE RETURN FALSE;
				}
			};*/
			IF ( $ingresado ) RETURN TRUE; ELSE RETURN FALSE;
		}
	}
	
	/**
	 * Return the existence of a session value
	 * 
	 * @category core
	 * @param String $value id of the session value
	 * @return null if does not exist or the value if exists
	 */
	public function getSessionValue ( $value ) {
		@session_start();
		return isset($_SESSION[$var])   ?  $_SESSION[$var]      :  NULL;
	}

	/**
	 * Create Session 
	 *
	 * @category core
	 * @param array $params array with custom session values
	 * @return true if the session is sucesfully created
	 */
	public function CreateSesion( array $params = array() ){
		if (  $this->isLogged() ) return false;
		@session_start();
		$defaults = array(
				'username'              => null,
				'owner_id'              => null,
				'ingresado'             => 'OK',//privado
				'webservice_hash'       => $this->WS_HASH(),//privado
				'secure_hash'           => $this->SE_HASH(),//privado
				'ultimo_acceso'         => date("Y-n-j H:i:s") //privado
		);
		if(is_array($params)) $params = array_merge($defaults, $params);
		foreach($params as $key => $value ){
			$_SESSION[$key] = $value ;
		}
		return true;
	}
	
	/**
	 * Destroy Session
	 *
	 * @category core
	 * @param boolean $fast_destroy FOR NOT BE IN WHILE INFINITE OF HASSESION TIMEOUT FUNCTION
	 * @return true if the session is sucesfully destroyed
	 */
	public function  CloseSesion($fast_destroy = FALSE){
		@session_start();
		IF (!$fast_destroy){
			IF (  $this->isLogged() ){
				$_SESSION = array();
				IF (ini_get("session.use_cookies")){
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 42000,$params["path"], $params["domain"],$params["secure"], $params["httponly"]);
				}
				IF ( session_destroy() )  RETURN TRUE; ELSE RETURN FALSE;
			} ELSE RETURN  FALSE;
		} ELSE {
			IF ( session_destroy() )  RETURN TRUE; ELSE RETURN FALSE;
		}
	}
	
	
	/**
	 * Ws hash 
	 *
	 * @category security
	 * @return webservice hash
	 */
	private function WS_HASH(){
		$VALUE = NULL;
		$A = SESSION_ID();
		$B = substr($_SERVER['HTTP_USER_AGENT'],0,10)."JAMES-JARA";
		$C = date('hisjmywz');
		$VALUE = MD5($A.$B.$C);
		RETURN $VALUE;
	}
	
	/**
	 * Secure hash
	 *
	 * @category security
	 * @return Secure hash
	 */
	private function SE_HASH(){
		$VALUE = NULL;
		$A = SESSION_ID();
		$B = substr($_SERVER['HTTP_USER_AGENT'],0,10)."JAMES-JARA";
		$C = $this->GetIp();
		$VALUE = MD5($A.$B.$C);
		RETURN $VALUE;
	}
	
	/**
	 * Get remote ip if is posible
	 *
	 * @note can be spoofed
	 * @category security
	 * @return remotre adress
	 */
	private function GetIp(){
		$VALUE = $_SERVER['REMOTE_ADDR'];
		RETURN $VALUE;
	}
	
	
	/**
	 *
	 * Output the response to the frontend
	 *
	 * @category core
	 * @param Mixed $data
	 * @param String $type headers type
	 * @param String $format format for the response (json,xml,etc)
	 * @param Array $extra_headers (if there is some header repeated will persevere the last header setted)
	 * @return return the result in a custom format or null in case of error
	 */
	public function CloudResponse( $data , $type , $template  = 'default' , $extra_headers = null ){
		//Custom functions to the set basic data templates
		if ( ($data = $this->setTemplate( $template , $type , $data )) == false ) $this->_log (' Error ocurren in template  ' , self::log_error);
		//Set the data type
		if ( $this->setType( $type ) == false )  						$this->_log (' Default Data Header Type not found ' , self::log_error);
		//If extra headers is set, appened headers
		if (isset($extra_headers) && is_array($extra_headers)) {
			foreach($extra_headers  as $key => $value){
				//Especial headers FIX
				if(strtolower($key)=='_status')
					header( sprintf('HTTP/1.1 %s', $value ) );
				else			
					header( sprintf('%s: %s', $key , $value ) );				
			}
			$this->_log (' custom headers added' , self::log_debug);
		}
		//Print data
		echo $data;
	}
	
	/**
	 * 
	 * Set the header response of the request
	 * 
	 * @category core
	 * @param String $type
	 * @return return true on success or false on invalid data header
	 * @more http://www.ietf.org/rfc/rfc4627.txt
	 */
	private function setType( $type ){	
		//SET DEFAULT DATA TYPES
		switch ( strtolower( $type ) ){
			case 'atom':
				header('Content-type: application/atom+xml');	
				break;
			case 'css':
				header('Content-type: text/css');	
				break;
			case 'js':
				header('Content-type: text/javascript');	
				break;
			case 'IMAGE':
				header('Content-type: image/jpeg');	
				break;
			case 'json':
				header('Content-type: application/json');					
				break;
			case 'pdf':
				header('Content-type: application/pdf');	
				break;
			case 'rss':
				header('Content-Type: application/rss+xml; charset=ISO-8859-1');	
				break;
			case 'xml':
				header('Content-type: text/xml');	
				break;
			case 'sound':				
				$mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3";
				header("Content-type: {$mime_type}");				
				break;
			case 'plain':
				header('Content-type: text/plain');	
				break;
			default :
				$this->_log ( sprintf(' Setting data type , invalid datatype %s ',$type) , self::log_error);
				return false;
				break;
		}
		$this->_log (' Setting data type '.$type , self::log_debug);
		return true;
	}


	/**
	 *
	 * Set the format response of the request
	 *
	 * @category core
	 * @param String $template template name
	 * @param String $format data type
	 * @param String $data array or string
	 * @return return formated data on success or false on invalid data header
	 */
	private function setTemplate( $template , $format , $data){
		//SET DEFAULT DATA FORMAT
		switch ( strtolower( $format ) ){
			case 'atom':
				//ToDO
				return $data;
				break;
			case 'css':
				//ToDO
				return $data;
				break;
			case 'js':
				//ToDO
				return $data;
				break;
			case 'image':
				//ToDO			
				if ( file_exists($data) ) {									
					header('Content-Description: imagen');
					header('Content-Type: image/'.pathinfo($data,PATHINFO_EXTENSION).'');
					header(sprintf('Content-Disposition: filename="%s"', basename($data)));
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($data));
					ob_clean();
					flush();
					readfile($data);
					exit;
				} else { 
					//ToDO improve this, show message or something ??? 
					$data = null;
					$this->_log (  sprintf(' file doenst exist  : %s  ',$data) , self::log_error); 
					return null;
				}
				break;
			case 'json':
				//if is array
				//return this->NewJson($data);
				return json_encode($data);  
				break;
			case 'pdf':
				//ToDO
				return $data;
				break;
			case 'rss':
				//ToDO
				return $data;
				break;
			case 'xml':
				//ToDO
				//Validate XML STRICT FORMAT
				if(!is_array($data)or(empty($data))) { $this->_log (  sprintf(' Invalid XML FORMAT , array is requerid  : %s  ',$data) , self::log_error); exit; }
				return $this->NewXml(NULL, NULL ,$data);           					
				break;
			case 'sound':			
				if ( file_exists($data) ) {
					header('Content-length: ' . filesize($data));
					header(sprintf('Content-Disposition: filename="%s"', basename($data)));
					header('X-Pad: avoid browser bug');
					header('Cache-Control: no-cache');
					$mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3"; //Todo fix this, because this should be seted in the set page.
					header("Content-type: {$mime_type}");					
					readfile($data);
					exit;
				} else { 
					//ToDO improve this, show message or something ??? 
					$data = null;
					$this->_log (  sprintf(' file doenst exist  : %s  ',$data) , self::log_error); 
					return null;
				}				
				break;
			case 'plain':
				//ToDO
				return $data ;
				break;
			default :
				//ToDO
				return $data;
				break;
		}
		$this->_log (' Setting data format '.$format , self::log_debug);
		return true;
	}
	
	
	//TODO IMPROVE AND PERFOMCE THIS CRAP!
	PUBLIC STATIC FUNCTION NewXml($root=NULL,$objeto_name=NULL,$data=NULL,$atributte=NULL){
		//detectar si no tiene root y node sera un xml invalido
		/*IF ((!isset($root))OR(!isset($objeto_name))){
			$this->_log ( sprintf(' error, a root for xml is requerid %s , %s ',$root,$objeto_name) , self::log_error);
			exit();
		}*/
		//detectar si es numerico, si lo es debe ser error xq el xml no comienza con numeros
		/*IF ((preg_match('/^\d/', $root) === 1)OR(preg_match('/^\d/', $objeto_name) === 1)){
			ECHO self::printLn(printf(JJX7Cloud::ERROR_27,$root,$objeto_name));exit();
		}*/
		//si no tiene data en array dara eror, x lo cual se iniciara en null
		IF($data==NULL)$data=ARRAY($objeto_name=>NULL);
	
		$xml = null;
		$xml = new XMLWRITER();
		$xml->openMemory();
		$xml->startDocument('1.0','UTF-8');
		@$xml->startElement($root);
		function write(XMLWriter $xml, $data){
			foreach($data as $key => $value){
				if (is_array($value) && isset($value[0])){
					foreach($value as $itemValue){
						//$xml->writeElement($key, $itemValue);
						if(is_array($itemValue)){
							$xml->startElement($key);
							write($xml, $itemValue);
							$xml->endElement();
							continue;
						}
						if (!is_array($itemValue)){
							$xml->writeElement($key, $itemValue."");
						}
					}
				}else if(is_array($value)){
					$xml->startElement($key);
					write($xml, $value);
					$xml->endElement();
					continue;
				}
				if (!is_array($value)){
					$xml->writeElement($key, $value."");
				}
			}
		}
		@write($xml, $data);
		$xml->endElement();
		RETURN $xml->outputMemory(true);
	}
		
	
	
	//=============5.Set main function==================
	/**
	 * Call this function to execute your program logic.
	 *
	 */
	public function main() {
		//Get the requerid values
		@$modulo = (($this->isGet==true) ? (int) $_GET[$this->getModuleVar()]  	:  (int) $_POST[$this->getModuleVar()]);
		@$verbo  = (($this->isGet==true) ? (int) $_GET[$this->getVerbVar()]  	:  (int) $_POST[$this->getVerbVar()]);	
		
		//Validate the module and verb
		if( isset($this->core[$modulo]->verbs[$verbo]) ){
			$this->_log( 
					sprintf(
					'Requesting module[id:%s][hint:%s] , verbo[id:%s][hint:%s] by '.(($this->isGet==true) ? '$_GET' : '$_POST')
					,$modulo,$this->core[$modulo]->hint, $verbo,$this->core[$modulo]->verbs[$verbo]->hint) 
					, self::log_info);			
			//Execute the CUSTOM FUNCTION
			if( $this->core[$modulo]->verbs[$verbo]->SetFunction() == false ){
				$this->_log( 'Please define a function for thuis ' , self::log_always);
				return false;				
			}
		} else
			$this->_log( ' Module or verb does not exist ' , self::log_always);
			return false;	
	}	
}

class modulo extends ws_x7cloud_034875 {

	protected $hint 	= null;
	protected $verbs 	= array();
	
	private static $instance;
	
	public static function getInstance(){
		if(!self::$instance){
			return self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct( ){
		//Todo, remove parent variables
		//unset( $this->verbs ); dont uncoment else will generate error
		unset( $this->debug );
		unset( $this->core );		
	}
	
	public function setHint( $value ){
		$this->hint = $value;
	}
	
	private function getVerbCount( ){		
		return count( $this->verbs ) + 1;
	}
		
	//Creates a new verb
	public function addVerb( $id ) {
		//Id cant be null
		if(empty($id)or(!isset($id))) throw new Exception(" Verb Id is requerid ");		
		//If id is true then autoincrement
		if($id===true) (int) $id = $this->getVerbCount(); else $id = (int)$id;		
		//Check if exists, if exists return error
		if(isset($this->verbs[$id])) throw new Exception( sprintf(" Verb with Id %s already exists",$id));
		//Create a new module
		$verbo = new verbo();
		$verbo->id = $id ;
		$verbo->hint = 'set a hint, please';
		//Add the verb to the module
		$this->verbs[$id] = $verbo;
		unset($verbo);
		return $this->verbs[$id];		
	}	
	
}

class verbo extends modulo {

	protected $hint 	= null;
	
	private static $instance;

	public static function getInstance(){
		if(!self::$instance){
			return self::$instance = new self();
		}
		return self::$instance;
	}

	public function setHint( $value ){
		$this->hint = $value;
	}
	
	public function setbody( $method ){
		echo 'ddddd';
		var_dump($method);
		//Execute the anonymous function
		$func = $this->$method;
		$func();
	}
	
	//This is a very ugly hack
	public function __call($closure, $webservice )
	{		
		//var_dump($args);
		/*if(@call_user_func_array($this->$closure, $args)==false){
			return false;
		}	*/	
		if(isset($this->$closure) === true) {
			//Execute the anonymous function
			$func = $this->$closure;
			$func();
			return true;
		} else {
			//Return false if the funcion does not defined
			return false;
		}
	}
	
	
	
}