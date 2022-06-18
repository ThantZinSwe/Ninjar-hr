@extends('layouts.app')
@section('title','Edit Salary')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('salary.update',$salary->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="">Employee</label>
                    <select name="employee" id="" class="form-control select-box">
                        <option value="">Choose Employee...</option>
                        @foreach ($employee as $employees)
                            <option value="{{ $employees->id }}" {{ old('employee',$salary->user_id)==$employees->id? 'selected':'' }}>{{ $employees->name }}</option>
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
                        <option value="01" @if($salary->month == '01') selected @endif>Jan</option>
                        <option value="02" @if($salary->month == '02') selected @endif>Feb</option>
                        <option value="03" @if($salary->month == '03') selected @endif>Mar</option>
                        <option value="04" @if($salary->month == '04') selected @endif>Apr</option>
                        <option value="05" @if($salary->month == '05') selected @endif>May</option>
                        <option value="06" @if($salary->month == '06') selected @endif>Jun</option>
                        <option value="07" @if($salary->month == '07') selected @endif>Jul</option>
                        <option value="08" @if($salary->month == '08') selected @endif>Aug</option>
                        <option value="09" @if($salary->month == '09') selected @endif>Sep</option>
                        <option value="10" @if($salary->month == '10') selected @endif>Oct</option>
                        <option value="11" @if($salary->month == '11') selected @endif>Nov</option>
                        <option value="12" @if($salary->month == '12') selected @endif>Dec</option>
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
                        <option value="{{ now()->subYears($i)->format('Y') }}" @if($salary->year == now()->subYears($i)->format('Y')) selected @endif>{{ now()->subYears($i)->format('Y') }}</option>
                        @endfor
                    </select>

                    @if ($errors->has('year'))
                        <p class="text-danger">{{ $errors->first('year') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Amount(MMK)</label>
                    <input type="text" name="amount" class="form-control" value="{{ old('amount',$salary->amount) }}">

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
