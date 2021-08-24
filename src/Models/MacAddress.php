<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;



/**
 * Class MacAddress
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @package App\Models
 * @mixin IdeHelperMacAddress
 */
class MacAddress extends \HaakCo\PostgresHelper\Models\BaseModels\BaseModel
{
    protected $table = 'mac_addresses';

    protected $casts = [
        'name' => 'macaddr'
    ];

    protected $fillable = [
        'name'
    ];
}
