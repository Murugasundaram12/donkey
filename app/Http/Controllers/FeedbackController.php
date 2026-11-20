<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feedback\FeedbackStoreRequest;
use App\Models\Feedback;
use App\Traits\VerifiesWhatsAppOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    use VerifiesWhatsAppOtp;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks=Feedback::latest()->get();
// dd($feedbacks);
        return view('admin.feedback.index', ['feedbacks' => $feedbacks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackStoreRequest $request)
    {
        $isPublic = !Auth::check();

        if ($isPublic && config('services.whatsapp.onboarding_otp_enabled')) {
            if (!$this->hasVerifiedOtp($request->get('phone'))) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Please verify your WhatsApp OTP before submitting.',
                        'requires_otp' => true,
                    ], 422);
                }

                return back()
                    ->withInput()
                    ->withErrors(['phone' => 'Please verify your WhatsApp OTP before submitting.']);
            }
        }

        Feedback::create($request->validated());

        if ($isPublic && config('services.whatsapp.onboarding_otp_enabled')) {
            session()->forget('subscriber_onboarding_otp_verified');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Successfully Submitted!',
            ], 200);
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
