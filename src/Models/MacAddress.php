<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

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
    protected string $table = 'mac_addresses';

    protected array $casts = [
        'name' => 'macaddr',
    ];

    protected array $fillable = ['name'];
}
