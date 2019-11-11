<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use App\Models;
use App\Models\InvoiceConfiguration as Model;

class StoreNumber
{
    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\InvoiceConfiguration
     */
    public static function setNext(Models\User $user): Model
    {
        $prefix = Model::select('value')
            ->where('key', 'number_prefix')
            ->byCompany($user->company)
            ->first()
            ->value ?? null;

        if ($prefix) {
            $number = static::nextWithPrefix($user->company, $prefix);
        } else {
            $number = static::nextWithoutPrefix($user->company);
        }

        return static::save($user, $number);
    }

    /**
     * @param \App\Models\Company $company
     * @param string $prefix
     *
     * @return int
     */
    protected static function nextWithPrefix(Models\Company $company, string $prefix): int
    {
        $last = Models\Invoice::where('number', 'LIKE', $prefix.'%')
            ->byCompany($company)
            ->orderBy('number', 'DESC')
            ->first();

        if (empty($last)) {
            return 1;
        }

        return (int)preg_replace('/^'.preg_quote($prefix, '/').'/', '', $last->number) + 1;
    }

    /**
     * @param \App\Models\Company $company
     *
     * @return int
     */
    protected static function nextWithoutPrefix(Models\Company $company): int
    {
        $last = Models\Invoice::byCompany($company)->orderBy('number', 'DESC')->first();

        return $last ? ((int)$last->number + 1) : 1;
    }

    /**
     * @param \App\Models\User $user
     * @param int $number
     *
     * @return \App\Models\InvoiceConfiguration
     */
    protected static function save(Models\User $user, int $number): Model
    {
        $row = Model::firstOrNew([
            'key' => 'number_next',
            'company_id' => $user->company_id,
        ]);

        $row->user_id = $user->id;
        $row->value = $number;
        $row->save();

        return $row;
    }
}
