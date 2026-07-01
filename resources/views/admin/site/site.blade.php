@extends('layouts.master')

@section('content')
    <style>
        .site-image-preview {
            display: block;
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-top: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 4px;
            background: #fff;
        }
    </style>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-2 page-title">General Settings</h2>
                    </div>
                    <div class="col-12">
                        <div class="card p-3">
                            <form action="{{ route('updateAppVerision', $site->id) }}" method="post" autocomplete="off"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="main_logo" class="form-label">Main Logo</label>
                                            <input type="file" id="main_logo" class="form-control site-image-input"
                                                name="main_logo" accept="image/*" data-preview="main_logo_preview">
                                            @if ($site->main_logo)
                                                <img id="main_logo_preview" class="site-image-preview"
                                                    src="{{ asset('site/' . $site->main_logo) }}" alt="Main Logo">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="sidebar_logo" class="form-label">Sidebar Logo</label>
                                            <input type="file" id="sidebar_logo" class="form-control site-image-input"
                                                name="sidebar_logo" accept="image/*" data-preview="sidebar_logo_preview">
                                            @if ($site->sidebar_logo)
                                                <img id="sidebar_logo_preview" class="site-image-preview"
                                                    src="{{ asset('site/' . $site->sidebar_logo) }}" alt="Sidebar Logo">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="sidebar_small_logo" class="form-label">Sidebar Logo
                                                <small>small</small>
                                            </label>
                                            <input type="file" id="sidebar_small_logo"
                                                class="form-control site-image-input" name="sidebar_small_logo"
                                                accept="image/*" data-preview="sidebar_small_logo_preview">
                                            @if ($site->sidebar_small_logo)
                                                <img id="sidebar_small_logo_preview" class="site-image-preview"
                                                    src="{{ asset('site/' . $site->sidebar_small_logo) }}"
                                                    alt="Sidebar Small Logo">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="favicon" class="form-label">Favicon
                                            </label>
                                            <input type="file" id="favicon" class="form-control site-image-input"
                                                name="favicon" accept="image/*" data-preview="favicon_preview">
                                            @if ($site->favicon)
                                                <img id="favicon_preview" class="site-image-preview"
                                                    src="{{ asset('site/' . $site->favicon) }}" alt="Favicon">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sitename" class="form-label">Site Name</label>
                                            <input type="text" id="sitename" class="form-control"
                                                placeholder="Site Name" required name="sitename"
                                                value="{{ old('sitename', $site->sitename) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" id="phone" class="form-control" placeholder="Phone"
                                                required name="phone" value="{{ old('phone', $site->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control" placeholder="Email"
                                                required name="email" value="{{ old('email', $site->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea id="address" class="form-control" placeholder="Enter address" name="address">{{ old('address', $site->address) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="driver_app" class="form-label">Rider App Version</label>
                                            <input type="text" id="driver_app" class="form-control"
                                                placeholder="Rider App Version" required name="driver_app"
                                                value="{{ old('driver_app', $site->driver_app) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user_app" class="form-label">User App Version</label>
                                            <input type="text" id="user_app" class="form-control"
                                                placeholder="User App Version" required name="user_app"
                                                value="{{ old('user_app', $site->user_app) }}">
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.site-image-input').forEach(function(input) {
            input.addEventListener('change', function() {
                const file = this.files && this.files[0];
                const previewId = this.dataset.preview;

                if (!file || !previewId) {
                    return;
                }

                let preview = document.getElementById(previewId);

                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = previewId;
                    preview.className = 'site-image-preview';
                    preview.alt = this.previousElementSibling ? this.previousElementSibling.textContent.trim() : 'Preview';
                    this.insertAdjacentElement('afterend', preview);
                }

                preview.src = URL.createObjectURL(file);
            });
        });
    </script>
@endsection
