<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Nette\Mail;

function sendEmail($email,$message)
{
    $mail = new Message;
    $mail->setFrom('MyBlog <206615407@qq.com>')
        ->addTo("$email")
        ->setSubject('MyBlog博客平台邮箱验证')
        ->setBody("$message");

    $mailer = new SmtpMailer([
        'host' => 'smtp.qq.com',
        'username' => '206615407@qq.com',
        'password' => 'zoudufpbwupobgjg',
    ]);
    $mailer->send($mail);
}
