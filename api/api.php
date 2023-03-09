<?php 

	class Api extends Rest {
		
		public function __construct() {
			parent::__construct();
		}

		public function process()
		{

			try {

				if(!class_exists('api_'.$this->apiVer))
                {
                    $this->throwError(API_DOES_NOT_EXIST, "API does not exist.");
                }

                $class = 'api_'.$this->apiVer;
                $api = new $class($this);

				$methodName = $this->serviceName.'_'.$this->methodName;
       
				if(method_exists($api, $methodName))
				{
					$api->$methodName();
				}


			} catch (Exception $e) {
				$this->throwError(API_DOES_NOT_EXIST, "API does not exist.");
			}
		}

		public function process_new()
		{

			try {

				if(!class_exists($this->serviceName))
                {
					$this->throwError(API_DOES_NOT_EXIST, "API service name does not exist.");
				}

				$service = new $this->serviceName($this);

				$methodName = $this->methodName.'_'.$this->apiVer;

				if(method_exists($service, $methodName))
				{
					$service->$methodName();
				}else
				{
					$this->throwError(API_DOES_NOT_EXIST, "API service method does not exist.");
				}


			} catch (Exception $e) {
				$this->throwError(API_DOES_NOT_EXIST, $e->getMessage());
			}
		}

		public function process_latest()
		{
			try {

				$class = $this->apiVer."\\".$this->serviceName;

				if(!class_exists($class))
                {
					$this->throwError(API_DOES_NOT_EXIST, "API service name does not exist.");
				}

				$service = new $class($this);

				$methodName = $this->methodName;
		
				if(method_exists($service, $methodName))
				{
					$service->$methodName();

				}else
				{
					$this->throwError(API_DOES_NOT_EXIST, "API service method does not exist.");
				}


			} catch (Exception $e) {
				$this->throwError(API_DOES_NOT_EXIST, $e->getMessage());
			}
		}
	
	}
	
 ?>