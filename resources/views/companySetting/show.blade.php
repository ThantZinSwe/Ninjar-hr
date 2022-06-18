@extends('layouts.app')
@section('title','Company Setting')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Company Name</p>
                    <p class="text-muted">{{ $setting->company_name }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Company Email</p>
                    <p class="text-muted">{{ $setting->company_email }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Company Phone</p>
                    <p class="text-muted">{{ $setting->company_phone }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Company Address</p>
                    <p class="text-muted">{{ $setting->company_address }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Office Start Time</p>
                    <p class="text-muted">{{ $setting->office_start_time }} AM</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Office End Time</p>
                    <p class="text-muted">{{ $setting->office_end_time }} PM</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Break Start Time</p>
                    <p class="text-muted">{{ $setting->break_start_time }} PM</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-0"><i class="fab fa-centercode"></i> Break End Time</p>
                    <p class="text-muted">{{ $setting->break_end_time }} PM</p>
                </div>

                @can('companySetting_edit')
                <div class="m-2">
                    <a href="{{ route('companySetting.edit',1) }}" class="btn btn-sm btn-theme"><i class="fas fa-edit"></i> Edit Company Setting</a>
                </div>
                @endcan
            </div>
        </div>
    </div>


@endsection
