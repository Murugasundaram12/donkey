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
            'aadhar_front' => $this->resolveDocumentUrl($vendor->aadharImage, [
                'subscriber/aadhar',
                'admin/subscriber/aadhar',
            ]),
            'aadhar_back' => $this->resolveDocumentUrl($vendor->aadharBackImage, [
                'subscriber/aadhar/back',
                'admin/subscriber/aadhar/back',
            ]),
            'pan_card' => $this->resolveDocumentUrl($vendor->pancardImage, [
                'subscriber/pan',
                'admin/subscriber/pan',
            ]),
            'bank_statement' => $this->resolveDocumentUrl($vendor->bankstatement, [
                'subscriber/bank',
                'admin/subscriber/bankstatement',
                'admin/subscriber/bank',
            ]),
            'customer_document' => $this->resolveDocumentUrl($vendor->customerdocument, [
                'subscriber/document',
                'admin/subscriber/document',
            ]),
            'qr_code' => $this->resolveDocumentUrl($vendor->qr, [
                'subscriber/qr',
                'qr',
                'admin/subscriber/qr',
            ]),
            'profile_image' => $this->resolveDocumentUrl($vendor->image, [
                'subscriber',
                'admin/subscriber/profile',
            ]),
            'verification_video' => $this->resolveDocumentUrl($vendor->video, [
                'subscriber/video',
                'admin/subscriber/video',
            ]),
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
     * Resolve Public Document URL based on actual file existence.
     *
     * @param string|null $filename
     * @param array $relativePaths
     * @return string|null
     */
    private function resolveDocumentUrl(?string $filename, array $relativePaths): ?string
    {
        if (empty($filename)) {
            return null;
        }

        $safeFilename = basename($filename);
        if ($safeFilename === '.' || $safeFilename === '..' || empty($safeFilename)) {
            return null;
        }

        foreach ($relativePaths as $relativePath) {
            $cleanRelativePath = trim($relativePath, '/');
            $candidatePath = public_path($cleanRelativePath . '/' . $safeFilename);

            if (file_exists($candidatePath) && is_file($candidatePath)) {
                return asset($cleanRelativePath . '/' . $safeFilename);
            }
        }

        return null;
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

    /**
     * Stream Authenticated Vendor Document
     *
     * Note: This endpoint provides secure, authenticated document streaming.
     * Legacy public document URLs returned by index() remain accessible for backward compatibility.
     *
     * @param Request $request
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function showFile(Request $request, $type)
    {
        $typeMapping = [
            'aadhar_front' => [
                'field' => 'aadharImage',
                'dirs' => [
                    public_path('subscriber/aadhar'),
                    public_path('admin/subscriber/aadhar'),
                ],
            ],
            'aadhar_back' => [
                'field' => 'aadharBackImage',
                'dirs' => [
                    public_path('subscriber/aadhar/back'),
                    public_path('admin/subscriber/aadhar/back'),
                ],
            ],
            'pan_card' => [
                'field' => 'pancardImage',
                'dirs' => [
                    public_path('subscriber/pan'),
                    public_path('admin/subscriber/pan'),
                ],
            ],
            'bank_statement' => [
                'field' => 'bankstatement',
                'dirs' => [
                    public_path('subscriber/bank'),
                    public_path('admin/subscriber/bankstatement'),
                    public_path('admin/subscriber/bank'),
                ],
            ],
            'customer_document' => [
                'field' => 'customerdocument',
                'dirs' => [
                    public_path('subscriber/document'),
                    public_path('admin/subscriber/document'),
                ],
            ],
            'qr_code' => [
                'field' => 'qr',
                'dirs' => [
                    public_path('subscriber/qr'),
                    public_path('qr'),
                    public_path('admin/subscriber/qr'),
                ],
            ],
            'profile_image' => [
                'field' => 'image',
                'dirs' => [
                    public_path('subscriber'),
                    public_path('admin/subscriber/profile'),
                ],
            ],
            'verification_video' => [
                'field' => 'video',
                'dirs' => [
                    public_path('subscriber/video'),
                    public_path('admin/subscriber/video'),
                ],
            ],
        ];

        if (!array_key_exists($type, $typeMapping)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid document type requested.'
            ], 400);
        }

        $vendor = $request->user();
        $config = $typeMapping[$type];
        $field = $config['field'];
        $rawFilename = $vendor->$field ?? null;

        if (empty($rawFilename) || !is_string($rawFilename)) {
            return response()->json([
                'status' => false,
                'message' => 'Document not found.'
            ], 404);
        }

        // Prevent path traversal
        $safeFilename = basename($rawFilename);
        if ($safeFilename === '.' || $safeFilename === '..' || empty($safeFilename)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid document path.'
            ], 404);
        }

        // Locate file across valid base directories with strict boundary verification
        $resolvedPath = null;
        foreach ($config['dirs'] as $dir) {
            $candidatePath = $dir . DIRECTORY_SEPARATOR . $safeFilename;
            $realDir = realpath($dir);
            $realCandidate = realpath($candidatePath);

            if ($realDir && $realCandidate && file_exists($realCandidate) && is_file($realCandidate)) {
                if (str_starts_with($realCandidate, $realDir . DIRECTORY_SEPARATOR)) {
                    $resolvedPath = $realCandidate;
                    break;
                }
            }
        }

        if (!$resolvedPath) {
            return response()->json([
                'status' => false,
                'message' => 'Document file not found on disk.'
            ], 404);
        }

        // Sanitize filename for Content-Disposition to prevent HTTP header injection
        $cleanFilename = preg_replace('/[\r\n\t"\x00-\x1F\x7F]/', '', $safeFilename);
        if (empty($cleanFilename)) {
            $cleanFilename = 'document';
        }

        $mimeType = File::mimeType($resolvedPath) ?: 'application/octet-stream';

        return response()->file($resolvedPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $cleanFilename . '"',
            'Cache-Control' => 'private, no-cache, must-revalidate',
        ]);
    }
}
