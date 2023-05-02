<?php

/** @noinspection PhpMissingFieldTypeInspection */

declare(strict_types=1);

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use Eloquent;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class IpType.
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $family
 * @property string $name
 * @property Collection|Ip[] $ips
 *
 * @mixin Eloquent
 */
class IpType extends BaseModel
{
    public $incrementing = false;

    protected $casts = [
        'id' => 'int',
        'family' => 'int',
    ];

    protected $fillable = ['id', 'family', 'name'];

    public function getTable()
    {
        return config('ip.tables.ip_types', parent::getTable());
    }

    /**
     * @return HasMany|Ip[]
     */
    public function ips(): HasMany|array
    {
        return $this->hasMany(Ip::class, 'ip_type_id');
    }
}
