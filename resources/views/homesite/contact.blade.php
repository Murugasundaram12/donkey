@extends('layouts/homemaster')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        .owl-nav{
            display:  none !important;
        }
        .owl-prev,
        .owl-next {
            display: none;
        }
@media only screen and (max-width:320px){
.page-title .content-box{
padding:0px !important;
}
}
        .sec1 {
            opacity: 0;
            filter: blur(5px);
            transition: all 1s;
            transform: translateX(-100%);
        }

        .show {
            opacity: 1;
            filter: blur(0);
            transform: translateX(0);

        }

        /* Style for success message */
        .alert-success {
            background-color: white;
            /* Green color */
            color: #4CAF50;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .picpic {
                background-size: 100% 200px !important;
                margin-top: -119px !important;
                margin-bottom: -219px !important;
                background-color: #f6f7fd !important;
                padding:0px !important;
            }

            .abtus {
                font-size: 20px !important;
                display:none !important;
            }

            .cel {
                font-size: 14px !important;
                margin-left: -30px !important;
            }

            .cads {
                font-size: 15px !important;
            }
            .conco{
            display:none !important;
            }

            .japbp {
                font-size: 24px !important;
            }

            .cont {
                color: white !important;
                font-weight:800 !important;
                display: inline-flex !important;
                width: 250px !important;
                margin-left: -40px !important;
            }
            .icbx{
                margin-top: -1px !important;
            }
            .inner-container{
            margin-right: 0px !important;
        }
        .oxh{
            overflow-x: hidden !important;
        }
        }
    </style>
    <!-- Page Title -->
    <div class="oxh">
    <section class="page-title picpic" style="background-image: asset('assets/images/contactbaner.jpeg');">
        <script>
            const ele = document.querySelectorAll('.sec1');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    } else {
                        entry.target.classList.remove('show');

                    }
                });
            });

            ele.forEach((el) => observer.observe(el));
        </script>
        <div class="background-text">
            <div data-parallax='{"x": 100}'>
                <div class="text-1"></div>
                <div class="text-2"></div>
            </div>
        </div>
        <div class="auto-container">
            <div class="content-box">
                <div class="content-wrapper">
                    <div class="title">
                        
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="contact-map">
            <iframe src="{{ $site_details[0]->map }}" width="600" height="490" style="border:0; width: 100%"
                allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </section>


    <!-- Contact Info section two -->
    <section class="contact-info-section-two">
        <div class="auto-container">
            <div class="nav-tabs-wrapper">
                <div class="nav nav-tabs tab-btn-style-two">
                    <div class="theme_carousel owl-theme owl-carousel"
                        data-options='{"loop": false, "margin": 0, "autoheight":true, "lazyload":true, "nav": true, "dots": true, "autoplay": true, "autoplayTimeout": 6000, "smartSpeed": 1000, "responsive":{ "0" :{ "items": "1" }, "600" :{ "items" : "1" }, "768" :{ "items" : "2" } , "992":{ "items" : "2" }, "1200":{ "items" : "3" }}}'>
                        <ul class="item">
                            <li><a class="active e1" data-toggle="tab" href="#tab-one">
                                    <h4>{{ $site_details[0]->sitename }}</h4>
                                </a></li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="tab-content ">
                <div class="tab-pane fadeInUp  animated" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="outer-box">
                                <h4><span class="flaticon-cursor cads "
                                        style="color:white;"></span>{{ $site_details[0]->address }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="wrapper-box">
                                            <div class="icon-box">
                                                <div class="icon"><span class="flaticon-calling"
                                                        style="color:white;"></span></div>
                                                <div class="text-area">
                                                    <div class="col">
                                                        <div class="text"><strong></strong>
                                                            @php
                                                                $phoneRaw = $site_details[0]->phone ?? '';
                                                                $numbers = explode('/', $phoneRaw);
                                                            @endphp
                                                            <a
                                                                href="tel:{{ str_replace([' ', '-', '(', ')'], '', $numbers[0] ?? '') }}" class="cont" style="color: white;font-weight:800;">

                                                                {{ $numbers[0] ?? '' }}
                                                            </a>
                                                            @if(!empty($numbers[1]))
                                                            <br>
                                                            <a
                                                                href="tel:{{ str_replace([' ', '-', '(', ')'], '', $numbers[1]) }}"  class="cont" style="color: white;font-weight:800;">

                                                                {{ $numbers[1] }}
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </div>








                                                </div>
                                            </div>
                                            <br>
                                            <div class="icon-box icbx" style="margin-top:-27px;">
                                                <div class="icon"><span class="flaticon-mail" style="color:white;"></span>
                                                </div>
                                                <div class="text-area">
                                                    <div class="text"><strong><a
                                                                href="mailto:{{ $site_details[0]->email }}"
                                                                style="color: white;"
                                                                class="cel">{{ $site_details[0]->email }}</a></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="sec-title">
                                <h2 class="japbp"> Join as Prime business partner</h2>
                            </div>
                            <div class="text">Join Forces with Us: <br> Together, We Can Take Our Business to the Next
                                Level.</div>
                            <div class="link">
                                <a href="{{ url('pbp') }}" class="readmore-link theme"
                                    style="background: #fff;
                                            color: #2a2a2a;
                                            border: 1px dashed black;
                                            border-radius: 20px;"><i
                                        class="flaticon-up-arrow"></i>More
                                    Details</a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact form section -->
    <section class="contact-form-section">
        <div class="auto-container">
            <div class="sec-title text-center">
                <div class="sub-titled"
                    style="font-weight: 900; font-size:25px; padding-bottom:18px;
            font-family: 'Poppins', sans-serif;">
                    Give a Feedback</div>
                <h2 class="japbp">We’re Always Here for You</h2>
                <div class="text">"Effortless buying, swift delivery – all in one place."</div>
            </div>
            <!--Contact Form-->
            <div class="contact-form">
                <div id="success-message" style="display: none;" class="alert alert-success">Successfully Submitted!</div>

                <form method="post" action="{{ route('feedback') }}" id="contact-form" onsubmit="showSuccessMessage();">

                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your Name"
                                required>
                        </div>
                        {{-- <div class="form-group col-md-6">
                        <input type="text" name="company" value="{{ old('company') }}" placeholder="Company Name" required>
                    </div> --}}
                        <div class="form-group col-md-6">
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="Your Email"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="phone" id="contact_phone" value="{{ old('phone') }}" placeholder="Phone Number"
                                inputmode="numeric" maxlength="12" required>
                            @if (config('services.whatsapp.onboarding_otp_enabled'))
                                <div class="d-flex align-items-center mt-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-dark" id="contactSendOtpBtn" style="font-size: 13px; padding: 4px 12px; border-radius: 4px;">
                                        Send OTP
                                    </button>
                                    <span class="text-success ml-2" id="contactOtpVerifiedText" style="display:none; font-weight: 600; font-size: 13px;">
                                        OTP verified successfully
                                    </span>
                                </div>
                                <div id="contactOtpInlineStatus" class="small mt-1" style="font-size: 12px;"></div>
                            @endif
                        </div>


                        <div class="form-group col-md-6">
                            <textarea name="message" placeholder="Message" value="{{ old('message') }}"></textarea>
                        </div>
                        <div class="form-group text-center col-md-12">
                            <input id="form_botcheck" class="form-control" type="hidden" value="">
                            <button class="theme-btn btn-style-one" type="submit" id="contactSubmitBtn"
                                data-loading-text="Please wait..."><span><i class="flaticon-up-arrow"></i>Send
                                    Feedback</span></button>
                        </div>
                    </div>
                </form>

                @if (config('services.whatsapp.onboarding_otp_enabled'))
                    <style>
                        #contactOtpModal {
                            z-index: 99999 !important;
                        }
                        #contactOtpModal .modal-dialog {
                            z-index: 100000 !important;
                            position: relative !important;
                            pointer-events: auto !important;
                        }
                        #contactOtpModal .modal-content {
                            pointer-events: auto !important;
                            background-color: #ffffff !important;
                            border: 1px solid rgba(0, 0, 0, 0.2) !important;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
                            border-radius: 12px !important;
                        }
                        #contactOtpInput {
                            pointer-events: auto !important;
                            user-select: text !important;
                            -webkit-user-select: text !important;
                            cursor: text !important;
                            background-color: #ffffff !important;
                            color: #111111 !important;
                            caret-color: #007bff !important;
                            position: relative !important;
                            z-index: 100005 !important;
                            height: 52px !important;
                            font-size: 24px !important;
                            font-weight: 700 !important;
                            letter-spacing: 6px !important;
                            text-align: center !important;
                            border: 2px solid #007bff !important;
                            border-radius: 8px !important;
                        }
                        #contactOtpInput:focus {
                            border-color: #0056b3 !important;
                            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25) !important;
                            background-color: #ffffff !important;
                            outline: none !important;
                        }
                    </style>
                    <!-- WhatsApp OTP Verification Modal -->
                    <div class="modal fade" id="contactOtpModal" tabindex="-1" role="dialog"
                        aria-labelledby="contactOtpModalLabel" aria-hidden="true" data-backdrop="static"
                        data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 8px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="contactOtpModalLabel" style="font-weight: 600;">
                                        Verify WhatsApp Number
                                    </h5>
                                    <button type="button" class="close contact-otp-close" aria-label="Close" style="border:none; background:none; font-size:24px; cursor:pointer;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="contactOtpStatus" class="alert" style="display:none; font-size: 14px;"></div>
                                    <p class="mb-2" style="font-size: 14px; color: #333;">
                                        Enter the 6-digit verification code sent to
                                        <strong id="contactOtpMaskedPhone"></strong>.
                                    </p>
                                    <p class="text-muted" style="font-size: 13px;">The code is valid for 60 seconds.</p>
                                    <div class="form-group mb-0">
                                        <label for="contactOtpInput" style="font-weight: 500;">Verification code</label>
                                        <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                            pattern="[0-9]{6}" maxlength="6" class="form-control"
                                            id="contactOtpInput" placeholder="Enter 6-digit OTP" style="font-size: 18px; letter-spacing: 4px; text-align: center;">
                                    </div>
                                </div>
                                <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                    <button type="button" class="btn btn-link" id="contactOtpResendBtn" style="color: #007bff; text-decoration: none; padding: 0;">
                                        Resend OTP
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-secondary" id="contactOtpCancelBtn">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-dark" id="contactOtpVerifyBtn">
                                            Verify OTP
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const contactForm = document.getElementById('contact-form');
                        if (!contactForm) return;

                        const phoneInput = document.getElementById('contact_phone') || contactForm.querySelector('[name="phone"]');
                        const requiresWhatsAppOtp = @json(config('services.whatsapp.onboarding_otp_enabled'));
                        const sendOtpUrl = @json(route('subscriber.onboarding.otp.send'));
                        const verifyOtpUrl = @json(route('subscriber.onboarding.otp.verify'));
                        const otpVerifiedKey = 'contact_form_otp_verified';

                        let otpVerifiedForSubmit = false;
                        let otpRequestInProgress = false;
                        let verifiedOtpPhone = '';
                        let otpSentPhone = '';
                        let otpExpiresAt = 0;
                        let otpMaskedPhone = '';

                        function csrfToken() {
                            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                   contactForm.querySelector('input[name="_token"]')?.value || '';
                        }

                        function setOtpStatus(message, type) {
                            const status = document.getElementById('contactOtpStatus');
                            if (!status) return;
                            if (!message) {
                                status.style.display = 'none';
                                status.textContent = '';
                                return;
                            }
                            status.className = 'alert alert-' + type;
                            status.textContent = message;
                            status.style.display = 'block';
                        }

                        function setInlineOtpStatus(message, type) {
                            const status = document.getElementById('contactOtpInlineStatus');
                            if (!status) return;
                            status.className = 'small mt-1 text-' + (type === 'danger' ? 'danger' : (type === 'warning' ? 'warning' : 'success'));
                            status.textContent = message;
                        }

                        function setOtpButtons(disabled) {
                            ['contactOtpResendBtn', 'contactOtpCancelBtn', 'contactOtpVerifyBtn'].forEach(function (id) {
                                const btn = document.getElementById(id);
                                if (btn) btn.disabled = disabled;
                            });
                            const sendBtn = document.getElementById('contactSendOtpBtn');
                            if (sendBtn) sendBtn.disabled = disabled;
                        }

                        function setOtpVerifiedState(isVerified, phone) {
                            otpVerifiedForSubmit = isVerified;
                            verifiedOtpPhone = isVerified ? (phone || '') : '';

                            const sendButton = document.getElementById('contactSendOtpBtn');
                            const verifiedText = document.getElementById('contactOtpVerifiedText');

                            if (sendButton) {
                                sendButton.style.display = isVerified ? 'none' : 'inline-block';
                                sendButton.textContent = 'Send OTP';
                            }

                            if (verifiedText) {
                                verifiedText.style.display = isVerified ? 'inline-block' : 'none';
                            }

                            try {
                                if (isVerified) {
                                    sessionStorage.setItem(otpVerifiedKey, JSON.stringify({
                                        phone: verifiedOtpPhone,
                                        saved_at: Date.now()
                                    }));
                                } else {
                                    sessionStorage.removeItem(otpVerifiedKey);
                                }
                            } catch (e) {}
                        }

                        function showOtpModal(maskedPhone) {
                            const maskedPhoneNode = document.getElementById('contactOtpMaskedPhone');
                            const input = document.getElementById('contactOtpInput');
                            if (maskedPhoneNode) {
                                maskedPhoneNode.textContent = maskedPhone || '';
                            }
                            if (input) {
                                input.value = '';
                            }
                            setOtpStatus('', 'info');

                            if (window.$ && typeof $('#contactOtpModal').modal === 'function') {
                                $('#contactOtpModal').modal('show');
                                setTimeout(function () {
                                    document.getElementById('contactOtpInput')?.focus();
                                }, 300);
                                return;
                            }

                            const modal = document.getElementById('contactOtpModal');
                            if (modal) {
                                modal.classList.add('show');
                                modal.style.display = 'block';
                                modal.removeAttribute('aria-hidden');
                                modal.setAttribute('aria-modal', 'true');
                            }
                        }

                        function hideOtpModal() {
                            if (window.$ && typeof $('#contactOtpModal').modal === 'function') {
                                $('#contactOtpModal').modal('hide');
                            }
                            const modal = document.getElementById('contactOtpModal');
                            if (modal) {
                                modal.classList.remove('show');
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                modal.removeAttribute('aria-modal');
                            }
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) {
                                backdrop.remove();
                            }
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
                            } catch (e) {
                                result = {};
                            }

                            if (!response.ok) {
                                const message = result.message || 'Unable to process OTP request. Please try again.';
                                throw new Error(message);
                            }

                            return result;
                        }

                        async function sendOtpForCurrentPhone(showModalAfterSend, isResend) {
                            if (!contactForm || otpRequestInProgress) return;
                            const phone = (phoneInput?.value || '').trim();

                            if (!phone) {
                                setOtpStatus('Please enter a phone number before requesting OTP.', 'danger');
                                setInlineOtpStatus('Please enter a phone number before requesting OTP.', 'danger');
                                return;
                            }

                            if (!isResend && otpSentPhone === phone && otpExpiresAt && Date.now() < otpExpiresAt) {
                                const message = 'Verification code already sent to your WhatsApp number.';
                                if (showModalAfterSend) {
                                    showOtpModal(otpMaskedPhone || phone);
                                }
                                setOtpStatus(message, 'success');
                                setInlineOtpStatus(message, 'success');
                                return;
                            }

                            const payload = new FormData();
                            payload.append('mobile', phone);
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
                                otpSentPhone = phone;
                                otpExpiresAt = Date.now() + (Number(result.expires_in || 60) * 1000);
                                otpMaskedPhone = result.masked_phone || phone;
                                if (showModalAfterSend) {
                                    showOtpModal(otpMaskedPhone);
                                }
                                setOtpStatus(result.message || 'Verification code sent.', 'success');
                                setInlineOtpStatus(result.message || 'Verification code sent.', 'success');
                            } catch (error) {
                                if (showModalAfterSend) {
                                    showOtpModal(phone);
                                }
                                setOtpStatus(error.message, 'danger');
                                setInlineOtpStatus(error.message, 'danger');
                            } finally {
                                otpRequestInProgress = false;
                                setOtpButtons(false);
                            }
                        }

                        async function verifyCurrentOtp() {
                            if (!contactForm || otpRequestInProgress) return;

                            const otpInput = document.getElementById('contactOtpInput');
                            const otp = otpInput ? otpInput.value.trim() : '';
                            if (!/^\d{6}$/.test(otp)) {
                                setOtpStatus('Please enter the 6-digit OTP.', 'danger');
                                return;
                            }

                            const currentPhone = (phoneInput?.value || '').trim();
                            const payload = new FormData();
                            payload.append('mobile', currentPhone);
                            payload.append('otp', otp);

                            otpRequestInProgress = true;
                            setOtpButtons(true);
                            setOtpStatus('Verifying OTP...', 'info');

                            try {
                                await postOtp(verifyOtpUrl, payload);
                                setOtpVerifiedState(true, currentPhone);
                                setInlineOtpStatus('OTP verified successfully.', 'success');
                                hideOtpModal();
                            } catch (error) {
                                setOtpStatus(error.message, 'danger');
                            } finally {
                                otpRequestInProgress = false;
                                setOtpButtons(false);
                            }
                        }

                        // Event Listeners for OTP
                        document.getElementById('contactSendOtpBtn')?.addEventListener('click', function () {
                            sendOtpForCurrentPhone(true, false);
                        });
                        document.getElementById('contactOtpVerifyBtn')?.addEventListener('click', verifyCurrentOtp);
                        document.getElementById('contactOtpResendBtn')?.addEventListener('click', function () {
                            sendOtpForCurrentPhone(false, true);
                        });
                        document.getElementById('contactOtpCancelBtn')?.addEventListener('click', hideOtpModal);
                        document.querySelector('.contact-otp-close')?.addEventListener('click', hideOtpModal);

                        document.getElementById('contactOtpInput')?.addEventListener('input', function () {
                            this.value = this.value.replace(/\D/g, '').slice(0, 6);
                        });
                        document.getElementById('contactOtpInput')?.addEventListener('keyup', function (event) {
                            if (event.key === 'Enter') {
                                verifyCurrentOtp();
                            }
                        });

                        // Phone input restrictions and verification state invalidation on change
                        if (phoneInput) {
                            phoneInput.addEventListener('input', function () {
                                this.value = this.value.replace(/[^0-9]/g, '');
                                if (otpVerifiedForSubmit && this.value !== verifiedOtpPhone) {
                                    setOtpVerifiedState(false, '');
                                    setInlineOtpStatus('Phone number changed. Please send OTP again.', 'warning');
                                }
                                if (this.value !== otpSentPhone) {
                                    otpSentPhone = '';
                                    otpExpiresAt = 0;
                                    otpMaskedPhone = '';
                                }
                            });
                        }

                        // Restore verified state if within 10 minutes
                        try {
                            const raw = sessionStorage.getItem(otpVerifiedKey);
                            if (raw) {
                                const parsed = JSON.parse(raw);
                                if (parsed.phone && (Date.now() - parsed.saved_at) < 600000) {
                                    if (phoneInput && phoneInput.value === parsed.phone) {
                                        setOtpVerifiedState(true, parsed.phone);
                                        setInlineOtpStatus('OTP verified successfully.', 'success');
                                    }
                                } else {
                                    sessionStorage.removeItem(otpVerifiedKey);
                                }
                            }
                        } catch (e) {}

                        // Form Submission handling
                        contactForm.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            const currentPhone = (phoneInput?.value || '').trim();

                            if (requiresWhatsAppOtp && !otpVerifiedForSubmit) {
                                if (otpSentPhone !== currentPhone) {
                                    const message = 'OTP verification is required. Sending OTP to your WhatsApp number.';
                                    setInlineOtpStatus(message, 'danger');
                                    showOtpModal(currentPhone);
                                    setOtpStatus(message, 'info');
                                    sendOtpForCurrentPhone(true, false);
                                    return;
                                }
                                const message = 'Please enter and verify the OTP before submitting the form.';
                                setInlineOtpStatus(message, 'danger');
                                showOtpModal(currentPhone);
                                setOtpStatus(message, 'danger');
                                return;
                            }

                            const submitBtn = document.getElementById('contactSubmitBtn');
                            if (submitBtn) submitBtn.disabled = true;

                            const formData = new FormData(contactForm);

                            try {
                                const response = await fetch(contactForm.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken(),
                                    },
                                });

                                const data = await response.json().catch(() => ({}));

                                if (response.ok) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Successfully Submitted',
                                        text: data.message || 'Successfully Submitted!',
                                        showConfirmButton: false,
                                        timer: 2500,
                                        timerProgressBar: true,
                                    });
                                    setOtpVerifiedState(false, '');
                                    contactForm.reset();
                                    setInlineOtpStatus('', 'success');
                                } else if (response.status === 422) {
                                    if (data.requires_otp) {
                                        setOtpVerifiedState(false, '');
                                        setInlineOtpStatus(data.message || 'Please verify your WhatsApp OTP.', 'danger');
                                        showOtpModal(currentPhone);
                                        setOtpStatus(data.message || 'Please verify your WhatsApp OTP.', 'danger');
                                    } else {
                                        const errorMsg = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Submission failed.');
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Validation Error',
                                            html: errorMsg,
                                        });
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while submitting feedback. Please try again.',
                                    });
                                }
                            } catch (err) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Network error occurred. Please try again.',
                                });
                            } finally {
                                if (submitBtn) submitBtn.disabled = false;
                            }
                        });
                    });
                </script>






            </div>
            <!--End Contact Form-->
        </div>
    </section></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.e1').trigger('click');
        })
    </script>
@endsection
