<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CompanyController extends BaseController
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $companies = $query->select([
            'id',
            'company_id',
            'company_code',
            'name',
            'email',
            'phone',
            'city',
            'state',
            'status',
            'created_at'
        ])
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->sendResponse($companies, 'Companies retrieved successfully.');
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $validated = $validator->validated();

        $validated['status'] = $validated['status'] ?? 'active';

        $company = Company::create($validated);

        return $this->sendResponse($company, 'Company created successfully.');
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company): JsonResponse
    {
        $company->loadCount('bookings');

        return $this->sendResponse($company, 'Company retrieved successfully.');
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $validated = $validator->validated();

        if (!array_key_exists('status', $validated)) {
            $validated['status'] = $company->status ?? 'active';
        }

        $company->update($validated);

        return $this->sendResponse($company, 'Company updated successfully.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return $this->sendResponse(null, 'Company deleted successfully.');
    }
}
