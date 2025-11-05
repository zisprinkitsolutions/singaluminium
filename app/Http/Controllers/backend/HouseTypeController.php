<?php

namespace App\Http\Controllers\backend;

use App\HouseType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{
    public function index(){
        $house_types = HouseType::all();
        return view('backend.house-type.index', compact('house_types'));
    }

    public function store(Request $request)
    {
        $house_type_id = $request->house_type_id;
        $house_type = $request->house_type;

        // Check if the house type already exists
        $house = HouseType::where('name', $house_type)
            ->when($house_type_id, function ($q) use ($house_type_id) {
                $q->where('id', '!=', $house_type_id);
            })
            ->first();

        if ($house) { // <-- should check $house, not $house_type
            return back()->with([
                'alert-type' => 'warning',
                'message' => 'The house type already exists!',
            ]);
        }

        // Create or update
        if ($house_type_id) {
            $house = HouseType::find($house_type_id);
        } else {
            $house = new HouseType();
        }

        $house->name = $house_type;
        $house->save();

        return back()->with([
            'alert-type' => 'success',
            'message' => 'House type saved successfully!',
        ]);
    }
}
