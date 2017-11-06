<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValiPost extends FormRequest
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
            'user_name' => 'bail|required',
            'real_name' => 'required|mbstr',
            'user_phone' => 'required|phone',
            'user_email' => 'email',
            'avatar' => 'file|mimes:jpeg,bmp,png,jpg',// 验证是否是成功上传文件|文件格式限制
        ];
    }

    public function messages()
    {
        return [
            'required'          => ':attribute 不能为空',
            'avatar.file'       => '文件上传失败',
            'mimes'             => ':attribute 上传格式不正确',
            'email'             => '邮箱格式不正确',
            'phone'             => '手机号格式不正确',
            'mbstr'             => ':attribute 请输入汉字'
        ];
    }
}
