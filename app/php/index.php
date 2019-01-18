<?php
// Classes
include('users.php');
include('http.php');

// Objects
$http = new HTTP();
try {
	$users = new Users($_ENV['RDS_HOST'], $_ENV['RDS_PORT'], $_ENV['RDS_DBNAME'], $_ENV['RDS_USERNAME'], $_ENV['RDS_PASSWORD']);
} catch (Exception $e) {
	$http->response('400', 'Bad Request', array('status'=>$e));
}

// Main code
$path = explode('/hello/', $http->path());
if (empty($path[1])) {
	$http->response('400', 'Bad Request', array('status'=>'Wrong context. Expected /hello/<username>'));
}
$username = $path[1];

if ($http->method() === 'PUT') {
	$dateOfBirth = $http->parameters('dateOfBirth');
	if ($dateOfBirth) {
		$users->insert($username, $dateOfBirth);
		$http->response('204', 'No Content');
	}

	$http->response('400', 'Bad Request', array('status'=>'Wrong parameter. Expected parameter dateOfBirth.'));

} elseif ($http->method() === 'GET') {
	if ($users->exist($username)) {
		$diff = $users->getBirthdayRemaining($username);
		if ($diff==0) {
			$http->response('200', 'OK', array('message'=>'Hello, '.$username.'! Your birthday is today'));
		}
		$http->response('200', 'OK', array('message'=>'Hello, '.$username.'! Your birthday is in '.$diff));
	}
	
	$http->response('400', 'Bad Request', array('status'=>'The user doesn\'t exist.'));
}

$http->response('400', 'Bad Request', array('status'=>'Wrong method. Expected GET or PUT.'));

?>