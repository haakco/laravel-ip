<?php

/** @noinspection UnknownInspectionInspection */

declare(strict_types=1);

namespace HaakCo\Ip\Libraries;

use function array_key_exists;
use Exception;
use HaakCo\Ip\Models\Ip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Torann\GeoIP\GeoIP;
use Torann\GeoIP\Location;

class IpLibrary
{
    /**
     * @throws Exception
     */
    public static function getIp(string $ipName): Ip
    {
        return cache()->remember(
            'ip_lookup_'.$ipName,
            config('custom.cache_short_seconds'),
            static function () use ($ipName): Ip {
                $ip =
                    Cache::lock('lock_ip_lookup_'.$ipName, 2)
                        ->block(2, function () use ($ipName): Ip {
                            return Ip::firstOrCreate([
                                'name' => $ipName,
                            ]);
                        });

                return $ip;
            },
        );
    }

    public static function isIpV4(string $ipAddress): bool
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    public static function isPublicIpV4(string $ipAddress): bool
    {
        return (bool) filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
        );
    }

    public static function isIpV6(string $ipAddress): bool
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public static function isIp(string $ipAddress): bool
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP);
    }

    public function isIpPublic(string $ipAddress): bool
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * @param  null  $ipAddress
     */
    public function getGeoIpArray($ipAddress = null): ?array
    {
        return self::getGeoIp($ipAddress)
            ->toArray();
    }

    public static function getGeoIp(?string $ipAddress = null): GeoIP|Location
    {
        if (null === $ipAddress) {
            $ipAddress = self::getMyIp();
        }

        return geoip($ipAddress);
    }

    public static function getMyIp(): string
    {
        $request = request();
        $forwardedIpArray = [];
        $mainIp = '';
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $forwardedIpArray = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        } elseif ($request instanceof Request) {
            $forwardedIpArray = $request->getClientIps();
        }

        if ($request instanceof Request) {
            $mainIp = $request->getClientIp();
        }

        if (array_key_exists(0, $forwardedIpArray) && ! empty($forwardedIpArray[0])) {
            $mainIp = trim((string) $forwardedIpArray[0]);
        }

        return trim((string) $mainIp);
    }
}
