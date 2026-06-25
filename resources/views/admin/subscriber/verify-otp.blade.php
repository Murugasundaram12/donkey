@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Verify WhatsApp Number</strong>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <p>Enter the 6-digit verification code sent to WhatsApp number {{ $maskedPhone }}.</p>
                        <p class="text-muted">The code is valid for 60 seconds.</p>

                        <form method="post" action="{{ route('subscriber.otp.verify') }}">
                            @csrf
                            <div class="form-group">
                                <label for="otp">Verification code</label>
                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    pattern="[0-9]{6}" maxlength="6"
                                    class="form-control @error('otp') is-invalid @enderror"
                                    id="otp" name="otp" required autofocus>
                                @error('otp')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Verify OTP</button>
                        </form>

                        <form method="post" action="{{ route('subscriber.otp.resend') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
