<?php

namespace App\Http\Controllers\API\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    /**
     * Return the active provider for a pincode and category.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'pincode' => ['required', 'digits:6'],
            'category' => ['required', 'integer', 'exists:category,id'],
        ]);

        $pincode = Pincode::where('pincode', $validated['pincode'])->first();
        $category = Category::whereKey($validated['category'])
            ->where('status', 1)
            ->exists();

        $provider = null;

        if ($pincode && $category) {
            $subscriberIds = Pincodebasedcategory::query()
                ->where('pincode_id', $pincode->id)
                ->where('category_id', $validated['category'])
                ->where('status', 1)
                ->orderBy('id')
                ->pluck('subscriber_id');

            $subscribers = Subscriber::query()
                ->whereIn('id', $subscriberIds)
                ->where('status', 1)
                ->where('blockedstatus', 1)
                ->get(['id', 'name', 'mobile', 'status', 'blockedstatus', 'expiryDate']);

            $provider = $subscribers->first(
                fn (Subscriber $subscriber): bool => Subscriber::isSubscriberActive($subscriber)
            );
        }

        return response()->json([
            'status' => true,
            'message' => $provider
                ? 'Active service provider found.'
                : 'No active service provider found for this pincode and category.',
            'data' => [
                'provider_active' => $provider !== null,
                'provider' => $provider ? [
                    'id' => (int) $provider->id,
                    'name' => (string) $provider->name,
                    'contact' => (string) $provider->mobile,
                ] : null,
            ],
        ]);
    }
}
