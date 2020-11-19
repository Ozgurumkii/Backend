<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getcustomerwithemail($email){
        $customer = Customer::where('email', $email)->get();
        if($customer->count() > 0){
            return response()->json([
                "status" => true,
                "customer" => $customer
            ]);
        }
        else{
            return response()->json([
                "status" => false,
                "message" => "Böyle bir kayıt yoktur"
            ]);
        }
    }


    public function index()
    {
        $customers = $this->user->customers()->get(["id", "name", "surname", "email", "phonenumber", "userid"])->toArray();

        return $customers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "surname" => "required",
            "email" => "required",
            "phonenumber" => "required"
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->email = $request->email;
        $customer->phonenumber = $request->phonenumber;

        if ($this->user->customers()->save($customer)) {
            return response()->json([
                "status" => true,
                "customer" => $customer
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be saved."
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            "name" => "required",
            "surname" => "required",
            "email" => "required",
            "phonenumber" => "required"
        ]);

        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->email = $request->email;
        $customer->phonenumber = $request->phonenumber;

        if ($this->user->customers()->save($customer)) {
            return response()->json([
                "status" => true,
                "customer" => $customer
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be updated."
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if ($customer->delete()) {
            return response()->json([
                "status" => true,
                "customer" => $customer
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be deleted."
            ]);
        }
    }
}
