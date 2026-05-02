@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <h2 class="mb-2 page-title">Companies List</h2>
                    <div class="col ml-auto">
                        <div class="dropdown float-right">
                            <a href="{{ route('admin.companies.create') }}">
                                <button class="btn btn-primary float-right ml-3" type="button">Add more +</button>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="card-text"> </p>

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
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body table-responsive">
                                    <table class="table datatables" id="dataTable-1">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Company Code</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($companies as $company)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $company->company_code }}</strong></td>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company->email }}</td>
                                                    <td>{{ $company->phone }}</td>
                                                    
                                                    <td>
                                                        <a href="{{ route('admin.companies.show', $company) }}" title="View">
                                                            <span class="fe fe-24 fe-eye text-warning"></span>
                                                        </a>
                                                        <a href="{{ route('admin.companies.edit', $company) }}" title="Edit">
                                                            <span class="fe fe-24 fe-edit text-success"></span>
                                                        </a>
                                                        <form id="delete-form-{{ $company->id }}"
                                                            action="{{ route('admin.companies.destroy', $company) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a href="#" class="delete-confirm" data-id="{{ $company->id }}"
                                                            title="Delete">
                                                            <span class="fe fe-24 fe-trash text-danger"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <div class="py-5">
                                                            <i class="fe fe-building fe-48 text-muted mb-3"></i>
                                                            <h5 class="mb-2">No companies found</h5>
                                                            <p class="text-muted mb-4">Get started by creating a new company.
                                                            </p>
                                                            <a href="{{ route('admin.companies.create') }}"
                                                                class="btn btn-primary">Add Company</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable-1').DataTable({
                autoWidth: true,
                "lengthMenu": [[16, 32, 64, -1], [16, 32, 64, "All"]],
                "order": []
            });

            $('.delete-confirm').click(function (e) {
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
