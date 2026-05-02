@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <h2 class="mb-2 page-title">Company Details</h2>
                    <div class="col ml-auto">
                        <div class="dropdown float-right">
                            <a href="{{ route('admin.companies.index') }}">
                                <button class="btn btn-secondary float-right ml-3" type="button">Back to Companies</button>
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="row my-4">
                    <!-- Company Info Card -->
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">{{ $company->name }}</h4>
                                @if($company->status == 'active')
                                    <span class="badge badge-success px-3 py-2">Active</span>
                                @else
                                    <span class="badge badge-secondary px-3 py-2">Inactive</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="text-muted" style="width: 40%;">Company ID</th>
                                                <td><strong>{{ $company->company_id }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Company Code</th>
                                                <td><span class="badge badge-light">{{ $company->company_code }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Name</th>
                                                <td>{{ $company->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Email</th>
                                                <td><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Phone</th>
                                                <td><a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="text-muted">Address</th>
                                                <td>{{ $company->full_address ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Created At</th>
                                                <td>{{ $company->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Updated At</th>
                                                <td>{{ $company->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning">
                                            <i class="fe fe-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.companies.toggle-status', $company) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $company->status == 'active' ? 'btn-secondary' : 'btn-success' }}">
                                                <i class="fe fe-{{ $company->status == 'active' ? 'pause' : 'play' }}"></i>
                                                {{ $company->status == 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                    <form id="delete-form-{{ $company->id }}"
                                        action="{{ route('admin.companies.destroy', $company) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger delete-confirm" data-id="{{ $company->id }}">
                                            <i class="fe fe-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Quick Stats</h4>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-12 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h2 class="mb-0">{{ $company->bookings_count ?? 0 }}</h2>
                                                <small class="text-muted">Total Bookings</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                @if($company->bookings && $company->bookings->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Recent Bookings</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($company->bookings as $booking)
                                                <tr>
                                                    <td><strong>#{{ $booking->booking_id ?? $booking->id }}</strong></td>
                                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($booking->status == 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @elseif($booking->status == 'cancelled')
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        @else
                                                            <span class="badge badge-warning">{{ ucfirst($booking->status ?? 'Pending') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('viewbooking', $booking) }}" class="btn btn-sm btn-info">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-confirm').click(function(e) {
                e.preventDefault();
                var formId = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: 'Company will be permanently deleted!',
                    icon: 'warning',
                    buttons: ["Cancel", "Yes, delete!"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $('#delete-form-' + formId).submit();
                    }
                });
            });
        });
    </script>
@endsection
