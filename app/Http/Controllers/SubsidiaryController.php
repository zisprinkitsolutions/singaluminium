<?php

namespace App\Http\Controllers;

use App\Subsidiary;
use App\AccountSubHead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubsidiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $subsidiarys = Subsidiary::paginate(10);
        return view('backend.subsidiary.index', compact('subsidiarys'));

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
    $validated = $request->validate([
        'company_name' => 'required|string|max:255|unique:subsidiaries,company_name',
        'trn_no' => 'required|string|max:100|unique:subsidiaries,trn_no',
        'company_name_arabic' => 'nullable|string|max:255',
        'company_address' => 'nullable|string|max:255',
        'company_address_arabic' => 'nullable|string|max:255',
        'company_email' => 'nullable|email|max:255',
        'company_mobile' => 'nullable|string|max:50',
        'company_tele' => 'nullable|string|max:50',
        'currency' => 'nullable|string|max:20',
        'p_o_box' => 'nullable|string|max:50',
        'running_no' => 'nullable|string|max:50',
        'title_name' => 'nullable|string|max:255',
        'arabic_context' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('subsidiaries', 'public');
        $validated['image'] = $path; // saved as 'subsidiaries/filename.jpg'
    }
    $company = Subsidiary::create($validated);
    $sub_head = new AccountSubHead;
    $sub_head->account_head_id = 1768;
    $sub_head->name = $company->company_name;
    $sub_head->company_id = $company->id;
    $sub_head->office_id = 1;
    $sub_head->save();
    $notification= array(
            'message'       => 'Subsidiary Saved!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Subsidiary  $subsidiary
     * @return \Illuminate\Http\Response
     */
    public function show(Subsidiary $subsidiary)
    {
      return view('backend.subsidiary.view', compact('subsidiary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subsidiary  $subsidiary
     * @return \Illuminate\Http\Response
     */
    public function edit(Subsidiary $subsidiary)
    {
        return view('backend.subsidiary.edit', compact('subsidiary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subsidiary  $subsidiary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subsidiary = Subsidiary::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255|unique:subsidiaries,company_name,' . $subsidiary->id,
            'trn_no' => 'required|string|max:100|unique:subsidiaries,trn_no,' . $subsidiary->id,
            'company_name_arabic' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_address_arabic' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_mobile' => 'nullable|string|max:50',
            'company_tele' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:20',
            'p_o_box' => 'nullable|string|max:50',
            'running_no' => 'nullable|string|max:50',
            'title_name' => 'nullable|string|max:255',
            'arabic_context' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {

            if ($subsidiary->image && Storage::disk('public')->exists($subsidiary->image)) {
                Storage::disk('public')->delete($subsidiary->image);
            }

            $path = $request->file('image')->store('subsidiaries', 'public');
            $validated['image'] = $path;
        }
        $subsidiary->update($validated);
        $notification= array(
            'message'       => 'Subsidiary Updated!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subsidiary  $subsidiary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subsidiary $subsidiary)
    {
        //
    }
}
