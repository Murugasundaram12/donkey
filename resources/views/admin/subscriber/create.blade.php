@extends('layouts.master')
<style>
    #pincode + .multiselect-dropdown {
        width: 100% !important;
        display: block !important;
    }

    #pincode + .multiselect-dropdown .multiselect-dropdown-list-wrapper {
        left: 0 !important;
        right: 0 !important;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Subscriber</h2>
                <p class="text-muted"></p>
                <div class="row">

                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <strong class="card-title">Add Subscriber</strong>
                            </div>
                            <div class="card-body">

                                <input type="hidden" id="pincodeSearchApiUrl" value="{{ route('pincode.search') ?? url('pincode/search') }}">
                                <form method="post" action="{{ url('subscriberstore') }}"
                                    autocomplete="off"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div id="pincodeTopAlert" class="alert alert-warning alert-dismissible fade show" style="display:none;">
                                        📍 Can't find your pincode? WhatsApp 9069067008 with your preferred pincode. We'll check and update you.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div id="subscriberClientSuccessMessage" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
                                        Application Submitted Successfully<br>
                                        Your application has been received and is under review.<br>
                                        The review process typically takes 5-7 business days.<br>
                                        You will receive a WhatsApp notification if your application is approved or rejected. If additional information is required, our team may contact you through our official WhatsApp number: 9069067008.<br>
                                        If your application remains under review beyond 7 business days, you may contact us through our official WhatsApp number using your registered mobile number and Service Provider ID.<br>
                                        To ensure timely processing for all applicants, please avoid sending repeated follow-up messages during the review period.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror "
                                                id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter name </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="location">Location</label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror" id="location"
                                                value="{{ old('location') }}" name="location" required>
                                            @error('location')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter location </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="password">Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror " id="password"
                                                name="password" value="{{ old('password') }}" autocomplete="new-password" required>
                                            @error('password')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter password </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="subscriptionDate">Subscription Date</label>
                                            <input class="form-control @error('subscriptionDate') is-invalid @enderror"
                                                id="subscriptionDate" type="date" value="{{ old('subscriptionDate', now()->format('Y-m-d')) }}"
                                                name="subscriptionDate" required>
                                            @error('subscriptionDate')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please select subscription date </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expiryDate">Expiry Date</label>
                                            <input class="form-control @error('expiryDate') is-invalid @enderror" id="expiryDate" type="text" value="{{ old('expiryDate') }}"
                                                name="expiryDate" readonly>
                                            @error('expiryDate')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please use a valid email </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mobile">Mobile</label>
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                id="mobile" value="{{ old('mobile') }}"
                                                onkeypress="return isNumberKey(event)" name="mobile" required>
                                            @error('mobile')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter mobile number </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="subscriptionPrice">Price for Subscription</label>
                                            <input type="text"
                                                class="form-control @error('subscriptionPrice') is-invalid @enderror"
                                                id="subscriptionPrice" value="{{ old('subscriptionPrice', 2) }}"
                                                onkeypress="return isNumberKey(event)" name="subscriptionPrice" readonly>
                                            @error('subscriptionPrice')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter subscription price </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="description">Address</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter description </div>
                                        </div>
                                        {{-- <div class="col-md-6 mb-3">
                                            <div>
                                                <label for="pincode">Pincode</label>
                                                <select name="pincode[]"
                                                    class="form-control @error('pincode') is-invalid @enderror" id="pincode"
                                                    multiple multiselect-search="true" value=""
                                                    multiselect-select-all="true"
                                                    onchange="console.log(this.selectedOptions)">
                                                    @foreach ($pincode as $pin)
                                                        <option value="{{ $pin->id }}"
                                                            {{ is_array(old('pincode')) && in_array($pin->id, old('pincode')) ? 'selected' : '' }}>
                                                            {{ $pin->pincode }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('pincode')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="invalid-feedback"> Please select a valid pincode. </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-6 mb-3">
                                            <div>
                                                <label for="pincode">Pincode</label>
                                                <select name="pincode[]"
                                                    class="form-control @error('pincode') is-invalid @enderror"
                                                    id="pincode"
                                                    multiple
                                                    multiselect-search="true"
                                                    multiselect-select-all="true">

                                                    @foreach ($pincode as $pin)
                                                        <option value="{{ $pin->id }}"
                                                            {{ is_array(old('pincode')) && in_array($pin->id, old('pincode')) ? 'selected' : '' }}>
                                                            {{ $pin->pincode }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('pincode')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                                <div class="invalid-feedback">
                                                    Please select a valid pincode.
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal: Pincode not listed -->
                                        <div class="modal fade" id="pincodeNotListedModal" tabindex="-1" role="dialog"
                                            aria-labelledby="pincodeNotListedModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pincodeNotListedModalLabel">Pincode Not Found</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        📍 Can't find your pincode? WhatsApp 9069067008 with your preferred pincode. We'll check and update you.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="pincodeModalOkBtn" data-dismiss="modal">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="aadharNo">Aadhar Number</label>
                                            <input type="text"
                                                class="form-control @error('aadharNo') is-invalid @enderror"
                                                id="aadharNo" value="{{ old('aadharNo') }}"
                                                onkeypress="return isNumberKey(event)" name="aadharNo" required>
                                            @error('aadharNo')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter aadhar number </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="aadharImage">Aadhar Image</label>
                                            <input type="file"
                                                class="form-control @error('aadharImage') is-invalid @enderror"
                                                id="aadharImage" value="{{ old('aadharImage') }}" accept=".pdf"
                                                name="aadharImage" required>
                                            <small id="aadharBackImage" class="form-text text-muted">Note:Please upload
                                                Pdf format </small>
                                            @error('aadharImage')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload aadhar image </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="aadharBackImage">Aadhar Back Image</label>
                                            <input type="file"
                                                class="form-control @error('aadharBackImage') is-invalid @enderror"
                                                id="aadharBackImage" value="{{ old('aadharBackImage') }}" accept=".pdf"
                                                name="aadharBackImage" required>
                                            <small id="aadharBackImage" class="form-text text-muted">Note:Please upload
                                                Pdf format </small>
                                            @error('aadharBackImage')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload aadhar back image </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="pancardImage">Pancard Image</label>
                                            <input type="file"
                                                class="form-control @error('pancardImage') is-invalid @enderror"
                                                id="pancardImage" value="{{ old('pancardImage') }}" accept=".pdf"
                                                name="pancardImage" required>
                                            <small id="pancardImage" class="form-text text-muted">Note:Please upload
                                                Pdf format </small>
                                            @error('pancardImage')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload pancard image </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="bankacno">Bank Account Number</label>
                                            <input type="text"
                                                class="form-control @error('bankacno') is-invalid @enderror"
                                                id="bankacno" value="{{ old('bankacno') }}" name="bankacno" required>
                                            @error('bankacno')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter bank account number </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ifsccode">IFSC Code</label>
                                            <input type="text"
                                                class="form-control @error('ifsccode') is-invalid @enderror"
                                                id="ifsccode" value="{{ old('ifsccode') }}" name="ifsccode" required>
                                            @error('ifsccode')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter bank's ifsc code </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bankstatement">Bank Statement</label>
                                            <input type="file"
                                                class="form-control @error('bankstatement') is-invalid @enderror"
                                                id="bankstatement" value="{{ old('bankstatement') }}"
                                                accept="application/pdf" name="bankstatement" required>
                                            <small id="bankstatement" class="form-text text-muted">Note:Please upload .pdf
                                                format </small>
                                            @error('bankstatement')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload bankstatement </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="account_type">Account Type</label>
                                            {{-- <input type="file"
                                            class="form-control @error('account_type') is-invalid @enderror"
                                            id="account_type" value="{{ old('account_type') }}"
                                            name="account_type" required> --}}
                                            <select class="form-control" name="account_type">
                                                <option value="">Select Type</option>
                                                <option {{ old('account_type') == 'Current' ? 'selected' : '' }}
                                                    value="Current">
                                                    Current</option>
                                                <option {{ old('account_type') == 'Savings' ? 'selected' : '' }}
                                                    value="Savings">
                                                    Savings</option>
                                            </select>
                                            {{-- <small id="account_type" class="form-text text-muted"></small> --}}
                                            @error('account_type')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please Select Account Type </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="image">Photo</label>
                                            <input type="file"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                value="{{ old('image') }}"
                                                accept="image/png,image/jpeg,image/jpg,image/gif" name="image" required>
                                            <small id="image" class="form-text text-muted">Note:Please upload
                                                Jpeg,Jpg,Png,gif
                                                format </small>
                                            @error('image')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please upload image </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="video">Video </label>
                                            <input type="file"
                                                class="form-control @error('video') is-invalid @enderror" id="video"
                                                value="{{ old('video') }}" accept=".mp4" name="video">
                                            <small id="video" class="form-text text-muted">
                                                Note: Uploading video verification now may speed up approval. Ensure your face and ID proof are clearly visible and state, "This ID belongs to me." If not uploaded, our team may request it through our official WhatsApp number (9069067008) to complete your onboarding process. Please do not share documents with any other number claiming to represent DO N KEY.
                                            </small>
                                            @error('video')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please upload Video </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="customerdocument">Document</label>
                                            <input type="file"
                                                class="form-control @error('customerdocument') is-invalid @enderror"
                                                id="customerdocument" value="{{ old('customerdocument') }}"
                                                accept=".pdf" name="customerdocument">
                                            <small id="customerdocument" class="form-text text-muted">Note: Reserved for official use by DO N KEY for agreements and related records. </small>
                                            @error('customerdocument')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload bike image </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            {{--
                                            <label for="qr">QR</label>
                                            <input type="file"
                                                class="form-control @error('qr') is-invalid @enderror"
                                                id="qr" value="{{ old('qr') }}"
                                                accept="image/*" name="qr">
                                            <small id="qr" class="form-text text-muted">Note:Please upload
                                                image
                                                format </small>
                                            @error('qr')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please upload bike image </div>
                                            --}}
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <label for="biketaxi_price">Bike Taxi Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('biketaxi_price') is-invalid @enderror "
                                                id="biketaxi_price" name="biketaxi_price"
                                                value="{{ old('biketaxi_price') }}" required>
                                            @error('biketaxi_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please Enter Bike Taxi km Price</div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <label for="pickup_price">Pick up and Drop Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('pickup_price') is-invalid @enderror "
                                                id="pickup_price" name="pickup_price" value="{{ old('pickup_price') }}"
                                                required>
                                            @error('pickup_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter pickup km price</div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <label for="buy_price">Buy and Delivery km price</label>
                                            <input type="text"
                                                class="form-control @error('buy_price') is-invalid @enderror "
                                                id="buy_price" name="buy_price" value="{{ old('buy_price') }}" required>
                                            @error('buy_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter buy and delivery km price</div>
                                        </div>
                                        <div class="col-md-2 mb-2 d-none"></div>
                                        <div class="col-md-4 mb-4 d-none">
                                            <label for="auto_price">Auto Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('auto_price') is-invalid @enderror "
                                                name="auto_price" value="{{ old('auto_price', 0) }}" required>
                                            @error('auto_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter auto km price</div>
                                        </div>

                                        <div class="col-md-4 mb-4 d-none">
                                            <label for="cab_price">Cab Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('cab_price') is-invalid @enderror "
                                                name="cab_price" value="{{ old('cab_price', 0) }}" required>
                                            @error('cab_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter cab km price</div>
                                        </div>
                                        <div class="col-md-2 mb-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="container">
                                            <p class="mb-2"><strong>Bike Taxi Fare</strong></p><br>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="bt_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bt_price1') is-invalid @enderror "
                                                        id="bt_price1" name="bt_price1" value="{{ old('bt_price1') }}"
                                                        required>
                                                    @error('bt_price1')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Bike Taxi (1 to 5 km) Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bt_price2">5 to 8 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bt_price2') is-invalid @enderror "
                                                        id="bt_price2" name="bt_price2" value="{{ old('bt_price2') }}"
                                                        required>
                                                    @error('bt_price2')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Bike Taxi (5 to 8 km) Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bt_price3">8 to 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bt_price3') is-invalid @enderror "
                                                        id="bt_price3" name="bt_price3" value="{{ old('bt_price3') }}"
                                                        required>
                                                    @error('bt_price3')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Bike Taxi (8 to 10 km)
                                                        Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bt_price4">Above 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bt_price4') is-invalid @enderror "
                                                        id="bt_price4" name="bt_price4" value="{{ old('bt_price4') }}"
                                                        required>
                                                    @error('bt_price4')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Bike Taxi (above 10 km)
                                                        Price
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-2"><strong>Pickup and drop Fare</strong></p><br>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="pk_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('pk_price1') is-invalid @enderror "
                                                        id="pk_price1" name="pk_price1" value="{{ old('pk_price1') }}"
                                                        required>
                                                    @error('pk_price1')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Pickup (1 to 5 km) Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="pk_price2">5 to 8 km</label>
                                                    <input type="text"
                                                        class="form-control @error('pk_price2') is-invalid @enderror "
                                                        id="pk_price2" name="pk_price2" value="{{ old('pk_price2') }}"
                                                        required>
                                                    @error('pk_price2')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Pickup (5 to 8 km) Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="pk_price3">8 to 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('pk_price3') is-invalid @enderror "
                                                        id="pk_price3" name="pk_price3" value="{{ old('pk_price3') }}"
                                                        required>
                                                    @error('pk_price3')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Pickup (8 to 10 km) Price
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="pk_price4">Above 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('pk_price4') is-invalid @enderror "
                                                        id="pk_price4" name="pk_price4" value="{{ old('pk_price4') }}"
                                                        required>
                                                    @error('pk_price4')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Pickup (above 10 km) Price
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-2"><strong>Buy and Delivery Fare</strong></p>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bd_price1') is-invalid @enderror "
                                                        id="bd_price1" name="bd_price1" value="{{ old('bd_price1') }}"
                                                        required>
                                                    @error('bd_price1')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Buy and Delivey (1 to 5 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price2">5 to 8 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bd_price2') is-invalid @enderror "
                                                        id="bd_price2" name="bd_price2" value="{{ old('bd_price2') }}"
                                                        required>
                                                    @error('bd_price2')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Buy and Delivey (5 to 8 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price3">8 to 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bd_price3') is-invalid @enderror "
                                                        id="bd_price3" name="bd_price3" value="{{ old('bd_price3') }}"
                                                        required>
                                                    @error('bd_price3')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Buy and Delivey (8 to 10
                                                        km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price4">Above 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bd_price4') is-invalid @enderror "
                                                        id="bd_price4" name="bd_price4" value="{{ old('bd_price4') }}"
                                                        required>
                                                    @error('bd_price4')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Buy and Delivey (above 10
                                                        km)
                                                        Price</div>
                                                </div>
                                            </div>
                                            <p class="mb-2 d-none"><strong>Auto Fare</strong></p>
                                            <div class="row d-none">
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('at_price1') is-invalid @enderror "
                                                        name="at_price1" value="{{ old('at_price1', 0) }}" required>
                                                    @error('at_price1')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Auto (1 to 5 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price2">5 to 8 km</label>
                                                    <input type="text"
                                                        class="form-control @error('at_price2') is-invalid @enderror "
                                                        id="at_price2" name="at_price2" value="{{ old('at_price2', 0) }}"
                                                        required>
                                                    @error('at_price2')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Auto (5 to 8 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price3">8 to 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('at_price3') is-invalid @enderror "
                                                        id="at_price3" name="at_price3" value="{{ old('at_price3', 0) }}"
                                                        required>
                                                    @error('at_price3')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Auto (8 to 10 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price4">Above 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('at_price4') is-invalid @enderror "
                                                        id="at_price4" name="at_price4" value="{{ old('at_price4', 0) }}"
                                                        required>
                                                    @error('at_price4')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Auto (above 10 km)
                                                        Price</div>
                                                </div>
                                            </div>
                                            <p class="mb-2 d-none"><strong>Cab Fare</strong></p>
                                            <div class="row d-none">
                                                <div class="col-md-3 mb-3">
                                                    <label for="cab_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('cab_price1') is-invalid @enderror "
                                                        name="cab_price1" value="{{ old('cab_price1', 0) }}" required>
                                                    @error('cab_price1')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Cab (1 to 5 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="cab_price2">5 to 8 km</label>
                                                    <input type="text"
                                                        class="form-control @error('cab_price2') is-invalid @enderror "
                                                        id="cab_price2" name="cab_price2"
                                                        value="{{ old('cab_price2', 0) }}" required>
                                                    @error('cab_price2')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Cab (5 to 8 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="cab_price3">8 to 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('cab_price3') is-invalid @enderror "
                                                        id="cab_price3" name="cab_price3"
                                                        value="{{ old('cab_price3', 0) }}" required>
                                                    @error('cab_price3')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Cab (8 to 10 km)
                                                        Price</div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="cab_price4">Above 10 km</label>
                                                    <input type="text"
                                                        class="form-control @error('cab_price4') is-invalid @enderror "
                                                        id="cab_price4" name="cab_price4"
                                                        value="{{ old('cab_price4', 0) }}" required>
                                                    @error('cab_price4')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback"> Please Enter Cab (above 10 km)
                                                        Price</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <center><button class="btn btn-primary mt-2" type="submit">Submit form</button></center>
                                </form>
                                <div class="modal fade" id="subscriberSuccessModal" tabindex="-1" role="dialog"
                                    aria-labelledby="subscriberSuccessModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-success" id="subscriberSuccessModalLabel">
                                                    Application Submitted Successfully
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Your application has been received and is under review.</p>
                                                <p>The review process typically takes 5&ndash;7 business days.</p>
                                                <p>You will receive a WhatsApp notification if your application is approved or rejected. If additional information is required, our team may contact you through our official WhatsApp number: 9069067008.</p>
                                                <p>If your application remains under review beyond 7 business days, you may contact us through our official WhatsApp number using your registered mobile number and Service Provider ID.</p>
                                                <p class="mb-0">To ensure timely processing for all applicants, please avoid sending repeated follow-up messages during the review period.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success" id="subscriberSuccessOkBtn">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.card-body -->
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->

                </div> <!-- end section -->
            </div> <!-- /.col-12 col-lg-10 col-xl-10 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ url('subscriberstore') }}"]');
    const select = document.getElementById('pincode');
    const subscriptionDateInput = document.getElementById('subscriptionDate');
    const expiryDateInput = document.getElementById('expiryDate');
    const pincodeDebugPrefix = '[Pincode Debug]';
    const draftKey = 'subscriber_onboarding_draft:' + window.location.pathname;
    const submitPendingKey = draftKey + ':submitted';
    const hasValidationErrors = @json($errors->any());
    const hasServerSuccess = @json(session()->has('success') || session()->has('show_success_modal'));
    const subscriberIndexUrl = @json(route('subscriber'));
    const navEntry = performance.getEntriesByType('navigation')[0];
    const isReload = navEntry && navEntry.type === 'reload';
    const isFreshEntry = new URLSearchParams(window.location.search).get('fresh') === '1'
        || (!isReload && document.referrer.indexOf('/subscriberList') !== -1);
    let pincodeModalShown = false;

    function setMessage(show) {
        $('#pincodeTopAlert').hide();
        if (show) {
            if (!pincodeModalShown && window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                $('#pincodeNotListedModal').modal('show');
                pincodeModalShown = true;
            }
        } else {
            pincodeModalShown = false;
        }
    }

    function getFieldKey(field) {
        return field.name || field.id;
    }

    function showClientSuccessMessage() {
        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            $('#subscriberSuccessModal').modal('show');
            return;
        }

        const successMessage = document.getElementById('subscriberClientSuccessMessage');
        if (successMessage) {
            successMessage.style.display = 'block';
        }
    }

    $(document).on('click', '#subscriberSuccessOkBtn', function () {
        window.location.href = subscriberIndexUrl;
    });

    function refreshMultiselect(field) {
        if (typeof field.loadOptions === 'function') {
            field.loadOptions();
        }
        const widget = field.nextElementSibling;
        if (widget && typeof widget.refresh === 'function') {
            widget.refresh();
        }
    }

    function clearSavedForm() {
        if (!form || !window.localStorage) return;
        localStorage.removeItem(draftKey);
        form.reset();
        form.querySelectorAll('select').forEach(function (field) {
            if (field.multiple) {
                Array.from(field.options).forEach(function (option) {
                    option.selected = false;
                });
            }
            refreshMultiselect(field);
            field.dispatchEvent(new Event('change', { bubbles: true }));
        });
        if (subscriptionDateInput && !subscriptionDateInput.value) {
            subscriptionDateInput.value = @json(now()->format('Y-m-d'));
        }
        setExpiryDate();
    }

    function saveDraft() {
        if (!form || !window.localStorage) return;

        const draft = {};
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (!field.name || field.type === 'file' || field.name === '_token') return;

            const key = getFieldKey(field);
            if (field.type === 'checkbox' || field.type === 'radio') {
                if (!draft[key]) draft[key] = [];
                if (field.checked) draft[key].push(field.value);
                return;
            }

            if (field.tagName === 'SELECT' && field.multiple) {
                draft[key] = Array.from(field.selectedOptions).map(function (option) {
                    return option.value;
                });
                return;
            }

            draft[key] = field.value;
        });

        localStorage.setItem(draftKey, JSON.stringify(draft));
    }

    function restoreDraft() {
        if (!form || !window.localStorage) return;

        const hadSubmitPending = window.sessionStorage && sessionStorage.getItem(submitPendingKey) === '1';

        if (isFreshEntry && !hasValidationErrors) {
            clearSavedForm();
            if (window.sessionStorage) {
                sessionStorage.removeItem(submitPendingKey);
            }
            window.history.replaceState({}, document.title, window.location.pathname);
            return;
        }

        if (hasValidationErrors) {
            if (window.sessionStorage) {
                sessionStorage.removeItem(submitPendingKey);
            }
        } else if (hasServerSuccess || hadSubmitPending) {
            clearSavedForm();
            if (window.sessionStorage) {
                sessionStorage.removeItem(submitPendingKey);
            }
            showClientSuccessMessage();
            return;
        }

        const rawDraft = localStorage.getItem(draftKey);
        if (!rawDraft) return;

        let draft = {};
        try {
            draft = JSON.parse(rawDraft);
        } catch (e) {
            localStorage.removeItem(draftKey);
            return;
        }

        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (!field.name || field.type === 'file' || field.name === '_token') return;

            const key = getFieldKey(field);
            if (!Object.prototype.hasOwnProperty.call(draft, key)) return;

            if (field.type === 'checkbox' || field.type === 'radio') {
                field.checked = Array.isArray(draft[key]) && draft[key].includes(field.value);
                return;
            }

            if (field.tagName === 'SELECT' && field.multiple && Array.isArray(draft[key])) {
                Array.from(field.options).forEach(function (option) {
                    option.selected = draft[key].includes(option.value);
                });
                refreshMultiselect(field);
                field.dispatchEvent(new Event('change', { bubbles: true }));
                return;
            }

            field.value = draft[key];
            field.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    function getDropdownContainer(searchInput) {
        const $wrap = $(searchInput).closest('.multiselect-dropdown-list-wrapper, .multiselect-dropdown');
        if ($wrap.length) {
            const $list = $wrap.find('.multiselect-dropdown-list').first();
            if ($list.length) return $list;
        }
        return $('#pincode').next('.multiselect-dropdown').find('.multiselect-dropdown-list').first();
    }

    function getVisiblePincodeOptions(searchInput) {
        const $dropdown = getDropdownContainer(searchInput);
        if (!$dropdown.length) return $();
        return $dropdown.children('div').filter(function () {
            const $row = $(this);
            const isSelectAll = $row.hasClass('multiselect-dropdown-all-selector');
            const hasCheckbox = $row.find('input[type="checkbox"]').length > 0;
            const isHiddenByPluginClass = $row.hasClass('multiselect-filter-hidden');
            const isVisible = $row.is(':visible') && $row.css('display') !== 'none';
            return !isSelectAll && hasCheckbox && !isHiddenByPluginClass && isVisible;
        });
    }

    function evaluatePincodeSearch(searchInput) {
        const $input = $(searchInput);
        const value = $input.val().trim();
        const visibleOptions = getVisiblePincodeOptions(searchInput);
        const $dropdown = getDropdownContainer(searchInput);
        const hiddenByFilterCount = $dropdown.children('div.multiselect-filter-hidden').length;

        console.log(pincodeDebugPrefix, 'search event fired', {
            value: value,
            length: value.length,
            visibleOptions: visibleOptions.length,
            hiddenByFilterCount: hiddenByFilterCount,
            jqueryLoaded: typeof window.jQuery !== 'undefined'
        });

        if (value.length >= 3 && visibleOptions.length === 0) {
            setMessage(true);
        } else {
            setMessage(false);
        }
    }

    $(document).on('keyup input', '.multiselect-dropdown-search, .multiselect-search', function () {
        evaluatePincodeSearch(this);
    });

    $(document).on('click', '.multiselect-dropdown-search, .multiselect-search', function () {
        console.log(pincodeDebugPrefix, 'search input focused/clicked');
    });

    $(document).on('change', '#pincode', function () {
        console.log(pincodeDebugPrefix, '#pincode changed, hiding alert');
        setMessage(false);
    });

    function initPincodeSearchDebug() {
        console.log(pincodeDebugPrefix, 'Init started', {
            alertExists: $('#pincodeTopAlert').length > 0,
            jqueryLoaded: typeof window.jQuery !== 'undefined',
            multiselectSearchCount: $('.multiselect-dropdown-search, .multiselect-search').length,
            pluginDropdownCount: $('.multiselect-dropdown').length,
            selectExists: $('#pincode').length > 0
        });
    }

    function waitForMultiselectInit(retries) {
        if ($('.multiselect-dropdown-search, .multiselect-search').length > 0) {
            console.log(pincodeDebugPrefix, 'Multiselect initialized');
            initPincodeSearchDebug();
            return;
        }
        if (retries <= 0) {
            console.warn(pincodeDebugPrefix, 'Multiselect search input not found after retries');
            initPincodeSearchDebug();
            return;
        }
        setTimeout(function () {
            waitForMultiselectInit(retries - 1);
        }, 200);
    }

    waitForMultiselectInit(20);

    // Keep this to ensure initial hidden state.
    setMessage(false);

    $(document).on('keyup', '.multiselect-dropdown-search, .multiselect-search', function () {
        const value = $(this).val().trim();
        console.log(pincodeDebugPrefix, 'keyup length check:', value.length);
    });

    function formatAsDDMMYYYY(date) {
        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const yyyy = date.getFullYear();
        return dd + '-' + mm + '-' + yyyy;
    }

    function setExpiryDate() {
        if (!subscriptionDateInput || !expiryDateInput) return;
        if (!subscriptionDateInput.value) {
            expiryDateInput.value = '';
            return;
        }

        const date = new Date(subscriptionDateInput.value + 'T00:00:00');
        if (Number.isNaN(date.getTime())) {
            expiryDateInput.value = '';
            return;
        }

        date.setDate(date.getDate() + 28);
        expiryDateInput.value = formatAsDDMMYYYY(date);
    }

    if (subscriptionDateInput && expiryDateInput) {
        subscriptionDateInput.addEventListener('change', setExpiryDate);
        if (subscriptionDateInput.value && !expiryDateInput.value) {
            setExpiryDate();
        }
    }

    restoreDraft();

    if (form) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) return;
            if (window.sessionStorage) {
                sessionStorage.setItem(submitPendingKey, '1');
            }
        });
        form.addEventListener('input', saveDraft);
        form.addEventListener('change', saveDraft);
    }

    if (select) {
        select.addEventListener('change', function () {
            setMessage(false);
        });
    }

});

function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

</script>
@endsection
