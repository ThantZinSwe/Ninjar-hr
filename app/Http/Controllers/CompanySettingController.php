<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanySettingController extends Controller {

    //Company Setting Show
    public function show( $id ) {

        if ( !auth()->user()->can( 'companySetting_view' ) ) {
            abort( 404 );
        }

        $setting = CompanySetting::findOrFail( $id );

        return view( 'companySetting.show' )->with( array( 'setting' => $setting ) );
    }

    //Company Setting Edit
    public function edit( $id ) {

        if ( !auth()->user()->can( 'companySetting_edit' ) ) {
            abort( 404 );
        }

        $setting = CompanySetting::findOrFail( $id );

        return view( 'companySetting.edit' )->with( array( 'setting' => $setting ) );
    }

    //Company Setting Update
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'companySetting_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'companyName'     => 'required',
            'companyEmail'    => 'required',
            'companyPhone'    => 'required',
            'companyAddress'  => 'required',
            'officeStartTime' => 'required',
            'officeEndTime'   => 'required',
            'breakStartTime'  => 'required',
            'breakEndTime'    => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $setting = array(
            'company_name'      => $request->companyName,
            'company_email'     => $request->companyEmail,
            'company_phone'     => $request->companyPhone,
            'company_address'   => $request->companyAddress,
            'office_start_time' => $request->officeStartTime,
            'office_end_time'   => $request->officeEndTime,
            'break_start_time'  => $request->breakStartTime,
            'break_end_time'    => $request->breakEndTime,
        );

        CompanySetting::findOrFail( $id )->update( $setting );

        return redirect()->route( 'companySetting.show', $id )->with( array( 'update' => 'Company Setting Update Success...' ) );

    }

}
