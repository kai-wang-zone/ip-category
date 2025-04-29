<?php
require __DIR__ . '/src/IpRangeCalculator.php';
use \KaiWangZone\ipCategory\IpRangeCalculator;

// 创建计算器实例
$ipCalculator = new IpRangeCalculator();

// 示例1: 计算标准C类网络
echo "=== 示例1: 192.168.1.10/24 ===<br/>";
$result = $ipCalculator->calculateRange('192.168.1.10', 24);
printIpRangeResult($result);

// 示例2: 计算A类大型网络
echo "<br/>=== 示例2: 10.0.0.5/8 ===<br/>";
$result = $ipCalculator->calculateRange('10.0.0.5', 8);
printIpRangeResult($result);

// 示例3: 计算小范围网络(/30)
echo "<br/>=== 示例3: 203.0.113.4/30 ===<br/>";
$result = $ipCalculator->calculateRange('203.0.113.4', 30);
printIpRangeResult($result);

// 示例4: 计算单个IP(/32)
echo "<br/>=== 示例4: 198.51.100.1/32 ===<br/>";
$result = $ipCalculator->calculateRange('198.51.100.1', 32);
printIpRangeResult($result);

// 示例5: 处理无效输入
echo "<br/>=== 示例5: 错误处理 ===<br/>";
try {
    // 无效IP地址
    $ipCalculator->calculateRange('256.168.1.1', 24);
} catch (InvalidArgumentException $e) {
    echo "错误1: " . $e->getMessage() . "<br/>";
}

try {
    // 无效子网掩码
    $ipCalculator->calculateRange('192.168.1.1', 33);
} catch (InvalidArgumentException $e) {
    echo "错误2: " . $e->getMessage() . "<br/>";
}

/**
 * 格式化输出IP范围结果
 */
function printIpRangeResult(array $result)
{
    echo "网络地址: " . $result['network'] . "<br/>";
    echo "广播地址: " . $result['broadcast'] . "<br/>";
    echo "第一个可用IP: " . $result['first_usable'] . "<br/>";
    echo "最后一个可用IP: " . $result['last_usable'] . "<br/>";
    echo "总IP数量: " . number_format($result['total_ips']) . "<br/>";
    echo "可用IP数量: " . number_format($result['usable_ips']) . "<br/>";
}
