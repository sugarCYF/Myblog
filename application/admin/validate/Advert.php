<?php

namespace app\admin\validate;

use think\Validate;

class Advert extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'advert_name'  => ['require', 'max' => 25],
        'advert_image'   => [  'regex' => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,30}/'],
        'advert_remarks' => 'number',
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
    ];
}
