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
        'advert_image'   => ['require'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'advert_name.require' => '广告名称不能为空',
        'advert_name.max'     => '广告名称最多不能超过25个字符',
        'advert_image.require'   => '请上传广告图片',
    ];
}
