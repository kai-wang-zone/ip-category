<?php

namespace KaiWangZone\ipCategory;

class IpConverter
{
    /**
     * 将IP字符串（如 "192.168.1.1"）转换为十进制数字
     */
    public static function ipToDecimal($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("Invalid IP address: {$ip}");
        }
        return ip2long($ip);
    }

    /**
     * 将十进制数字转换回IP字符串
     */
    public static function decimalToIp($decimal)
    {
        return long2ip($decimal);
    }

    /**
     * 将IP字符串分割成数组（如 ["192", "168", "1", "1"]）
     */
    public static function ipToArray($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("Invalid IP address: {$ip}");
        }
        return explode('.', $ip);
    }

    /**
     * 将IP数组合并成字符串（如 "192.168.1.1"）
     */
    public static function arrayToIp($ipParts)
    {
        if (count($ipParts) !== 4) {
            throw new \InvalidArgumentException("IP array must have 4 parts");
        }
        return implode('.', $ipParts);
    }
}