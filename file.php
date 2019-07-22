<?php

require_once './Michelf/Markdown.php';

//获取文件名称
$file_name = $_POST['file_name'];

//$content = file_get_contents(iconv('utf-8', 'gbk', './'. $file_name. '.md'));

$content = file_get_contents($file_name. '.md');

$my_html = Markdown::defaultTransform($content);

echo json_encode($my_html);
