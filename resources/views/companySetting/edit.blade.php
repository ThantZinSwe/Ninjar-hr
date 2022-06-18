@extends('layouts.app')
@section('title','Edit Company Setting')
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
            <form action="{{ route('companySetting.update',1) }}" method="post">
                @csrf
                @method('PUT')
                <div class="md-form">
                    <label for="">Company Name</label>
                    <input type="text" name="companyName" class="form-control" value="{{ old('companyName',$setting->company_name) }}">

                    @if ($errors->has('companyName'))
                        <p class="text-danger">{{ $errors->first('companyName') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Company Email</label>
                    <input type="email" name="companyEmail" class="form-control" value="{{ old('companyEmail',$setting->company_email) }}">

                    @if ($errors->has('companyEmail'))
                        <p class="text-danger">{{ $errors->first('companyEmail') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Company Phone</label>
                    <input type="number" name="companyPhone" class="form-control" value="{{ old('companyPhone',$setting->company_phone) }}">

                    @if ($errors->has('companyPhone'))
                        <p class="text-danger">{{ $errors->first('companyPhone') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Company Address</label>
                    <textarea class="md-textarea form-control pt-3" name="companyAddress" rows="3">{{ old('address',$setting->company_address) }}</textarea>

                    @if ($errors->has('companyAddress'))
                        <p class="text-danger">{{ $errors->first('companyAddress') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Office Start Time</label>
                    <input type="text" name="officeStartTime" class="form-control timePicker" value="{{ old('officeStartTime',$setting->office_start_time) }}">

                    @if ($errors->has('officeStartTime'))
                        <p class="text-danger">{{ $errors->first('officeStartTime') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Office End Time</label>
                    <input type="text" name="officeEndTime" class="form-control timePicker" value="{{ old('officeEndTime',$setting->office_end_time) }}">

                    @if ($errors->has('officeEndTime'))
                        <p class="text-danger">{{ $errors->first('officeEndTime') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Break Start Time</label>
                    <input type="text" name="breakStartTime" class="form-control timePicker" value="{{ old('breakStartTime',$setting->break_start_time) }}">

                    @if ($errors->has('breakStartTime'))
                        <p class="text-danger">{{ $errors->first('breakStartTime') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Break End Time</label>
                    <input type="text" name="breakEndTime" class="form-control timePicker" value="{{ old('breakEndTime',$setting->break_end_time) }}">

                    @if ($errors->has('breakEndTime'))
                        <p class="text-danger">{{ $errors->first('breakEndTime') }}</p>
                    @endif
                </div>

                @can('companySetting_edit')
                <div class="d-flex justify-content-center">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-theme btn-sm btn-block">Confirm</button>
                    </div>
                </div>
                @endcan

            </form>
        </div>
    </div>

@endsection
@section('script')

    <script>
        $(document).ready(function(){
            $('.timePicker').daterangepicker({
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
