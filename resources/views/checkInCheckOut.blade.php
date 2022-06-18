@extends('layouts.appPlain')
@section('title','Check-In Check-Out ')
@section('content')

    <div class="d-flex justify-content-center content align-items-center" style="height:100vh">
        <div class="col-md-6">
            <div class="card check-card">

                <div class="card-header text-center">
                    <h5>Office-Attendance</h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-5">
                        <h5>QR Code</h5>
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($hashValue)) !!} ">
                        {{-- <img src="{{ asset('image/lock.jpg') }}" alt="" class="lock-image"> --}}
                        <p class="text-muted">Please scan QR Code for office-attendance.</p>
                    </div><hr>
                    <div class="text-center">
                        <h5>Pin Code</h5>
                        <input type="text" name="mycode" id="pincode-input1">
                    </div>

                    <div class="my-3 text-center">
                        <p class="text-muted mb-0">Enter your Pin Code for office-attendance.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('#pincode-input1').pincodeInput({inputs:6,complete:function(value, e, errorElement){
                console.log("code entered: " + value);

                $.ajax({
                    url: '/checkinout',
                    type: 'POST',
                    data: {"pin_code":value},
                    success: function(res){

                        if(res.status == 'success'){
                            Toast.fire({
                                icon: 'success',
                                title: res.message,
                            });
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: res.message,
                            });
                        }
                        $('.pincode-input-container .pincode-input-text').val("");
                        $('.pincode-input-text').first().select().focus();
                    }
                });
            }});

            $('.pincode-input-text').first().select().focus();
        });
    </script>
@endsection
