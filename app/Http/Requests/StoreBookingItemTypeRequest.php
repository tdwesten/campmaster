<?php

namespace App\Http\Requests;

use App\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingItemTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy will be applied at controller level
    }

    public function rules(): array
    {
        $tenantId = Tenant::current()?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('booking_item_types', 'name')
                    ->where(fn ($q) => $tenantId ? $q->where('tenant_id', $tenantId) : $q)
                    ->whereNull('deleted_at'),
            ],
            'interval_type' => ['required', Rule::in(['night', 'period'])],
            'price_per_unit' => ['required', 'integer', 'min:0'],
            'tax_class_id' => ['nullable', 'uuid', 'exists:tax_classes,id'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('booking_item_types.validation.name.required'),
            'name.unique' => __('booking_item_types.validation.name.unique'),
            'interval_type.required' => __('booking_item_types.validation.interval_type.required'),
            'interval_type.in' => __('booking_item_types.validation.interval_type.in'),
            'price_per_unit.required' => __('booking_item_types.validation.price_per_unit.required'),
            'price_per_unit.integer' => __('booking_item_types.validation.price_per_unit.integer'),
            'price_per_unit.min' => __('booking_item_types.validation.price_per_unit.min'),
            'tax_class_id.uuid' => __('booking_item_types.validation.tax_class_id.uuid'),
            'active.boolean' => __('booking_item_types.validation.active.boolean'),
        ];
    }
}
