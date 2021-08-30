<?php

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
 * @property Collection|Ip[] $ips_ip_type
 * @mixin Eloquent
 */
class IpType extends BaseModel
{
    public $incrementing = false;
    protected string $table = 'ip_types';

    protected array $casts = [
        'id' => 'int',
        'family' => 'int',
    ];

    protected array $fillable = ['id', 'family', 'name'];

    /**
     * @return HasMany|Ip[]
     */
    public function ips(): HasMany|array
    {
        return $this->hasMany(Ip::class, 'ip_type_id');
    }
}
