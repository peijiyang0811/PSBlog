<!-- 菜单列表 -->
<ul class="sidebar-nav">

</ul>
<script>
    /*发送请求获取链接数据*/
    $(function(){
        $.ajax({
            url:'/api/getAdminLinks',
            type:"POST",
            dataType:"json",
            data:{type:2},
            success:function(json){
                if (json.code == 200) {
                    var url = window.location.protocol +'//'+window.location.host;
                    var html = '<li class="sidebar-nav-heading">菜单 <span class="sidebar-nav-heading-info">Menu</span></li>';
                    $.each(json.data, function(k, v){
                        if (v.url == 'javascript:;') {
                            html += '<li class="sidebar-nav-link"><a href="javascript:;" class="sidebar-nav-sub-title"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'<span class="jiantou am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span></a>';
                            if (v.child) {
                                html += '<ul class="sidebar-nav sidebar-nav-sub">';
                                $.each(v.child, function(kk, vv){
                                    html += '<li class="sidebar-nav-link menu_li"><a href="'+url+vv.url+'" class="menu_a" level="2"><span class="'+vv.icon+' sidebar-nav-link-logo"></span> '+vv.title+'</a></li>';
                                });
                                html += '</ul>';
                            }
                            html += '</li>';
                        } else {
                            html += '<li class="sidebar-nav-link menu_li"><a href="'+url+v.url+'" class="menu_a"  level="1"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'</a></li>';
                        }
                    });
                    $('.sidebar-nav').html(html);
                }
            },
            async: false// 设置同步方法
        });
        var curr_url = document.location.href;
        $('.menu_a').each(function(){
            var this_a = $(this);
            // 获取当前链接的url
            var this_url = this_a.attr('href');
            // 判断当前 href 是不是 当前链接
            if (curr_url.indexOf(this_url) != -1) {
                // 是当前url 所在的链接
                // 获取当前level 是一级链接还是 二级链接,其他的不用管
                var level = this_a.attr('level');
                switch (parseInt(level)) {
                    case 1:
                        // 是一级链接 增加 active样式
                        this_a.addClass('active');
                        break;
                    case 2:
                        // 是二级链接,对其自身元素和父级和爷爷级别的元素做样式添加
                        this_a.addClass('sub-active');
                        // 找他的爷爷元素 ul,让这个列表显示出来
                        this_a.parent().parent().css('display', 'block');
                        // 找他太太爷爷的第一个孩子的第二个孩子
                        this_a.parent().parent().parent().find('.jiantou').addClass('sidebar-nav-sub-ico-rotate');
                        this_a.parent().parent().parent().find('>a').addClass('active');
                        break;
                    default:
                        // 默认给一级链接样式
                        this_a.addClass('active');
                }
            }
        });
    });
    // 侧边二级菜单
    $(document).on('click', '.sidebar-nav-sub-title', function() {
        $(this).siblings('.sidebar-nav-sub').slideToggle(80)
            .end()
            .find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
    });

</script>
{{--ajax 请求后台给出所有链接数据--}}