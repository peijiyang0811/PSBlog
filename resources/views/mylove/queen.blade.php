<!DOCTYPE>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>阳❤敏</title>
    <link type="text/css" rel="stylesheet" href="/js/love/default.css">
    <script type="text/javascript" src="/js/love/jquery.min.js"></script>
    <script type="text/javascript" src="/js/love/jscex.min.js"></script>
    <script type="text/javascript" src="/js/love/jscex-parser.js"></script>
    <script type="text/javascript" src="/js/love/jscex-jit.js"></script>
    <script type="text/javascript" src="/js/love/jscex-builderbase.min.js"></script>
    <script type="text/javascript" src="/js/love/jscex-async.min.js"></script>
    <script type="text/javascript" src="/js/love/jscex-async-powerpack.min.js"></script>
    <script type="text/javascript" src="/js/love/functions.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/love/love.js" charset="utf-8"></script>
    <style type="text/css">
        .STYLE1 {color: #666666}
        body {
            background-image: url("../../images/psfml/psfml.jpg");
            background-repeat: no-repeat;
        }

        .txt {
            color: #ff3597;
            font-weight: bold;
        }
    </style>
</head>
<body >
<audio autoplay><source src="/mp3/ppasn.mp3" type="audio/mp3" /></audio>
<div id="main">
    <div id="error">

    </div>
    <div id="wrap">
        <div id="text">
            <div id="code">
                <font color="#c20f80">
                    <span class="say txt">Hello,My dear :</span><br>
                    <span class="say"> </span><br>
                    <span class="say txt">There's your boy.I will say something to you.I'm stupid.I can't say sweet words.So i have never confessed to you.</span><br>
                    <span class="say"> </span><br>
                    <span class="say txt"></span><br>
                    <span class="say txt">My family is not rich as you.I can't give you anything.But i have my hands,i can earn for our home.I promise.</span><br>
                    <span class="say txt"></span><br>
                    <span class="say txt">卿不弃，君不离............</span>
                    <span class="say txt"></span><br>
                    <span class="say txt">I pledge my love.</span><br>
                    <span class="say"> </span><br>
                    <span class="say txt"><span class="space"></span> -- For my lady. The only one! --</span>
                </font>
            </div>
        </div>
        <div id="clock-box">
            <span class="STYLE1"></span><span style="color: #ff51ed; font-weight: 500 ">MyQueen</span>
            <span class="STYLE1">We are already in love……</span>
            <div id="clock" style="color: #3b89ef"></div>
        </div>
        <canvas id="canvas" width="1100" height="680"></canvas>
    </div>
</div>
<script>
    (function(){
        var canvas = $('#canvas');

        if (!canvas[0].getContext) {
            $("#error").show();
            return false;        }

        var width = canvas.width();
        var height = canvas.height();
        canvas.attr("width", width);
        canvas.attr("height", height);
        var opts = {
            seed: {
                x: width / 2 - 20,
                color: "rgb(190, 26, 37)",
                scale: 2
            },
            branch: [
                [535, 680, 570, 250, 500, 200, 30, 100, [
                    [540, 500, 455, 417, 340, 400, 13, 100, [
                        [450, 435, 434, 430, 394, 395, 2, 40]
                    ]],
                    [550, 445, 600, 356, 680, 345, 12, 100, [
                        [578, 400, 648, 409, 661, 426, 3, 80]
                    ]],
                    [539, 281, 537, 248, 534, 217, 3, 40],
                    [546, 397, 413, 247, 328, 244, 9, 80, [
                        [427, 286, 383, 253, 371, 205, 2, 40],
                        [498, 345, 435, 315, 395, 330, 4, 60]
                    ]],
                    [546, 357, 608, 252, 678, 221, 6, 100, [
                        [590, 293, 646, 277, 648, 271, 2, 80]
                    ]]
                ]]
            ],
            bloom: {
                num: 700,
                width: 1080,
                height: 650
            },
            footer: {
                width: 1200,
                height: 5,
                speed: 10
            }
        };

        var tree = new Tree(canvas[0], width, height, opts);
        var seed = tree.seed;
        var foot = tree.footer;
        var hold = 1;

        canvas.click(function(e) {
            var offset = canvas.offset(), x, y;
            x = e.pageX - offset.left;
            y = e.pageY - offset.top;
            if (seed.hover(x, y)) {
                hold = 0;
                canvas.unbind("click");
                canvas.unbind("mousemove");
                canvas.removeClass('hand');
            }
        }).mousemove(function(e){
            var offset = canvas.offset(), x, y;
            x = e.pageX - offset.left;
            y = e.pageY - offset.top;
            canvas.toggleClass('hand', seed.hover(x, y));
        });

        var seedAnimate = eval(Jscex.compile("async", function () {
            seed.draw();
            while (hold) {
                $await(Jscex.Async.sleep(10));
            }
            while (seed.canScale()) {
                seed.scale(0.95);
                $await(Jscex.Async.sleep(10));
            }
            while (seed.canMove()) {
                seed.move(0, 2);
                foot.draw();
                $await(Jscex.Async.sleep(10));
            }
        }));

        var growAnimate = eval(Jscex.compile("async", function () {
            do {
                tree.grow();
                $await(Jscex.Async.sleep(10));
            } while (tree.canGrow());
        }));

        var flowAnimate = eval(Jscex.compile("async", function () {
            do {
                tree.flower(2);
                $await(Jscex.Async.sleep(10));
            } while (tree.canFlower());
        }));

        var moveAnimate = eval(Jscex.compile("async", function () {
            tree.snapshot("p1", 240, 0, 610, 680);
            while (tree.move("p1", 500, 0)) {
                foot.draw();
                $await(Jscex.Async.sleep(10));
            }
            foot.draw();
            tree.snapshot("p2", 500, 0, 610, 680);

            // 会有闪烁不得意这样做, (＞﹏＜)
            canvas.parent().css("background", "url(" + tree.toDataURL('image/png') + ")");
            canvas.css("background", "#ffe");
            $await(Jscex.Async.sleep(300));
            canvas.css("background", "none");
        }));

        var jumpAnimate = eval(Jscex.compile("async", function () {
            var ctx = tree.ctx;
            while (true) {
                tree.ctx.clearRect(0, 0, width, height);
                tree.jump();
                foot.draw();
                $await(Jscex.Async.sleep(25));
            }
        }));

        var textAnimate = eval(Jscex.compile("async", function () {
            var together = new Date();
            together.setFullYear(2011, 10, 24);          //时间年月日 Thu Nov 24 2011 10:10:01 GMT+0800 (中国标准时间) 月份 错了1个数字
            together.setHours(10);                      //小时
            together.setMinutes(10);                    //分钟
            together.setSeconds(1);                 //秒前一位
            together.setMilliseconds(0);                //秒第二位
            console.log(together);
            $("#code").show().typewriter();
            $("#clock-box").fadeIn(500);
            while (true) {
                timeElapse(together);
                $await(Jscex.Async.sleep(1000));
            }
        }));

        var runAsync = eval(Jscex.compile("async", function () {
            $await(seedAnimate());
            $await(growAnimate());
            $await(flowAnimate());
            $await(moveAnimate());

            textAnimate().start();

            $await(jumpAnimate());
        }));

        runAsync().start();
    })();
</script>
</body>
</html>