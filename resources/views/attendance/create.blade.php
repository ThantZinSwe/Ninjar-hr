@extends('layouts.app')
@section('title','Create Attendance')
@section('extra_css')
    <style>
        .daterangepicker .drp-calendar.left {
            margin-right: 8px !important;
        }
    </style>
@endsection
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>
    <div class="card">
        <div class="card-body">

           @include('layouts.error')
            <form action="{{ route('attendance.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="">Employee</label>
                    <select name="employee" id="" class="form-control select-box">
                        <option value="">Choose Employee...</option>
                        @foreach ($employee as $employees)
                            <option value="{{ $employees->id }}" {{ old('employee')==$employees->id? 'selected':'' }}>{{ $employees->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('employee'))
                        <p class="text-danger">{{ $errors->first('employee') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Date</label>
                    <input type="text" name="date" class="form-control date" value="{{ old('date') }}">

                    @if ($errors->has('date'))
                        <p class="text-danger">{{ $errors->first('date') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Checkin Time</label>
                    <input type="text" name="checkin" class="form-control datePicker" value="{{ old('checkin') }}">
                </div>

                <div class="md-form">
                    <label for="">Checkout Time</label>
                    <input type="text" name="checkout" class="form-control datePicker" value="{{ old('checkout') }}">
                </div>

                <div class="d-flex justify-content-center">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-theme btn-sm btn-block">Confirm</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection
@section('script')

    <script>
        $(document).ready(function(){
            $('.date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : "YYYY-MM-DD",
                }
            });

            $('.datePicker').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "autoApply": true,
                "locale":{
                    "format" : "HH:mm:ss",
                }
            }).on('show.daterangepicker', function(ev, picker) {
                $('.calendar-table').hide();
            });
        });
    </script>

@endsection
