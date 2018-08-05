<?php
    header('Access-Control-Allow-Origin:http://www.testcookie1.com');
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: GET, POST, PUT");
	header('Access-Control-Allow-Headers: X-Custom-Header');
	header('Access-Control-Max-Age: 1728000');
	setcookie("test2", "test2");
?>

<script>
  document.cookie="test22=11";
</script>
