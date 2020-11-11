<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
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
    public function index()
    {
        $appointments = $this->user->appointments()->with('customer')->with('apartment')->get();

        return $appointments;
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
            "customerid" => "required",
            "apartmentid" => "required",
            "startdate" => "required",
            "enddate" => "required",
        ]);

        $appointment = new Appointment();
        $appointment->customerid = $request->customerid;
        $appointment->apartmentid = $request->apartmentid;
        $appointment->startdate = $request->startdate;
        $appointment->enddate = $request->enddate;

        $appointmentDateControl = self::appointmentDateControl($request);
        if($appointmentDateControl){
            return response()->json([
                "status" => false,
                "message" => "Belirtilen randevu tarihi müsait değildir..."
            ], 500);
        }
        else{
            if ($this->user->appointments()->save($appointment)) {
                return response()->json([
                    "status" => true,
                    "appointment" => $appointment
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ops, task could not be saved."
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->validate($request, [
            "apartmentid" => "required",
            "startdate" => "required",
            "enddate" => "required",
        ]);

        $appointment->apartmentid = $request->apartmentid;
        $appointment->startdate = $request->startdate;
        $appointment->enddate = $request->enddate;

        $appointmentDateControl = self::appointmentDateControl($request);
        if($appointmentDateControl)
        {
            return response()->json([
                "status" => false,
                "message" => "Belirtilen randevu tarihi müsait değildir..."
            ], 500);
        }
        else
        {
            if ($this->user->appointments()->save($appointment)) {
                return response()->json([
                    "status" => true,
                    "appointment" => $appointment
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ops, task could not be updated."
                ], 500);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        if ($appointment->delete()) {
            return response()->json([
                "status" => true,
                "appointment" => $appointment
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be deleted."
            ]);
        }
    }


    /* Special Methods */

    // Randevu tarihlerinin kontrolü
    public function appointmentDateControl($request){
        $existStartDateCount = $this->user->appointments()->where('startdate', '<=', $request->startdate)->orderBy('startdate', 'DESC')->take(1)->where('enddate', '>=', $request->startdate)->get()->count();
        $existEndDateCount = $this->user->appointments()->where('startdate', '<=', $request->enddate)->orderBy('startdate', 'DESC')->take(1)->where('enddate', '>=', $request->enddate)->get()->count();
        $existStartAndEndDateCount = $this->user->appointments()->where('startdate', '>=', $request->startdate)->orderBy('startdate', 'ASC')->take(1)->where('startdate', '<=', $request->enddate)->get()->count();
        if($existStartDateCount > 0 || $existEndDateCount > 0 || $existStartAndEndDateCount > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }
}
