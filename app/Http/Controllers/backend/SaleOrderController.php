<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\PartyInfo;
use Illuminate\Http\Request;

class SaleOrderController extends Controller
{
    public function customerPost(Request $request)
    {
        // return $request->all();
        $request->validate([
            'pi_name' => 'required',
            'pi_type'        => 'required',

        ],
        [
            'pi_name.required' => 'Cost Center is required',
            'pi_type.required' => 'Type is required',
        ]
    );
    $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();

            if ($latest) {
                $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
                ++$pi_code;
            } else {
                $pi_code = 1;
            }
            if($pi_code<10)
            {
                $c_code="PI-000".$pi_code;
            }
            elseif($pi_code<100)
            {
                $c_code="PI-00".$pi_code;
            }
            elseif($pi_code<1000)
            {
                $c_code="PI-0".$pi_code;
            }
            else
            {
                $c_code="PI-".$pi_code;
            }
            $draftCost = new PartyInfo();
            $draftCost->pi_code = $c_code;
        $draftCost->pi_name = $request->pi_name;
        $draftCost->pi_type = $request->pi_type;
        $draftCost->trn_no = $request->trn_no;
        $draftCost->address = $request->address;
        $draftCost->con_person = $request->con_person;
        $draftCost->con_no = $request->con_no;
        $draftCost->phone_no = $request->phone_no;
        $draftCost->email = $request->email;
        $sv=$draftCost->save();
        $newCustomer=$draftCost;
        // dd($customer);
        $customers=PartyInfo::where('pi_type', $request->pi_type)->get();

        // return back()->with('success','Added Successfylly',compact('newCustomer'));
        return Response()->json(['page' => view('backend.ajax.form.customerSelect', ['customers' => $customers])->render(),
                'newCustomer' => $newCustomer
        ]);

    }
}
