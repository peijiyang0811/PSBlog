function validate(type, value) {
    var val = {
        // /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
        email:/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
        password:/\w{6,18}/,
        qq:/^[1-9][0-9]{4,9}$/,
        phone:/^13[\d]{9}$|^14[4,7,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^166\d{8}$|^17[^4|^9]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$/,
        mbstr:/^[\u4e00-\u9fa5]{2,10}$/,
        url:/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/
    };
    var bool = false;
    switch (type) {
        case 'email':
            if (val.email.test(value)) bool = true;
            break;
        case 'password':
            if (val.password.test(value)) bool = true;
            break;
        case 'phone':
            if (val.phone.test(value)) bool = true;
            break;
        case 'qq':
            if (val.qq.test(value)) bool = true;
            break;
        case 'mbstr':
            if (val.mbstr.test(value)) bool = true;
            break;
        case 'url':
            if (val.url.test(value)) bool = true;
            break;
    }
    return bool;
}

