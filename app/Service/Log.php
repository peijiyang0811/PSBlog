<?php
/**
 * Created by PhpStorm.
 * User: hhh
 * Date: 2017/9/25 0025
 * Time: 15:31
 */

namespace App\Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Log
{
    /**
     * @name 记录日志
     * 该日志记录器提供了RFC 5424中定义的八种日志级别：
     * emergency、alert、critical、error、warning、notice、info 和 debug。
     *      self::DEBUG     => 'DEBUG',
            self::INFO      => 'INFO',
            self::NOTICE    => 'NOTICE',
            self::WARNING   => 'WARNING',
            self::ERROR     => 'ERROR',
            self::CRITICAL  => 'CRITICAL',
            self::ALERT     => 'ALERT',
            self::EMERGENCY => 'EMERGENCY',
     * @param string $message 记录信息
     * @param array $content 格式化数组
     * @param string $level 日志等级
     * @param string $name 日志文件的名字
     *
     *
     * */
    public static function write(string $message, array $content = [], string $name = 'info',string $level = 'info'){
        $log = new Logger($name);
        $date = date('Ymd');
        // 日志目录
        $logDir = storage_path('logs' . DIRECTORY_SEPARATOR .$date);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        switch (strval($level)) {
            case 'info':
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::INFO));
                $log -> info($message, $content);
                break;
            case 'debug':
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::DEBUG));
                $log -> info($message, $content);
                break;
            case 'notice':
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::NOTICE));
                $log -> info($message, $content);
                break;
            case 'warning':
                // 警告
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::WARNING));
                $log -> info($message, $content);
                break;
            case 'error':
                // 错误
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::ERROR));
                $log -> info($message, $content);
                break;
            case 'alert':
                // 警报
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::ALERT));
                $log -> info($message, $content);
                break;
            case 'critical':
                // 危急的
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::CRITICAL));
                $log -> info($message, $content);
                break;
            case 'emrgency':
                // 紧急的
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::EMERGENCY));
                $log -> info($message, $content);
                break;
            default:
                $log->pushHandler(new StreamHandler($logDir . DIRECTORY_SEPARATOR . $name . '.log', Logger::INFO));
                $log -> info($message, $content);
                break;
        }

    }

}