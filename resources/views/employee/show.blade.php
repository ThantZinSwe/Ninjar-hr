@extends('layouts.app')
@section('title','Employee Details')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-6 text-center">
                    <img src="{{ $employee->profile_img_path() }}" alt="" class="profile-img">

                    <div class="p-3">
                        <h5 class="fw-bold">{{ ucfirst($employee->name) }}</h5>
                        <p class="text-muted mb-2"><span class="text-dark">{{ $employee->employee_id }}</span> | {{ $employee->phone }}</p>
                        <p class="text-muted mb-2 mt-2"><span class="badge badge-pill badge-light">{{ $employee->department ? $employee->department->title : '-' }}</span></p>
                        <p class="text-muted mb-0">
                            @foreach ($employee->roles as $role)
                                <span class="badge badge-pill badge-success mt-1">{{ $role->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="col-md-6 dash-border">
                    <div class="mt-3 ms-1">
                        <p class="mb-2"><strong>Email : </strong><span class="text-muted">{{ $employee->email }}</span></p>
                        <p class="mb-2"><strong>Phone : </strong><span class="text-muted">{{ $employee->phone }}</span></p>
                        <p class="mb-2"><strong>Address : </strong><span class="text-muted">{{ $employee->address }}</span></p>
                        <p class="mb-2"><strong>Birthday : </strong><span class="text-muted">{{ $employee->birthday }}</span></p>
                        <p class="mb-2"><strong>Nrc Number : </strong><span class="text-muted">{{ $employee->nrc_number }}</span></p>
                        <p class="mb-2"><strong>Gender : </strong><span class="text-muted">{{ ucfirst($employee->gender) }}</span></p>
                        <p class="mb-2"><strong>Date Of Join : </strong><span class="text-muted">{{ $employee->date_of_join }}</span></p>
                        <p class="mb-2"><strong>Is Present : </strong>
                            <span class="text-muted">
                                @if ($employee->is_present == 1)
                                    <span class="badge badge-pill badge-success">Present</span>
                                @else
                                    <span class="badge badge-pill badge-danger">Leave</span>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
