<?php

/**
 * Created by Reliese Model.
 */

namespace HaakCo\Ip\Models;



/**
 * Class MacAddress
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\User[] $users
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\UserMacAddress[] $user_mac_addresses_mac_address
 * @package App\Models
 * @mixin IdeHelperMacAddress
 */
class MacAddress extends \HaakCo\PostgresHelper\Models\BaseModels
{
    protected $table = 'public.mac_addresses';

    protected $casts = [
        'name' => 'macaddr'
    ];

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->belongsToMany(\HaakCo\Ip\Models\User::class, 'public.user_mac_addresses', 'mac_address_id')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    public function user_mac_addresses_mac_address()
    {
        return $this->hasMany(\HaakCo\Ip\Models\UserMacAddress::class, 'mac_address_id');
    }
}
