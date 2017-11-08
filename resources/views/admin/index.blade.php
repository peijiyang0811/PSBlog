<?php
/**
 * Created by PhpStorm.
 * User: peijiyang
 * Des:后台首页
 * Date: 2017/9/4 0004
 * Time: 15:38
 */
?>

@extends('layout.admin')
@section('title', '首页')
@section('contents')
    <div class="container-fluid am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                <div class="page-header-heading">
                    {{--<span class="am-icon-home page-header-heading-icon"></span>--}}
                    欢迎您: 裴纪阳 来到--<small>PS伐木累后台管理系统</small>
                </div>
            </div>
            <div class="am-u-lg-4 tpl-index-settings-button">
                <button type="button" class="page-header-button"><span class="am-icon-paint-brush"></span> 系统设置</button>
            </div>
        </div>

    </div>

    <div class="row-content am-cf">
        {{--一些统计信息--}}
        <div class="row  am-cf">
            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                <div class="widget widget-primary am-cf">
                    <div class="widget-statistic-header">
                        月度博文发布总数:
                    </div>
                    <div class="widget-statistic-body">
                        <div class="widget-statistic-value">
                            +30
                        </div>
                        <div class="widget-statistic-description">
                            <button type="button" class="am-btn am-btn-success am-round">查看列表</button>
                        </div>
                        <span class="widget-statistic-icon am-icon-book"></span>
                    </div>
                </div>
            </div>
            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                <div class="widget widget-purple am-cf">
                    <div class="widget-statistic-header">
                        本季度利润
                    </div>
                    <div class="widget-statistic-body">
                        <div class="widget-statistic-value">
                            ￥27,294
                        </div>
                        <div class="widget-statistic-description">
                            本季度比去年多收入 <strong>2593元</strong> 人民币
                        </div>
                        <span class="widget-statistic-icon am-icon-support"></span>
                    </div>
                </div>
            </div>
        </div>
        {{--服务器状态统计--}}
        <div class="row am-cf">
            <div class="am-u-sm-12 am-u-md-12">
                <div class="widget am-cf" style="min-height: 350px">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">专用服务器负载</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body widget-body-md am-fr">

                        <div class="am-progress-title">
                            CPU 使用率
                            <span class="am-fr am-progress-title-more"><span id="cpu_used">0</span>% / 100%</span>
                        </div>
                        <div class="am-progress">
                            <div class="am-progress-bar" id="cpu_bars"></div>
                        </div>
                        <div class="am-progress-title">内存 使用率 <span class="am-fr am-progress-title-more"><span id="mem_used">0</span>MB / <span id="mem_total">0</span>MB</span></div>
                        <div class="am-progress">
                            <div class="am-progress-bar" id="mem_bar"></div>
                        </div>
                        <div class="am-progress-title">SWAP 使用率 <span class="am-fr am-progress-title-more"><span id="swap_used">0</span>MB / <span id="swap_total">0</span>MB</span></div>
                        <div class="am-progress">
                            <div class="am-progress-bar" id="swap_bar"></div>
                        </div>
                        <div class="am-progress-title">硬盘 使用率 <span class="am-fr am-progress-title-more"><span id="disk_used">0</span>GB / <span id="disk_total">0</span>GB</span></div>
                        <div class="am-progress">
                            <div class="am-progress-bar" id="disk_bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--当前操作系统及服务器信息展示--}}
        <div class="row am-cf">
            <div class="am-u-sm-6 am-u-md-6 am-u-lg-6">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">当前登录系统信息</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body  widget-body-lg am-fr">

                        <table width="100%" class="am-table am-table-compact tpl-table-black " id="example-r">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeX">
                                <td>操作系统</td>
                                <td>{{getOS()}}</td>
                            </tr>
                            <tr class="gradeX">
                                <td>当前ip</td>
                                <td>{{getClientIp()}}</td>
                            </tr>
                            <tr class="gradeX">
                                <td>浏览器版本</td>
                                <td>{{getAgentInfo()}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="am-u-sm-6 am-u-md-6 am-u-lg-6">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">服务器信息</div>
                        <div class="widget-function am-fr">
                            <a href="javascript:;" class="am-icon-cog"></a>
                        </div>
                    </div>
                    <div class="widget-body  widget-body-lg am-fr">

                        <table width="100%" class="am-table am-table-compact tpl-table-black " id="example-r">
                            <tbody>
                                <tr class="gradeX">
                                    <?php
                                        $searchURL = 'http://ip.taobao.com/service/getIpInfo.php';
                                        $result = curl($searchURL, ['ip' => '47.92.29.51'], 0, 0);
                                        //{"code":0,"data":{"country":"\u4e2d\u56fd","country_id":"CN","area":"\u534e\u5317","area_id":"100000","region":"\u6cb3\u5317\u7701","region_id":"130000","city":"\u5f20\u5bb6\u53e3\u5e02","city_id":"130700","county":"","county_id":"-1","isp":"\u963f\u91cc\u5df4\u5df4","isp_id":"100098","ip":"47.92.29.199"}}
                                        if ($result['body']['code'] == 1) $area = '未知地址';
                                    ?>
                                    <td>服务器位置</td>
                                    <td>{{$result['body']['data']['country'].'-'.$result['body']['data']['area'].'-'.$result['body']['data']['region'].'-'.$result['body']['data']['city']}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>提供商</td>
                                    <td>{{$result['body']['data']['isp']}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>项目根路径</td>
                                    <td>{{base_path()}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>操作系统版本</td>
                                    <td>{{php_uname('a')}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>服务器</td>
                                    <td>{{$_SERVER['SERVER_SOFTWARE']}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>PHP版本</td>
                                    <td>{{PHP_VERSION}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <?php $db = DB::select('select VERSION() as mysql_version')[0];?>
                                    <td>MYSQL版本</td>
                                    <td>{{$db -> mysql_version}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>PHP_SAPI</td>
                                    <td>{{PHP_SAPI}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>上传最大限制</td>
                                    <td>{{ini_get('upload_max_filesize')}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>时区设置</td>
                                    <td>{{ini_get('date.timezone')}}</td>
                                </tr>
                                <tr class="gradeX">
                                    <td>PHP运行内存限制</td>
                                    <td>{{ini_get('memory_limit')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{asset('/admin/js/sysStatus.js')}}"></script>
    <script>
        getSysStatus();
        $(function(){
            // 每2秒执行一次
            setInterval("getSysStatus()", 2000);
        });
    </script>
@endsection