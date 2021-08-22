<?php

/**
 * Created by Reliese Model.
 */

namespace HaakCo\Ip\Models;

use Carbon\Carbon;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class IpType
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $family
 * @property string $name
 * @property Collection|Ip[] $ips_ip_type
 * @package HaakCo\Ip\Models
 */
class IpType extends BaseModel
{
    protected $table = 'ip_types';
    public $incrementing = false;

    protected $casts = [
        'id' => 'int',
        'family' => 'int'
    ];

    protected $fillable = [
        'id',
        'family',
        'name'
    ];

    public function ips_ip_type()
    {
        return $this->hasMany(Ip::class, 'ip_type_id');
    }
}
