<?php
class HTTP {

        public function method() {
                return $_SERVER['REQUEST_METHOD'];
        }

        public function response($httpCode='200', $httpMessage='OK', $data=array()) {
                header('HTTP/1.1 '.$httpCode.' '.$httpMessage);
		header('Content-Type: application/json');
		$json = json_encode($data);
		exit($json);
        }

        public function parameters($key) {
                $parameters = file_get_contents('php://input');
                $parameters = json_decode($inputs, true);
                if (isset($parameters[$key])) {
                        return $parameters[$key];
                }
                return false;
        }

        public function path() {
                return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

}
?>