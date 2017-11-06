<?php

namespace App\Console;

use App\Service\AliSMS;
use App\Service\Weather;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     * 定义应用的命令调度
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*  ->cron('* * * * *');	在自定义Cron调度上运行任务
                ->everyMinute();	每分钟运行一次任务
                ->everyFiveMinutes();	每五分钟运行一次任务
                ->everyTenMinutes();	每十分钟运行一次任务
                ->everyThirtyMinutes();	每三十分钟运行一次任务
                ->hourly();	每小时运行一次任务
                ->daily();	每天凌晨零点运行任务
                ->dailyAt('13:00');	每天13:00运行任务
                ->twiceDaily(1, 13);	每天1:00 & 13:00运行任务
                ->weekly();	每周运行一次任务
                ->monthly();	每月运行一次任务
                ->monthlyOn(4, '15:00');	每月4号15:00运行一次任务
                ->quarterly();	每个季度运行一次
                ->yearly();	每年运行一次
                ->timezone('America/New_York');	设置时区

                ->weekdays();	只在工作日运行任务
                ->sundays();	每个星期天运行任务
                ->mondays();	每个星期一运行任务
                ->tuesdays();	每个星期二运行任务
                ->wednesdays();	每个星期三运行任务
                ->thursdays();	每个星期四运行任务
                ->fridays();	每个星期五运行任务
                ->saturdays();	每个星期六运行任务
                ->between($start, $end);	基于特定时间段运行任务
                ->when(Closure);	基于特定测试运行任务
            介于时间的约束条件
                between 方法用于限定一天中特定时间段的任务执行：  ，unlessBetween 方法用于排除指定时间段任务的执行：
            任务钩子
                使用 before 和 after 方法，你可以指定在调度任务完成之前和之后要执行的代码
                    $schedule->command('emails:send')
                                 ->daily()
                                 ->before(function () {
                                     // 任务即将开始...
                                 })
                                 ->after(function () {
                                     // 任务已经完成...
                                });
            Ping URL

            使用 pingBefore 和 thenPing方法，调度器可以在任务完成之前和之后自动 ping 给定的 URL。
            该方法在通知外部服务时很有用，例如 Laravel Envoyer，在调度任务开始或完成的时候：
            $schedule->command('emails:send')
                                             ->daily()
                                             ->pingBefore($url)
                                             ->thenPing($url);

         * */
        // $schedule->command() -> daily(); 可以使用 command 方法通过命令名或类来调度一个 Artisan 命令：
        // 起床提醒
        $schedule -> call(function () {
            //调度闭包调用
            $now = time();// 当前时间戳
            $end = strtotime('2017-12-23');// 考研时间
            $start = strtotime('2011-12-24');//开始时间
            $time = date('Y-m-d');
            $n = date('N');// 1（表示星期一）到 7（表示星期天）
            switch (intval($n)) {
                case 1:
                    $time .= ' 周一';
                    break;
                case 2:
                    $time .= ' 周二';
                    break;
                case 3:
                    $time .= ' 周三';
                    break;
                case 4:
                    $time .= ' 周四';
                    break;
                case 5:
                    $time .= ' 周五';
                    break;
                case 6:
                    $time .= ' 周六';
                    break;
                case 7:
                    $time .= ' 周日';
                    break;
            }
            $love_days = ceil(($now - $start) / 86400);
            $kaoyan_days = ceil(($end - $now) / 86400) . ' days';
            //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
            //$weather = new Weather();
            //$temperature = $weather -> getWeather('开封');
            //                'time'              => $time. ', 今天: '.$temperature['weather'].','.$temperature['templow'].'℃ ~ '.$temperature['temphigh'].'℃, 今日穿衣指数:'.$temperature['index'][6]['ivalue'].'-'.$temperature['index'][6]['detail'],
            $message = [
                'name'              => 'MyQueen',
                'time'              => $time,
                'love_days'         => $love_days,
                'kaoyan_days'       => $kaoyan_days,
            ];
            $sms = new AliSMS();
            $sms -> send('18039670508', 'SMS_93355018', $message);
        }) -> dailyAt('06:00');
        // $schedule->exec('node /home/forge/script.js')->daily();exec 命令可用于发送命令到操作系统：
        // 午饭提醒
        $schedule -> call(function (){
            $time = date('H:i');
            $sms = new AliSMS();
            $sms -> send('18039670508', 'SMS_99860002', ['time' => $time]);
        }) -> dailyAt('11:30');
        // 18:00
        $schedule -> call(function (){
            $code = 'SMS_100085026';
            $phone = '18039670508';
            $time = date('Y-m-d H:i');
            $n = date('N');// 1（表示星期一）到 7（表示星期天）
            switch (intval($n)) {
                case 1:
                    $time .= ' 周一';
                    break;
                case 2:
                    $time .= ' 周二';
                    break;
                case 3:
                    $time .= ' 周三';
                    break;
                case 4:
                    $time .= ' 周四';
                    break;
                case 5:
                    $time .= ' 周五';
                    break;
                case 6:
                    $time .= ' 周六';
                    break;
                case 7:
                    $time .= ' 周日';
                    break;
            }
            $message = [
                'now'   => $time
            ];
            $sms = new AliSMS();
            $sms -> send($phone, $code, $message);
        }) -> dailyAt('17:30');
        // 23:30
        $schedule -> call(function (){
            $code = 'SMS_99905041';
            $phone = '18039670508';
            //$weather = new Weather();
            //$temperature = $weather -> getWeather('开封');
            //'now'   => $time . ', 明天 '.$temperature['daily'][1]['day']['weather'].' '.$temperature['daily'][1]['night']['templow'].'℃ ~ '.$temperature['daily'][1]['day']['temphigh'].' ℃。'
            $time = date('Y-m-d H:i');
            $n = date('N');// 1（表示星期一）到 7（表示星期天）
            switch (intval($n)) {
                case 1:
                    $time .= ' 周一';
                    break;
                case 2:
                    $time .= ' 周二';
                    break;
                case 3:
                    $time .= ' 周三';
                    break;
                case 4:
                    $time .= ' 周四';
                    break;
                case 5:
                    $time .= ' 周五';
                    break;
                case 6:
                    $time .= ' 周六';
                    break;
                case 7:
                    $time .= ' 周日';
                    break;
            }
            $message = [
                'now'   => $time
            ];
            $sms = new AliSMS();
            $sms -> send($phone, $code, $message);
        }) -> dailyAt('23:30');
        /*// 晚上天气预报
        $schedule -> call(function(){
            $weather = new Weather();
            $temperature = $weather -> getWeather('开封');
            $code = '';
            if ($temperature) {
                // 明天
                $message[] = $temperature['daily'][1]['sunrise'];//日出
                $message[] = $temperature['daily'][1]['sunset'];//日落
                $message[] = $temperature['daily'][1]['day']['weather'];//天气文本
                $message[] = $temperature['daily'][1]['day']['temphigh'];//最高温度
                $message[] = $temperature['daily'][1]['day']['winddirect'];//风向
                $message[] = $temperature['daily'][1]['day']['windpower'];//风速
                $message[] = $temperature['daily'][1]['night']['weather'];// 夜晚天气文本
                $message[] = $temperature['daily'][1]['night']['temphigh'];// 夜晚最高温度
                $message[] = $temperature['daily'][1]['night']['winddirect'];// 夜晚风向
                $message[] = $temperature['daily'][1]['night']['windpower'];// 夜晚风速
                $sms = new AliSMS();
                $sms -> send('18039670520,18039670508', $code, $message);
            }

        }) -> dailyAt('18:00');
        // 早上当天天气播报
        $schedule -> call(function(){
            $weather = new Weather();
            $temperature = $weather -> getWeather('开封');
            $code = '';
            if ($temperature)
            {
                $message[] = $temperature['weather'];// 天气文本
                $message[] = $temperature['templow'];// 最低温
                $message[] = $temperature['temphigh'];// 最高温
                $message[] = $temperature['winddirect'];// 风向
                $message[] = $temperature['windpower'];// 风速
                $message[] = $temperature['index'][1]['detail'];// 运动指数
                $message[] = $temperature['index'][2]['detail'];// 紫外线指数
                $message[] = $temperature['index'][6]['detail'];// 穿衣指数
                $message[] = $temperature['api']['pm2_5'];// pm2.5
                $message[] = $temperature['api']['quality'].'('.$temperature['api']['aqiinfo']['level'].')';// 空气质量
                $sms = new AliSMS();
                $sms -> send('18039670520,18039670508', $code, $message);
            }
        }) -> dailyAt('06:00');*/

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
