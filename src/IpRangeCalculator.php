<?php

namespace KaiWangZone\ipCategory;

class IpRangeCalculator
{
    /**
     * 计算IP地址范围
     *
     * @param string $ip IPv4地址 (如: 192.168.1.10)
     * @param int $mask 子网掩码 (0-32)
     * @return array 包含网络信息的数组
     * @throws InvalidArgumentException
     */
    public function calculateRange($ip, $mask)
    {
        $this->validateInput($ip, $mask);

        $network = $this->calculateNetworkAddress($ip, $mask);
        $broadcast = $this->calculateBroadcastAddress($ip, $mask);

        return [
            'network' => $network,
            'broadcast' => $broadcast,
            'first_usable' => $this->getFirstUsableIp($network, $mask),
            'last_usable' => $this->getLastUsableIp($broadcast, $mask),
            'total_ips' => $this->calculateTotalIps($mask),
            'usable_ips' => $this->calculateUsableIps($mask)
        ];
    }

    /**
     * 验证输入参数
     */
    private function validateInput($ip, $mask)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new \InvalidArgumentException('Invalid IPv4 address');
        }

        if ($mask < 0 || $mask > 32) {
            throw new \InvalidArgumentException('Mask must be between 0 and 32');
        }
    }

    /**
     * 计算网络地址
     */
    private function calculateNetworkAddress($ip, $mask)
    {
        $ipLong = ip2long($ip);
        $maskLong = ~((1 << (32 - $mask)) - 1);
        $networkLong = $ipLong & $maskLong;
        return long2ip($networkLong);
    }

    /**
     * 计算广播地址
     */
    private function calculateBroadcastAddress($ip, $mask)
    {
        $ipLong = ip2long($ip);
        $maskLong = (1 << (32 - $mask)) - 1;
        $broadcastLong = $ipLong | $maskLong;
        return long2ip($broadcastLong);
    }

    /**
     * 获取第一个可用IP
     */
    private function getFirstUsableIp($network, $mask)
    {
        // 处理/31和/32的特殊情况
        if ($mask >= 31) {
            return $network;
        }
        return long2ip(ip2long($network) + 1);
    }

    /**
     * 获取最后一个可用IP
     */
    private function getLastUsableIp($broadcast, $mask)
    {
        // 处理/31和/32的特殊情况
        if ($mask >= 31) {
            return $broadcast;
        }
        return long2ip(ip2long($broadcast) - 1);
    }

    /**
     * 计算总IP数量
     */
    private function calculateTotalIps($mask)
    {
        return pow(2, 32 - $mask);
    }

    /**
     * 计算可用IP数量
     */
    private function calculateUsableIps($mask)
    {
        $total = $this->calculateTotalIps($mask);

        // 处理/31和/32的特殊情况
        if ($mask >= 31) {
            return $total;
        }
        return max($total - 2, 0);
    }

    /**
     * 检查IP是否在指定范围内
     *
     * @param string $ip 要检查的IP
     * @param string $rangeIp 范围IP
     * @param int $mask 子网掩码
     * @return bool
     */
    public function isIpInRange($ip, $rangeIp, $mask)
    {
        $this->validateInput($ip, $mask);
        $this->validateInput($rangeIp, $mask);

        $network = $this->calculateNetworkAddress($rangeIp, $mask);
        $broadcast = $this->calculateBroadcastAddress($rangeIp, $mask);

        $ipLong = ip2long($ip);
        $networkLong = ip2long($network);
        $broadcastLong = ip2long($broadcast);

        return ($ipLong >= $networkLong) && ($ipLong <= $broadcastLong);
    }
}