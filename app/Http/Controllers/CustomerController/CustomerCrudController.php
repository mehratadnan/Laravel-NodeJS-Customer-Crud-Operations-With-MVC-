<?php

namespace App\Http\Controllers\CustomerController;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerCrudController extends Controller
{

    // this function to do post request to nodeJS server to store Customer
    public function store(Request $request){
        $checkvalidation = $this->checkRequest($request, "addCustomer","");
        if ($checkvalidation === true) {
            $url = $this->server."/customer/addNewCustomer";
            $response = $this->doRequest($url,http_build_query($request->all()),"POST");
            if($response['code'] !== 200){
                return $this->response->fail( ['message' =>  $response]);
            }else{
                return $this->response->success(['message'=>json_decode($response['result'])]);
            }
        } else {
            return $this->response->fail($checkvalidation);
        }
    }

    // this function to do post request to nodeJS server to update Customer
    public function update(Request $request, $id){
        $checkvalidation = $this->checkRequest($request, "editCustomer","");
        if ($checkvalidation === true) {
            $url = $this->server."/customer/updateCustomer/".$id;

            /*// hash user's password
            $password = Hash::make($request->password);
            // edit password with hashed password
            $request['password'] = $password;*/

            $response = $this->doRequest($url,http_build_query($request->all()),"POST");
            if($response['code'] !== 200){
                return $this->response->fail( ['message' =>  $response]);
            }else{
                return $this->response->success(['message'=>json_decode($response['result'])]);
            }
        } else {
            return $this->response->fail($checkvalidation);
        }
    }

    // this function to do post request to nodeJS server to delete Customer by id
    public function destroy( $id){
        $id = trim(strip_tags($id));
        if (!empty($id)) {
            $url = $this->server."/customer/deleteCustomer/".$id;
            $response = $this->doRequest($url,[],"get");
            if($response['code'] !== 200){
                return $this->response->fail( ['message' =>  $response]);
            }else{
                return $this->response->success(['message'=>json_decode($response['result'])]);
            }
        } else {
            return $this->response->fail(['message'=>"no id selected"]);
        }
    }

    // this function to do post request to nodeJS server to reDelete Customer by id
    public function reDestroy( $id){
        $id = trim(strip_tags($id));
        if (!empty($id)) {
            $url = $this->server."/customer/re/deleteCustomer/".$id;
            $response = $this->doRequest($url,[],"get");
            if($response['code'] !== 200){
                return $this->response->fail( ['message' =>  $response]);
            }else{
                return $this->response->success(['message'=>json_decode($response['result'])]);
            }
        } else {
            return $this->response->fail(['message'=>"no id selected"]);
        }
    }

    // this function to do post request to nodeJS server to delete All Customer
    public function destroyAll( ){
        $url = $this->server."/customer/deleteAllCustomer";
        $response = $this->doRequest($url,[],"get");
        if($response['code'] !== 200){
            return $this->response->fail( ['message' =>  $response]);
        }else{
            return $this->response->success(['message'=>json_decode($response['result'])]);
        }
    }

    // this function to do post request to nodeJS server to delete All Customer
    public function reDestroyAll( ){
        $url = $this->server."/customer/re/deleteAllCustomer";
        $response = $this->doRequest($url,[],"get");
        if($response['code'] !== 200){
            return $this->response->fail( ['message' =>  $response]);
        }else{
            return $this->response->success(['message'=>json_decode($response['result'])]);
        }
    }

    // this function to do post request to nodeJS server to get All Customer
    public function selectAll( ){
        $url = $this->server."/customer/getAllCustomer";
        $response = $this->doRequest($url,[],"GET");
        if($response['code'] !== 200){
            return $this->response->fail( ['message' =>  $response]);
        }else{
            return $this->response->success(['message'=>json_decode($response['result'])]);
        }
    }

    // this function to do post request to nodeJS server to get  Customer
    public function show( $id){
        $id = trim(strip_tags($id));
        if (!empty($id)) {
            $url = $this->server."/customer/getCustomer/".$id;
            $response = $this->doRequest($url,[],"GET");
            if($response['code'] !== 200){
                return $this->response->fail( ['message' =>  $response]);
            }else{
                return $this->response->success(['message'=>json_decode($response['result'])]);
            }
        } else {
            return $this->response->fail(['message'=>"no id selected"]);
        }
    }


    // this function to send request to database server
    public function doRequest( $url ,$data ,$method){
        $response = $this->curlServiceProvider->curl(
            $url, false,
            $data,
            $method ,[]
        );
        return $response;
    }


    /**
     * @param $request
     * @param $ctrl
     * @param $id
     * @return bool|\Illuminate\Support\MessageBag|string
     */
    //to validate inputs
    private function checkRequest($request, $ctrl, $id)
    {
        //Request Cleaning
        foreach ($request->all() as $key => $value) {
            if (!is_array($request[$key])) {
                $request[$key] = trim(strip_tags($request[$key]));
            }
        }

        if ($ctrl === "addCustomer") {
            //Request Validator
            $validate = $this->checkValidator($request, [
                'full_name' => 'required|max:25',
                'email' => 'required|email|max:25',
                'password' => 'required|max:25',
                'birth_date' => 'required|date|max:10'
            ]);
        } else if ($ctrl === "editCustomer") {
            //Request Validator
            $validate = $this->checkValidator($request, [
                'full_name' => 'required|max:25',
                'email' => 'required|email|max:25',
                'password' => 'required|max:25',
                'birth_date' => 'required|date|max:10'
            ]);
        }

        if (!empty($validate)) {
            return $validate;
        } else {
            return true;
        }


    }

}



