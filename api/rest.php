<?php 

	class Rest {
		protected $request;
		protected $serviceName;
		protected $methodName;
		public $param;
		public $dbConn;
		protected $userId;
		protected $apiVer = 'v1';
		protected $activeVers = ['v1','v2'];
		protected $httpRequetMethod;

		public function __construct() {
			
			if($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
				$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.');
			}
			$this->httpRequetMethod = $_SERVER['REQUEST_METHOD'];

			$handler = fopen('php://input', 'r');
			$this->request = stream_get_contents($handler);
			$this->validateRequest();

			$this->dbConn = new inc\database\DB();

			if( 'user_login' != strtolower( $this->serviceName.'_'.$this->methodName) ) {
				$this->validateToken();
			}
		}

		public function validateRequest() {
			// print_r($_SERVER);
			// if(empty($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
			// 	$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');
			// }
	
			$url = strtolower(trim($_SERVER['REQUEST_URI']));
			$urls = explode('/',ltrim( $url , '/') );


			if(empty($urls) || !in_array($urls[API_VER_INDEX], $this->activeVers))
			{

				$this->throwError(REQUEST_METHOD_NOT_VALID,"API does not exist.");
			}

			$this->apiVer = $urls[API_VER_INDEX];
			
			if(empty($urls[SERVICE_INDEX])) {
				$this->throwError(API_SERVICE_REQUIRED, "API service is required.");
			}

			$this->serviceName = $urls[SERVICE_INDEX];


			if(empty($urls[METHOD_INDEX])) {
				$this->throwError(API_METHOD_REQUIRED, "API service's method is required.");
			}

			$this->methodName = $urls[METHOD_INDEX];
			
			if($this->httpRequetMethod!=='GET'){
				
				$data = json_decode($this->request, true);

				if(empty($data)) {
					$this->throwError(API_PARAM_REQUIRED, "API PARAM is required.");
				}
				
				$this->param = $data;
			}
		}

		public function validateParameter($fieldName, $value, $dataType, $required = true) {

			if($required == true && empty($value) == true) {
				$this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName . " parameter is required.");
			}

			switch ($dataType) {
				case 'BOOLEAN':
					if(!is_bool($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be boolean.');
					}
					break;
				case 'INTEGER':
					if(!is_numeric($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be numeric.');
					}
					break;

				case 'STRING':
					if(!is_string($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be string.');
					}
					break;

				case 'DATETIME':
					$d = DateTime::createFromFormat('Y-m-d H:i:s', $value);

					if(!$d || $d->format('Y-m-d H:i:s') !== $value)
					{
						$d = date("Y-m-d H:i:s", strtotime($value) );
					}else
					{
						$d = $d->format('Y-m-d H:i:s');
					}
				
					if(!$d)
					{
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be string.');
					}
					
					$value = $d;
					break;
				
				default:
					$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName);
					break;
			}

			return $value;

		}


		public function validateToken() {
			try {
				$token = $this->getBearerToken();
				$payload = inc\JWT::decode($token, SECRET_KEY, ['HS256']);


				$user = $this->dbConn->selectSQL("SELECT * FROM users WHERE id = '{$payload->userId}' LIMIT 1");

				if(empty($user)) {
					$this->returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
				}

				$this->userId = $payload->userId;

			} catch (Exception $e) {
				$this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
			}
		}

		public function throwError($code, $message) {
			header("content-type: application/json");
			$errorMsg = json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
			echo $errorMsg; exit;
		}

		public function returnResponse($code, $data) {
			header("content-type: application/json");
			$response = json_encode(['response' => ['status' => $code, "result" => $data]]);
			echo $response; exit;
		}

		/**
	    * Get hearder Authorization
	    * */
	    public function getAuthorizationHeader(){
	        $headers = null;
	        if (isset($_SERVER['Authorization'])) {
	            $headers = trim($_SERVER["Authorization"]);
				
	        }
	        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	        } elseif (function_exists('apache_request_headers')) {
	            $requestHeaders = apache_request_headers();
	            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	            if (isset($requestHeaders['Authorization'])) {
	                $headers = trim($requestHeaders['Authorization']);
	            }
	        }
	        return $headers;
	    }
	    /**
	     * get access token from header
	     * */
	    public function getBearerToken() {
	        $headers = $this->getAuthorizationHeader();
	        // HEADER: Get the access token from the header
	        if (!empty($headers)) {
	            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	                return $matches[1];
	            }
	        }
	        $this->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
	    }
	}
 ?>