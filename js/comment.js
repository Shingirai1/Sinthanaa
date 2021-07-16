$(document).ready(function(){
	//send data to server
	function sendData(){
		var xhr = new XMLHttpRequest();
		  xhr.onreadystatechange = function (){
			switch(xhr.readyState){
			case 4:
				// XHR Communication failure
				if(xhr.status == 0){
					alert("XHR Communication failure");
				// XHR Communication success
				}else{
					// check if Request success
					if((200 <= xhr.status && xhr.status < 300) || (xhr.status == 304)){
						var result = xhr.responseText;
						alert("status: " + result.substring(0,7));
						//alert("status:" + xhr.responseText);
					// Check Request fails
					}else{
						alert("Other response:" + xhr.status);
					}
				}
				break;
			}
		}
		
		var Name=new $('.Name').val();
		var Email=new $('.Email').val();
		var Comment=new $('.Text').val();
		
		xhr.open("post",'index.php',true);
		var formdata = new FormData();
		formdata.append("submit","submit");
		formdata.append("Name",Name);
		formdata.append("Email",Email);
		formdata.append("Comment",Comment);
		xhr.send(formdata);
	}
	//check form fields
	function check(){
		
		var errormessage="";
		
		if($('.Name').val()=="NAME"){
			errormessage+="Please enter your name ?\n";
		}
		if($('.Email').val()=="EMAIL"){
			errormessage+="Please enter your email ? \n";
		}
		if($('.Text').val()=="MESSAGE"){
			errormessage+="Please write a comment ?\n";
		}
		if(errormessage){
			alert("please provide the following fields: \n" + errormessage);
			return false;
		}
		
		sendData();
		clearForm();
		return true;
	}
	//reset form after submit
	function clearForm(){
		var formReset=document.querySelector("form");
		formReset.reset();
		return true;
	}
//onclick send
$('.send').click(function(){
		check();
	});
});