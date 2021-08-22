<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;



/**
 * Class IpType
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $family
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Ip[] $ips_ip_type
 * @package App\Models
 * @mixin IdeHelperIpType
 */
class IpType extends \App\Models\BaseModels\BaseModel
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
        return $this->hasMany(\App\Models\Ip::class, 'ip_type_id');
    }
}
