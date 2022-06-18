@extends('layouts.app')
@section('title','Create Salary')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('salary.store') }}" method="post">
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

                <div class="form-group">
                    <label for="">Month</label>
                    <select name="month" id="" class="form-control select-month">
                        <option value="">Choose a month</option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>

                    @if ($errors->has('month'))
                        <p class="text-danger">{{ $errors->first('month') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Year</label>
                    <select name="year" id="" class="form-control select-year">
                        <option value="">Choose a year</option>
                        @for($i=0; $i<5;$i++)
                        <option value="{{ now()->subYears($i)->format('Y') }}">{{ now()->subYears($i)->format('Y') }}</option>
                        @endfor
                    </select>

                    @if ($errors->has('year'))
                        <p class="text-danger">{{ $errors->first('year') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Amount(MMK)</label>
                    <input type="text" name="amount" class="form-control" value="{{ old('amount') }}">

                    @if ($errors->has('amount'))
                        <p class="text-danger">{{ $errors->first('amount') }}</p>
                    @endif
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
    </script>

@endsection
