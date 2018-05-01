<?php

###这些是往window的cmd页面中文乱码的解决办法
// define('UTF32_BIG_ENDIAN_BOM', chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
// define('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
// define('UTF16_BIG_ENDIAN_BOM', chr(0xFE) . chr(0xFF));
// define('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
// define('UTF8_BOM', chr(0xEF) . chr(0xBB) . chr(0xBF));
header("Content-type:text/html;charset=utf-8");
//获取文件夹列表（存成）
$dir_list = require_once 'config.php';

//获取文件列表
foreach($dir_list as $key => $value){
		//循环读取文件夹
		if ($handle = opendir($value)) {
		  while (false !== ($file = readdir($handle))){
				if ($file != "." && $file != "..")
				{
            $file1 = explode('.', $file);
            if ($file1[1] == 'md') {
              $file_list[$value][] = iconv('GB2312', 'UTF-8', $file1[0]);
            }
				}
			}
		}
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>2018-study</title>
        <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
           *{margin: 0;padding: 0}
           html, body{height: 100%}
           #title{
              float: left;
              width:15%;
              height: 100%;
              border-right: 2px solid #ccc;
              overflow: auto;
			 }
           #con{float: right;
                height:100%;
                width: 85%;
                overflow: auto;
				padding: 30px;
			 }
			.fixed-btn{		  
				position: fixed;
				right: 1%;
				bottom: 5%;
				width: 40px;
				border: 1px solid #eee;
				background-color: white;
				font-size: 24px;
				z-index: 1040;
				text-align: center;
			}
        </style>
    </head>
    <body>
    <div id="title">
      <ul class="nav nav-pills nav-stacked" >
        <?php foreach ($dir_list as $key => $value): ?>
           <li><a href="#"><?php echo $value ?></a></li>
            <ul class="nav nav-pills nav-stacked" >
                <?php $list = $file_list[$value];
                foreach ($list as $key1 => $value1): ?>
                    <li style="padding:0 0 0 20px" file_name="<?php echo $value.'/'.$value1 ?>" class="file"><a href="javascript:void(0)"><?php echo $value1 ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
      </ul>
    </div>
    <div id="con">
		<div id="content"></div>
		<div class="fixed-btn">	
			<span class="glyphicon glyphicon-arrow-up"></span>
		</div>
	</div>
    <script>
      $('.file').click(function(){
        var file_name = $(this).attr('file_name');
        // alert(file_name);
        $.ajax({
          url:"./file.php",
		  type:'post',
          data: {
            'file_name': file_name
          },
          success:function(result){
            // alert(JSON.stringify(result));
						$('#con>#content').html(JSON.parse(result));
          }
        });
      })

	    $(document).ready(function(){
		 $('#con').scroll(function () {
				if ($(this).scrollTop() > 50) {
					$('.fixed-btn').fadeIn();
				} else {
					$('.fixed-btn').fadeOut();
				}
			});
			// scroll body to 0px on click
			$('.fixed-btn').click(function () {
				$('.fixed-btn').fadeOut();
				$('#con').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
	});
    </script>
</body>
</html>
