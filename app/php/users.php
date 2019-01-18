<?php
class Users {
	private $db;

	function __construct($host, $port, $dbName, $username, $password) {
		$postgresql = 'host='.$host.' port='.$port.' dbname='.$dbName.' user='.$username.' password='.$password;
		$this->db = pg_connect($postgresql);
		if (!$this->db) {
			throw new Exception('Error trying to connect to the database.');
		}
	}

	// Insert or update the username with the birthday
	public function insert($username, $dateOfBirth) {
		$username = htmlentities($username);
		$dateOfBirth = htmlentities($dateOfBirth);
		$query = '
			INSERT INTO users (username, dateOfBirth) VALUES ('.$username.', '.$dateOfBirth.')
			ON CONFLICT (username) DO
			UPDATE SET username = '.$username.', dateOfBirth = '.$dateOfBirth;

		return pg_query($this->db, $query); 
	}

	// Returns true if the username exists, otherwise false
	public function exist($username) {
		$username = htmlentities($username);
		$query = 'SELECT * FROM users WHERE username = '.$username;
		$result = pg_query($this->db, $query);
		return ($result?true:false);
	}

	// Returns the birthday from username, otherwise false
	public function getBirthday($username) {
		$username = htmlentities($username);
		$query = 'SELECT dateOfBirth FROM users WHERE username = '.$username;
		$result = pg_query($this->db, $query);
		if (!$result) {
			return false;
		}
		$row = pg_fetch_row($result);
		return $row[0];
	}
	
	// Returns the number of day before the birthday or zero if the birthday is today
	public function getBirthdayRemaining($username) {
		$currentDate = new DateTime('today');
		$birthday = $this->getBirthday($username);
		
		// Get month and day from birthday and concat with current year
		$birthdayMod = $currentDate->format('Y').substr($birthday, -6);
		$birthdayMod = new DateTime($birthdayMod);

		// Get the diff beteween birthday and current day
		$interval = $currentDate->diff($birthdayMod)->format('%r%a');
		if ($birthdayMod == $currentDate) {
			return 0;
		} elseif ($birthdayMod > $currentDate) {
			return $interval;
		} else {
			return (365+$interval);
		}
	}

}
?>