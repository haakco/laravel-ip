<?php

/** @noinspection PhpMissingFieldTypeInspection */

declare(strict_types=1);

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use Eloquent;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;

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
 *
 * @mixin Eloquent
 */
class IpPrivateRanges extends BaseModel
{
    protected $casts = [
        'family' => 'int',
        'name' => 'string',
        'description' => 'string',
    ];

    protected $fillable = ['family', 'name', 'description'];

    public function getTable()
    {
        return config('ip.tables.ip_types', parent::getTable());
    }
}
