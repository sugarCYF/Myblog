<?php

namespace app\admin\validate;

use think\Validate;

class SensitiveWords extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'word'  => ['require', 'max' => 25],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'word.require' => '名称不能为空',
        'word.max'     => '名称最多不能超过25个字符',
    ];
}
