$(function(){
    setInterval('timer()', 1000);
});
// 每隔一秒刷新一下时间
function timer(){
    // 当前时间
    var date = new Date();
    /*date.getYear(); //获取当前年份(2位)
    date.getFullYear(); //获取完整的年份(4位,2014)
    date.getMonth(); //获取当前月份(0-11,0代表1月)
    date.getDate(); //获取当前日(1-31)
    date.getDay(); //获取当前星期X(0-6,0代表星期天)
    date.getTime(); //获取当前时间(从1970.1.1开始的毫秒数)
    date.getHours(); //获取当前小时数(0-23)
    date.getMinutes(); //获取当前分钟数(0-59)
    date.getSeconds(); //获取当前秒数(0-59)
    date.getMilliseconds(); //获取当前毫秒数(0-999)
    date.toLocaleDateString(); //获取当前日期 如 2014年6月25日
    date.toLocaleTimeString(); //获取当前时间 如 下午4:45:06
    date.toLocaleString(); //获取日期与时间 如 2014年6月25日 下午4:45:06*/
    var y = date.getFullYear();
    var m = parseInt(date.getMonth()) + 1;
    var d = date.getDate();
    var week = '周日';
    var dd = date.getDay();
    if (dd == 1) week = '周一';
    if (dd == 2) week = '周二';
    if (dd == 3) week = '周三';
    if (dd == 4) week = '周四';
    if (dd == 5) week = '周五';
    if (dd == 6) week = '周六';

    var h = date.getHours().toString();
    if (h < 10) h = '0'+ h;

    var min = date.getMinutes().toString();
    if (min < 10) min = '0'+ min;

    var s = date.getSeconds().toString();
    if (s < 10) s = '0' + s;

    var t = h + ':' + min + ':' + s;
    var time = '&nbsp;&nbsp;&nbsp;&nbsp;' + y + '-' + m + '-' + d + ' ' + week + ' ' + t;
    $('.am-icon-clock-o').html(time);
}