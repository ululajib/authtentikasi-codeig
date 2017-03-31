<?php

class User_model extends CI_Model{

  function __construct()
  {
    parent::__construct();
  }

  public function insert_user()
  {
    $this->load->helper('string');
    $_SESSION['token'] = random_string('alnum', 16);

    $data = [
              'email'    => $this->input->post('email'),
              'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
              'token'    => $_SESSION['token']
            ];
    $this->db->insert('tb_users', $data);

  }

  public function get_user($key, $value)
  {
    $query = $this->db->get_where('tb_users', [$key => $value]);
    if (!empty($query->row_array())) {
      return $query->row_array();
    }
      return false;
  }

  public function update_role($user_id, $role)
  {
    $data = ['aktive' => $role];
    $this->db->where('id', $user_id);
    return $this->db->update('tb_users', $data);
  }

  public function is_login()
  {
    if (!isset($_SESSION['loged_in'])) {
      return false;
    }
    return true;
  }

  public function checkPassword($email, $password)
  {
    $hash = $this->get_user('email', $email)['password'];
    if (password_verify($password, $hash)) {
      return true;
    }
    return false;
  }

}
