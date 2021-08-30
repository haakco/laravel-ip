<?php

/** @noinspection PhpMissingFieldTypeInspection */

declare(strict_types=1);

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use Eloquent;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Ip.
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $ip_type_id
 * @property bool $is_public
 * @property string $name
 * @property int $netmask
 * @property IpType $ipType
 * @mixin Eloquent
 */
class Ip extends BaseModel
{
    protected $casts = [
        'ip_type_id' => 'int',
        'is_public' => 'boolean',
        'name' => 'string',
        'netmask' => 'int',
    ];

    protected $fillable = ['ip_type_id', 'is_public', 'name', 'netmask'];

    public function getTable()
    {
        return config('ip.tables.ip_types', parent::getTable());
    }

    public function ipType(): BelongsTo|IpType
    {
        return $this->belongsTo(IpType::class, 'ip_type_id');
    }
}
