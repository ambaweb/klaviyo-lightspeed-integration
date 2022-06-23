<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\LightSpeedApi;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            //'dob' => 'required|date',
            //'state' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        ]);
        
        $response = LightSpeedApi::createCustomer([
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "dob" => $request->dob,
            "Contact" => [
                "noEmail" => "false",
                "noPhone" => "false",
                "noMail" => "false",
                "Addresses" => [
                    "ContactAddress" => [
                        "state" => $request->state
                    ]
                ],
                "Phones" => [
                    "Contact" => [
                        "number" => $request->phone,
                        "useType" => 'Mobile'
                    ]
                    ],
                    "Emails" => [
                        "ContactEmail" => [
                            "address" => $request->email,
                            "useType" => 'Primary'
                        ]
                    ]
            ]
        ]);

        return response()->json($response, $response['status']);
    }
}