<?php

/** @noinspection UnknownInspectionInspection */

/** @noinspection PhpUnused */

namespace App\Libraries\Helper;

use App\Models\Ip;
use Cache;
use DB;
use Exception;
use Torann\GeoIP\GeoIP;
use Torann\GeoIP\Location;

class IpLibrary
{


    /**
     * @param string $ipName
     * @return Ip
     * @throws Exception
     */
    public static function getIp(string $ipName): Ip
    {
        return cache()->remember(
            'ip_lookup_' . $ipName,
            config('custom.cache_short_seconds'),
            static function () use ($ipName) {
                $ip = Cache::lock('lock_ip_lookup_' . $ipName, 2)
                    ->block(
                        2,
                        function () use ($ipName) {
                            return Ip::firstOrCreate(
                                [
                                    'name' => $ipName
                                ],
                                [
                                    'ip_type_id' => IpLibrary::isIpV4($ipName) ? 4 : 6,
                                    'is_public' => !(IpLibrary::isIpV4($ipName) && !IpLibrary::isPublicIpV4($ipName)),
                                ]
                            );
                        }
                    );

                if ($ip->wasRecentlyCreated) {
                    $sql = 'UPDATE
    ips
SET
    netmask = masklen(name)
WHERE
     is_public IS NULL
  OR netmask IS NULL';

                    DB::update($sql);
                }
                return $ip;
            }
        );
    }

    /**
     * @param string $ipAddress
     * @return bool
     */
    public static function isIpV4(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4
        );
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isPublicIpV4(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 |
            FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE
        );
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isIpV6(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV6
        );
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isIp(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP
        );
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public function isIpPublic(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE
        );
    }

    /**
     * @param null $ipAddress
     *
     * @return array|null
     */
    public function getGeoIpArray($ipAddress = null): ?array
    {
        return self::getGeoIp($ipAddress)->toArray();
    }

    /**
     * @param null $ipAddress
     *
     * @return GeoIP|Location
     */
    public static function getGeoIp($ipAddress = null)
    {
        if ($ipAddress === null) {
            $ipAddress = self::getMyIp();
        }
        return geoip($ipAddress);
    }

    /**
     * @return string
     */
    public static function getMyIp(): string
    {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $forwardedIpArray = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $forwardedIpArray = request()->ips();
        }

        $mainIp = request()->ip();

        if (array_key_exists(0, $forwardedIpArray) && !empty($forwardedIpArray[0])) {
            $mainIp = trim($forwardedIpArray[0]);
        }
        return trim($mainIp);
    }
}
