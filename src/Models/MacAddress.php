<?php

/** @noinspection PhpMissingFieldTypeInspection */

declare(strict_types=1);

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use Eloquent;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;

/**
 * Class MacAddress.
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $name
 * @mixin Eloquent
 */
class MacAddress extends BaseModel
{
    protected $casts = [
        'name' => 'macaddr',
    ];
    protected $fillable = ['name'];

    public function getTable()
    {
        return config('ip.tables.mac_addresses', parent::getTable());
    }
}
