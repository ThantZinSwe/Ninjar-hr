@extends('layouts.app')
@section('title','Ninja HR Home')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-12 text-center">
                    <img src="{{ $employee->profile_img_path() }}" alt="" class="profile-img">

                    <div class="p-3">
                        <h5 class="fw-bold">{{ ucfirst($employee->name) }}</h5>
                        <p class="text-muted mb-2"><span class="text-dark">{{ $employee->employee_id }}</span> | {{ $employee->phone }}</p>
                        <p class="text-muted mb-2 mt-2"><span class="badge badge-pill badge-light">{{ $employee->department ? $employee->department->title : '-' }}</span></p>
                        <p class="text-muted mb-0">
                            @foreach ($employee->roles as $role)
                                <span class="badge badge-pill badge-success mt-1 ">{{ $role->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
