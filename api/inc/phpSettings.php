<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!is_writable(session_save_path())) {
    echo 'Session path "'.session_save_path().'" is not writable for PHP!';
}


ini_set('date.timezone', 'Asia/Kuala_Lumpur');
ini_set('session.save_path', dirname(dirname(__FILE__)).'/sessions');
ini_set('display_errors', 1);

define('NOW', date('Y-m-d H:i:s'));
define('TODAY', date('Y-m-d'));

/*Security*/
define('SECRET_KEY', 'test123');

/*Data Type*/
define('BOOLEAN', 	'1');
define('INTEGER', 	'2');
define('STRING', 	'3');

define('API_VER_INDEX', '2');
define('SERVICE_INDEX', '3');
define('METHOD_INDEX', 	'4');

/*Error Codes*/
define('REQUEST_METHOD_NOT_VALID',		        100);
define('REQUEST_CONTENTTYPE_NOT_VALID',	        101);
define('REQUEST_NOT_VALID', 			        102);
define('VALIDATE_PARAMETER_REQUIRED', 			103);
define('VALIDATE_PARAMETER_DATATYPE', 			104);
define('API_SERVICE_REQUIRED', 			        105);
define('API_METHOD_REQUIRED', 					106);
define('API_PARAM_REQUIRED', 					106);
define('API_DOES_NOT_EXIST', 					107);
define('INVALID_USER_PASS', 					108);
define('USER_NOT_ACTIVE', 						109);
define('USER_NOT_EXISTED', 					    110);
define('SERVER_BUSY', 					        111);
define('SERVICE_ID_NOT_EXISTED', 			    112);

define('SUCCESS_RESPONSE', 						200);

/*Server Errors*/

define('JWT_PROCESSING_ERROR',					300);
define('ATHORIZATION_HEADER_NOT_FOUND',			301);
define('ACCESS_TOKEN_ERRORS',					302);	

?>