<?php

namespace App\Models;

use Carbon\Carbon;
use HaakCo\PostgresHelper\Models\BaseModels\BaseModel;
use Illuminate\Database\Eloquent\Collection;

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
 * @property Collection|MonitorNaughtyIp[] $monitor_naughty_ips_ip
 * @property Collection|Monitor[] $monitors
 * @property Collection|MonitorIp[] $monitor_ips_ip
 * @property Collection|Domain[] $domains
 * @property Collection|DomainIp[] $domain_ips_ip
 * @property Collection|MonitorResultPing[] $monitor_result_pings_ip
 * @property Collection|DomainIpsHistory[] $domain_ips_histories_ip
 * @package App\Models
 * @mixin IdeHelperIp
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

    public function monitor_naughty_ips_ip()
    {
        return $this->hasMany(MonitorNaughtyIp::class, 'ip_id');
    }

    public function monitors()
    {
        return $this->belongsToMany(Monitor::class, 'monitor_ips', 'ip_id')
            ->withPivot('id', 'company_id')
            ->withTimestamps();
    }

    public function monitor_ips_ip()
    {
        return $this->hasMany(MonitorIp::class, 'ip_id');
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'domain_ips_history', 'ip_id')
            ->withPivot('id', 'added_at', 'removed_at');
    }

    public function domain_ips_ip()
    {
        return $this->hasMany(DomainIp::class, 'ip_id');
    }

    public function monitor_result_pings_ip()
    {
        return $this->hasMany(MonitorResultPing::class, 'ip_id');
    }

    public function domain_ips_histories_ip()
    {
        return $this->hasMany(DomainIpsHistory::class, 'ip_id');
    }
}
