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

  private function sendEmailForgotPassword($email, $code) {
    include_once('Mail.php');
    global $config;
    $content = '
<center>
<h1><span style="color:#9acd32">A</span>ndroid <span style="color:#9acd32">R</span>eviews <span style="color:#9acd32">M</span>anager</h1>
<i style="color:#9acd32">The Android Developer\'s best friend</i>
</center>
<br><br>

Hello,<br>
<br>
Forgot your password? Click here to reset it:<br>
<a href="'.$config['website']['url'].'login/?reset_email='.$email.'&reset_code='.$code.'#forgot"
 style="display: inline-block; color: #ffffff; text-decoration: none; font-weight: bold; padding: 20px; background-color: #9acd32; border-radius: 10px; margin: 10px;">
Reset your password</a>

<br><br>
<small style="color: #cccccc;">You you don\'t want to reset your password and think you received this e-mail by mistake, please ignore it. Nothing will happen to your account.</small>
</center>
';

    $headers['From']    = 'noreply@androidreviewsmanager.com';
    $headers['To']      = $email;
    $headers['Subject'] = 'Android Reviews Manager: Reset your password';
    $content = utf8_encode($content);
    $headers['Content-Type'] = "text/html; charset=\"UTF-8\"";
    $headers['Content-Transfer-Encoding'] = "8bit";
  
    $params['sendmail_path'] = '/usr/lib/sendmail';
  
    $mail_object =& Mail::factory('sendmail', $params);
    return ($mail_object->send($headers['To'], $headers, $content) === true);
  }

  public function forgotPassword() {
    if (empty($_POST['f_forgot_email']))
      return $this->setError('Invalid e-mail');
    $email = protect($_POST['f_forgot_email']);
    $q = $this->db->pdo->prepare('SELECT * FROM users WHERE email=?');
    try { $q->execute(array($email)); }
    catch (PDOException $e) { return $this->setError(PdoGetMessage($q)); }
    if (!$q->rowCount())
      return $this->setError('Unknown E-mail address.');
    $code = md5(rand());
    $q = $this->db->pdo->prepare('INSERT INTO forgot_password(email, code) VALUES(?, ?) ON DUPLICATE KEY UPDATE code=?');
    try { $q->execute(array($email, $code, $code)); }
    catch (PDOException $e) { return $this->setError(PdoGetMessage($q)); }
    if (!$this->sendEmailForgotPassword($email, $code))
      return $this->setError('Couldn\'t send you an email to reset your password. Try again later.');
    return true;
  }

  public function resetPassword() {
    if (empty($_POST['f_reset_email'])
	|| empty($_POST['f_reset_code'])
	|| empty($_POST['f_reset_password'])
	|| empty($_POST['f_reset_passwordcheck']))
      return $this->setError('At least one field is missing');
    $email = protect($_POST['f_reset_email']);
    $code = protect($_POST['f_reset_code']);
    $q = $this->db->pdo->prepare('SELECT * FROM forgot_password WHERE email=? AND code=?');
    try { $q->execute(array($email, $code)); }
    catch (PDOException $e) { return $this->setError(PdoGetMessage($q)); }
    if (!$q->rowCount())
      return $this->setError('Invalid e-mail or code.');
    if ($_POST['f_reset_password'] != $_POST['f_reset_passwordcheck'])
      return $this->setError('Your passwords do not match. Please try again.');
    $q = $this->db->pdo->prepare('UPDATE users SET password=MD5(?) WHERE email=?');
    try { $q->execute(array($_POST['f_reset_password'], $email)); }
    catch (PDOException $e) { return $this->setError(PdoGetMessage($q)); }
    // set code to not valid anymore
    $q = $this->db->pdo->prepare('DELETE FROM forgot_password WHERE email=? AND code=?');
    try { $q->execute(array($email, $code)); }
    catch (PDOException $e) { return true; } // ignored on purpose
    return true;
  }

}
