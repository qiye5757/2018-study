
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>
<script>
 // var xhr = new XMLHttpRequest();
 // xhr.withCredentials = true;
 // xhr.open('GET', "http://www.testcookie2.com");
//  xhr.withCredentials = true;
//  xhr.send();
//  $.ajax({
//	   xhrFields: {
 //        withCredentials: true
//	  },
//      url: "http://www.testcookie2.com",
 //     success: function(){
//      	console.log(11);
//      }
//  })

var xhr = new XMLHttpRequest();
xhr.open('PUT', "http://www.testcookie2.com", true);
xhr.setRequestHeader('X-Custom-Header', 'value');
xhr.send();
</script>
