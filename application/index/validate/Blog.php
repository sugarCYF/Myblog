<?php

namespace app\index\validate;

use think\Validate;

class Blog extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'blog_title'  => ['require', 'max' => 50],
        'blog_content'   => [  'require'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'blog_title.require' => '博文标题不能为空',
        'blog_title.max'     => '博文标题最多不能超过50个字符',
        'blog_content.regex'   => '博文不能为空',
    ];
}
