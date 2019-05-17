<?php

namespace app\admin\validate;

use think\Validate;

class Link extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'link_title'  => ['require', 'max' => 25],
        'link_content'   => [  'regex' => '/((https|http|ftp|rtsp|mms){0,1}(:\/\/){0,1})www\.(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+/'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'link_title.require' => '链接标题不能为空',
        'link_content.max'     => '链接标题最多不能超过25个字符',
        'link_addtime.regex'   => '请输入符合格式的网址例如：https://www.baidu.com',
    ];
}
