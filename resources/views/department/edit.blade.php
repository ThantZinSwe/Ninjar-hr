@extends('layouts.app')
@section('title','Edit Department')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('department.update',$department->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="md-form">
                    <label for="">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title',$department->title) }}">

                    @if ($errors->has('title'))
                        <p class="text-danger">{{ $errors->first('title') }}</p>
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
