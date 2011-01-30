<?PHP


class Controller {
	
	function __construct(HTTP_Request $request, HTTP_Response $response){
		$this->request = $request;
		$this->response = $response;
	}
	
	function __call($method, $params){
		
		Logger::log('Controller', get_class($this).' doesn\'t have an action called '.$method, L_WARN);
		$this->response->setError('Controller '.get_class($this).' doesn\'t have an action called '.$method);
		$this->response->setStatus(404);
	}


    function requireLogin(){
        $session = Session::getInstance();
        if(!$session->get("loggedin")){
            $session->set("afterLogin", $this->request->uri);
            $this->response->redirect("/User/login");
            return false;
        }
        return true;
    }

    function requireAdmin(){
        $session = Session::getInstance();
        if($this->requireLogin()){
            $user = $session->get("user");
            if ($user->is_admin){
                return true;
            } else {
                throw Exception("Access Denied");
            }
        } else {
            return false;
        }
    }
}
