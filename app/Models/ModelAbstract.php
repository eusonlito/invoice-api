<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

abstract class ModelAbstract extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param int $id
     *
     * @return void
     */
    public function scopeById(Builder $q, int $id)
    {
        $q->where('id', $id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param \App\Models\Company $company
     *
     * @return void
     */
    public function scopeByCompany(Builder $q, Company $company)
    {
        $q->where('company_id', $company->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function scopeByUser(Builder $q, User $user)
    {
        $q->where('user_id', $user->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeEnabled(Builder $q)
    {
        $q->where('enabled', 1);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeDefault(Builder $q)
    {
        $q->where('default', 1);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeSimple(Builder $q)
    {
        $q->select('id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeDetail(Builder $q)
    {
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExport(Builder $q)
    {
        $q->orderBy('id', 'ASC');
    }

    /**
     * @param string $column
     * @param int $value
     * @param string $every
     *
     * @return \Illuminate\Database\Query\Expression
     */
    public static function dateAdd(string $column, int $value, string $every): Expression
    {
        return DB::raw('DATE_ADD(`'.$column.'`, INTERVAL '.(int)$value.' '.strtoupper($every).')');
    }
}
