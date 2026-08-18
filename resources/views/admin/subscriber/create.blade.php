@extends('layouts.master')
<style>

    #pincode+.multiselect-dropdown {
        width: 100% !important;
        display: block !important;
    }

    #pincode+.multiselect-dropdown .multiselect-dropdown-list-wrapper {
        left: 0 !important;
        right: 0 !important;
    }

    #pincode+.multiselect-dropdown span.optext:empty {
        display: none !important;
    }

    #pincode+.multiselect-dropdown span.placeholder {
        background-color: transparent !important;
        color: #6c757d !important;
        cursor: default !important;
        min-height: 0 !important;
        opacity: 1 !important;
        vertical-align: baseline !important;
    }

    .subscriber-submit-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        margin-right: 0.4rem;
        vertical-align: -0.15em;
        border: 0.15em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: subscriberSubmitSpin 0.75s linear infinite;
    }

    @keyframes subscriberSubmitSpin {
        to {
            transform: rotate(360deg);
        }
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

                                <input type="hidden" id="pincodeSearchApiUrl"
                                    value="{{ route('pincode.search') ?? url('pincode/search') }}">
                                <form method="post" action="{{ url('subscriberstore') }}" autocomplete="on"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div id="pincodeTopAlert" class="alert alert-warning alert-dismissible fade show"
                                        style="display:none;">
                                        📍 Can't find your pincode? WhatsApp 9069067008 with your preferred pincode. We'll
                                        check and update you.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div id="subscriberClientSuccessMessage"
                                        class="alert alert-success alert-dismissible fade show" role="alert"
                                        style="display:none;">
                                        Application Submitted Successfully<br>
                                        Your application has been received and is under review.<br>
                                        The review process typically takes 5-7 business days.<br>
                                        You will receive a WhatsApp notification if your application is approved or
                                        rejected. If additional information is required, our team may contact you through
                                        our official WhatsApp number: 9069067008.<br>
                                        If your application remains under review beyond 7 business days, you may contact us
                                        through our official WhatsApp number using your registered mobile number and Service
                                        Provider ID.<br>
                                        To ensure timely processing for all applicants, please avoid sending repeated
                                        follow-up messages during the review period.
                                        <button type="button" class="close subscriber-success-close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div id="subscriberAjaxStatus" class="alert" role="alert" style="display:none;"></div>
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
                                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                                id="location" value="{{ old('location') }}" name="location" required>
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
                                                name="password" value="{{ old('password') }}" autocomplete="new-password"
                                                required>
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
                                                id="subscriptionDate" type="{{ Auth::check() ? 'date' : 'text' }}"
                                                value="{{ Auth::check() ? old('subscriptionDate', now()->format('Y-m-d')) : now()->format('d-m-Y') }}"
                                                @if(!Auth::check()) placeholder="dd-mm-yyyy" pattern="\d{2}-\d{2}-\d{4}"
                                                @endif name="subscriptionDate" autocomplete="off" @if(!Auth::check())
                                                readonly onkeydown="return false;" @endif required>
                                            @error('subscriptionDate')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please select subscription date </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expiryDate">Expiry Date</label>
                                            <input class="form-control @error('expiryDate') is-invalid @enderror"
                                                id="expiryDate" type="text" value="{{ old('expiryDate') }}"
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
                                            @if (!Auth::check() && config('services.whatsapp.onboarding_otp_enabled'))
                                                <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                                    id="subscriberSendOtpBtn">
                                                    Send OTP
                                                </button>
                                                <span class="text-success ml-2" id="subscriberOtpVerifiedText"
                                                    style="display:none;">OTP verified</span>
                                                <div id="subscriberOtpInlineStatus" class="small mt-1"></div>
                                            @endif
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
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description"
                                                required>{{ old('description') }}</textarea>
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
                                                    <option value="{{ $pin->id }}" {{ is_array(old('pincode')) &&
                                                        in_array($pin->id, old('pincode')) ? 'selected' : '' }}>
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
                                                <label for="pincode">
                                                    Pincode
                                                    {{-- <a
                                                        href="https://postal-codes.cybo.com/india/600056_poonamallee#google_vignette"
                                                        class="btn btn-sm btn-outline-info ml-1 py-0 px-2" target="_blank"
                                                        rel="noopener noreferrer"
                                                        title="Note: This is a third-party website. Please refer to their terms, conditions and privacy policy before use."
                                                        aria-label="Open pincode information. Note: This is a third-party website. Please refer to their terms, conditions and privacy policy before use.">i</a>
                                                    --}}
                                                </label>
                                                <select name="pincode[]"
                                                    class="form-control @error('pincode') is-invalid @enderror" id="pincode"
                                                    multiple placeholder="Select up to 5 Pincodes" multiselect-search="true"
                                                    multiselect-select-all="true" multiselect-lazy="true"
                                                    multiselect-chunk-size="250">

                                                    @foreach ($pincode as $pin)
                                                        <option value="{{ $pin->id }}" {{ is_array(old('pincode')) && in_array($pin->id, old('pincode')) ? 'selected' : '' }}>
                                                            {{ $pin->pincode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">
                                                    Note: To check nearby pincodes, please click on
                                                    <a href="https://postal-codes.cybo.com/india" target="_blank"
                                                        title="Check Nearby Pincodes">
                                                        <i class="fas fa-info-circle text-primary"></i>
                                                    </a>
                                                </small>

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
                                                        <h5 class="modal-title" id="pincodeNotListedModalLabel">Pincode Not
                                                            Found</h5>
                                                        <button type="button" class="close pincode-modal-close"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        📍 Can't find your pincode? WhatsApp 9069067008 with your preferred
                                                        pincode. We'll check and update you.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary pincode-modal-close"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary pincode-modal-close"
                                                            id="pincodeModalOkBtn" data-dismiss="modal">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <label for="aadharNo">Aadhar Number</label>
                                            <input type="text" class="form-control @error('aadharNo') is-invalid @enderror"
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
                                            <input type="text" class="form-control @error('bankacno') is-invalid @enderror"
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
                                            <input type="text" class="form-control @error('ifsccode') is-invalid @enderror"
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
                                                id="account_type" value="{{ old('account_type') }}" name="account_type"
                                                required> --}}
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
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                id="image" value="{{ old('image') }}"
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
                                            <input type="file" class="form-control @error('video') is-invalid @enderror"
                                                id="video" value="{{ old('video') }}" accept=".mp4" name="video">
                                            <small id="video" class="form-text text-muted">
                                                Note: Uploading video verification now may speed up approval. Ensure your
                                                face and ID proof are clearly visible and say, "This ID belongs to me." If
                                                not uploaded, our team may request it through our official WhatsApp number
                                                (9069067008) to complete your onboarding process. Please do not share
                                                documents with any other number claiming to represent DO N KEY.
                                            </small>
                                            @error('video')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Please upload Video </div>
                                        </div>
                                        @auth
                                            <div class="col-md-6 mb-3">
                                                <label for="customerdocument">Document</label>
                                                <input type="file"
                                                    class="form-control @error('customerdocument') is-invalid @enderror"
                                                    id="customerdocument" value="{{ old('customerdocument') }}" accept=".pdf"
                                                    name="customerdocument">
                                                <small id="customerdocument" class="form-text text-muted">Note: Reserved for
                                                    official use by DO N KEY for agreements and related records. </small>
                                                @error('customerdocument')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="invalid-feedback"> Please upload bike image </div>
                                            </div>
                                        @endauth


                                        <div class="col-12 mb-4">
                                            <label class="font-weight-bold">Price Setup Guideline</label><br>
                                            <small class="d-block text-muted mt-2">
                                                ✓ Enter fair and competitive service fares.<br>
                                                ✓ Prices can be updated at any time.<br>
                                                ✓ All pricing and price modifications are monitored by the DO N KEY team.

                                            </small>

                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#sizeChartModal">
                                                <i class="fas fa-ruler-combined mr-2"></i> View Price Guidelines
                                            </button>


                                        </div>

                                        <!-- Size Chart Modal -->
                                        <div class="modal fade" id="sizeChartModal" tabindex="-1"
                                            aria-labelledby="sizeChartModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="sizeChartModalLabel">Select Price Chart
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('public/admin/assets/images/pricechart.jpeg') }}"
                                                            alt="Size Chart" class="img-fluid rounded"
                                                            style="max-height:600px;">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-lg-4 mb-4">
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
                                        </div> --}}
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <label for="biketaxi_price">Bike Taxi Service Fare (Per KM)</label>
                                            <input type="text"
                                                class="form-control @error('biketaxi_price') is-invalid @enderror"
                                                id="biketaxi_price" name="biketaxi_price"
                                                value="{{ old('biketaxi_price') }}" placeholder="₹20 - ₹30 Suggested : ₹25"
                                                required>

                                            @error('biketaxi_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            <div class="invalid-feedback">
                                                Please enter the Bike Taxi fare per KM.
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <label for="pickup_price">Pick up and Drop Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('pickup_price') is-invalid @enderror "
                                                id="pickup_price" name="pickup_price" value="{{ old('pickup_price') }}"
                                                >
                                            @error('pickup_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter pickup km price</div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <label for="buy_price">Buy and Delivery km price</label>
                                            <input type="text"
                                                class="form-control @error('buy_price') is-invalid @enderror "
                                                id="buy_price" name="buy_price" value="{{ old('buy_price') }}" placeholder="₹35 - ₹50 Suggested : ₹40">
                                            @error('buy_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter buy and delivery km price</div>
                                        </div>
                                        {{-- <div class="col-md-2 mb-2 d-none"></div> --}}
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <label for="auto_price">Auto Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('auto_price') is-invalid @enderror "
                                                name="auto_price" value="{{ old('auto_price') }}" placeholder="₹60 - 80 Suggested : ₹70">
                                            @error('auto_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter auto km price</div>
                                        </div>

                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <label for="cab_price">Cab Service Fare</label>
                                            <input type="text"
                                                class="form-control @error('cab_price') is-invalid @enderror "
                                                name="cab_price" value="{{ old('cab_price') }}" placeholder="₹60 - 80 Suggested : ₹70" required>
                                            @error('cab_price')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback"> Please enter cab km price</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Bike Taxi Fare</strong></p><br>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="bt_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('bt_price1') is-invalid @enderror "
                                                        id="bt_price1" name="bt_price1" value="{{ old('bt_price1') }}" placeholder="₹4 / km Suggested : ₹5"
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
                                                        id="bt_price2" name="bt_price2" value="{{ old('bt_price2') }}" placeholder="₹6 / km Suggested : ₹7"
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
                                                        id="bt_price3" name="bt_price3" value="{{ old('bt_price3') }}" placeholder="₹8 / km Suggested : ₹9"
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
                                                        id="bt_price4" name="bt_price4" value="{{ old('bt_price4') }}" placeholder="₹10 / km Suggested : ₹11"
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
                                                        id="bd_price1" name="bd_price1" value="{{ old('bd_price1') }}" placeholder="₹5 / km Suggested : ₹6"
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
                                                        id="bd_price2" name="bd_price2" value="{{ old('bd_price2') }}" placeholder="₹7 / km Suggested : ₹8"
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
                                                        id="bd_price3" name="bd_price3" value="{{ old('bd_price3') }}" placeholder="₹9 / km Suggested : ₹10"
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
                                                        id="bd_price4" name="bd_price4" value="{{ old('bd_price4') }}" placeholder="₹11 / km Suggested : ₹12"
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
                                            <p class="mb-2"><strong>Auto Fare</strong></p>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="bd_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('at_price1') is-invalid @enderror "
                                                        name="at_price1" value="{{ old('at_price1') }}" placeholder="₹10 / km Suggested : ₹12" required>
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
                                                        id="at_price2" name="at_price2" value="{{ old('at_price2') }}" placeholder="₹12 / km Suggested : ₹14"
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
                                                        id="at_price3" name="at_price3" value="{{ old('at_price3') }}" placeholder="₹14 / km Suggested : ₹16"
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
                                                        id="at_price4" name="at_price4" value="{{ old('at_price4') }}" placeholder="₹16 / km Suggested : ₹18"
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
                                            <p class="mb-2"><strong>Cab Fare</strong></p>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="cab_price1">1 to 5 km</label>
                                                    <input type="text"
                                                        class="form-control @error('cab_price1') is-invalid @enderror "
                                                        name="cab_price1" value="{{ old('cab_price1') }}" placeholder="₹14 / km Suggested : ₹15" required>
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
                                                        id="cab_price2" name="cab_price2" value="{{ old('cab_price2') }}" placeholder="₹16 / km Suggested : ₹18"
                                                        required>
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
                                                        id="cab_price3" name="cab_price3" value="{{ old('cab_price3') }}" placeholder="₹18 / km Suggested : ₹20"
                                                        required>
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
                                                        id="cab_price4" name="cab_price4" value="{{ old('cab_price4') }}" placeholder="₹20 / km Suggested : ₹22"
                                                        required>
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




                                    <center>
                                        <button class="btn btn-primary mt-2" type="submit" id="subscriberSubmitBtn">
                                            <span class="subscriber-submit-spinner" id="subscriberSubmitSpinner"
                                                style="display:none;" aria-hidden="true"></span>
                                            <span id="subscriberSubmitText">Submit form</span>
                                        </button>
                                    </center>
                                </form>
                                <div class="modal fade" id="subscriberOtpModal" tabindex="-1" role="dialog"
                                    aria-labelledby="subscriberOtpModalLabel" aria-hidden="true" data-backdrop="static"
                                    data-keyboard="false">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="subscriberOtpModalLabel">
                                                    Verify WhatsApp Number
                                                </h5>
                                                <button type="button" class="close subscriber-otp-close"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="subscriberOtpStatus" class="alert" style="display:none;"></div>
                                                <p class="mb-2">
                                                    Enter the 6-digit verification code sent to
                                                    <strong id="subscriberOtpMaskedPhone"></strong>.
                                                </p>
                                                <p class="text-muted">The code is valid for 60 seconds.</p>
                                                <div class="form-group mb-0">
                                                    <label for="subscriberOtpInput">Verification code</label>
                                                    <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                                        pattern="[0-9]{6}" maxlength="6" class="form-control"
                                                        id="subscriberOtpInput">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" id="subscriberOtpResendBtn">
                                                    Resend OTP
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="subscriberOtpCancelBtn">
                                                    Cancel
                                                </button>
                                                <button type="button" class="btn btn-primary" id="subscriberOtpVerifyBtn">
                                                    Verify OTP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="subscriberSuccessModal" tabindex="-1" role="dialog"
                                    aria-labelledby="subscriberSuccessModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-success" id="subscriberSuccessModalLabel">
                                                    Application Submitted Successfully
                                                </h5>
                                                <button type="button" class="close subscriber-success-close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Your application has been received and is under review.</p>
                                                <p>The review process typically takes 5&ndash;7 business days.</p>
                                                <p>You will receive a WhatsApp notification if your application is approved
                                                    or rejected. If additional information is required, our team may contact
                                                    you through our official WhatsApp number: 9069067008.</p>
                                                <p>If your application remains under review beyond 7 business days, you may
                                                    contact us through our official WhatsApp number using your registered
                                                    mobile number and Service Provider ID.</p>
                                                <p class="mb-0">To ensure timely processing for all applicants, please avoid
                                                    sending repeated follow-up messages during the review period.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success"
                                                    id="subscriberSuccessOkBtn">OK</button>
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
            const pincodeSearchApiUrl = document.getElementById('pincodeSearchApiUrl')?.value;
            const subscriptionDateInput = document.getElementById('subscriptionDate');
            const expiryDateInput = document.getElementById('expiryDate');
            const pincodeDebugPrefix = '[Pincode Debug]';
            const draftKey = 'subscriber_onboarding_draft:' + window.location.pathname;
            const submitPendingKey = draftKey + ':submitted';
            const otpVerifiedKey = draftKey + ':otp_verified';
            const fileDraftDbName = 'subscriber_onboarding_file_drafts';
            const fileDraftStoreName = 'file_drafts';
            const fileDraftKey = draftKey + ':files';
            const currentSubscriptionDate = @json(now()->format('d-m-Y'));
            const isPublicSubscriberForm = @json(!Auth::check());
            const requiresWhatsAppOtp = @json(!Auth::check() && config('services.whatsapp.onboarding_otp_enabled'));
            const sendOtpUrl = @json(route('subscriber.onboarding.otp.send'));
            const verifyOtpUrl = @json(route('subscriber.onboarding.otp.verify'));
            const hasValidationErrors = @json($errors->any());
            const hasServerSuccess = @json(session()->has('success') || session()->has('show_success_modal'));
            const subscriberIndexUrl = @json(route('subscriber'));
            const createSubscriberUrl = @json(route('createSubscriber'));
            const navEntry = performance.getEntriesByType('navigation')[0];
            const isReload = navEntry && navEntry.type === 'reload';
            const isFreshEntry = new URLSearchParams(window.location.search).get('fresh') === '1'
                || (!isReload && document.referrer.indexOf('/subscriberList') !== -1);
            let pincodeModalShown = false;
            let pincodeSearchRequest = 0;
            let otpVerifiedForSubmit = false;
            let otpRequestInProgress = false;
            let verifiedOtpMobile = '';
            let otpSentMobile = '';
            let otpExpiresAt = 0;
            let otpMaskedPhone = '';
            let allowPageLeave = false;
            let restoringFileDraft = false;
            let submitInProgress = false;

            function setMessage(show) {
                $('#pincodeTopAlert').toggle(!!show);
                if (show) {
                } else {
                    pincodeModalShown = false;
                }
            }

            function closePincodeModal() {
                pincodeModalShown = false;
                const $modal = $('#pincodeNotListedModal');

                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    $modal.modal('hide');
                }

                $modal.removeClass('show').hide().attr('aria-hidden', 'true');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            }

            $(document).on('click', '.pincode-modal-close', function () {
                closePincodeModal();
            });

            $('#pincodeNotListedModal').on('hidden.bs.modal', function () {
                pincodeModalShown = false;
            });

            function getFieldKey(field) {
                return field.name || field.id;
            }

            function openFileDraftDb() {
                return new Promise(function (resolve, reject) {
                    if (!window.indexedDB) {
                        reject(new Error('IndexedDB is not available.'));
                        return;
                    }

                    const request = indexedDB.open(fileDraftDbName, 1);
                    request.onupgradeneeded = function () {
                        request.result.createObjectStore(fileDraftStoreName);
                    };
                    request.onsuccess = function () {
                        resolve(request.result);
                    };
                    request.onerror = function () {
                        reject(request.error || new Error('Unable to open file draft storage.'));
                    };
                });
            }

            async function writeFileDraft(files) {
                const db = await openFileDraftDb();
                return new Promise(function (resolve, reject) {
                    const transaction = db.transaction(fileDraftStoreName, 'readwrite');
                    transaction.objectStore(fileDraftStoreName).put(files, fileDraftKey);
                    transaction.oncomplete = function () {
                        db.close();
                        resolve();
                    };
                    transaction.onerror = function () {
                        db.close();
                        reject(transaction.error || new Error('Unable to save file draft.'));
                    };
                });
            }

            async function readFileDraft() {
                const db = await openFileDraftDb();
                return new Promise(function (resolve, reject) {
                    const transaction = db.transaction(fileDraftStoreName, 'readonly');
                    const request = transaction.objectStore(fileDraftStoreName).get(fileDraftKey);
                    request.onsuccess = function () {
                        resolve(request.result || {});
                    };
                    request.onerror = function () {
                        reject(request.error || new Error('Unable to read file draft.'));
                    };
                    transaction.oncomplete = function () {
                        db.close();
                    };
                });
            }

            async function removeFileDraft() {
                try {
                    const db = await openFileDraftDb();
                    await new Promise(function (resolve, reject) {
                        const transaction = db.transaction(fileDraftStoreName, 'readwrite');
                        transaction.objectStore(fileDraftStoreName).delete(fileDraftKey);
                        transaction.oncomplete = function () {
                            db.close();
                            resolve();
                        };
                        transaction.onerror = function () {
                            db.close();
                            reject(transaction.error || new Error('Unable to clear file draft.'));
                        };
                    });
                } catch (error) {
                    // Missing IndexedDB support should not block the form.
                }
            }

            function getFileFields() {
                return form ? Array.from(form.querySelectorAll('input[type="file"][name]')) : [];
            }

            async function saveFileDraft() {
                if (!form || !window.indexedDB || restoringFileDraft) return;

                const files = {};
                getFileFields().forEach(function (field) {
                    files[getFieldKey(field)] = Array.from(field.files || []);
                });

                try {
                    await writeFileDraft(files);
                } catch (error) {
                    // Large files can exceed browser storage quota. Keep normal form behavior.
                }
            }

            async function restoreFileDraft() {
                if (!form || !window.indexedDB || typeof DataTransfer === 'undefined') return;

                let files = {};
                try {
                    files = await readFileDraft();
                } catch (error) {
                    return;
                }

                restoringFileDraft = true;
                try {
                    getFileFields().forEach(function (field) {
                        const savedFiles = files[getFieldKey(field)];
                        if (!Array.isArray(savedFiles) || savedFiles.length === 0) return;

                        const transfer = new DataTransfer();
                        savedFiles.forEach(function (file) {
                            transfer.items.add(file);
                        });

                        field.files = transfer.files;
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                } finally {
                    restoringFileDraft = false;
                }
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

            function closeSubscriberSuccessMessage() {
                const modal = document.getElementById('subscriberSuccessModal');
                const successMessage = document.getElementById('subscriberClientSuccessMessage');

                if (modal && window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    $('#subscriberSuccessModal').modal('hide');
                }

                if (modal && window.bootstrap && window.bootstrap.Modal) {
                    const instance = window.bootstrap.Modal.getInstance(modal);
                    if (instance) instance.hide();
                }

                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                }
                if (successMessage) successMessage.style.display = 'none';

                document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
            }

            $(document).on('click', '.subscriber-success-close', function () {
                closeSubscriberSuccessMessage();
            });

            $(document).on('click', '#subscriberSuccessOkBtn', function () {
                closeSubscriberSuccessMessage();
                allowPageLeave = true;
                window.location.replace(
                    isPublicSubscriberForm ? createSubscriberUrl + '?fresh=1' : subscriberIndexUrl
                );
            });

            function csrfToken() {
                return form ? form.querySelector('input[name="_token"]')?.value : '';
            }

            function setAjaxStatus(message, type) {
                const status = document.getElementById('subscriberAjaxStatus');
                if (!status) return;

                status.className = 'alert alert-' + (type || 'info');
                status.innerHTML = message || '';
                status.style.display = message ? 'block' : 'none';
                if (message && type === 'danger') {
                    status.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function setSubmitLoading(isLoading) {
                submitInProgress = isLoading;
                const submitButton = document.getElementById('subscriberSubmitBtn');
                const submitSpinner = document.getElementById('subscriberSubmitSpinner');
                const submitText = document.getElementById('subscriberSubmitText');

                if (submitButton) submitButton.disabled = isLoading;
                if (submitSpinner) submitSpinner.style.display = isLoading ? 'inline-block' : 'none';
                if (submitText) submitText.textContent = isLoading ? 'Submitting...' : 'Submit form';
            }

            function clearAjaxValidationErrors() {
                if (!form) return;
                form.querySelectorAll('.is-invalid').forEach(function (field) {
                    field.classList.remove('is-invalid');
                });
                form.querySelectorAll('.ajax-invalid-feedback').forEach(function (node) {
                    node.remove();
                });
            }

            function findFieldForError(key) {
                if (!form || !key) return null;
                const candidates = [key];
                const arrayKey = key.replace(/\.\d+$/, '[]');
                if (arrayKey !== key) candidates.push(arrayKey);
                candidates.push(key.split('.')[0]);
                candidates.push(key.split('.')[0] + '[]');

                for (const candidate of candidates) {
                    const escaped = window.CSS && CSS.escape ? CSS.escape(candidate) : candidate.replace(/"/g, '\\"');
                    const field = form.querySelector('[name="' + escaped + '"]');
                    if (field) return field;
                }

                return null;
            }

            function renderAjaxValidationErrors(errors) {
                clearAjaxValidationErrors();
                const messages = [];
                Object.keys(errors || {}).forEach(function (key) {
                    const errorMessages = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
                    const message = errorMessages[0] || 'Invalid value.';
                    messages.push(message);

                    const field = findFieldForError(key);
                    if (!field) return;
                    field.classList.add('is-invalid');

                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback ajax-invalid-feedback';
                    feedback.textContent = message;
                    if (field.parentElement) {
                        field.parentElement.appendChild(feedback);
                    }
                });

                setAjaxStatus(messages.length ? messages.join('<br>') : 'Please check the form details.', 'danger');
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid && typeof firstInvalid.focus === 'function') {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus({ preventScroll: true });
                }
            }

            async function submitSubscriberFormAjax() {
                if (!form || submitInProgress) return;

                clearAjaxValidationErrors();
                setAjaxStatus('', 'info');
                setSubmitLoading(true);

                try {
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken(),
                        },
                        body: new FormData(form),
                    });

                    let result = {};
                    try {
                        result = await response.json();
                    } catch (error) {
                        result = {};
                    }

                    if (response.status === 422 && result.errors) {
                        renderAjaxValidationErrors(result.errors);
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(result.message || 'Unable to submit the application. Please try again.');
                    }

                    setAjaxStatus('', 'success');
                    clearSavedForm();
                    if (window.sessionStorage) {
                        sessionStorage.removeItem(submitPendingKey);
                        sessionStorage.removeItem(otpVerifiedKey);
                    }
                    setOtpVerifiedState(false, '');
                    setInlineOtpStatus('', 'success');
                    showClientSuccessMessage();
                    removeFileDraft().catch(function () {});
                } catch (error) {
                    if (window.sessionStorage) {
                        sessionStorage.removeItem(submitPendingKey);
                    }
                    setAjaxStatus(error.message || 'Unable to submit the application. Please try again.', 'danger');
                } finally {
                    setSubmitLoading(false);
                }
            }

            function setOtpStatus(message, type) {
                const status = document.getElementById('subscriberOtpStatus');
                if (!status) return;

                status.textContent = message || '';
                status.className = 'alert alert-' + (type || 'info');
                status.style.display = message ? 'block' : 'none';
            }

            function setInlineOtpStatus(message, type) {
                const status = document.getElementById('subscriberOtpInlineStatus');
                if (!status) return;

                status.textContent = message || '';
                status.className = 'small mt-1 text-' + (type || 'muted');
            }

            function setOtpButtons(disabled) {
                ['subscriberOtpResendBtn', 'subscriberOtpCancelBtn', 'subscriberOtpVerifyBtn'].forEach(function (id) {
                    const button = document.getElementById(id);
                    if (button) button.disabled = disabled;
                });

                const sendButton = document.getElementById('subscriberSendOtpBtn');
                if (sendButton) sendButton.disabled = disabled;
            }

            function setOtpVerifiedState(isVerified, mobile) {
                otpVerifiedForSubmit = isVerified;
                verifiedOtpMobile = isVerified ? (mobile || '') : '';

                const sendButton = document.getElementById('subscriberSendOtpBtn');
                const verifiedText = document.getElementById('subscriberOtpVerifiedText');

                if (sendButton) {
                    sendButton.textContent = 'Send OTP';
                    sendButton.style.display = isVerified ? 'none' : 'inline-block';
                    sendButton.classList.remove('btn-outline-success');
                    sendButton.classList.add('btn-outline-primary');
                }

                if (verifiedText) {
                    verifiedText.style.display = isVerified ? 'inline' : 'none';
                }

                if (window.sessionStorage) {
                    if (isVerified) {
                        sessionStorage.setItem(otpVerifiedKey, JSON.stringify({
                            mobile: verifiedOtpMobile,
                            verified_at: Date.now()
                        }));
                    } else {
                        sessionStorage.removeItem(otpVerifiedKey);
                    }
                }
            }

            function showOtpModal(maskedPhone) {
                const maskedPhoneNode = document.getElementById('subscriberOtpMaskedPhone');
                const input = document.getElementById('subscriberOtpInput');

                if (maskedPhoneNode) maskedPhoneNode.textContent = maskedPhone || '';
                if (input) input.value = '';
                setOtpStatus('', 'info');

                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    $('#subscriberOtpModal').modal('show');
                    setTimeout(function () {
                        document.getElementById('subscriberOtpInput')?.focus();
                    }, 300);
                    return;
                }

                const modal = document.getElementById('subscriberOtpModal');
                if (modal) {
                    modal.style.display = 'block';
                    modal.classList.add('show');
                    modal.removeAttribute('aria-hidden');
                }
            }

            function hideOtpModal() {
                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    $('#subscriberOtpModal').modal('hide');
                }

                const modal = document.getElementById('subscriberOtpModal');
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                }

                document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
            }

            async function postOtp(url, payload) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                    },
                    body: payload,
                });

                let result = {};
                try {
                    result = await response.json();
                } catch (error) {
                    result = {};
                }

                if (!response.ok) {
                    const message = result.message || 'Unable to process OTP request. Please try again.';
                    throw new Error(message);
                }

                return result;
            }

            async function sendOtpForCurrentMobile(showModalAfterSend, isResend) {
                if (!form || otpRequestInProgress) return;
                const mobile = form.querySelector('[name="mobile"]')?.value || '';

                if (!mobile.trim()) {
                    setOtpStatus('Please enter mobile number before requesting OTP.', 'danger');
                    setInlineOtpStatus('Please enter mobile number before requesting OTP.', 'danger');
                    return;
                }

                if (!isResend && otpSentMobile === mobile && otpExpiresAt && Date.now() < otpExpiresAt) {
                    const message = 'Verification code already sent to your WhatsApp number.';
                    if (showModalAfterSend) {
                        showOtpModal(otpMaskedPhone || mobile);
                    }
                    setOtpStatus(message, 'success');
                    setInlineOtpStatus(message, 'success');
                    return;
                }

                const payload = new FormData();
                payload.append('mobile', mobile);
                if (isResend) {
                    payload.append('resend', '1');
                }

                otpRequestInProgress = true;
                setOtpButtons(true);
                setOtpStatus(isResend ? 'Resending OTP...' : 'Sending OTP...', 'info');
                setInlineOtpStatus(isResend ? 'Resending OTP...' : 'Sending OTP...', 'muted');

                try {
                    const result = await postOtp(sendOtpUrl, payload);
                    setOtpVerifiedState(false, '');
                    otpSentMobile = mobile;
                    otpExpiresAt = Date.now() + (Number(result.expires_in || 60) * 1000);
                    otpMaskedPhone = result.masked_phone || mobile;
                    if (showModalAfterSend) {
                        showOtpModal(otpMaskedPhone);
                    }
                    setOtpStatus(result.message || 'Verification code sent.', 'success');
                    setInlineOtpStatus(result.message || 'Verification code sent.', 'success');
                } catch (error) {
                    if (showModalAfterSend) {
                        showOtpModal(mobile);
                    }
                    setOtpStatus(error.message, 'danger');
                    setInlineOtpStatus(error.message, 'danger');
                } finally {
                    otpRequestInProgress = false;
                    setOtpButtons(false);
                }
            }

            async function verifyCurrentOtp() {
                if (!form || otpRequestInProgress) return;

                const otpInput = document.getElementById('subscriberOtpInput');
                const otp = otpInput ? otpInput.value.trim() : '';
                if (!/^\d{6}$/.test(otp)) {
                    setOtpStatus('Please enter the 6-digit OTP.', 'danger');
                    return;
                }

                const payload = new FormData();
                payload.append('mobile', form.querySelector('[name="mobile"]')?.value || '');
                payload.append('otp', otp);

                otpRequestInProgress = true;
                setOtpButtons(true);
                setOtpStatus('Verifying OTP...', 'info');

                try {
                    await postOtp(verifyOtpUrl, payload);
                    const currentMobile = form.querySelector('[name="mobile"]')?.value || '';
                    setOtpVerifiedState(true, currentMobile);
                    setInlineOtpStatus('OTP verified successfully.', 'success');
                    hideOtpModal();
                } catch (error) {
                    setOtpStatus(error.message, 'danger');
                } finally {
                    otpRequestInProgress = false;
                    setOtpButtons(false);
                }
            }

            $(document).on('click', '#subscriberSendOtpBtn', function () {
                sendOtpForCurrentMobile(true);
            });
            $(document).on('click', '#subscriberOtpVerifyBtn', verifyCurrentOtp);
            $(document).on('click', '#subscriberOtpResendBtn', function () {
                sendOtpForCurrentMobile(false, true);
            });
            $(document).on('click', '#subscriberOtpCancelBtn', function () {
                hideOtpModal();
            });
            $(document).on('click', '.subscriber-otp-close', function () {
                hideOtpModal();
            });
            $(document).on('input', '#subscriberOtpInput', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
            $(document).on('keyup', '#subscriberOtpInput', function (event) {
                if (event.key === 'Enter') {
                    verifyCurrentOtp();
                }
            });

            function refreshMultiselect(field) {
                if (typeof field.loadOptions === 'function') {
                    field.loadOptions();
                }
                const widget = field.nextElementSibling;
                if (widget && typeof widget.refresh === 'function') {
                    widget.refresh();
                }
                if (field.id === 'pincode') {
                    cleanupBlankPincodeChips(field);
                }
            }

            function getChipLabel(chip) {
                return Array.from(chip.childNodes)
                    .filter(function (node) {
                        return node.nodeType === Node.TEXT_NODE;
                    })
                    .map(function (node) {
                        return node.textContent.replace(/\u00a0/g, '').trim();
                    })
                    .join('');
            }

            function cleanupBlankPincodeChips(field) {
                const widget = field.nextElementSibling;
                if (!widget) return;

                let deselectedBlankOption = false;
                widget.querySelectorAll('span.optext:not(.maxselected)').forEach(function (chip) {
                    if (getChipLabel(chip) !== '') return;

                    if (chip.srcOption) {
                        chip.srcOption.selected = false;
                        deselectedBlankOption = true;
                    }
                    chip.remove();
                });

                if (deselectedBlankOption && typeof widget.refresh === 'function') {
                    widget.refresh();
                }
            }

            function clearSavedForm() {
                if (!form || !window.localStorage) return;
                localStorage.removeItem(draftKey);
                removeFileDraft();
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
                if (isPublicSubscriberForm && subscriptionDateInput && !subscriptionDateInput.value) {
                    subscriptionDateInput.value = currentSubscriptionDate;
                }
                setExpiryDate();
            }

            function saveDraft() {
                if (!form || !window.localStorage) return;

                const draft = {};
                form.querySelectorAll('input, select, textarea').forEach(function (field) {
                    if (!field.name || field.type === 'file' || field.name === '_token') return;
                    if (isPublicSubscriberForm && field.id === 'subscriptionDate') {
                        field.value = currentSubscriptionDate;
                        return;
                    }

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
                        sessionStorage.removeItem(otpVerifiedKey);
                    }
                    window.history.replaceState({}, document.title, window.location.pathname);
                    return;
                }

                if (hasValidationErrors) {
                    if (window.sessionStorage) {
                        sessionStorage.removeItem(submitPendingKey);
                    }
                } else if (hasServerSuccess && !isReload) {
                    clearSavedForm();
                    if (window.sessionStorage) {
                        sessionStorage.removeItem(submitPendingKey);
                        sessionStorage.removeItem(otpVerifiedKey);
                    }
                    showClientSuccessMessage();
                    return;
                } else if (hadSubmitPending && window.sessionStorage) {
                    sessionStorage.removeItem(submitPendingKey);
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

            function restoreOtpVerifiedState() {
                if (!form || !window.sessionStorage) return;

                const rawVerified = sessionStorage.getItem(otpVerifiedKey);
                if (!rawVerified) return;

                let verified = {};
                try {
                    verified = JSON.parse(rawVerified);
                } catch (error) {
                    sessionStorage.removeItem(otpVerifiedKey);
                    return;
                }

                const mobile = form.querySelector('[name="mobile"]')?.value || '';
                const isRecent = verified.verified_at && (Date.now() - Number(verified.verified_at)) < 10 * 60 * 1000;

                if (verified.mobile && verified.mobile === mobile && isRecent) {
                    setOtpVerifiedState(true, mobile);
                    setInlineOtpStatus('OTP verified successfully.', 'success');
                    return;
                }

                sessionStorage.removeItem(otpVerifiedKey);
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

            async function evaluatePincodeSearch(searchInput) {
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

                if (value.length < 3 || visibleOptions.length > 0) {
                    setMessage(false);
                    return;
                }

                // No available option is visible. Check the complete pincode master
                // before showing "not found", because an existing pincode may be
                // hidden when it belongs to another active subscriber.
                const requestId = ++pincodeSearchRequest;
                try {
                    const response = await fetch(
                        pincodeSearchApiUrl + '?q=' + encodeURIComponent(value),
                        { headers: { 'Accept': 'application/json' } }
                    );
                    if (!response.ok) throw new Error('Pincode lookup failed');

                    const result = await response.json();
                    if (requestId !== pincodeSearchRequest || $input.val().trim() !== value) return;

                    setMessage(result.exists_in_master !== true);
                } catch (error) {
                    // Never show a false "not found" popup when lookup is unavailable.
                    if (requestId === pincodeSearchRequest) setMessage(false);
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
                    if (select) {
                        cleanupBlankPincodeChips(select);
                    }
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

            function parseSubscriptionDate(value) {
                const parts = value.split('-');
                if (parts.length !== 3) return null;

                let day;
                let month;
                let year;

                if (parts[0].length === 4) {
                    year = Number(parts[0]);
                    month = Number(parts[1]);
                    day = Number(parts[2]);
                } else {
                    day = Number(parts[0]);
                    month = Number(parts[1]);
                    year = Number(parts[2]);
                }

                const date = new Date(year, month - 1, day);
                if (
                    Number.isNaN(date.getTime()) ||
                    date.getFullYear() !== year ||
                    date.getMonth() !== month - 1 ||
                    date.getDate() !== day
                ) {
                    return null;
                }

                return date;
            }

            function setExpiryDate() {
                if (!subscriptionDateInput || !expiryDateInput) return;
                if (!subscriptionDateInput.value) {
                    expiryDateInput.value = '';
                    return;
                }

                const date = parseSubscriptionDate(subscriptionDateInput.value);
                if (!date) {
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
            restoreFileDraft();
            restoreOtpVerifiedState();
            if (select) {
                cleanupBlankPincodeChips(select);
            }
            if (isPublicSubscriberForm && subscriptionDateInput) {
                subscriptionDateInput.value = currentSubscriptionDate;
                setExpiryDate();
            }

            if (form) {
                const mobileInput = form.querySelector('[name="mobile"]');
                if (mobileInput) {
                    mobileInput.addEventListener('input', function () {
                        if (otpVerifiedForSubmit && this.value !== verifiedOtpMobile) {
                            setOtpVerifiedState(false, '');
                            setInlineOtpStatus('Mobile number changed. Please send OTP again.', 'warning');
                        }
                        if (this.value !== otpSentMobile) {
                            otpSentMobile = '';
                            otpExpiresAt = 0;
                            otpMaskedPhone = '';
                        }
                    });
                }

                form.addEventListener('submit', function (event) {
                    if (window.sessionStorage) {
                        sessionStorage.removeItem(submitPendingKey);
                    }
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        form.reportValidity();
                        return;
                    }
                    if (requiresWhatsAppOtp && !otpVerifiedForSubmit) {
                        event.preventDefault();
                        const mobile = form.querySelector('[name="mobile"]')?.value || '';
                        if (otpSentMobile !== mobile) {
                            const message = 'OTP verification is required. Sending OTP to your WhatsApp number.';
                            setInlineOtpStatus(message, 'danger');
                            showOtpModal(mobile);
                            setOtpStatus(message, 'info');
                            sendOtpForCurrentMobile(true, false);
                            return;
                        }

                        const message = 'Please enter and verify the OTP before submitting the form.';
                        setInlineOtpStatus(message, 'danger');
                        showOtpModal(mobile);
                        setOtpStatus(message, 'danger');
                        return;
                    }
                    allowPageLeave = true;
                    if (isPublicSubscriberForm) {
                        event.preventDefault();
                        submitSubscriberFormAjax();
                    } else {
                        setSubmitLoading(true);
                    }
                });
                form.addEventListener('input', saveDraft);
                form.addEventListener('change', saveDraft);
                getFileFields().forEach(function (field) {
                    field.addEventListener('change', saveFileDraft);
                });
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
