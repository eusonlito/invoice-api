<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use App\Domains\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    public static function configUpdate(): array
    {
        return [
            'all' => false,

            'rules' => [
                'client_address_billing_id' => 'required|integer',
                'client_address_shipping_id' => 'nullable|integer',
                'number' => 'required',
                'date_at' => 'required|date',
                'paid_at' => 'nullable|date',
                'required_at' => 'nullable|date',
                'invoice_serie_id' => 'required|integer',
                'invoice_status_id' => 'required|integer',
                'payment_id' => 'nullable|integer',
                'discount_id' => 'nullable|integer',
                'tax_id' => 'nullable|integer',
                'shipping_id' => 'nullable|integer',
                'percent_discount' => 'numeric',
                'percent_tax' => 'numeric',
                'amount_due' => 'numeric',
                'amount_shipping' => 'numeric',
                'amount_paid' => 'numeric',
                'comment_public' => 'string',
                'comment_private' => 'string',

                'items' => 'required|array',
                'items.*.reference' => 'string',
                'items.*.description' => 'required',
                'items.*.amount_price' => 'numeric',
                'items.*.quantity' => 'required|numeric',
                'items.*.percent_discount' => 'integer',
            ],

            'messages' => [
                'client_address_billing_id.required' => __('validator.client_address_billing_id-required'),
                'client_address_billing_id.integer' => __('validator.client_address_billing_id-integer'),
                'client_address_shipping_id.integer' => __('validator.client_address_shipping_id-integer'),
                'number.required' => __('validator.number-required'),
                'date_at.required' => __('validator.date_at-required'),
                'date_at.date' => __('validator.date_at-date'),
                'paid_at.date' => __('validator.paid_at-date'),
                'required_at.date' => __('validator.required_at-date'),
                'invoice_serie_id.required' => __('validator.invoice_serie_id-required'),
                'invoice_serie_id.integer' => __('validator.invoice_serie_id-integer'),
                'invoice_status_id.required' => __('validator.invoice_status_id-required'),
                'invoice_status_id.integer' => __('validator.invoice_status_id-integer'),
                'payment_id.integer' => __('validator.payment_id-integer'),
                'discount_id.integer' => __('validator.discount_id-integer'),
                'tax_id.integer' => __('validator.tax_id-integer'),
                'shipping_id.integer' => __('validator.shipping_id-integer'),
                'percent_discount.numeric' => __('validator.percent_discount-numeric'),
                'percent_tax.numeric' => __('validator.percent_tax-numeric'),
                'amount_due.numeric' => __('validator.amount_due-numeric'),
                'amount_shipping.numeric' => __('validator.amount_shipping-numeric'),
                'amount_paid.numeric' => __('validator.amount_paid-numeric'),
                'comment_public.string' => __('validator.comment_public-string'),
                'comment_private.string' => __('validator.comment_private-string'),

                'items.required' => __('validator.items-required'),
                'items.array' => __('validator.items-array'),
                'items.*.reference.string' => __('validator.reference-string'),
                'items.*.description.required' => __('validator.description-required'),
                'items.*.amount_price.numeric' => __('validator.amount_price-numeric'),
                'items.*.quantity.required' => __('validator.quantity-required'),
                'items.*.quantity.numeric' => __('validator.quantity-numeric'),
                'items.*.percent_discount.integer' => __('validator.percent_discount-integer'),
            ]
        ];
    }

    /**
     * @return array
     */
    public static function configCreate(): array
    {
        return static::configUpdate();
    }
}
