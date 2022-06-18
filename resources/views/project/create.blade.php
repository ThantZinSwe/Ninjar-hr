@extends('layouts.app')
@section('title','Create Project')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('project.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="md-form">
                    <label for="">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">

                    @if ($errors->has('title'))
                        <p class="text-danger">{{ $errors->first('title') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Description</label>
                    <textarea class="md-textarea form-control" name="description" rows="3">{{ old('description') }}</textarea>

                    @if ($errors->has('description'))
                        <p class="text-danger">{{ $errors->first('description') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Images (JPG,JPEG,PNG)</label>
                    <input type="file" name="images[]" id="images" class="form-control p-1" multiple accept=".png, .jpg, .jpeg">

                    <div class="preview_img mt-2">

                    </div>
                </div>

                <div class="form-group">
                    <label for="">Files (PDF)</label>
                    <input type="file" name="files[]" id="files" class="form-control p-1" multiple accept="application/pdf">
                </div>

                <div class="md-form">
                    <label for="">Start Date</label>
                    <input type="text" name="startDate" class="form-control datePicker" value="{{ old('startDate') }}">

                    @if ($errors->has('startDate'))
                        <p class="text-danger">{{ $errors->first('startDate') }}</p>
                    @endif
                </div>

                <div class="md-form">
                    <label for="">Deadline</label>
                    <input type="text" name="deadline" class="form-control datePicker" value="{{ old('deadline') }}">

                    @if ($errors->has('deadline'))
                        <p class="text-danger">{{ $errors->first('deadline') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Leaders</label>
                    <select name="leaders[]" id="" class="form-control select-ninja" multiple>
                        <option value="">Please Choose...</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('leaders[]')==$employee->id? 'selected' : ''}}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Members</label>
                    <select name="members[]" id="" class="form-control select-ninja" multiple>
                        <option value="">Please Choose...</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('members[]')==$employee->id? 'selected' : ''}}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Priority</label>
                    <select name="priority" id="" class="form-control select-ninja">
                        <option value="">Choose an Option...</option>
                        <option value="high" {{ old('priority')=='high'? 'selected':'' }}>High</option>
                        <option value="middle" {{ old('priority')=='middle'? 'selected':'' }}>Middle</option>
                        <option value="low" {{ old('priority')=='low'? 'selected':'' }}>Low</option>
                    </select>

                    @if ($errors->has('priority'))
                        <p class="text-danger">{{ $errors->first('priority') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" id="" class="form-control select-ninja">
                        <option value="">Choose an Option...</option>
                        <option value="pending" {{ old('status')=='pending'? 'selected':'' }}>Pending</option>
                        <option value="in_progress" {{ old('status')=='in_progress'? 'selected':'' }}>In Progress</option>
                        <option value="complete" {{ old('status')=='complete'? 'selected':'' }}>Complete</option>
                    </select>

                    @if ($errors->has('status'))
                        <p class="text-danger">{{ $errors->first('status') }}</p>
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

            $('.datePicker').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale":{
                    "format" : "YYYY-MM-DD",
                }
            });

            $('#images').on('change',function(){
                var file_length = document.getElementById('images').files.length;
                $('.preview_img').html('');
                for(var i=0; i<file_length; i++){
                    $('.preview_img').append(`<img src = "${URL.createObjectURL(event.target.files[i])}"/>`)
                }
            });
        });
    </script>

@endsection
