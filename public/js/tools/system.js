var pass_hash = 'base64:HpDOeZbN7XEyUKMAiUBlryYPQmj1CT5igmacMI0ov3c=';
function alertInfo(modal, title, value) {
    modal.find('.am-modal-hd').html(title);
    modal.find('.am-modal-bd').html(value);
    modal.modal();
}
function is_mobile(mobile) {
    var preg = /^13[\d]{9}$|^14[4,7,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^166\d{8}$|^17[^4|^9]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$/;
    if (!preg.test(mobile)) return false;
    return true;
}
function checkPass(password) {
    var check = /[\w]{6,18}/;
    if (!check.test(password)) return false;
    return true;
}
function login_validate(tagObj, type, message) {
    tagObj.focus(function () {
        $('.user_error').remove();
        tagObj.removeClass('am-field-error');
    });
    tagObj.blur(function () {
        //失去焦点
        var value = tagObj.val();
        if (value.length < 1) {
            tagObj.addClass('am-field-error');
            tagObj.after('<span class="user_error am-badge am-badge-danger  am-radius">'+message+'不能为空</span>');
        } else {
            var bool = false;
            var vali_message = '';
            var url = '';
            switch (type){
                case 'phone':
                    if (is_mobile(value)) {
                        url = '/admin/api/getavatar';
                        bool = true;
                    } else {
                        vali_message = '手机号格式不正确';
                    }
                    break;
                case 'password':
                    if (checkPass(value)){
                        bool = true;
                    } else {
                        vali_message = '请输入6~18位由字母,字符,数字任意组合的密码';
                    }
                    break;
                default:
                    if (is_mobile(value)) bool = true;
                    break;
            }
            if (bool) {
                tagObj.addClass('am-field-valid');
                if (url) {
                    // 根据用户名获取头像
                    $.post(url, {user:value, type:1}, function (json) {
                        if (json.code != 200) {
                            alertInfo('登录错误', json.message);
                            return;
                        }
                        tagObj.attr('is_ok', '1');
                        // 成功后,更换logo 为头像
                        $('.theme-white .tpl-login-logo .am-img-thumbnail').attr('src', json.data);
                    });
                } else if(type == 'password') {
                    // 密码字段
                    tagObj.attr('is_ok', '1');
                }
            } else {
                tagObj.addClass('am-field-error');
                tagObj.after('<span class="user_error am-badge am-badge-danger  am-radius">'+vali_message+'</span>');
            }
        }

    });
}
function loading() {
    var progress = $.AMUI.progress;
    progress.start();
    progress.set(0.5);
    progress.inc(0.3);
    function end() {
        progress.done(true);
    }
    setTimeout('end()',3000);

}
function reloadUrl() {
    window.location.reload();
}
/*
    统计字符类型
*/
function bitTotal(num) {
    modes = 0;
    for (i = 0; i < 4; i++) {
        if (num & 1) modes++;
        num >>>= 1;
    }
    return modes;
}
/*
    返回密码的强度级别
*/
function checkStrong(sPW) {
    if (sPW.length <= 4)
        return 0; //密码太短
    Modes = 0;
    for (i = 0; i < sPW.length; i++) {
        //测试每一个字符的类别并统计一共有多少种模式.
        Modes |= CharMode(sPW.charCodeAt(i));
    }
    return bitTotal(Modes);
}
//清除左边空格
function js_ltrim(deststr)
{
    if(deststr==null)return "";
    var pos=0;
    var retStr=new String(deststr);
    if (retStr.lenght==0) return retStr;
    while (retStr.substring(pos,pos+1)==" ") pos++;
    retStr=retStr.substring(pos);
    return(retStr);
}
//清除右边空格
function js_rtrim(deststr)
{
    if(deststr==null)return "";
    var retStr=new String(deststr);
    var pos=retStr.length;
    if (pos==0) return retStr;
    while (pos && retStr.substring(pos-1,pos)==" " ) pos--;
    retStr=retStr.substring(0,pos);
    return(retStr);
}
//清除左边和右边空格
function js_trim(deststr)
{
    if(deststr==null)return "";
    var retStr=new String(deststr);
    var pos=retStr.length;
    if (pos==0) return retStr;
    retStr=js_ltrim(retStr);
    retStr=js_rtrim(retStr);
    return retStr;
}
//obj:数据对象
//dispStr :失败提示内容显示字符串
function checkUrlValid( obj, dispStr)
{
    if(obj == null)
    {
        alert("传入对象为空");
        return false;
    }
    var str = obj.value;
    var urlpatern0 = /^((ht|f)tps?):\/\/.+$/i;
    if(!urlpatern0.test(str))
    {
        alert(dispStr+"不合法：必须以'http:\/\/'或'https:\/\/'开头!");
        obj.focus();
        return false;
    }
    var urlpatern2= /^((ht|f)tps?):\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?.+$/i;
    if(!urlpatern2.test(str))
    {
        alert(dispStr+"端口号必须为数字且应在1－65535之间!");
        obj.focus();
        return false;
    }

    var urlpatern1 =/^((ht|f)tps?):\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?(\/((\.)?(\?)?=?&?[a-zA-Z0-9_-](\?)?)*)*$/i;
    if(!urlpatern1.test(str))
    {
        alert(dispStr+"不合法,请检查!");
        obj.focus();
        return false;
    }
    var s = "0";
    var t =0;
    var re = new RegExp(":\\d+","ig");
    while((arr = re.exec(str))!=null)
    {
        s = str.substring(RegExp.index+1,RegExp.lastIndex);
        if(s.substring(0,1)=="0")
        {
            alert(dispStr+"端口号不能以0开头!");
            obj.focus();
            return false;
        }
        t = parseInt(s);
        if(t<1 || t >65535)
        {
            alert(dispStr+"端口号必须为数字且应在1－65535之间!");
            obj.focus();
            return false;
        }
    }
    return true;
}