<?php
/******************************************************

*   Author          Unbeleadsable <support@unbeleasable.com>
*   Version         1.0.3
*   Last modified   February 3rd, 2016
*   Web             http://
*   This is the database manager file. It contains access
*	to the database getting and setting records
*******************************************************/
define('LOG_FILE', "{$_SERVER['DOCUMENT_ROOT']}/app/user_action.log");
date_default_timezone_set('America/Montreal');
define('TPS', '0.05');
define('TVQ', '0.09975');

# Function to auload an object
require_once("User.class.php");
require_once("MySQLConnection.class.php");

class DBManager extends MySQLConnection{

	/**
		USERS SECTION
	*/

	/**
	*	Function to add a user in database.
	*	@arr:	form array
	*/

	public function addUser($arr){
		$query = $this->myDB->prepare("INSERT INTO users VALUES(DEFAULT, DEFAULT, ?, ?, ?, ?, ?, ?, NULL, DEFAULT, DEFAULT)");

		if($query->execute(array(trim($arr['username']), md5($arr['password']), trim($arr['email']), trim($arr['name']), $arr['country'], $arr['level']))){
			$this->write_to_log("User added [Username: " . $arr['username']. "]", $_SESSION['user']['username']);return true;
		}else
			return false;
	}

	/**
	*	Function to add a user in database.
	*	@arr:	form array
	*/
	public function addTeamUser($arr){
		$query = $this->myDB->prepare("INSERT INTO users VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, NULL, DEFAULT, DEFAULT)");

		if($query->execute(array($arr['main_user'], trim($arr['username']), md5($arr['password']), trim($arr['email']), trim($arr['name']), $arr['country'], $arr['level']))){
			$this->write_to_log("Team User added [Username: " . $arr['username']. "]", $_SESSION['user']['username']);return true;
		}else
			return false;
	}

	public function editTeamUser($arr){
		$query = $this->myDB->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?");
		return $query->execute(array(trim($arr['name']), trim($arr['email']), $arr['password'], $arr['user_id']));
	}

	public function getTeamUsers($userId){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id FROM users AS u LEFT JOIN agents AS a ON a.user_id = u.user_id WHERE u.main_user = ? AND COALESCE(a.agent_status, u.status) = " . User::ACTIVE);
		$query->execute(array($userId));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to delete a user -> set status to 0.
	*	@userId: id of the user to update
	*/
	public function deleteUser($userId){
		$query = $this->myDB->prepare("UPDATE users SET status = " . User::DELETED . " WHERE user_id = ?");
		return $query->execute(array($userId));
	}

	public function banUser($userId){
		$query = $this->myDB->prepare("UPDATE users SET status = " . User::BANNED . " WHERE user_id = ?");
		return $query->execute(array($userId));
	}

	public function reinstateUser($userId){
		$query = $this->myDB->prepare("UPDATE users SET status = " . User::ACTIVE . " WHERE user_id = ?");
		return $query->execute(array($userId));
	}

	/*
	*	Function to delete a user key.
	*	@keyId: id of the key.
	*/
	public function deleteUserKey($keyId){
		$query = $this->myDB->prepare("DELETE FROM users_keys WHERE user_key_id = ?");
		$query->execute(array($keyId));
	}

	/**
	*	Function to edit a user in database.
	*	@arr:	form array
	*/
	public function editUser($arr){
		$query = $this->myDB->prepare("UPDATE users SET username = ?, email = ?, name = ?, level = ? WHERE user_id = ?");

		if($query->execute(array(trim($arr['username']), trim($arr['email']), trim($arr['name']), $arr['level'], $arr['user_id']))){
			$this->write_to_log("User edited [Username: " . $arr['username']. "]", $_SESSION['user']['username']);
			return true;
		}else
			return false;
	}

	public function editAgentUser($email, $name, $id){
		$query = $this->myDB->prepare("UPDATE users SET email = ?, name = ? WHERE user_id = ?");
		return $query->execute(array(trim($email), trim($name), $id));
	}

	public function editAgentLogin($array){
		$query = $this->myDB->prepare("UPDATE users SET email = ?, username = ?, password = MD5(?) WHERE user_id = ?");
		return $query->execute(array(trim($array['email']), trim($array['username']), trim($array['password']), $array['user_id']));
	}

	/*
	*	Function to generate a temporary key for the user.
	*	@userId: id of the user.
	*	@value: value of the key.
	*	@type: type of the key.
	*/
	public function generateUserKey($userId, $value, $type){
		$query = $this->myDB->prepare("INSERT INTO users_keys(value, type, user_fk) VALUES(?, ?, ?);");
		$query->execute(array($value, $type, $userId));
	}

	/*
	*	Function to get a user by id.
	*	@userId: id of the user we want to get.
	*/
	public function getUser($userId){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id FROM users AS u LEFT JOIN agents AS a ON a.user_id = u.user_id WHERE u.user_id = ?");
		$query->execute(array($userId));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get a user by email.
	*	@email: email of the user.
	*/
	public function getUserByEmail($email){
		$query = $this->myDB->prepare("SELECT * FROM users WHERE email = ? AND status NOT IN (" . User::DELETED . ", " . User::BANNED .")");
		$query->execute(array($email));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get a user by username.
	*	@username: username of the user
	*/
	public function getUserByUsername($username){
		$query = $this->myDB->prepare("SELECT * FROM users WHERE username = ? AND status NOT IN (" . User::DELETED . ", " . User::BANNED .")");
		$query->execute(array($username));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get a key by it's value and type.
	*	@value: value of the key.
	*	@type: type of the key.
	*/
	public function getUserKey($value, $type){
		$query = $this->myDB->prepare("SELECT * FROM users_keys WHERE value = ? AND type = ?");
		$query->execute(array($value, $type));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get a key by an user id and type.
	*	@userId: id of the user.
	*	@type: type of the key.
	*/
	public function getUserKeyByUserId($userId, $type){
		$query = $this->myDB->prepare("SELECT * FROM users_keys WHERE user_fk = ? AND type = ?");
		$query->execute(array($userId, $type));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/**
	*	Function to get all the users from database
	*/
	public function getUsers(){
		$query = $this->myDB->query("SELECT * FROM users WHERE status = " . User::ACTIVE . " ORDER BY name ASC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	*	Function to get a single user from database
	*/
	public function getSingleUser($id){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id FROM users AS u LEFT JOIN agents AS a ON a.user_id = u.user_id WHERE u.user_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to reload the profile of a user.
	*	@userId: id of the user to reload.
	*/
	public function reloadProfile($userId){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id FROM users AS u LEFT JOIN agents AS a ON a.user_id = u.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE u.user_id = ?");
		$query->execute(array($userId));

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function reloadAgentProfile($agent_id){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id FROM users AS u LEFT JOIN agents AS a ON a.user_id = u.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_id = ?");
		$query->execute(array($agent_id));

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to reset a user's password.
	*	@userId: id of the user to reset password.
	*/
	public function resetPassword($userId){
		$query = $this->myDB->prepare("UPDATE users SET password = md5(''), changed_password = 0 WHERE user_id = ?");
		$query->execute(array($userId));
	}

	/**
	 *	Function to check if the login credentials are correct.
	 *	@login: 			username or email of the user.
	 *	@password:			password of the user without encryption.
	 */
	public function signIn($login, $password){
		$query = $this->myDB->prepare("SELECT *, COALESCE(a.user_id, u.user_id) as user_id
										   FROM users AS u
										   LEFT JOIN agents AS a ON a.user_id = u.user_id
										   WHERE (u.username = ? OR u.email = ?) AND u.password = ? AND u.status = 1");
		$query->execute(array($login, $login, md5($password)));

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to update the last login time of an user.
	*	@userId: id of the user to update
	*/
	public function updateLastLogin($user){
		$query = $this->myDB->prepare("UPDATE users SET last_login = NOW() WHERE (username = ? OR email = ?)");
		$query->execute(array($user,$user));
	}

	/*
	*	Function to update a user's password.
	*	@userId: id of the user
	*	@password: password without encryption
	*/
	public function updatePassword($user_id, $password){
		$query = $this->myDB->prepare("UPDATE users SET password = ?, changed_password = 1 WHERE user_id = ?");
		$query->execute(array(md5($password), $user_id));
	}

	/*
	*	Function to update the last usage of a key.
	*	@keyId: id of the key to update.
	*/
	public function updateUserKeyUsage($keyId){
		$query = $this->myDB->prepare("UPDATE users_keys SET last_access_time = NOW() WHERE user_key_id = ?");
		$query->execute(array($keyId));
	}


	/*
	*	Function to add an email to the blacklist.
	*	@email: email to blacklist
	*/
	public function addBlacklist($email){
		$query = $this->myDB->prepare("INSERT IGNORE INTO blacklists(email) VALUES(?)");
		return $query->execute(array(trim($email)));
	}

	public function removeBlacklist($email){
		$query = $this->myDB->prepare("DELETE FROM blacklists WHERE email = ?");
		$query->execute(array(trim($email)));
	}

	public function getBlacklists(){
		$query = $this->myDB->query("SELECT email AS emails FROM blacklists");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get an email from the blacklist table
	*	@email: email to search for
	*/
	public function getBlacklistEmail($email){
		$query = $this->myDB->prepare("SELECT * FROM blacklists WHERE email = ?");
		$query->execute(array($email));
		return $query->fetch(PDO::FETCH_ASSOC);
	}



	/**
		INVOICES
	*/
	/*
	*	Function to register a new Student.
	*	@data: array with the information of the student.
	*/
	public function addInvoice($data){
		try{
			$this->myDB->beginTransaction();

			$query = $this->myDB->prepare("INSERT INTO invoices(invoice_num, install, monthly, ads, agent_fk) VALUES(?, ?, ?, ?, ?)");
			$query->execute(array(
				$data['invoice_num'],
				$data['install'],
				$data['monthly'],
				$data['ads'],
				$data['agent_id']));

			$lastInsertId = $this->myDB->lastInsertId();
			$this->myDB->commit();
		}catch(PDOException $e){
			$this->myDB->rollback();
		}

		$this->write_to_log("New invoice added [Invoice Num: " . $data['invoice_num'], "System");
	}

	/*
	*	Function to register a new Student.
	*	@data: array with the information of the student.
	*/
	public function addAnyInvoice($data){
		$query = $this->myDB->prepare("INSERT INTO other_invoices VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$query->execute($data);
		$this->write_to_log("New Any invoice added [Invoice Num: " . $data[1], "System");
	}

	/*
	*	Function to get all invoices from database
	*/
	public function getInvoices(){
		$query = $this->myDB->query("SELECT i.*, a.agent_name AS name FROM invoices AS i JOIN agents AS a ON agent_id = i.agent_fk ORDER BY i.invoice_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all invoices from database
	*/
	public function getOtherInvoices(){
		$query = $this->myDB->query("SELECT * FROM other_invoices ORDER BY invoice_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}


	/*
	*	Function to search all invoices from database
	*/
	public function searchInvoices($search){
		$whereClause = "";
		$whereArray = array();

		if($search['agent_fk'] != "-1"){
			$whereClause .= " agent_fk = ? AND";
			array_push($whereArray, $search['agent_fk']);
		}

		if($search['month'] != "-1"){
			$whereClause .= " MONTH(invoice_date) = ? AND";
			array_push($whereArray, $search['month']);
		}

		$daterange = explode(" - ", $search["daterange"]);

		if($daterange[0] != "All Dates"){
			$whereClause .= " DATE(invoice_date) BETWEEN ? AND ? AND";
			array_push($whereArray, $daterange[0]);
			array_push($whereArray, $daterange[1]);
		}

		if($search['year'] != "-1"){
			$whereClause .= " YEAR(invoice_date) = ? AND";
			array_push($whereArray, $search['year']);
		}

		if($whereClause == "")
			$whereClause = "1=1";
		else
			$whereClause = substr($whereClause, 0, -4);

		$query = $this->myDB->prepare("SELECT i.*, a.agent_name name FROM invoices AS i JOIN agents AS a ON a.agent_id = i.agent_fk WHERE " . $whereClause ." ORDER BY i.invoice_id DESC");
		$query->execute($whereArray);

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to search all invoices from database
	*/
	public function searchOtherInvoices($search){
		$whereClause = "";
		$whereArray = array();

		if($search['month'] != "-1"){
			$whereClause .= " MONTH(invoice_date) = ? AND";
			array_push($whereArray, $search['month']);
		}

		$daterange = explode(" - ", $search["daterange"]);

		if($daterange[0] != "All Dates"){
			$whereClause .= " DATE(invoice_date) BETWEEN ? AND ? AND";
			array_push($whereArray, $daterange[0]);
			array_push($whereArray, $daterange[1]);
		}

		if($search['year'] != "-1"){
			$whereClause .= " YEAR(invoice_date) = ? AND";
			array_push($whereArray, $search['year']);
		}

		if($whereClause == "")
			$whereClause = "1=1";
		else
			$whereClause = substr($whereClause, 0, -4);

		$query = $this->myDB->prepare("SELECT * FROM other_invoices  WHERE " . $whereClause ." ORDER BY invoice_id DESC");
		$query->execute($whereArray);

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get single invoices from database
	*/
	public function getInvoice($invoice_number){
		$query = $this->myDB->prepare("SELECT * FROM invoices AS i JOIN agents ON agent_id = i.agent_fk WHERE i.invoice_num = ?");
		$query->execute(array($invoice_number));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get single other invoices from database
	*/
	public function getOtherInvoice($invoice_number){
		$query = $this->myDB->prepare("SELECT * FROM other_invoices WHERE invoice_num = ?");
		$query->execute(array($invoice_number));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAgentInvoice($agent){
		$query = $this->myDB->prepare("SELECT * FROM invoices WHERE agent_fk = ?");
		$query->execute(array($agent));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllInvoices(){
		$query = $this->myDB->query("SELECT i.* FROM invoices AS i JOIN agents ON agent_id = i.agent_fk");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getMonthlyIncome($month){
		$query = $this->myDB->prepare("SELECT SUM(total) AS total FROM invoices WHERE MONTH(invoice_date) = ?");
		$query->execute(array($month));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getYearlyIncome($year){
		$query = $this->myDB->prepare("SELECT SUM(total) AS total FROM invoices WHERE YEAR(invoice_date) = ?");
		$query->execute(array($year));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getIncomeBetween($from, $to){
		$query = $this->myDB->prepare("SELECT SUM(total) AS total FROM invoices WHERE invoice_date BETWEEN ? AND ?");
		$query->execute(array($from, $to));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/**
		EMAIL SECTION
	*/

	/*
	*	Function to add a copy of an email.
	*	@to: the receiver of the email
	*	@content: the content of the email
	*/
	public function addEmail($to, $content){
		$query = $this->myDB->prepare("INSERT INTO emails(`to`, `content`) VALUES(?, ?)");

		if($query->execute(array($to, $content)))
			return true;
		else
			return false;
	}

	public function getEmail($id){
		$query = $this->myDB->prepare("SELECT * FROM emails WHERE email_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all the emails.
	*/
	public function getEmails(){
		$query = $this->myDB->query("SELECT * FROM emails ORDER BY email_sent_date DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	AGENT LEADS SECTION
	 */

	public function getAgentLeads(){
		$query = $this->myDB->query("SELECT * FROM agent_leads WHERE lead_status > 0 ORDER BY lead_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentLeadsByCountry($country){
		$query = $this->myDB->prepare("SELECT * FROM agent_leads WHERE lead_status > 0 AND lead_country = ? ORDER BY lead_id DESC");
		$query->execute(array($country));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getBuyerAgentLeads(){
		$query = $this->myDB->query("SELECT * FROM agent_leads WHERE lead_status > 0 AND lead_type = 'home_buyers' ORDER BY lead_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSellerAgentLeads(){
		$query = $this->myDB->query("SELECT * FROM agent_leads WHERE lead_status > 0 AND lead_type = 'home_sellers' ORDER BY lead_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentLeadsBulk($bulk){
		$query = $this->myDB->query("SELECT CONCAT_WS(' - ', lead_name, lead_agency) AS name FROM agent_leads WHERE lead_id IN ($bulk) AND lead_status > 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentLeadsByEmail($email){
		$query = $this->myDB->prepare("SELECT * FROM agent_leads WHERE lead_email = ? AND lead_status > 0");
		$query->execute(array($email));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAgentLeadsByID($id){
		$query = $this->myDB->prepare("SELECT * FROM agent_leads WHERE lead_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteAgentLeads($id){
		if(is_numeric($id)){
			$query = $this->myDB->query("UPDATE agent_leads SET lead_status = 0 WHERE lead_id = $id");
			return 1;
		}
	}

	public function removeAgentLeads($id){
		if(is_numeric($id)){
			$query = $this->myDB->query("DELETE FROM agent_leads WHERE lead_id = $id");
			return 1;
		}
	}

	public function addToAgentLeads($data, $user_id = 0){
		$error = false;

		if($data['buyer_option'] == "buyers" OR $data['buyer_option'] == "both"){
			$query = $this->myDB->prepare("INSERT INTO agent_leads VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, DEFAULT, ?, ?, ?, DEFAULT, DEFAULT, DEFAULT)");
			$error = $query ->execute(array(uniqid(), $user_id, $data['name'], $data['email'], $data['phone'], $data['areas'] . ", " . $data['state'], $data['agency'], $data['lang'], $data['ref'], $data['country'], "home_buyers"));
		}

		if($data['buyer_option'] == "sellers" OR $data['buyer_option'] == "both"){
			$query= $this->myDB->prepare("INSERT INTO agent_leads VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, DEFAULT, ?, ?, ?, DEFAULT, DEFAULT, DEFAULT)");
			$error = $query->execute(array(uniqid(), $user_id, $data['name'], $data['email'], $data['phone'], $data['areas'] . ", " . $data['state'], $data['agency'], $data['lang'], $data['ref'], $data['country'], "home_sellers"));
		}

		return $error;
	}

	public function editAgentLeads($data){
		$query = $this->myDB->prepare("UPDATE agent_leads SET lead_name = ?, lead_email = ?, lead_phone = ?, lead_areas = ?, lead_agency = ?, lead_license = ?, lead_board = ?, lead_ref = ?, lead_comments = ?, lead_lang = ? WHERE lead_id = ?");
		return $query->execute(array($data['name'], $data['email'], $data['phone'], $data['areas'], $data['agency'], $data['license'], $data['board'], $data['ref'], $data['comments'], $data['lang'], $data['id']));
	}

	public function UpdateAgentLeadsStatus($id, $lead){
		$query = $this->myDB->prepare("UPDATE agent_leads SET lead_status = ? WHERE lead_id = ?");
		return $query->execute(array($id, $lead));
	}

	public function addLeadComment($arr){
		$query = $this->myDB->prepare("UPDATE agent_leads SET lead_comments = ? WHERE lead_id = ?");
		$query->execute(array($arr['comments'], $arr['id']));
	}

	/**
		LEADS STATUS
	*/
	public function getAgentStatus($id){
		$query = $this->myDB->query("SELECT * FROM status WHERE (agent_fk = 0 OR agent_fk = $id) AND deleted = 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addLeadStatus($arr){
		$query = $this->myDB->prepare("INSERT INTO status VALUES(DEFAULT, ?, ?, ?, DEFAULT)");
		return $query->execute(array($arr['name_en'], $arr['name_fr'], $arr['agent_fk']));
	}

	public function getStatus($id){
		$query = $this->myDB->query("SELECT * FROM status WHERE id = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function editLeadStatus($arr){
		$query = $this->myDB->prepare("UPDATE status SET name_en = ?, name_fr = ? WHERE id = ?");
		$query->execute(array($arr['name_en'], $arr['name_fr'], $arr['id']));
	}

	public function deleteStatus($id){
		$query = $this->myDB->query("UPDATE status SET deleted = 1 WHERE id = $id");
	}


	/**
		CREDIT CARDS
	*/
	public function addCreditCard($arr){
		$query = $this->myDB->prepare("INSERT INTO credit_cards VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, DEFAULT, ?)");
		return $query->execute(array($arr['name'], $arr['type'], $arr['num'], $arr['mm'], $arr['year'], $arr['cvv'], $arr['payment'], $arr['agent_fk']));
	}

	public function updateCreditCard($arr){
		$query = $this->myDB->prepare("UPDATE credit_cards SET name = ?, type = ?, mm = ?, year = ?, cvv = ? WHERE id = ?");
		$query->execute(array($arr['name'], $arr['type'], $arr['mm'], $arr['year'], $arr['cvv'], $arr['card']));
	}

	public function addDefaultCreditCard($arr){
		$query = $this->myDB->prepare("INSERT INTO credit_cards VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		return $query->execute(array($arr['name'], $arr['type'], $arr['num'], $arr['mm'], $arr['year'], $arr['cvv'], $arr['payment'], 1, $arr['id']));
	}

	public function getCreditCards($type, $id){
		$query = $this->myDB->query("SELECT id, num, selected FROM credit_cards WHERE agent_fk = $id AND payment = $type");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCreditCard($id){
		$query = $this->myDB->query("SELECT * FROM credit_cards WHERE agent_fk = $id AND payment = 1 AND selected = 1");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getSingleCreditCard($id){
		$query = $this->myDB->query("SELECT * FROM credit_cards WHERE id = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function selectCreditCard($type, $id){
		$query = $this->myDB->prepare("UPDATE credit_cards SET selected = ? WHERE id = ?");
		return $query->execute(array($type, $id));
	}

	public function resetSelected($id){
		$query = $this->myDB->query("UPDATE credit_cards SET selected = 0 WHERE agent_fk = $id");
	}

	public function deleteCreditCard($id){
		$query = $this->myDB->query("DELETE FROM credit_cards WHERE id = $id");
	}


	/**
		AGENTS
	*/
	public function createAgent($id, $address, $ad, $password){
		$data = $this->getAgentLeadsByID($id);

		$this->myDB->query("UPDATE agent_leads SET lead_status = -1 WHERE lead_id = $id");

		$signature = "<p><b>". $data['lead_name'] . "</b></p><p>" . $data['lead_email'] . "</p><p>" . $data['lead_phone'] . "</p><p>" . $data['lead_agency']."</p>";

		$query = $this->myDB->query("UPDATE area_mapping SET agent_type = 2 WHERE agent_fk = '" . $data['internal_id']. "'");
		$query = $this->myDB->prepare("INSERT INTO agents(internal_id, agent_name, agent_email, agent_phone, agent_address, agent_areas, agent_agency, agent_license, agent_board, agent_ref, agent_lang, agent_comments, agent_signature, ad_campaign, agent_slug) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$query->execute(array($data['internal_id'], $data['lead_name'], $data['lead_email'], $data['lead_phone'], $address, $data['lead_areas'], $data['lead_agency'],$data['lead_license'], $data['lead_board'], $data['lead_ref'], $data['lead_lang'], $data['lead_comments'], $signature, $ad, $data['lead_type']));
		$agent_id = $this->myDB->lastInsertId();

		if($data['user_id'] == 0){
			$this->addUser(array('username' => str_replace(array(" ", "-"), "", strtolower($data['lead_name'])).$agent_id, 'password' => $password, 'email' => $data['lead_email'], 'name' => $data['lead_name'], 'level' => 10));
			$user_id = $this->myDB->lastInsertId();
			$query = $this->myDB->query("UPDATE agents SET user_id = $user_id WHERE agent_id = $agent_id");
		}else{
			$user_id = $data['user_id'];
			$query = $this->myDB->query("UPDATE agents SET user_id = $user_id WHERE agent_id = $agent_id");
		}

		if($data['lead_type'] == 'home_sellers'){
			$this->myDB->query("INSERT INTO agent_landing_page(agent_fk) VALUES ($agent_id)");
			$this->funnelPerAgent($agent_id, 'home_sellers');
		}else if($data['lead_type'] == 'home_buyers'){
			$city = $data['lead_areas'];
			$this->myDB->query("INSERT INTO buyers_landing_page(agent_fk, city) VALUES ($agent_id, '$city')");
			$this->funnelPerAgent($agent_id, 'home_buyers');
		}

		return $this->getAgentByID($agent_id);
	}

	//GOD THIS IS GREASSY AND I WILL PROBABLY GO TO HELL FOR THIS, OH WELL...#sorrynotsosorry
	//Function to retrieve all the funnel categories and their individual funnels and add it back for the said agent
	public function funnelPerAgent($agent_id, $agent_type){
		$query = $this->myDB->query("SELECT * FROM funnel_category WHERE agent = 0 AND agent_type = '$agent_type' AND status = 1");
		foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $category) {
			$this->myDB->query("INSERT INTO funnel_category VALUES (DEFAULT, '".$category['title']."', $agent_id, '$agent_type', 1)");
			$cat_id = $this->myDB->lastInsertId();
			$query = $this->myDB->query("SELECT * FROM funnels WHERE agent = 0 AND status = 1 AND category = " . $category['id']);


			foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $funnel){
				$off = $this->myDB->query("SELECT GROUP_CONCAT(funnel_fk) f FROM funnels_off WHERE agent_fk = $agent_id");
				$off_result = explode(",", $off->fetch(PDO::FETCH_ASSOC)['f']);
				$status = in_array($funnel['funnel_id'], $off_result)?0:1;
				$query = $this->myDB->prepare("INSERT INTO funnels VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?)");
				$query->execute(array($funnel['name'], $funnel['content'], $funnel['interval'], $cat_id, $funnel['language'], $agent_id, $status));
			}
		}
	}

	public function getAgentByID($id){
		$query = $this->myDB->prepare("SELECT a.*, GROUP_CONCAT(area_name) assigned_area FROM agents a LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_id = ? AND a.agent_status > 0 GROUP BY a.agent_id ORDER BY a.agent_id DESC");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAllAgentEmails(){
		$query = $this->myDB->query("SELECT GROUP_CONCAT(agent_email) AS emails FROM agents WHERE agent_status > 0");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAgentByUserID($id){
		$query = $this->myDB->prepare("SELECT * FROM agents WHERE user_id = ? AND agent_status > 0");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAgentByEmail($email){
		$query = $this->myDB->prepare("SELECT * FROM agents WHERE agent_email = ? AND agent_status > 0");
		$query->execute(array($email));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAgents(){
		$query = $this->myDB->query("SELECT a.*, u.user_id, u.status, GROUP_CONCAT(area_name) assigned_area FROM agents a JOIN users u ON u.user_id = a.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_status > 0 GROUP BY a.agent_id ORDER BY a.agent_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentsByCountry($country){
		$query = $this->myDB->prepare("SELECT a.*, u.user_id, u.status, GROUP_CONCAT(area_name) assigned_area FROM agents a JOIN users u ON u.user_id = a.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_status > 0 AND agent_country = ? GROUP BY a.agent_id ORDER BY a.agent_id DESC");
		$query->execute(array($country));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getBuyerAgents(){
		$query = $this->myDB->query("SELECT a.*, u.user_id, u.status, GROUP_CONCAT(area_name) assigned_area FROM agents a JOIN users u ON u.user_id = a.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_status > 0 AND a.agent_slug = 'home_buyers' GROUP BY a.agent_id ORDER BY a.agent_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSellerAgents(){
		$query = $this->myDB->query("SELECT a.*, u.user_id, u.status, GROUP_CONCAT(area_name) assigned_area FROM agents a JOIN users u ON u.user_id = a.user_id LEFT JOIN area_mapping am ON am.agent_fk = a.internal_id LEFT JOIN areas ar ON am.area_fk = ar.area_id WHERE a.agent_status > 0 AND a.agent_slug = 'home_sellers' GROUP BY a.agent_id ORDER BY a.agent_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentsAll(){
		$query = $this->myDB->query("SELECT * FROM agents WHERE agent_status = 1");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentsAllByCountry($country){
		$query = $this->myDB->prepare("SELECT * FROM agents WHERE agent_status = 1 AND agent_country = ?");
		$query->execute(array($country));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function editAgent($data){
		$query = $this->myDB->prepare("UPDATE agents SET agent_name = ?, agent_email = ?, agent_address = ?, agent_phone = ?, agent_avatar = ?, agent_areas = ?, agent_agency = ?, agent_license = ?, agent_board = ?, agent_ref = ?, campaign_id = ?, agent_comments = ?, agent_lang = ?, agent_signature = ?, phone_alert =?, email_alert = ? WHERE agent_id = ?");
		$this->write_to_log("User edited [data: " . implode(",", $data). "]", $_SESSION['user']['username']);return $query->execute(array($data['name'], $data['email'], $data['address'], $data['phone'], $data['avatar'], $data['areas'], $data['agency'], $data['license'], $data['board'], $data['ref'], $data['camp'], $data['comments'], $data['lang'], $data['signature'], $data['phone_notification'], $data['email_notification'], $data['id']));
	}

	public function upadateAdBudget($amount){
		$query = $this->myDB->prepare("UPDATE agents SET ad_campaign = ? WHERE agent_id = ?");
		return $query->execute(array($amount, $_SESSION['user']['agent_id']));
	}

	public function getAgentsBulk($bulk){
		$query = $this->myDB->query("SELECT CONCAT_WS(' - ', agent_name, agent_agency) AS name FROM agents WHERE agent_id IN ($bulk) AND agent_status > 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function deleteAgent($id){
		if(is_numeric($id)){
			$query = $this->myDB->query("UPDATE agents SET agent_status = 0 WHERE agent_id = $id");
			return 1;
		}
	}

	public function agents_budget(){
		$query = $this->myDB->query("
			SELECT a.agent_id, a.user_id, a.agent_name, a.campaign_id, a.budget_comments, a.agent_slug,
			GROUP_CONCAT(area_name) assigned_area, i.invoice_date, r.next_billing, ads
			FROM agents a JOIN area_mapping am
			ON am.agent_fk = a.internal_id
			JOIN areas ar ON am.area_fk = ar.area_id
			JOIN invoices i ON i.agent_fk = a.agent_id
			JOIN reccurent r ON r.agent_fk = a.agent_id
			WHERE a.agent_status > 0 AND a.agent_slug = 'home_sellers'
			AND invoice_id = (
				SELECT MAX(invoice_id)
				FROM invoices
				WHERE agent_fk = agent_id)
			GROUP BY a.agent_id
			ORDER BY a.agent_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function agents_buyer_budget(){
		$query = $this->myDB->query("
			SELECT a.agent_id, a.user_id, a.agent_name, a.campaign_id, a.budget_comments, a.agent_slug,
			GROUP_CONCAT(area_name) assigned_area, i.invoice_date, r.next_billing, ads
			FROM agents a JOIN area_mapping am
			ON am.agent_fk = a.internal_id
			JOIN areas ar ON am.area_fk = ar.area_id
			JOIN invoices i ON i.agent_fk = a.agent_id
			JOIN reccurent r ON r.agent_fk = a.agent_id
			WHERE a.agent_status > 0
			AND a.agent_slug = 'home_buyers'
			AND invoice_id = (
				SELECT MAX(invoice_id)
				FROM invoices
				WHERE agent_fk = agent_id)
			GROUP BY a.agent_id
			ORDER BY a.agent_id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateAgentStatus($agent, $status){
		$this->myDB->query("UPDATE agents SET agent_status = $status WHERE agent_id = $agent");
	}

	public function enableDisableAgent($agent, $user, $action){
		if($action == "lock"){
			$this->myDB->query("UPDATE agents SET agent_status = 3 WHERE agent_id = $agent");
			$this->myDB->query("UPDATE reccurent SET status = 0 WHERE agent_fk = $agent");
			$this->banIfOnlyOneAccountLeft($user);

		}else{
			$this->myDB->query("UPDATE agents SET agent_status = 1 WHERE agent_id = $agent");
			$this->myDB->query("UPDATE users SET status = 1 WHERE user_id = $user");

			if(date('Y-m-d', strtotime($this->agentNextPayment($agent)['next_billing']. ' + 1 months')) <= date("Y-m-d"))
				$this->myDB->query("UPDATE reccurent SET next_billing = DATE_ADD(NOW(), INTERVAL 1 DAY), next_try = next_billing, counter = 0, status = 1 WHERE agent_fk = $agent");
			else
				$this->myDB->query("UPDATE reccurent SET status = 1 WHERE agent_fk = $agent");
		}
	}

	public function banIfOnlyOneAccountLeft($userID){
		$other_accounts = $this->myDB->query("SELECT agent_id FROM agents a JOIN reccurent r ON r.agent_fk = a.agent_id WHERE a.user_id = $userID AND r.status = 1");

		if(COUNT($other_accounts->fetchAll(PDO::FETCH_ASSOC)) <= 1)
			$this->myDB->query("UPDATE users SET status = 2 WHERE user_id = $userID");
	}


	/**
		AREA MAPPING
	*/
	public function getAreaByName($name){
		$query = $this->myDB->prepare("SELECT * FROM areas WHERE area_name = ? AND area_status > 0");
		$query->execute(array($name));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function addArea($data){
		try{
			$this->myDB->beginTransaction();

			$query = $this->myDB->prepare("INSERT INTO areas(area_name, area_latlng) VALUES(?, ?)");
			$query->execute(array($data['areaName'], $data['latlng']));

			$lastInsertId = $this->myDB->lastInsertId();
			$this->myDB->commit();
		}catch(PDOException $e){
			$this->myDB->rollback();
		}

		foreach ($data['agents'] as $value) {
			$query = $this->myDB->prepare("INSERT INTO area_mapping VALUES(?, ?, ?)");
			$val = array($lastInsertId,substr($value, 2));

			if($value[0] == "l")
				array_push($val, 1);
			elseif($value[0] == "a")
				array_push($val, 2);

			$query->execute($val);
		}

		return 1;
	}

	public function editArea($data){
		$query = $this->myDB->prepare("UPDATE areas SET area_name = ?, area_latlng = ? WHERE area_id = ?");
		$query->execute(array($data['areaName'], $data['latlng'], $data['id']));

		$query = $this->myDB->query("DELETE FROM area_mapping WHERE area_fk = " . $data['id']);

		foreach ($data['agents'] as $value) {
			try{
				$query = $this->myDB->prepare("INSERT INTO area_mapping VALUES(?, ?, ?)");
				$val = array($data['id'],substr($value, 2));

				if($value[0] == "l")
					array_push($val, 1);
				elseif($value[0] == "a")
					array_push($val, 2);

				$query->execute($val);
			}catch(PDOException $e){
				#NOTHING TO DO HERE
			}
		}

		return 1;
	}

	public function getAgentsByAreaID($id){
		$query = $this->myDB->query("
			SELECT area_fk,agent_fk,am.agent_type mapping_type,lead_id,lead_name,lead_email,lead_phone,lead_areas,lead_agency,lead_lang,lead_license,lead_board,lead_ref,lead_type,lead_status,lead_comments,lead_date,agent_id,a.internal_id,a.user_id,agent_name,agent_email,agent_phone,agent_address,agent_areas,agent_agency,agent_license,agent_board,agent_ref,agent_lang,agent_status,agent_comments,agent_signature,ad_campaign,campaign_id,budget_comments,phone_alert,email_alert,agent_slug,agent_date
			FROM area_mapping AS am
			LEFT JOIN agent_leads AS al ON am.agent_fk = al.internal_id
			LEFT JOIN agents AS a ON am.agent_fk = a.internal_id
			WHERE am.area_fk = $id AND (a.agent_status = 1 OR al.lead_status = 1)");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAreas(){
		$query = $this->myDB->query("SELECT * FROM areas WHERE area_status > 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAreasByCountry($country){
		$c = $country == "US" ? "United States" : "Canada";
		$query = $this->myDB->query("SELECT * FROM areas WHERE area_status > 0 AND area_name LIKE '%$c'");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getOtherAreas($user, $id){
		$query = $this->myDB->query("SELECT a.area_name, ag.agent_id , ag.agent_slug
									 FROM areas a
									 LEFT JOIN area_mapping am ON am.area_fk = a.area_id
									 LEFT JOIN agents AS ag ON am.agent_fk = ag.internal_id
									 WHERE ag.user_id = $user AND ag.agent_status IN (1,2)");
		return $query ? $query->fetchAll(PDO::FETCH_ASSOC): false;
	}

	public function getAgentArea($internal_id){
		$query = $this->myDB->query("SELECT area_name FROM areas JOIN area_mapping ON area_fk = area_id WHERE agent_fk = $internal_id AND area_status > 0");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getAreaById($id){
		$query = $this->myDB->prepare("SELECT a.*, GROUP_CONCAT(CASE agent_type WHEN 1 THEN CONCAT('l_', am.agent_fk) ELSE CONCAT('a_', am.agent_fk) END) AS area_agents FROM areas AS a LEFT JOIN area_mapping AS am ON am.area_fk = a.area_id WHERE a.area_id = ? AND a.area_status > 0 GROUP BY a.area_id");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteArea($id){
		if(is_numeric($id)){
			$query = $this->myDB->query("UPDATE areas SET area_status = 0 WHERE area_id = $id");
			return 1;
		}
	}

	/**
	LANDING PAGES
	*/

	public function getSellerLandingPage($id){
		$query = $this->myDB->prepare("SELECT * FROM agent_landing_page AS l JOIN agents AS a ON a.agent_id = l.agent_fk WHERE l.agent_fk = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getBuyerLandingPage($id){
		$query = $this->myDB->prepare("SELECT * FROM buyers_landing_page AS l JOIN agents AS a ON a.agent_id = l.agent_fk WHERE l.agent_fk = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function editSellerLandingPage($data){
		$query = $this->myDB->prepare("UPDATE agent_landing_page SET city_fr = ?, city_en = ?, title_fr = ?, title_en = ?, sub_title_1_fr = ?, sub_title_1_en = ?, sub_title_2_fr = ?, sub_title_2_en = ?, bg_img = ?, agent_title_fr = ?, agent_title_en = ?, final_text_fr = ?, final_text_en = ? WHERE id = ?");
		return $query->execute(array($data['city_fr'], $data['city_en'], $data['title_fr'], $data['title_en'], $data['sub_title_1_fr'], $data['sub_title_1_en'], $data['sub_title_2_fr'], $data['sub_title_2_en'], $data['defaultBackground'], $data['agent_title_fr'], $data['agent_title_en'], $data['final_text_fr'], $data['final_text_en'], $data['id']));
	}

	public function editBuyerLandingPage($data){
		$query = $this->myDB->prepare("UPDATE buyers_landing_page SET title_en = ?, title_fr = ?, sub_title_en = ?, sub_title_fr = ?, email_field_en = ?, email_field_fr = ?, next_button_en = ?, next_button_fr = ?, bedroom_label_en = ?, bedroom_label_fr = ?, buying_frame_en = ?, buying_frame_fr = ?, city = ?, name_label_en = ?, name_label_fr = ?, name_field_en = ?, name_field_fr = ?, phone_label_en = ?, phone_label_fr = ?, phone_field_en = ?, phone_field_fr = ?, thank_you_en = ?, thank_you_fr = ?, bg_img = ? WHERE id = ?");
		return $query->execute(array($data["title_en"], $data["title_fr"], $data["sub_title_en"], $data["sub_title_fr"], $data["email_field_en"], $data["email_field_fr"], $data["next_button_en"], $data["next_button_fr"], $data["bedroom_label_en"], $data["bedroom_label_fr"], $data["buying_frame_en"], $data["buying_frame_fr"], $data["city_fr"], $data["name_label_en"], $data["name_label_fr"], $data["name_field_en"], $data["name_field_fr"], $data["phone_label_en"], $data["phone_label_fr"], $data["phone_field_en"], $data["phone_field_fr"], $data["thank_you_en"], $data["thank_you_fr"], $data["defaultBackground"], $data['id']));
	}

	/**
			HOME BUYER LEADS
	*/
	public function addBuyerLead($email, $agent, $src, $lang, $funnelID){
		$id = -1;

		$query = $this->myDB->prepare("SELECT id FROM home_buyers WHERE email = ?");
		$query->execute(array($email));
		$result = $query->fetch(PDO::FETCH_ASSOC);

		if($result){
			$id = $result['id'];
			$this->myDB->query("UPDATE home_buyers SET `date` = now(), comments = '', agent_fk = $agent WHERE id = $id");
		}else{
			$query = $this->myDB->prepare("INSERT INTO home_buyers (email, agent_fk, source, type, lang) VALUES(?, ?, ?, ?, ?)");
			$query->execute(array($email, $agent, $src, 'Buyer', $lang));
			$id = $this->myDB->lastInsertId();
			//$this->myDB->query("INSERT INTO home_sellers_meta(home_lead_fk) VALUES($id)");
		}

		return $id;
	}

	public function updateBuyerLeadPartial($col, $val, $id){
		$query = $this->myDB->prepare("UPDATE home_buyers SET {$col} = ? WHERE id = ?");
		$query->execute(array($val, $id));

		$query = $this->myDB->prepare("SELECT * FROM home_buyers WHERE id = ? AND DATE(`date`) = DATE(NOW())");
		$query->execute(array($id));
		$res = $query->fetch();

		if($res['name'] != '' AND $res['email'] != '' AND $res['phone'] != '' AND $res['buying'] != ''){
			$this->myDB->query("UPDATE home_buyers SET status = 1 WHERE id = " . $res['id']);
			return $res;
		}
	}


	/**
	HOME SELLER LEADS
	*/
	public function addAddressLead($address, $agent, $src, $lang, $funnelID){
		$id = -1;

		$query = $this->myDB->prepare("SELECT id FROM home_sellers WHERE address = ?");
		$query->execute(array($address));
		$result = $query->fetch(PDO::FETCH_ASSOC);

		if($result){
			$id = $result['id'];
			$this->myDB->query("UPDATE home_sellers SET `date` = now(), comments = '', funnels = $funnelID, agent_fk = $agent WHERE id = $id");
		}else{
			$query = $this->myDB->prepare("INSERT INTO home_sellers (address, funnels, agent_fk, source, type, lang) VALUES(?, ?, ?, ?, ?, ?)");
			$query->execute(array($address, $funnelID, $agent, $src, 'Seller', $lang));
			$id = $this->myDB->lastInsertId();
			$this->myDB->query("INSERT INTO home_sellers_meta(home_lead_fk) VALUES($id)");
		}

		return $id;
	}

	public function addManualLead($array){
		if($array['type'] == 'home_sellers'){
			$query = $this->myDB->prepare("INSERT INTO home_sellers (agent_fk, address, name, phone, email, selling, source, type, comments, status, lang) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$query->execute(array($array['agent_id'], $array['address'], $array['name'], $array['phone'], $array['email'], $array['selling'], 'm', $array['type'], $array['notes'], $array['status'], $array['lang']));
		}else if($array['type'] == 'home_buyers'){ //TODO
			$query = $this->myDB->prepare("INSERT INTO home_sellers (agent_fk, address, name, phone, email, selling, source, type, comments, status, lang) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$query->execute(array($array['agent_id'], $array['address'], $array['name'], $array['phone'], $array['email'], $array['selling'], 'm', $array['type'], $array['notes'], $array['status'], $array['lang']));
		}
		
		$id = $this->myDB->lastInsertId();
		$this->myDB->query("INSERT INTO home_sellers_meta(home_lead_fk) VALUES($id)");
	}

	public function addImportLeads($array, $meta){
		$query = $this->myDB->prepare("INSERT INTO home_sellers VALUES(:id,:agent_fk,:address,:name,:phone,:email,:funnels,:funnel_switch,:selling,:source,:type,:comments,:status,:lang,:dateAdded)");
		$query->execute($array);
		$meta['home_lead_fk'] = $this->myDB->lastInsertId();
		$query = $this->myDB->prepare("INSERT INTO home_sellers_meta VALUES(:value_range,:value_epp,:beds,:baths,:sqft,:buying_frame,:price_range,:neighborhood,:prequalified,:lender,:lender_phone,:lender_email,:loan_type,:credit,:planning_sell,:alert_setup,:other_contact,:other_contact_phone,:other_contact_email,:home_lead_fk)");
		$query->execute($meta);
	}

	public function updateHomeLeadsPartial($col, $val, $id){
		$query = $this->myDB->prepare("UPDATE home_sellers SET {$col} = ? WHERE id = ?");
		$query->execute(array($val, $id));

		$query = $this->myDB->prepare("SELECT * FROM home_sellers WHERE id = ? AND DATE(`date`) = DATE(NOW())");
		$query->execute(array($id));
		$res = $query->fetch();

		if($res['name'] != '' AND $res['email'] != '' AND $res['phone'] != '' AND $res['selling'] != ''){
			$this->myDB->query("UPDATE home_sellers SET status = 1 WHERE id = $id");
			return $res;
		}
	}

	public function updateHomeLead($arr){
		$address = $arr['street'] . ", " . $arr['city'] . ", " . $arr['province'] . " " . $arr['postal'];
		$query = $this->myDB->prepare("UPDATE home_sellers SET name = ?, phone = ?, email = ?, selling = ?, lang = ?, address = ? WHERE id = ?");
		$query->execute(array($arr['name'], $arr['phone'], $arr['email'], $arr['selling'], $arr['lang'], $address, $arr['id']));

		$query = $this->myDB->prepare("UPDATE home_sellers_meta SET value_range = ?, value_epp = ?, beds = ?, baths = ?, sqft = ?, buying_frame = ?, price_range = ?, neighborhood = ?, prequalified = ?, lender = ?, lender_phone = ?, lender_email = ?, loan_type = ?, credit = ?, planning_sell = ?, alert_setup = ?, other_contact = ?, other_contact_phone = ?, other_contact_email = ? WHERE home_lead_fk = ?");
		$query->execute(array($arr['range'], $arr['eppraisal'], $arr['beds'], $arr['baths'], $arr['sqft'], $arr['buying'], $arr['price'], $arr['neighborhoods'], $arr['prequalified'], $arr['lender'], $arr['lender_phone'], $arr['lender_email'], $arr['loan_type'], $arr['credit'], $arr['planning_sell'], $arr['alert_setup'], $arr['other_contact'], $arr['other_contact_phone'], $arr['other_contact_email'], $arr['id']));
	}

	public function updateLeadFunnel($id, $funnel){
		$this->myDB->query("UPDATE home_sellers SET funnels = $funnel WHERE id = $id");
	}

	public function updateLeadPartial($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET name = ?, phone = ?, email = ?, lang = ?, address = ? WHERE id = ?");
		$query->execute(array($arr['name'], $arr['phone'], $arr['email'], $arr['lang'], $arr['address'], $arr['lead_id']));

		return 1;
	}

	public function getHomeSellerMeta(){
		$query = $this->myDB->query("SHOW COLUMNS FROM home_sellers_meta");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
		AGENT'S LEAD
	*/
	public function getAgentsLead($id, $table){
		$query = $this->myDB->query("SELECT * FROM $table WHERE name != '' AND email != '' AND phone != '' AND agent_fk = $id AND status > 0 ORDER BY `date` DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentArchievedLead($id, $table){
		$query = $this->myDB->query("SELECT * FROM $table WHERE agent_fk = $id AND status = 0 ORDER BY id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function filterDateAgentsLead($id, $table, $date){
		$whereClause = "";
		$whereArray = array();

		$daterange = explode(" - ", $date);

		if(COUNT($daterange) == 2){
			$whereClause .= " DATE(`date`) BETWEEN ? AND ? AND ";
			array_push($whereArray, $daterange[0]);
			array_push($whereArray, $daterange[1]);
		}else{
			$whereClause .= " DATE(`date`) = ? AND ";
			array_push($whereArray, $daterange[0]);
		}

		array_push($whereArray, $id);

		$query = $this->myDB->prepare("SELECT * FROM $table AS l LEFT JOIN home_sellers_meta AS hl ON hl.home_lead_fk = l.id WHERE $whereClause name != '' AND email != '' AND phone != '' AND agent_fk = ? AND status > 0 ORDER BY `date` DESC");
		$query->execute($whereArray);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLeadsForCron($table){
		$test = "a.agent_id = 5 AND ";

		$query = $this->myDB->query("SELECT l.*, TIMESTAMPDIFF(HOUR,l.date,now()) AS dTime, a.agent_id, a.agent_name, a.agent_email, a.agent_signature, a.agent_lang FROM $table AS l JOIN agents AS a ON a.agent_id = l.agent_fk WHERE name != '' AND email != '' AND phone != '' AND status > 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentsLeadAddresses($id){
		$query = $this->myDB->query("SELECT * FROM home_sellers WHERE agent_fk = $id AND (status = -1 OR status > 0) ORDER BY id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function filterDateAgentsLeadAddresses($id, $date){
		$whereClause = "";
		$whereArray = array();
		$trail = " agent_fk = ? AND (status = -1 OR status > 0) ORDER BY id DESC";

		$daterange = explode(" - ", $date);

		if(COUNT($daterange) == 2){
			$whereClause .= " DATE(`date`) BETWEEN ? AND ? AND";
			array_push($whereArray, $daterange[0]);
			array_push($whereArray, $daterange[1]);
		}else{
			$whereClause .= " DATE(`date`) = ? AND";
			array_push($whereArray, $daterange[0]);
		}

		array_push($whereArray, $id);

		$query = $this->myDB->prepare("SELECT * FROM home_sellers WHERE $whereClause $trail");
		$query->execute($whereArray);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAgentsPartialLead($id, $table){
		$query = $this->myDB->query("SELECT * FROM $table WHERE agent_fk = $id AND (CONCAT_WS('', name, phone, email) <> '' XOR (name != '' AND email != '' AND phone != '')) AND status != 0 ORDER BY id DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function filterDateAgentsPartialLead($id, $table, $date){
		$whereClause = "";
		$whereArray = array();

		$daterange = explode(" - ", $date);

		if(COUNT($daterange) == 2){
			$whereClause .= " DATE(`date`) BETWEEN ? AND ? AND ";
			array_push($whereArray, $daterange[0]);
			array_push($whereArray, $daterange[1]);
		}else{
			$whereClause .= " DATE(`date`) = ? AND ";
			array_push($whereArray, $daterange[0]);
		}

		$query = $this->myDB->prepare("SELECT * FROM $table WHERE $whereClause agent_fk = $id AND (CONCAT_WS('',
				name,phone,email) <> '' XOR (name != '' AND email != '' AND phone != '')) AND status != 0 ORDER BY id DESC");
		$query->execute($whereArray);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSingleAgentsLead($id){
		$query = $this->myDB->query("SELECT * FROM home_sellers hs JOIN home_sellers_meta AS hm ON hm.home_lead_fk = hs.id WHERE hs.id = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getSingleLeadByEmailByType($email, $table){
		$query = $this->myDB->prepare("SELECT id FROM $table WHERE email = ? LIMIT 1");
		$query->execute(array($email));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function recoverHomeLead($id, $table){
		if(is_numeric($id)){
			$query = $this->myDB->prepare("UPDATE $table SET status =  1 WHERE id = ?");
			return $query->execute(array($id));
		}
	}

	public function DeleteForeverHomeLead($id, $table){
		if(is_numeric($id)){
			$query = $this->myDB->prepare("UPDATE $table SET status =  -2 WHERE id = ?");
			return $query->execute(array($id));
		}
	}

	public function DeleteForeverBulkHomeLead($ids, $table){
		$this->myDB->query("UPDATE $table SET status = -2 WHERE id IN ($ids)");
		return 1;
	}

	public function recoverBulkHomeLead($ids, $table){
		$this->myDB->query("UPDATE $table SET status = 1 WHERE id IN ($ids)");
		return 1;
	}

	public function addCommentsHomeLeads($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET comments = ? WHERE id = ?");
		$query->execute(array($arr['comments'], $arr['id']));
	}

	public function updateSelling($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET selling = ? WHERE id = ?");
		$query->execute(array($arr['text'], $arr['id']));
	}

	public function updateFunnelSwitch($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET funnel_switch = ? WHERE id = ?");
		$query->execute(array($arr['switch'], $arr['id']));
	}

	public function updateStatus($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET status = ? WHERE id = ?");
		$query->execute(array($arr['status'], $arr['id']));
	}

	public function updateType($arr){
		$query = $this->myDB->prepare("UPDATE home_sellers SET type = ? WHERE id = ?");
		$query->execute(array($arr['text'], $arr['id']));
	}

	public function deleteBulkLeads($ids, $table){
		$query = $this->myDB->query("UPDATE $table SET status = 0 WHERE id IN ($ids)");
	}

	public function deleteBulkLeadsAddress($ids){
		$query = $this->myDB->query("UPDATE home_sellers SET status = 0 WHERE id IN ($ids)");
	}

	public function deleteSingleLead($id){
		$query = $this->myDB->query("UPDATE home_sellers SET status = 0 WHERE id  = $id");
	}

	public function agentLeadStats($id, $table, $date = "All"){
		if($id == "")
			$id = 0;

		$whereDate = "";
		$whereArray = array();

		if($date != "All"){
			$daterange = explode(" - ", $date);

			if(COUNT($daterange) == 2){
				$whereDate .= " DATE(`date`) BETWEEN ? AND ? AND ";
				array_push($whereArray, $daterange[0]);
				array_push($whereArray, $daterange[1]);
			}else{
				$whereDate .= " DATE(`date`) = ? AND ";
				array_push($whereArray, $daterange[0]);
			}
		}

		array_push($whereArray, $id);

		$stats = array();
		$query = $this->myDB->prepare("SELECT id FROM $table WHERE $whereDate name != '' AND email != '' AND phone != '' AND agent_fk = ? AND status > 0");
		$query->execute($whereArray);
		$stats['completed'] = COUNT($query->fetchAll(PDO::FETCH_ASSOC));

		$query = $this->myDB->prepare("SELECT id FROM $table WHERE $whereDate agent_fk = ? AND (CONCAT_WS('',
				name,phone,email) <> '' XOR (name != '' AND email != '' AND phone != '')) AND (status = -1 OR status > 0)");
		$query->execute($whereArray);
		$stats['partial'] = COUNT($query->fetchAll(PDO::FETCH_ASSOC));

		if($table == "home_sellers"){
			$query = $this->myDB->prepare("SELECT id FROM home_sellers WHERE $whereDate agent_fk = ? AND (status = -1 OR status > 0)");
			$query->execute($whereArray);
			$stats['address'] = COUNT($query->fetchAll(PDO::FETCH_ASSOC));
		}

		return $stats;
	}

	/**
		TEMPLATE SECTION
	*/

	/*
	*	Function to add an email template.
	*	@data: array with the information of the template.
	*/
	public function addTemplate($data){
		$query = $this->myDB->prepare("INSERT INTO emails_templates(name, content, slug) VALUES(?, ?, ?)");

		if($query->execute(array(trim(ucfirst($data['name'])), trim(ucfirst($data['content'])), trim(strtolower($data['slug']))))){
			$this->write_to_log("New template added [Slug: " . $data['slug'] ."]", $_SESSION['user']['username']);
			return true;
		}else
			return false;
	}

	/*
	*	Function to delete a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function deleteTemplate($id){
		$query = $this->myDB->prepare("UPDATE emails_templates SET status = 0 WHERE email_template_id = ?");
		return $query->execute(array($id));
	}

	/*
	*	Function to get an email template by a slug.
	*	@slug: slug of the template
	*/
	public function getTemplateBySlug($slug){
		$query = $this->myDB->prepare("SELECT * FROM emails_templates WHERE slug = ? AND status = 1");
		$query->execute(array($slug));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get an email template.
	*	@id: id of the template to get.
	*/
	public function getTemplate($id){
		$query = $this->myDB->prepare("SELECT * FROM emails_templates WHERE status = 1 AND email_template_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getTemplates(){
		$query = $this->myDB->query("SELECT * FROM emails_templates WHERE status = 1 ORDER BY name ASC, slug ASC, content ASC");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to update an email template.
	*	@id: id of the template to update
	*	@data: information that the new email template will get
	*/
	public function updateTemplate($id, $data){
		$query = $this->myDB->prepare("UPDATE emails_templates SET name = ?, content = ?, slug = ? WHERE email_template_id = ?");
		$this->write_to_log("Template updated [Slug: " . $data['slug'] ."]", $_SESSION['user']['username']);
		return $query->execute(array(trim(ucfirst($data['name'])), trim(ucfirst($data['content'])), trim(strtolower($data['slug'])), $id));
	}


	/**
		FUNNEL SECTION
	*/

	/*
	*	Function to add an email template.
	*	@data: array with the information of the template.
	*/
	public function addFunnel($data){
		$query = $this->myDB->prepare("INSERT INTO funnels VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, DEFAULT)");
		return $query->execute(array($data['name'], $data['content'], intval($data['intervalNum'])*intval($data['intervalFrame']), $data['funnel'], $data['lang'], isset($_SESSION['user']['agent_id'])?$_SESSION['user']['agent_id']:0));
	}

	/*
	*	Function to delete a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function deleteFunnel($id){
		$query = $this->myDB->prepare("UPDATE funnels SET status = -1 WHERE funnel_id = ?");
		return $query->execute(array($id));
	}

	/*
	*	Function to pause a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function pauseFunnel($id){
		$query = $this->myDB->prepare("UPDATE funnels SET status = 0 WHERE funnel_id = ?");
		$query->execute(array($id));
	}

	/*
	*	Function to pause a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function unPauseFunnel($id){
		$query = $this->myDB->prepare("UPDATE funnels SET status = 1 WHERE funnel_id = ?");
		$query->execute(array($id));
	}

	/*
	*	Function to pause a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function disableFunnels(){
		$query = $this->myDB->prepare("UPDATE funnels SET status = 0 WHERE agent = ?");
		$query->execute(array($_SESSION['user']['agent_id']));
	}

	/*
	*	Function to pause a template by it's id.
	*	@id: id of the template to delete.
	*/
	public function enableFunnels(){
		$query = $this->myDB->prepare("UPDATE funnels SET status = 1 WHERE agent = ?");
		$query->execute(array($_SESSION['user']['agent_id']));
	}


	/*
	*	Function to get an email template by a slug.
	*	@slug: slug of the template
	*/
	public function getFunnelBySlug($slug){
		$query = $this->myDB->prepare("SELECT * FROM funnels WHERE slug = ? AND status = 1");
		$query->execute(array($slug));
		return $query->fetch(PDO::FETCH_ASSOC);
	}


	/*
	*	Function to get an email template.
	*	@id: id of the template to get.
	*/
	public function getFunnel($id){
		$query = $this->myDB->prepare("SELECT * FROM funnels WHERE funnel_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getPausedFunnels($id){
		$query = $this->myDB->query("SELECT GROUP_CONCAT(funnel_fk) AS funnels FROM funnels_off WHERE agent_fk = $id GROUP BY agent_fk");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getNotPausedEnglishFunnels($id){
		$query = $this->myDB->query("SELECT * FROM funnels WHERE agent = $id AND status > 0 AND language = 'EN' ORDER BY `interval` ASC, name ASC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getNotPausedFrenchFunnels($id){
		$query = $this->myDB->query("SELECT * FROM funnels WHERE agent = $id AND status > 0 AND language = 'FR' ORDER BY `interval` ASC, name ASC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getNextFunnel($time, $funnels, $agent){
		$backTime = $time - 24;

		if(!empty($time) && !empty($funnels) && !empty($agent)){
			$query = $this->myDB->query("SELECT * FROM funnels WHERE agent = $agent AND category = '$funnels' AND `interval` BETWEEN $backTime AND $time AND  status > 0 ORDER BY `interval` LIMIT 1");
			return $query->fetch(PDO::FETCH_ASSOC);
		}else
			return false;
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getFunnelsByCat($agent, $cat){
		$query = $this->myDB->query("SELECT * FROM funnels WHERE agent = $agent AND category = $cat AND status > -1 ORDER BY `interval`");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to update an email template.
	*	@id: id of the template to update
	*	@data: information that the new email template will get
	*/
	public function updateFunnel($id, $data){
		$query = $this->myDB->prepare("UPDATE funnels SET name = ?, content = ?, `interval` = ?, category = ?, language = ? WHERE funnel_id = ?");
		return $query->execute(array(trim(ucfirst($data['name'])), trim(ucfirst($data['content'])), intval($data['intervalNum'])*intval($data['intervalFrame']), $data['funnel'], $data['lang'], $id));
	}

	public function addFunnelCategory($name, $type, $agent = 0){
		$query = $this->myDB->prepare("INSERT INTO funnel_category SET title = ?, agent_type = ?, agent = ?");
		return $query->execute(array($name, $type, $agent));
	}

	public function editFunnelCategory($name, $type, $id){
		$query = $this->myDB->prepare("UPDATE funnel_category SET title = ?, agent_type = ? WHERE id = ?");
		return $query->execute(array($name, $type, $id));
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getFunnelCategories($id){
		$query = $this->myDB->query("SELECT * FROM funnel_category WHERE agent =  $id AND status > 0 ORDER BY title ASC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/*
	*	Function to get all the email templates.
	*/
	public function getFunnelCat($id){
		$query = $this->myDB->query("SELECT * FROM funnel_category WHERE id = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getFunnelCatByTitle($title, $agent){
		$query = $this->myDB->query("SELECT * FROM funnel_category WHERE title = '$title' AND agent = $agent");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteFunnelCategory($id){
		$query = $this->myDB->query("UPDATE funnel_category SET status = 0 WHERE id = $id");
	}

	public function getFunnelSent($id){
		$query = $this->myDB->query("SELECT funnels FROM funnels_sent WHERE lead = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function addUpdateFunnelSent($id, $array){
		$this->myDB->query("INSERT INTO funnels_sent VALUES(DEFAULT, $id, '$array') ON DUPLICATE KEY UPDATE funnels = '$array'");
	}

	/**
	TASK SECTION
	 */

	public function addTask($arr){
		$query = $this->myDB->prepare("INSERT INTO tasks VALUES(DEFAULT, ?, ?, ?, ?, ?, DEFAULT)");
		$query->execute(array($arr['task'], $arr['importance'], $arr['date'], $arr['id'], $_SESSION['user']['agent_id']));
	}

	public function getTasks($id){
		$query = $this->myDB->query("SELECT * FROM tasks WHERE lead_fk = $id AND status > 0 ORDER BY importance DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUpcomingTasks(){
		$id = $_SESSION['user']['agent_id'];
		$query = $this->myDB->query("SELECT * FROM tasks WHERE dateTime > now() AND agent_fk = $id AND status > 0");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateTask($arr){
		$query = $this->myDB->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
		$query->execute(array($arr['done'], $arr['id']));
	}

	public function deleteTask($id){
		$this->myDB->query("UPDATE tasks SET status = 0 WHERE task_id = $id");
	}

	public function getAgentTasks($id, $status){
		$query = $this->myDB->query("SELECT *, t.status AS task_status FROM tasks t JOIN home_sellers h ON t.lead_fk = h.id WHERE t.agent_fk = $id AND t.status = $status ORDER BY dateTime DESC, importance DESC");

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
		GROUPS SECTION
	*/
	public function getGroups(){
		$query = $this->myDB->query("SELECT * FROM emails_groups WHERE status = 1 ORDER BY group_name ASC");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function createEmailGroup($name, $emails){
		$query = $this->myDB->prepare("INSERT INTO emails_groups(`group_name`, `group_emails`) VALUES(?, ?)");

		if($query->execute(array($name, $emails)))
			return true;
		else
			return false;
	}

	public function getGroupByName($name){
		$query = $this->myDB->prepare("SELECT * FROM emails_groups WHERE group_name = ? AND status = 1");
		$query->execute(array($name));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getGroup($id){
		$query = $this->myDB->prepare("SELECT * FROM emails_groups WHERE email_group_id = ? AND status = 1");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function updateEmailGroup($groupId, $emails){
		$query = $this->myDB->prepare("UPDATE emails_groups SET group_emails = ? WHERE email_group_id = ?");
		return $query->execute(array($emails, $groupId));
	}

	public function deleteGroup($id){
		$query = $this->myDB->prepare("UPDATE emails_groups SET status = 0 WHERE email_group_id = ?");
		return $query->execute(array($id));
	}


	public function addMessageHistory($type, $subject, $msg, $lead){
		$subject = str_replace("'", "\\'", $subject);
		$query = $this->myDB->prepare("INSERT INTO messageHistory VALUES(DEFAULT, ?,  ?, ?, DEFAULT, ?)");
		$query->execute(array($type, $subject, $msg, $lead));
	}

	public function getMessageHistory($lead){
		$query = $this->myDB->query("SELECT * FROM messageHistory WHERE lead_fk = $lead ORDER BY `date` DESC");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSingleMessageHistory($id){
		$query = $this->myDB->query("SELECT * FROM messageHistory WHERE id = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}


	/*
		RECURRING PAYMENTS
	*/

	public function getAllReccuringProfile(){
		$query = $this->myDB->query("
			SELECT *, r.id AS rID
			 FROM agents a
			 LEFT JOIN area_mapping am
			 ON am.agent_fk = a.internal_id
			 LEFT JOIN areas ar
			 ON am.area_fk = ar.area_id
			 LEFT JOIN reccurent r
			 ON a.agent_id = r.agent_fk
			 LEFT JOIN credit_cards c
			 ON c.agent_fk = a.agent_id
			 ORDER BY rID");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getReccuringOverview(){
		$query = $this->myDB->query("
			SELECT 	agent_name,agent_email,agent_date,r.agent_fk,r.next_billing,r.counter,
					area_name,MAX(i.invoice_date) invoice_date,
					CASE r.status
						WHEN 0 THEN '<span class=\"label label-danger\">inactive</span>'
						WHEN 1 AND r.counter = 0 THEN '<span class=\"label label-success\">active</span>'
						ELSE '<span class=\"label label-warning\">error</span>'
					END AS status
			FROM agents a LEFT JOIN area_mapping am
			ON am.agent_fk = a.internal_id
			LEFT JOIN areas ar
			ON am.area_fk = ar.area_id
			LEFT JOIN reccurent r
			ON a.agent_id = r.agent_fk
			LEFT JOIN invoices i
			ON a.agent_id = i.agent_fk
			GROUP BY a.agent_id
			ORDER BY r.status,r.next_billing");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addReccuringProfile($id){
		$this->myDB->query("INSERT INTO reccurent(agent_fk, next_billing, next_try) VALUES($id, NOW() + INTERVAL 1 MONTH, NOW() + INTERVAL 1 MONTH)");
	}

	public function getReccuringProfile(){
		$query = $this->myDB->query("
			SELECT * FROM agents a
			LEFT JOIN area_mapping am
			ON am.agent_fk = a.internal_id
			LEFT JOIN areas ar
			ON am.area_fk = ar.area_id
			JOIN reccurent r
			ON a.agent_id = r.agent_fk
			LEFT JOIN credit_cards c
			ON c.agent_fk = a.agent_id
			WHERE DATE(r.next_try) <= DATE(NOW())
				AND r.counter < 5 AND r.status = 1 AND (c.selected = 1 || c.id IS NULL)
			GROUP BY a.agent_id
			LIMIT 5");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function agentNextPayment($id){
		$query = $this->myDB->query("SELECT next_billing FROM reccurent WHERE agent_fk = $id");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function resetRecurringDate($id, $user = 0){
		$this->myDB->query("UPDATE reccurent SET next_billing = DATE_ADD(next_billing, INTERVAL 1 MONTH), next_try = next_billing, counter = 0 WHERE agent_fk = $id");
		$this->myDB->query("UPDATE agents SET agent_status = 1 WHERE agent_id = $id");
		$this->myDB->query("UPDATE users SET status = 1 WHERE user_id = $user");
	}

	public function updateReccuringCount($id){
		$this->myDB->query("UPDATE reccurent SET counter = counter + 1, next_try = DATE_ADD(next_try, INTERVAL 1 DAY) WHERE agent_fk = $id");
		$this->myDB->query("UPDATE agents SET agent_status = 2 WHERE agent_id = $id");
	}


	/**
		EVALUATION
	*/

	public function addEvaluation($lead, $low, $high, $muni, $comments){
		$query = $this->myDB->prepare("INSERT INTO evaluations VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, DEFAULT)");
		$query->execute(array($lead, $_SESSION['user']['agent_id'], $low, $high, $muni, $comments));
	}

	public function getEvaluations($agent){
		$query = $this->myDB->query("SELECT *, e.comments com FROM evaluations e JOIN home_sellers h ON e.lead_fk = h.id WHERE e.agent_fk = $agent AND e.status = 1");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getEvaluationsSent(){
		$query = $this->myDB->query("SELECT id_e, lead_fk FROM evaluations WHERE status = 1");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getEvaluation($id){
		$query = $this->myDB->query("SELECT *, e.comments com FROM evaluations e JOIN home_sellers h ON e.lead_fk = h.id WHERE e.id_e = $id AND e.status = 1");
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getCalls(){
		$query = $this->myDB->query("SELECT * FROM calls WHERE call_status = 1");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCallsByCountry($country){
		$query = $this->myDB->prepare("SELECT * FROM calls WHERE call_status = 1 AND call_country = ?");
		$query->execute(array($country));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSingleCall($id){
		$query = $this->myDB->prepare("SELECT * FROM calls WHERE call_status = 1 AND call_id = ?");
		$query->execute(array($id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function addCallRecord($call){
		$query = $this->myDB->prepare("INSERT INTO calls VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, DEFAULT)");
		return $query->execute(array($call['name'], $call['phone'], $call['source'], $call['desired_area'], $call['country'], $call['notes'], $call['status']));
	}

	public function updateCallRecord($call){
		$query = $this->myDB->prepare("UPDATE calls SET call_name = ?, call_phone = ?, call_source = ?, call_desired_area = ?, call_notes = ?, call_state = ? WHERE call_id = ?");
		return $query->execute(array($call['name'], $call['phone'], $call['source'], $call['desired_area'], $call['notes'], $call['status'], $call['id']));
	}

	public function deleteCallRecord($id){
		$query = $this->myDB->prepare("UPDATE calls SET call_status = 0 WHERE call_id = ?");
		return $query->execute(array($id));
	}

	/**
		LOG ACTION SECTION
	*/

	/*
	*	Function to log all the actions.
	*	@action: 	action performed.
	*	@user: 		user who performed the action
	*/
	public function write_to_log($action, $user){
		// Timestamp
		$text = '[' . date ('m/d/Y g:i A') . '] - ';
		$text .= $action . " - " . $user;

		// Write to log
		$fp = fopen (LOG_FILE , 'a');
		fwrite ($fp, $text . "\n");
		fclose ($fp ); // close file
		chmod (LOG_FILE , 0600);
	}
}
?>