<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MemberModel;
use App\Models\TokenTxn;

class MemberFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $MM = new MemberModel($this->db);
        if (!$session->get('login_member_id')) {
            return redirect()->to('/user/login');
        }

        $result = (object)$MM->find($session->get('login_member_id'));
        $is_active = $MM->isActive($result->member_id);
        if ($is_active) {
            $status = 'active';
        } else {
            $status = 'inactive';
        }
        $userData = [
            'name' => $result->name,
            'id' => $result->id,
            'status' => $status,
            'memid' => $result->member_id,
            'username' => $result->username,
            'sponsor_id' => $result->sponsor_id,
            'email' => $result->email,
        ];
        $session->set($userData);

        $wallet_address = $result->wallet_address;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.trongrid.io/v1/accounts/' . $wallet_address . '/transactions/trc20?limit=50&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $TOKEN = new TokenTxn();
        curl_close($curl);
        $response = json_decode($response);
       
        $results = $response->data;
        foreach ($results as $res) {
            if ($res->to == $wallet_address) {
                /* print_r($res);
                exit; */
                $is_added = (object)$TOKEN->where('hashcode', $res->transaction_id)->first();
                if (!isset($is_added->id)) {
                    $amount = $res->value / 1000000;
                    $TOKEN->insert(
                        [
                            'member_id' => $result->member_id,
                            'hashcode' => $res->transaction_id,
                            'txn_from' => $res->from,
                            'type' => 'Add Fund',
                            'txn_to' => $res->to,
                            'qty' => $amount,
                            'timeStamp' => $res->block_timestamp,
                            'status' => 1,
                        ]
                    );

                    $MM->addFund($result->member_id, $amount);
                }
            }
        }

        //exit;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
