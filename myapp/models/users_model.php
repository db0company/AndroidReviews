<?php

class Users_Model extends TinyMVC_Model {

  var $lastError;
  private function setError($msg) {
    $this->lastError = $msg;
    return false;
  }

  private function checkEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  private function checkInvite($invite) {
    return check_invite('androidreviews', $invite);
  }

  public function createAccount($email, $password) {
    $email = protect($email);
    $q = $this->db->pdo->prepare('INSERT INTO users(email, password) VALUES(?, MD5(?))');
    try { $q->execute(array($email, $password)); }
    catch (PDOException $e) {
      $err = $q->errorInfo();
      if ($err[1] == 1062)
    	return $this->setError('An account already exists with this email.');
      return $this->setError(PdoGetMessage($q));
    }
    return $email;
  }

  public function login($email, $password) {
    $email = protect($email);
    $q = $this->db->pdo->prepare('SELECT * FROM users WHERE email=? AND password=MD5(?)');
    try { $q->execute(array($email, $password)); }
    catch (PDOException $e) { return $this->setError(PdoGetMessage($q)); }
    if (!$q->rowCount())
      return $this->setError('Authentication failed. Check if your email and password are correct and try again.');
    return $email;
  }

  public function createAccountForm() {
    if (isset($_POST['f_create_account_submit'])) {
      $email = $_POST['f_create_account_email'];
      $password = $_POST['f_create_account_password'];
      $password_check = $_POST['f_create_account_password_check'];
      $invite = $_POST['f_create_account_invite'];
      if (empty($email) || empty($password) || empty($password_check))
	return $this->setError('At least one field is missing.');
      if (empty($invite))
	return $this->setError('This service is currently open to people who have been invited only.');
      if (!$this->checkInvite($invite))
	return $this->setError('Invalid Invite. It might has been used already.');
      if (!$this->checkEmail($email))
	return $this->setError('Invalid email address.');
      if ($password != $password_check)
	return $this->setError('Your passwords do not match. Please try again.');
      if (strlen($password) < 6)
	return $this->setError('Your password should be at least 6 characters long.');
      return $this->createAccount($email, $password);
    }
    return true;
  }

  public function loginForm() {
    if (isset($_POST['f_login_submit'])) {
      $email = $_POST['f_login_email'];
      $password = $_POST['f_login_password'];
      if (empty($email) || empty($password))
	return $this->setError('At least one field is missing.');
      if (!$this->checkEmail($email))
	return $this->setError('Invalid email address.');
      return $this->login($email, $password);
    }
    return true;
  }

}
