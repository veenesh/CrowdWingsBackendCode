<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\VendorModel;
use App\Models\StateModel;
use App\Models\CityModel;

class Vendor extends BaseController
{

  public function __construct()
  {
    $this->session = session();
  }

    public function login()
    {
      $VM = new VendorModel($this->db);
      



      if(isset($_POST['login'])){
        $result = $VM->validateMember($_POST);
        if($result->is_valid){
          $this->session->set('vendor_member_id', $result->valid_id);
          return redirect()->to('vendor/dashboard');
        }
      }
       return view('auth/vendor-login');
    }

    public function register()
    {
      $data=[];
      $VM = new VendorModel($this->db);
      $SM = new StateModel($this->db);
      $CM = new CityModel($this->db);

      $data['states']=$SM->findAll();
      $data['cities']=[];
      if(isset($_POST['state'])){
        $data['cities']=$CM->where('state_id', $_POST['state'])->findAll();;
      }

      if(isset($_POST['register'])){
        
        $rules = [
          
            'name'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'Name is required.',
                ],
            ],
            'email'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'Email is required.',
                ],
            ],
            'phone'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'Phone is required.',
                ],
            ],
         
            'state'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'State is required.',
                ],
            ],
            'city'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'City is required.',
                ],
            ],
            'password'=>[
              'rules'=>'required',
                'errors' => [
                  'required' => 'Password is required.',
                ],
            ],  
          
        ];

        if($this->validate($rules)){
          
     
          $date=date('Y-m-d');
          $dataIns = [
            'name'=>$_POST['name'],
            'email'=>$_POST['email'],
            'phone'=>$_POST['phone'],
            'password'=>$_POST['password'],
            'city_id'=>$_POST['city'],
            'state_id'=>$_POST['state'],
            'created_at'=>$date,
            'updated_at'=>$date,
          ];        
          
          $result = $VM->insert($dataIns);
          if($result){
            return redirect()->to('vendor/register')->with('success', 'Registered successfully..');
          }
        }else{
          $data['validation']=$this->validator;
        }
      }
      return view('auth/vendor-register',  $data);
    }

    public function logout()
    {
      $this->session->destroy();
		  return redirect()->to('vendor/login');
    }
}
