<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$atime = $file->atime();  //上次访问时间
var_dump($atime);

$ctime = $file->ctime();  //inode修改时间
var_dump($ctime);

$group = $file->group();  //文件所属用户组
var_dump($group);

$inode = $file->inode();  //文件的inode
var_dump($inode);

$mtime = $file->mtime();  //文件修改时间
var_dump($mtime);

$owner = $file->owner();  //文件的所有者
var_dump($owner);

$perms = $file->perms();  //文件的权限
var_dump($perms);

$size = $file->size();  //文件大小(字节数)
var_dump($size);

$type = $file->type();  //类型(可能的值有 fifo，char，dir，block，link，file 和 unknown。)
var_dump($type);
