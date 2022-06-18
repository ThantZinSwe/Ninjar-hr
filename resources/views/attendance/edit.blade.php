@extends('layouts.app')
@section('title','Edit Attendance')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            @include('layouts.error')
            <form action="{{ route('attendance.update',$attendance->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="">Employee</label>
                    <select name="employee" id="" class="form-control select-box">
                        <option value="">Choose Employee...</option>
                        @foreach ($employee as $employees)
                            <option value="{{ $employees->id }}" {{ old('employee',$attendance->user_id)==$employees->id? 'selected':'' }}>{{ $employees->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('employee'))
                        <p class="text-danger">{{ $errors->first('employee') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Date</label>
                    <input type="text" name="date" class="form-control date" value="{{ old('date',$attendance->date) }}">

                    @if ($errors->has('date'))
                        <p class="text-danger">{{ $errors->first('date') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Checkin Time</label>
                    <input type="text" name="checkin" class="form-control datePicker" value="{{ old('checkin', Carbon\Carbon::parse($attendance->checkin_time)->format('H:i:s')) }}">
                </div>

                <div class="md-form">
                    <label for="">Checkout Time</label>
                    <input type="text" name="checkout" class="form-control datePicker" value="{{ old('checkout', Carbon\Carbon::parse($attendance->checkout_time)->format('H:i:s')) }}">
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
            picker.container.find('.calendar-table').hide();
        });
    });
</script>
@endsection
