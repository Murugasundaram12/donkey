<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    /**
     * Get All Vendor Documents
     */
    public function index(Request $request)
    {
        $vendor = $request->user();

        $documents = [
            'aadhar_no' => (string) ($vendor->aadharNo ?? ''),
            'aadhar_front' => $vendor->aadharImage ? asset('public/subscriber/aadhar/' . $vendor->aadharImage) : null,
            'aadhar_back' => $vendor->aadharBackImage ? asset('public/subscriber/aadhar/back/' . $vendor->aadharBackImage) : null,
            'pan_card' => $vendor->pancardImage ? asset('public/subscriber/pan/' . $vendor->pancardImage) : null,
            'bank_statement' => $vendor->bankstatement ? asset('public/subscriber/bank/' . $vendor->bankstatement) : null,
            'customer_document' => $vendor->customerdocument ? asset('public/subscriber/document/' . $vendor->customerdocument) : null,
            'qr_code' => $vendor->qr ? asset('public/subscriber/qr/' . $vendor->qr) : null,
            'profile_image' => $vendor->image ? asset('public/subscriber/' . $vendor->image) : null,
            'verification_video' => $vendor->video ? asset('public/subscriber/video/' . $vendor->video) : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Vendor documents retrieved successfully',
            'data' => [
                'documents' => $documents
            ]
        ]);
    }

    /**
     * Upload Vendor Document
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:aadhar_front,aadhar_back,pan_card,bank_statement,customer_document,qr,video,profile',
            'document_file' => 'required|file|max:10240',
            'aadhar_no' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = $request->user();
        $type = $request->document_type;
        $file = $request->file('document_file');
        $fileName = time() . '_' . $type . '_' . uniqid() . '.' . $file->extension();

        if ($request->filled('aadhar_no')) {
            $vendor->aadharNo = $request->aadhar_no;
        }

        switch ($type) {
            case 'aadhar_front':
                $file->move(public_path('subscriber/aadhar'), $fileName);
                $vendor->aadharImage = $fileName;
                break;
            case 'aadhar_back':
                $file->move(public_path('subscriber/aadhar/back'), $fileName);
                $vendor->aadharBackImage = $fileName;
                break;
            case 'pan_card':
                $file->move(public_path('subscriber/pan'), $fileName);
                $vendor->pancardImage = $fileName;
                break;
            case 'bank_statement':
                $file->move(public_path('subscriber/bank'), $fileName);
                $vendor->bankstatement = $fileName;
                break;
            case 'customer_document':
                $file->move(public_path('subscriber/document'), $fileName);
                $vendor->customerdocument = $fileName;
                break;
            case 'qr':
                $file->move(public_path('subscriber/qr'), $fileName);
                $vendor->qr = $fileName;
                break;
            case 'video':
                $file->move(public_path('subscriber/video'), $fileName);
                $vendor->video = $fileName;
                break;
            case 'profile':
                $file->move(public_path('subscriber'), $fileName);
                $vendor->image = $fileName;
                break;
        }

        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Document uploaded successfully',
            'data' => [
                'document_type' => $type,
                'file_name' => $fileName,
            ]
        ]);
    }

    /**
     * Delete Vendor Document
     */
    public function destroy(Request $request, $type)
    {
        $vendor = $request->user();
        $allowedTypes = ['aadhar_front', 'aadhar_back', 'pan_card', 'bank_statement', 'customer_document', 'qr', 'video', 'profile'];

        if (!in_array($type, $allowedTypes)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid document type.'
            ], 400);
        }

        switch ($type) {
            case 'aadhar_front':
                $vendor->aadharImage = null;
                break;
            case 'aadhar_back':
                $vendor->aadharBackImage = null;
                break;
            case 'pan_card':
                $vendor->pancardImage = null;
                break;
            case 'bank_statement':
                $vendor->bankstatement = null;
                break;
            case 'customer_document':
                $vendor->customerdocument = null;
                break;
            case 'qr':
                $vendor->qr = null;
                break;
            case 'video':
                $vendor->video = null;
                break;
            case 'profile':
                $vendor->image = null;
                break;
        }

        $vendor->save();

        return response()->json([
            'status' => true,
            'message' => 'Document removed successfully'
        ]);
    }
}
