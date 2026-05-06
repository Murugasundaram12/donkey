@extends('layouts.submaster')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <h2 class="mb-2 page-title">Service Area - {{ $pincodeBasedcategories[0]->pincode?->pincode }}
                        <small>(Service Settings)</small>
                    </h2>

                    <div class="col ml-auto">
                        <div class="dropdown float-right">
                            {{-- <a href="{{ route('subscribers.enquiryform') }}"><button class="btn btn-primary float-right ml-3"
                                    type="button">Add more +</button></a> --}}
                            <a href="{{ route('pincodes.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>


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
                                            {{-- <th>City</th> --}}
                                            <th>Category</th>
                                            {{-- <th>Employee Id</th>

                                            <th>Mobile</th>
                                            <th>Email</th>

                                            <th>Category</th> --}}

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($pincodeBasedcategories as $pincodeBasedcategory)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                {{-- <td>{{ $pincode->city }}</td> --}}
                                                <td>{{ $pincodeBasedcategory->category->category }}</td>
                                                {{-- @if ($enquiry->subscriberId == 0)
                                                    <td>Admin</td>
                                                @else
                                                    <td>{{ $enquiry->emp_id }}</td>
                                                @endif
                                                <td>{{ $enquiry->mobile }}</td>

                                                <td>{{ $enquiry->mailId }}</td>
                                                <td>{{ $enquiry->category }}</td> --}}

                                                <td>
                                                    <input type="checkbox" data-id="{{ $pincodeBasedcategory->id }}"
                                                        name="status" class="js-switch"
                                                        {{ $pincodeBasedcategory->status == 1 ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
    <script src="https://technext.github.io/tinydash/js/jquery.dataTables.min.js"></script>
    <script src='https://technext.github.io/tinydash/js/dataTables.bootstrap4.min.js'></script>
    <script>
        $('#dataTable-1').DataTable({
            autoWidth: true,
            "lengthMenu": [
                [16, 32, 64, -1],
                [16, 32, 64, "All"]
            ]
        });
    </script>
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            let switchery = new Switchery(html, {
                size: 'small'
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.js-switch').change(function() {
                let status = $(this).prop('checked') === true ? 1 : 0;
                let Id = $(this).data('id');
                console.log(Id, status);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('pincodebasedcategorystatus') }}",
                    data: {
                        'status': status,
                        'id': Id
                    },
                    success: function(data) {
                        //console.log(data.success);
                        // $('#message').fadeIn().html(data.success);
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endsection
