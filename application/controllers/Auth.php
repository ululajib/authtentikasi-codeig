<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('user_model');
  }

	public function register()
	{
    $this->form_validation->set_rules('email', 'Email', 'required|is_unique[tb_users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('password2', 'verivy Password', 'required|matches[password]');

    if ( $this->form_validation->run() === false ) {
      $this->load->view('auth/register');
    }  else {
      $this->user_model->insert_user();
      $this->send_email_verification($this->input->post('email'), $_SESSION['token']);
      redirect('login');
    }


	}


  public function send_email_verification($email, $token){
    $this->load->library('email');
    $this->email->from('janijumi1997@gmail.com', 'muhammad ulul ajib');
    $this->email->to($email);
    $this->email->subject('register autentikasi ');
    $this->email->message("
                          clik for register in aplication <a href='http://localhost/myCodeig/verify/$email/$token'>comfirmation</a>
                          ");
    $this->email->set_mailtype('html');
    $this->email->send();
  }

  public function verify_register($email, $token){
    $user = $this->user_model->get_user('email', $email);

    if (!$user) die('email tidak ada');
    if ($user['token'] !== $token) die('token tidak cocok');

    $this->user_model->update_role($user['id'], 1);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['loged_in'] = true;
    redirect('profile');

  }

  public function login()
  {
    if ($this->user_model->is_login()) {
      redirect('profile');
    }

    $this->form_validation->set_rules('email', 'Email', 'required|callback_checkEmial|callback_checkAktive');
    $this->form_validation->set_rules('password', 'Password', 'required|callback_checkPassword');

    if ( $this->form_validation->run() === false ) {
      $this->load->view('auth/login');
    }  else {
      $user = $this->user_model->get_user('email', $this->input->post('email'));
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['loged_in'] = true;
      redirect('profile');

    }

  }

  public function checkEmial($email)
  {
    if (!$this->user_model->get_user('email', $email)) {
      $this->form_validation->set_message('checkEmial', 'email belum terdaftar ..!!!');
      return false;
    }
    return true;
  }

  public function checkPassword($password)
  {
    $user = $this->user_model->get_user('email', $this->input->post('email'));
    if (!$this->user_model->checkPassword($user['email'], $password)) {
      $this->form_validation->set_message('checkPassword', 'password yang anda masukan salah');
      return false;
    }
    return true;
  }

  public function checkAktive($email)
  {
    $user = $this->user_model->get_user('email',$email);
    if ($user['aktive'] == 0 ) {
      $this->form_validation->set_message('checkAktive', 'akun anda belum di aktifkan mohon verifikasi email anda');
      return false;
    }
    return true;
  }

  public function logout()
  {
    unset($_SESSION['user_id'], $_SESSION['loged_in']);
    redirect('login');
  }

}
