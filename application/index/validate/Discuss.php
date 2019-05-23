<?php

namespace app\index\validate;

use think\Validate;

class Discuss extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'discuss_content'  => ['require', 'max' => 100],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'discuss_content.require' => '评论内容不能为空',
        'discuss_content.max'     => '评论内容最多不能超过50个字符',
    ];
}
