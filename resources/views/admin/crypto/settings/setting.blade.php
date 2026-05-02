@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-2 page-title">Settings <small>(Crypto)</small></h2>
                    </div>
                    <div class="col-12">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Wallet Balance</label>
                                        <input type="text" class="form-control" placeholder="Balance" required
                                            name="" value="{{ $site->mining_wallet }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mt-4">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#staticBackdrop">Add Coin</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Add Coins</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('crypto.addCoins', ['site' => $site->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="form-label">Add Coin</label>
                                                    <input type="text" class="form-control" name="add_coin"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value.length > 10) this.value = this.value.slice(0, 10);"
                                                        value="{{ old('add_coin') }}" placeholder="Coin" required>
                                                    @error('add_coin')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning">Add Coin</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('crypto.settings.update', $site->id) }}" method="post"
                                autocomplete="off" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mining_coin" class="form-label">Coins <small class="text-danger">(
                                                    For Each Mining )</small></label>
                                            <input type="text" class="form-control" placeholder="Coins"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value.length > 2) this.value = this.value.slice(0, 2);"
                                                name="mining_coin" value="{{ old('mining_coin', $site->mining_coin) }}">
                                            @error('mining_coin')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="indirect_percentage" class="form-label">Percentage <small
                                                    class="text-danger">(
                                                    For Indirect Mining )</small></label>
                                            <input type="text" class="form-control" placeholder="Percentage"
                                                name="indirect_percentage"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value.length > 2) this.value = this.value.slice(0, 2);"
                                                value="{{ old('indirect_percentage', $site->indirect_percentage) }}">
                                            @error('indirect_percentage')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
