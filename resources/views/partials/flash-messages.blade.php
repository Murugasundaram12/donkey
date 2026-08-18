@php
    $successMessage = session('success') ?? session('success_message') ?? session('status');
@endphp

@if ($successMessage)
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $successMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
@endif
