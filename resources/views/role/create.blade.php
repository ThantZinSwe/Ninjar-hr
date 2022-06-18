@extends('layouts.app')
@section('title','Create Role')
@section('content')

    <div>
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('role.store') }}" method="post">
                @csrf

                <div class="md-form">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">

                    @if ($errors->has('name'))
                        <p class="text-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <label for="">Permissions</label>
                <div class="row">
                    @foreach ($permission as $items)
                    <div class="col-md-3 col-6 mt-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="permissions[]" class="custom-control-input" id="check_{{ $items->id }}" value="{{ $items->name }}">
                            <label class="custom-control-label" for="check_{{ $items->id }}">{{ $items->name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
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
