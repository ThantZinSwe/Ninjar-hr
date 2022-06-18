@extends('layouts.app')
@section('title','Edit Employees')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('employee.update',$employee->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="md-form">
                    <label for="">Employee Id</label>
                    <input type="text" name="employeeId" class="form-control" value="{{ old('employeeId',$employee->employee_id) }}">

                    @if ($errors->has('employeeId'))
                        <p class="text-danger">{{ $errors->first('employeeId') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$employee->name) }}">


                    @if ($errors->has('name'))
                        <p class="text-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Phone</label>
                    <input type="number" name="phone" class="form-control" value="{{ old('phone',$employee->phone) }}">

                    @if ($errors->has('phone'))
                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$employee->email) }}">

                    @if ($errors->has('email'))
                        <p class="text-danger">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Pin Code</label>
                    <input type="number" name="pinCode" class="form-control" value="{{ old('pinCode',$employee->pin_code) }}">

                    @if ($errors->has('pinCode'))
                        <p class="text-danger">{{ $errors->first('pinCode') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                </div>

                <div class="md-form">
                    <label for="">Nrc Number</label>
                    <input type="text" name="nrcNumber" class="form-control" value="{{ old('nrcNumber',$employee->nrc_number) }}">

                    @if ($errors->has('nrcNumber'))
                        <p class="text-danger">{{ $errors->first('nrcNumber') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Gender</label>
                    <select name="gender" id="" class="form-control">
                        <option value="">Choose Options...</option>
                        <option value="male" {{ old('gender',$employee->gender)=='male'? 'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender',$employee->gender)=='female'? 'selected':'' }}>Female</option>
                    </select>

                    @if ($errors->has('gender'))
                        <p class="text-danger">{{ $errors->first('gender') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Brithday</label>
                    <input type="text" name="birthday" class="form-control birthday" value="{{ old('birthday',$employee->birthday) }}">

                    @if ($errors->has('birthday'))
                        <p class="text-danger">{{ $errors->first('birthday') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Address</label>
                    <textarea class="md-textarea form-control" name="address" rows="3">{{ old('address',$employee->address) }}</textarea>

                    @if ($errors->has('address'))
                        <p class="text-danger">{{ $errors->first('address') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Department</label>
                    <select name="department" id="" class="form-control select-box">
                        <option value="">Choose Department...</option>
                        @foreach ($department as $departments)
                            <option value="{{ $departments->id }}" {{ old('department',$employee->department_id)==$departments->id? 'selected':'' }}>{{ $departments->title }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('department'))
                        <p class="text-danger">{{ $errors->first('department') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Role</label>
                    <select name="roles[]" id="" class="form-control select-box" multiple>
                        <option value="">Choose Role...</option>
                        @foreach ($role as $roles)
                            <option value="{{ $roles->name }}" @if (in_array($roles->id,$oldRole)) selected @endif>{{ $roles->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('roles[]'))
                        <p class="text-danger">{{ $errors->first('roles[]') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Date Of Join</label>
                    <input type="text" name="dateOfJoin" class="form-control dateOfJoin" value="{{ old('dateOfJoin',$employee->date_of_birth) }}">

                    @if ($errors->has('dateOfJoin'))
                        <p class="text-danger">{{ $errors->first('dateOfJoin') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="profileImage">Profile Image</label>
                    <input type="file" name="profileImage" id="profileImage" class="form-control p-1">

                    <div class="preview_img mt-2">
                        @if ($employee->profile_img)
                            <img src="{{ $employee->profile_img_path() }}" alt="">
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Is Present</label>
                    <select name="isPresent" id="" class="form-control">
                        <option value="">Choose options...</option>
                        <option value="1" {{ old('isPresent',$employee->is_present)=='1'? 'selected':'' }}>Yes</option>
                        <option value="0" {{ old('isPresent',$employee->is_present)=='0'? 'selected':'' }}>No</option>
                    </select>

                    @if ($errors->has('isPresent'))
                        <p class="text-danger">{{ $errors->first('isPresent') }}</p>
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
        $(document).ready(function(){
            $('.birthday').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                'maxDate': moment(),
                "locale":{
                    "format" : "YYYY-MM-DD",
                }
            });

            $('.dateOfJoin').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : "YYYY-MM-DD",
                }
            });

            $('#profileImage').on('change',function(){
                var file_length = document.getElementById('profileImage').files.length;
                $('.preview_img').html('');

                for(var i=0; i<file_length; i++){
                    $('.preview_img').append(`<img src = "${URL.createObjectURL(event.target.files[i])}"/>`);
                }
            });

        });
    </script>

@endsection
