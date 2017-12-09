<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinksPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'site_url' => 'required|is_url|active_url',
            'title' => 'required|unique:links,title',
            'contact_name' => 'required|mbstr|nullable',
            'contact_qq' => 'required|is_qq|nullable',
            'contact_phone' => 'nullable|phone',
            'avatar' => 'file|mimes:jpeg,bmp,png,jpg',// 验证是否是成功上传文件|文件格式限制
        ];
    }

    public function messages()
    {
        return [
            'required'          => ':attribute 不能为空',
            'is_qq'             => 'QQ号码格式不正确',
            'is_url'             => 'URL格式不正确',
            'phone'             => '手机号格式不正确',
            'mbstr'             => ':attribute 请输入汉字',
            'avatar.file'       => '文件上传失败',
            'active_url'        => ':attribute 这是一个虚假链接'
        ];
    }
}
