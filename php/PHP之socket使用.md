## PHP中socket的使用

 最近做了一个项目需要使用socket收发数据，于是学习了PHP中socket的用法，特此记录下来，便于以后查阅。（当然只是个demo形式的）

### socket是什么？

 我的理解：socket是一套API接口，封装了TCP/IP协议族。

### PHP中如何使用socket？

 前任栽树，后人乘凉。在PHP中，通过官方自带的`Sockets`扩展库，`Stream 函数 `扩展库可以创建多种协议的服务器和客户端。`Stream 函数`扩展库是封装好了的`Sockets`扩展库，更容易使用。

### PHP使用socket实例

#### TCP通信过程

 	/*
	  +-------------------------------
	  *    @socket通信整个过程
	  +-------------------------------
	  *    @socket_create
	  *    @socket_bind
	  *    @socket_listen
	  *    @socket_accept
	  *    @socket_read
	  *    @socket_write
	  *    @socket_close
	  +--------------------------------
	  */
	 
##### TCP服务端

	<?php
	
	//确保在连接客户端时不会超时
	set_time_limit(0);
	
	$ip = '127.0.0.1';
	$port = 1222;
	
	if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
	    echo "socket_create() 失败的原因是:".socket_strerror($sock)."\n";
	}
	
	if(($ret = socket_bind($sock,$ip,$port)) < 0) {
	    echo "socket_bind() 失败的原因是:".socket_strerror($ret)."\n";
	}
	 
	if(($ret = socket_listen($sock,4)) < 0) {
	    echo "socket_listen() 失败的原因是:".socket_strerror($ret)."\n";
	}
	
	do {
	    if (($msgsock = socket_accept($sock)) < 0) {
	        echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
	        break;
		} else {
	        
	        //发到客户端
			$msg = "发送成功";
	        socket_write($msgsock, $msg, strlen($msg));
			
			//接收客户端数据
			$buf = socket_read($msgsock,8192);
	      
	    }
	    //echo $buf;
	    socket_close($msgsock);
	
	} while (true);
	
	socket_close($sock);
	?>

##### TCP客户端

	/**
	 * tcp 客户端
	 */
	private function tcp_client($port, $ip, $in)
	{
		set_time_limit(0);

		//创建socket
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket < 0) {
			$this->return_data(null, "socket创建失败，原因：". socket_strerror($socket)  ,RESPONSE_SOCKET_ERROR);
		}

    	//进行socket连接
		$result = socket_connect($socket, $ip, $port);
		if ($result < 0) {
			$this->return_data(null, "socket连接失败，原因：". socket_strerror($result)  ,RESPONSE_SOCKET_ERROR);
		}
		$result_data = '';

		//发送socket数据到服务器
		if(!socket_write($socket, $in, strlen($in))) {
			$this->return_data(null, "socket发送数据失败，原因：". socket_strerror($socket)  ,RESPONSE_SOCKET_ERROR);
		}

		//获取socket返回值
		while($out = socket_read($socket, 8192)) {
			$result_data = $out;
		}

		//关闭socket
		socket_close($socket);

		return $result_data;
	}

#### UDP协议

##### UDP服务端

	<?php
 
	//Reduce errors
	error_reporting(~E_WARNING);
	 
	//Create a UDP socket
	if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
	{
	    $errorcode = socket_last_error();
	    $errormsg = socket_strerror($errorcode);
	     
	    die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	 
	echo "Socket created \n";
	 
	// Bind the source address
	if( !socket_bind($sock, "127.0.0.1" , 9999) )
	{
	    $errorcode = socket_last_error();
	    $errormsg = socket_strerror($errorcode);
	     
	    die("Could not bind socket : [$errorcode] $errormsg \n");
	}
	 
	echo "Socket bind OK \n";
	 
	//Do some communication, this loop can handle multiple clients
	while(1)
	{
	    echo "Waiting for data ... \n";
	     
	    //Receive some data
	    $r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
	    echo "$remote_ip : $remote_port -- " . $buf;
	     
	    //Send back the data to the client
	    socket_sendto($sock, "OK " . $buf , 100 , 0 , $remote_ip , $remote_port);
	}
	 
	socket_close($sock);


##### UDP客户端

	/**
	 * udp 客户端
	 */
	private function udp_client($port, $ip, $sendMsg)
	{
		set_time_limit(0);
		$handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
		if( !$handle ){
			$this->return_data(null, "连接失败，{$errno} - {$errstr}"  ,RESPONSE_UNDATA_ERROR);
		}
		fwrite($handle, $sendMsg);
		$result = fread($handle, 1024);
		fclose($handle);
		return $result;
	}

### socket通信

#### 数据通信

 首先，可以先看手册上的一段话，或者点击这里[字符串类型详解](http://php.net/manual/zh/language.types.string.php#language.types.string.details)：

	PHP 中的 string 的实现方式是一个由字节组成的数组再加上一个整数指明缓冲区长度。
	并无如何将字节转换成字符的信息，由程序员来决定。
	字符串由什么值来组成并无限制；
	特别的，其值为 0（"NUL bytes"）的字节可以处于字符串任何位置
	（不过有几个函数，在本手册中被称为非"二进制安全"的，也许会把 NUL 字节之后的数据全都忽略）。 

	字符串类型的此特性解释了为什么 PHP 中没有单独的"byte"类型 - 已经用字符串来代替了。
	返回非文本值的函数 - 例如从网络套接字读取的任意数据 - 仍会返回字符串。

 由此可见，无论数据怎么显示到屏幕上，字符串都是同一种存储格式，即按字节存储。 而且

	某些函数假定字符串是以单字节编码的，但并不需要将字节解释为特定的字符。
	例如 substr()，strpos()，strlen() 和 strcmp()。 

 所以如果用socket发送数据包，可以通过这几个函数进行处理。

##### pack()函数

 首先先看，pack()函数的使用方式（可以将数据压缩为二进制）

 > string pack ( string $format [, mixed $args [, mixed $... ]] )

 `$format`参数，定义以什么形式压缩`$args`参数，以下为`$format`可以使用的参数，可以组合。

	a 一个填充空的字节串
	A 一个填充空格的字节串
	b 一个位串，在每个字节里位的顺序都是升序
	B 一个位串，在每个字节里位的顺序都是降序
	c 一个有符号 char（8位整数）值
	C 一个无符号 char（8位整数）值；关于 Unicode 参阅 U
	d 本机格式的双精度浮点数
	f 本机格式的单精度浮点数
	h 一个十六进制串，低四位在前
	H 一个十六进制串，高四位在前
	i 一个有符号整数值，本机格式
	I 一个无符号整数值，本机格式
	l 一个有符号长整形，总是 32 位
	L 一个无符号长整形，总是 32 位
	n 一个 16位短整形，“网络”字节序（大头在前）
	N 一个 32 位短整形，“网络”字节序（大头在前）
	p 一个指向空结尾的字串的指针
	P 一个指向定长字串的指针
	q 一个有符号四倍（64位整数）值
	Q 一个无符号四倍（64位整数）值
	s 一个有符号短整数值，总是 16 位
	S 一个无符号短整数值，总是 16 位，字节序跟机器芯片有关
	u 一个无编码的字串
	U 一个 Unicode 字符数字
	v 一个“VAX”字节序（小头在前）的 16 位短整数
	V 一个“VAX”字节序（小头在前）的 32 位短整数
	w 一个 BER 压缩的整数
	x 一个空字节（向前忽略一个字节）
	X 备份一个字节
	Z 一个空结束的（和空填充的）字节串
	@ 用空字节填充绝对位置

 以下为我这次使用所写的，当然也可以结合到一起，具体参考通信数据格式

	$start_tag = pack("n", 28400);
	$end_tag = pack("n", 28656);
	$dest_address = pack("a36", $get_data["control_ip"]."@".$get_data["proxy_ip"]);
	$cmd = pack("N", 65574);
	$prama_length = pack("N", $pramas_length);
	$prama = pack("a{$pramas_length}", $pramas);
	$send_pack = $start_tag.$dest_address.$cmd.$prama_length.$prama.$end_tag;

##### unpack()函数

 首先先看，unpack()函数的使用方式（可以将二进制数据解压缩）

 > array unpack ( string $format , string $data )

 `$format`参数，定义以什么形式解压缩`$data`参数（同上）。

 以下为我这次使用所写的，当然也可以结合到一起，具体参考通信数据格式

	$return_start_tag  = substr($quickly_positioning, 0, 2);
    $return_end_tag = substr($quickly_positioning, $return_length-2, $return_length);
 	if (unpack("n", $return_start_tag)[1] != config("quickly_positioning.response_pack_start_tag_dec") || unpack("n", $return_end_tag)[1] != config("quickly_positioning.response_pack_end_tag_dec")) {
        $this->return_data(null, "数据包格式错误", RESPONSE_SOCKET_ERROR);
    }	    
    $return_cmd_hex = substr($quickly_positioning, 38, 41);
    $return_cmd = unpack("N", $return_cmd_hex);

#### 展示包的数据

 如果调试的时候想要看自己的包到底以什么二进制形式发送出去的，可以使用一些函数对二进制流数据进行处理，因为直接打印会以当前编码进行显示。关于详情可以查看[php中关于转换进制的用法](https://www.jianshu.com/p/6b1865c2c791)

##### bin2hex()

 该函数可以将数据的二进制字符串转换为十六进制值

 > string bin2hex ( string $str )

### 总结

 以上只是demo形式的socket通信，希望对需要的人有用，也方便自己以后查阅。想要学的更多还需要对一些配置的学习，以及一些坑的处理。


 

 
