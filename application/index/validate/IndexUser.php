<?php

namespace app\index\validate;

use think\Validate;

class IndexUser extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'user_name'  => ['require', 'max' => 25],
        'user_pwd'   => [  'regex' => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,30}/'],
        'user_email' => ['regex' => '/([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})/'],
        'user_call' => ['regex' => '/1[34578]\d{9}/'],
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'user_name.require' => '名称不能为空',
        'user_name.max'     => '名称最多不能超过25个字符',
        'user_pwd.regex'   => '密码必须6-30位，有字母大小写和数字',
        'user_email.regex' => '请输入正确的邮箱',
        'user_call.regex' => '请输入正确的手机号',
    ];
}
