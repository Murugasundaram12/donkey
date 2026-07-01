<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $exists = Employee::where('email', $value)->exists()
                        || Subscriber::where('email', $value)->exists();

                    if (!$exists) {
                        $fail('The selected email is invalid.');
                    }
                },
            ],
        ];
    }
}
