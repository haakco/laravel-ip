<?php

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;

/**
 * Class MacAddress
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $name
 * @package HaakCo\Ip\Models
 */
class MacAddress extends BaseModel
{
    protected $table = 'mac_addresses';

    protected $casts = [
        'name' => 'macaddr'
    ];

    protected $fillable = [
        'name'
    ];
}
