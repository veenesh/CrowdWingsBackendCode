<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CityModel;

class Admin extends BaseController
{

  public function __construct()
  {
    $this->session = session();
  }

    public function login()
    {
      $UM = new UserModel();
      
      
      if(isset($_POST['login'])){
        $username = $_POST['member_id'];
        $pass = md5($_POST['password']);
        $result = $UM->where([
          'username'=>$username,
          'password'=>$pass
        ])->find();
        if(sizeof($result)){
          $result = (object)$result[0];
          $this->session->set('admin_id', $result->user_id);
          return redirect()->to('admin/dashboard');
        }

      }
       return view('auth/admin-login');
    }


    public function logout()
    {
      $this->session->destroy();
		  return redirect()->to('admin/login');
    }
}
