/*获取服务器状态*/
function getSysStatus() {
    $.post('/admin/ajax/systus', function (data) {
        // am-progress-bar-warning am-progress-bar-danger
        // {
        //  "data":{
        //      "cpu":{"us_usage":"2.3","sys_usage":"1.3","free_usage":"96.3","ni":"0.0","wait_for_io":"0.0","hi":"0.0","si":"0.0","st":"0.0"},
        //      "disk":{"total":"40G","avail":"33G","used":"5.2G","free":"33G","usage":"14"},
        //      "memory":{"total":"996.082","used":"915.812","free":"80.270","buffers":"80.270","usage":91.87},
        //      "swap":{"total":"0.000","used":0,"free":0,"cached":"479.984M"},
        //      "task":{"total":"105","running":"1","sleeping":"104","stopped":"0","zombie":"0"}},
        //  "time":"2017-09-05 23:07"
        // }
        var cpu = data.data.cpu;
        var memory = data.data.memory;
        var swap = data.data.swap;
        var disk = data.data.disk;
        // cpu bar 设置
        var cpu_used = parseFloat(cpu.us_usage) + parseFloat(cpu.sys_usage);
        $('#cpu_used').html(cpu_used);
        $('#cpu_bars').css('width', cpu_used+'%');// 进度条
        var cpu_css = barCss(cpu_used);
        if (cpu_css) $('#cpu_bars').addClass(cpu_css);// 进度条样式
        // memory bar
        var memory_usage = parseFloat(memory.usage);
        barCss('mem_bar', memory_usage);
        $('#mem_used').html(memory.used);
        $('#mem_total').html(memory.total);
        // disk bar
        barCss('disk_bar', parseFloat(disk.usage));
        $('#disk_used').html(disk.used);
        $('#disk_total').html(disk.total);
        // swap bar
        $('#swap_used').html(swap.used);
        $('#swap_total').html(swap.total);
        barCss('swap_bar', swap.usage);
    });
}

function barCss(id_name, usage) {
    var limit = {
        min: parseFloat(50.00),
        max: parseFloat(80.00)
    };
    var css = '';
    if (usage <= limit.min && usage >= 0) css = '';
    if (usage > limit.min && usage <= limit.max) css = 'am-progress-bar-warning';
    if (usage > limit.max) css = 'am-progress-bar-danger';
    $('#'+id_name).css('width', usage + '%');
    if (css) {
        $('#'+id_name).addClass(css);
    }
}
