<?php

namespace App\Controllers\AdminArea;

use App\Models\AdViewIncomeModel;
use App\Models\RequestModel;
use App\Controllers\BaseController;

class Income extends BaseController
{
    public function listAdView(){
        $AV = new AdViewIncomeModel();

        if(isset($_POST['delete'])){
            $AV->delete($_POST['id']);
        }

        $data['results'] = $AV->findAll();
        $data['title'] = "Ad View Income";
        return view('admin/income/adview', $data);
    }

    public function withdrawalRequest(){
        $RM = new RequestModel();

        if(isset($_POST['approve'])){
            $id=$_POST['id'];

            $result = $RM->impsTransfer($id);
            print_r($result);
            exit;
            /* $RM->update($id, [
                'status'=>1
            ]); */
            return redirect()->back()->with('success', 'Request accepted successfully....');
        }
        if(isset($_POST['reject'])){
            $id=$_POST['id'];
            $RM->update($id, [
                'status'=>2
            ]);
            return redirect()->back()->with('error', 'Request rejected successfully....');
        }

        /* if(isset($_GET['type'])){
            if($_GET['type']=='all'){
                $type="All Withdrawal";
                $data['results'] = $RM->findAll();
            }
            if($_GET['type']=='pending'){
                $type="Pending Withdrawal";
                $data['results'] = $RM->WHERE(['status'=>0])->findAll();
            }
            if($_GET['type']=='success'){
                $type="Successfull Withdrawal";
                $data['results'] = $RM->WHERE(['status'=>1])->findAll();
            }
            if($_GET['type']=='reject'){
                $type="Rejected Withdrawal";
                $data['results'] = $RM->WHERE(['status'=>2])->findAll();
            }
        }
        else{
            $type="All Withdrawal";
            $data['results'] = $RM->findAll();
        } */
        
        $type="New Request";
        $data['results'] = $RM->newRequest();
        $data['title'] = $type;
        return view('admin/income/withdrawal-request', $data);
    }
}
