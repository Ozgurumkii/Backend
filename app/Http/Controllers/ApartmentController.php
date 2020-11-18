<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
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
    public function getall(){
        $apartments = Apartment::get();
        return $apartments;
    }

    public function index()
    {
        $apartments = $this->user->apartments()->get(["id", "postcode", "state", "userid"])->toArray();

        return $apartments;
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
            "postcode" => "required",
        ]);

        $apartment = new Apartment();
        $apartment->postcode = $request->postcode;
        $apartment->state = 1;

        if ($this->user->apartments()->save($apartment)) {
            return response()->json([
                "status" => true,
                "apartment" => $apartment
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
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        $this->validate($request, [
            "postcode" => "required",
            "state" => "required"
        ]);

        $apartment->postcode = $request->postcode;
        $apartment->state = $request->state;

        if ($this->user->apartments()->save($apartment)) {
            return response()->json([
                "status" => true,
                "apartment" => $apartment
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
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        if ($apartment->delete()) {
            return response()->json([
                "status" => true,
                "apartment" => $apartment
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be deleted."
            ]);
        }
    }
}
