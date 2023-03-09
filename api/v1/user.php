<?php 
namespace v1;

class User
{
    private $restObj;
    private $tableName = "users";

    function __construct(\Rest $obj)
    {
        $this->restObj = $obj;
    }

    private function token_generate()
		{
		
			$username = $this->restObj->validateParameter('username', $this->restObj->param['username'], 'STRING');
			$pass = $this->restObj->validateParameter('password', $this->restObj->param['password'], 'STRING');

			try {

				$user = $this->restObj->dbConn->selectSQL("SELECT * FROM {$this->tableName} WHERE username = '{$this->restObj->dbConn->escapeStr($username)}' LIMIT 1");

				if(empty($user)) {
					$this->restObj->returnResponse(INVALID_USER_PASS, ["message"=>"Username or Password is incorrect."]);
				}

				////validate password
				if (!password_verify($pass, $user['password']))
				{
					$this->restObj->returnResponse(USER_NOT_EXISTED, ["message"=>"User not existed."]);
				}

				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (60*60),
					'userId' => $user['id']
				];

				return ['token'=> \inc\JWT::encode($paylod, SECRET_KEY), 'username' => $username ];

				// $this->restObj->returnResponse(SUCCESS_RESPONSE, $token);

			} catch (\Exception $e) {
				$this->restObj->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}

		}

		public function login()
		{
			try{
				$resp = $this->token_generate();
				$this->restObj->returnResponse(SUCCESS_RESPONSE, $resp);
			}
			catch(\Exception $e)
			{
				$this->restObj->throwError(INVALID_USER_PASS, $e->getMessage());
				
			}
		}

}

?>