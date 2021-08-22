<?php

/**
 * Created by Reliese Model.
 */

namespace HaakCo\Ip\Models;



/**
 * Class Ip
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $ip_type_id
 * @property boolean $is_public
 * @property string $name
 * @property int $netmask
 * @property \HaakCo\Ip\Models\IpType $ip_type
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\MonitorNaughtyIp[] $monitor_naughty_ips_ip
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\Monitor[] $monitors
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\MonitorIp[] $monitor_ips_ip
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\Domain[] $domains
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\DomainIp[] $domain_ips_ip
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\User[] $users
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\MonitorResultPing[] $monitor_result_pings_ip
 * @property \Illuminate\Database\Eloquent\Collection|\HaakCo\Ip\Models\DomainIpsHistory[] $domain_ips_histories_ip
 * @package App\Models
 * @mixin IdeHelperIp
 */
class Ip extends \HaakCo\PostgresHelper\Models\BaseModels
{
    protected $table = 'public.ips';

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
        return $this->belongsTo(\HaakCo\Ip\Models\IpType::class, 'ip_type_id');
    }

    public function monitor_naughty_ips_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\MonitorNaughtyIp::class, 'ip_id');
    }

    public function monitors()
    {
        return $this->belongsToMany(\HaakCo\Ip\Models\Monitor::class, 'public.monitor_ips', 'ip_id')
                    ->withPivot('id', 'company_id')
                    ->withTimestamps();
    }

    public function monitor_ips_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\MonitorIp::class, 'ip_id');
    }

    public function short_url_trackings_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\ShortUrlTracking::class, 'ip_id');
    }

    public function short_url_trackings_proxy_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\ShortUrlTracking::class, 'proxy_ip_id');
    }

    public function domains()
    {
        return $this->belongsToMany(\HaakCo\Ip\Models\Domain::class, 'public.domain_ips_history', 'ip_id')
                    ->withPivot('id', 'added_at', 'removed_at');
    }

    public function domain_ips_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\DomainIp::class, 'ip_id');
    }

    public function users()
    {
        return $this->belongsToMany(\HaakCo\Ip\Models\User::class, 'public.user_ips', 'ip_id')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    public function user_ips_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\UserIp::class, 'ip_id');
    }

    public function monitor_result_pings_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\MonitorResultPing::class, 'ip_id');
    }

    public function domain_ips_histories_ip()
    {
        return $this->hasMany(\HaakCo\Ip\Models\DomainIpsHistory::class, 'ip_id');
    }
}
