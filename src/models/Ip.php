<?php

/**
 * Created by Reliese Model.
 */

namespace HaakCo\Ip\Models;


use Carbon\Carbon;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;

/**
 * Class Ip
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $ip_type_id
 * @property boolean $is_public
 * @property string $name
 * @property int $netmask
 * @property IpType $ip_type
 * @package HaakCo\Ip\Models
 */
class Ip extends BaseModel
{
    protected $table = 'ips';

    protected $casts = [
        'ip_type_id' => 'int',
        'is_public' => 'boolean',
        'name' => 'string',
        'netmask' => 'int'
    ];

    protected $fillable = [
        'ip_type_id',
        'is_public',
        'name',
        'netmask'
    ];

    public function ip_type()
    {
        return $this->belongsTo(IpType::class, 'ip_type_id');
    }
}
