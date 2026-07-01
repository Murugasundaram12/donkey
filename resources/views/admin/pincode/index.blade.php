@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <h2 class="mb-2 page-title">Pincode</h2>
                    @can('pincode-create')
                        <div class="col ml-auto">
                            <div class="dropdown float-right">
                                <a href="{{ url('createpincode') }}"><button class="btn btn-primary float-right ml-3"
                                        type="button">Add more +</button></a>

                            </div>
                        </div>
                    @endcan


                </div>
                <p class="card-text"> </p>
                @if (Session::has('success'))
                    <!-- Small table -->
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong> </strong> {{ Session::get('success') }} <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (Session::has('error'))
                    <!-- Small table -->
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" x-data="{ showMessage: true }"
                            x-show="showMessage" x-init="setTimeout(() => showMessage = false, 3000)">
                            <strong> </strong> {{ Session::get('error') }} <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="row my-4">

                    <!-- Small table -->
                    <div class="col-md-12">


                        <div class="card shadow">

                            <div class="card-body">

                                <!-- table -->
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>

                                            <th>S.No</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Village/Town</th>
                                            <th>Taluk</th>
                                            <th>Pincode</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pincode as $pin)
                                            <tr>


                                                <td>{{ $pincode->firstItem() + $loop->index }}</td>
                                                <td>{{ $pin->state }}</td>
                                                <td>{{ $pin->district }}</td>
                                                <td>{{ $pin->city }}</td>
                                                <td>{{ $pin->taluk }}</td>
                                                <td>{{ $pin->pincode }}</td>

                                                <td>
                                                    @can('pincode-edit')
                                                        <a href="{{ url('pincode/' . $pin->id) }}"><span
                                                                class="fe fe-24 fe-edit text-success"></span></a>
                                                    @endcan
                                                    @can('pincode-delete')
                                                        <a href="{{ url('pincodedelete/' . $pin->id) }}"
                                                            class="button delete-confirm"><span
                                                                class="fe fe-24 fe-trash text-danger"></span></a>
                                                    @endcan


                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $pincode->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'This record and it`s details will be permanantly deleted!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection
