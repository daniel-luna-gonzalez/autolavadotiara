<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            "customer.name"                 => 'string|required|min:3',
            'customer.last_name'            => 'string|required|min:3',
            'customer.mother_last_name'     => 'string',
            'customer.birthday'             => '|required|string',
            'customer.email'                => 'string|required|email',
            'customer.name'                 => 'string|required',
            'vehicle.marca'                 => 'string|required',
            'vehicle.modelo'                => 'string|required',
            'vehicle.color'                 => 'string|required',
            'vehicle.placas'                => 'string|required',
            "vehicle.nivelEstacionamiento"  => 'string|required',
            'vehicle.Nocajon'               => 'string|required',
            'vehicle.depto'                 => 'string|required',
            'vehicle.vehicleType'           => 'string|required',
            'vehicle.washDays'              => 'array'
        ];
    }

    public function response(array $errors){
        return response()->json(["status" => false, "message" => "falló en validaciones", "validations" => $errors], 402);

    }

}
