<?php

/**
 * Created by Reliese Model.
 */

namespace HaakCo\Ip\Models;



/**
 * Class IpType
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $family
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\Ip[] $ips_ip_type
 * @package App\Models
 * @mixin IdeHelperIpType
 */
class IpType extends \HaakCo\PostgresHelper\Models\BaseModels
{
    protected $table = 'public.ip_types';
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
        return $this->hasMany(\HaakCo\Ip\Models\Ip::class, 'ip_type_id');
    }
}
