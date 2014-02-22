<?php
include 'config.php';
	$where = '';
	$login = '';
	
	if(array_search(1,$_GET) == 'my'){
		if(!empty($_SESSION['login']['id'])){
			$login = 1;
			$user_id=$_SESSION['login']['id'];
			$where = $where . " where user_id = $user_id";
		}else{
			echo '<script>alert("Not logged in!");location="index.php"</script>';
		}
	}
	$link=db_ini();
	$sql="select * from photo $where order by time desc";
	$res=mysql_query($sql);
	
	$list=array();
	if(!empty($res)){
		while($row=mysql_fetch_assoc($res)){
			$list[]=$row;
		}
	}

//检查登陆
if($_SESSION){
	$login = $_SESSION['login']['id'];
}

//echo $_SESSION['login']['id'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="js/jquery-1.7.2.js" type="text/javascript"></script>
<script src="js/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/uploadify.css">
<style type="text/css">
*{margin:0;padding:0;font-family:微软雅黑;font-size:13px}
a{text-decoration:none}
.delete{position:absolute;right:0;z-index:9;top:0}
ul,li{list-style:none}
body {
	font: 13px Arial, Helvetica, Sans-serif;background:url(images/bg.png) repeat-x;
}
#header{height:100px;margin-bottom:15px;}
#wrap{margin:0 auto;width:980px}
.mybutton{border-radius: 5px;}
.uploadify:hover .mybutton{border-radius: 5px;}

#updiv ,#login{padding:10px 0 0 10px;box-shadow:0 0 5px 5px #FAAA3C;background:#fff;position:fixed;display:none;top:100px;width:400px;height:300px;}
.cancel{width:16px height:16px;margin:5px;float:right;}

#show ul{position:relative;padding:10px;overflow:hidden;width:215px ;height:305px;border:1px solid #ddd;float:left;margin:0 10px 10px 0;text-align:center}
#show ul img{cursor:pointer}
#show_big{position:fixed;top:0;display:none}
#show_big .big{margin:50px auto;width:980px;height:400px;text-align:center;color:#fff}
#show_big .cancel{position:absolute;right:0;top:0}
#login input{ width:255px;height:30px ;margin:20px}
#reg{display:none}
#nav{margin-top:70px;}
#nav a{margin:0 2px;background:#fff;padding:5px 10px;}
</style>

<script type="text/javascript" src="js/dump.js">
</script>
</head>

<body  onresize="updivPosi()">
<div id="wrap">
	<div id="header">
		<p id="nav"> 
			<a href="index.php">Home</a>
			<a href="javascript:;" id="upbut" onclick="updiv()">Add photos</a> 
			<?php if($login==''){echo '<a onclick="logDiv()" href="javascript:;">Login</a>';}?>
			<?php if($login>=1){echo '<a href="javascript:;" onclick="logout()" >Logout</a>';}?>
			<a href="?my=1">Myself</a>
		</p>
	</div>
	
	
	<div id="show">
		<?php 
			if(empty($list)){echo 'Empty，'. '<a href="javascript:;" id="upbut" onclick="updiv()">' . 'Upload now !</a>';}
			foreach($list as $v){	?>
			<ul pid="<?php echo $v['id']?>">
					<li class="small"><img pid="<?php echo $v['id']?>" src="./uploads/200/<?php echo $v['savename']?>"></li>
					
					<li pid="<?php echo $v['id']?>" class="beizhu"><?php if($v['beizhu']==''){echo '<img src="images/edit.gif">';}else{echo $v['beizhu'];}?> </li>
				</ul>
		<?php }?>
	</div>
</div>
	
	<!---------------------隐藏的DVI------------------------>
	<div id="show_big">
		<div class="big"></div>
		<span class="cancel"><img src="images/back.png"></span>
	
	</div>
	<div id="login">
		<div class="cancel"><img src="images/uploadify-cancel.png"></div>
		<form id="log">
			Username：<input type="text" name="username"><br>
			Password：<input type="password" name="pass"><br>
			<input type="hidden" name="m" value="log">
			<a href="javascript:;" onclick="register()">Register!</a>
			<input type="button" value="Login">
		</form>
		<form id="reg" >
			Username：<input type="text" name="username"><br>
			Password：<input type="password" name="pass"><br>
			Password：<input type="password" name="repass"><br>
			<input type="hidden" name="m" value="reg">
			<a href="javascript:;" onclick="login()">Login！</a>
			<input type="button" value="Register">
		</form>
	</div>
	
	<div id="updiv">
		<div class="cancel"><img src="images/uploadify-cancel.png"></div>
		<form>
			<div id="queue"></div><!--这个div，用来显示文件上传队列,通过queueID关联-->
			<input id="file_upload" name="file_upload" type="file" multiple="true">
		</form>
		<div id="progress"></div><!--onUploadProgress事件使用-->
	</div>
	<script type="text/javascript">
		
		//注销
		function logout(){
			if(!confirm('Logout？')){return false};
			$.get('action.php',{m:'logout'},function(re){
				if(re==1){
					location.reload(true);
				};
			});
		}
		//注册
		function register(){
			$('#log').slideUp();
			$('#reg').slideDown();
		}
		function login(){
			$('#log').slideDown();
			$('#reg').slideUp();
		}
		//显示上传文件div
		function updiv(){
				//检查是否登陆了
				$.get('action.php',{m:'checklog'},function(re){
					if(re==8){
						$('#updiv').css('left',function(){
							var sw=document.documentElement.clientWidth;
							return sw/2-$(this).width()/2+'px';
						});
						$('#updiv').slideDown();
					}else{
						alert('You have not landed yet');
						logDiv();
					}
				})
			
				
			}
		
		//窗口缩放后调整div的位置
		function updivPosi(){
				$('#updiv').css('left',function(){
					var sw=document.documentElement.clientWidth;
					return sw/2-$(this).width()/2+'px';
				});
				$('#login').css('left',function(){
					var sw=document.documentElement.clientWidth;
					return sw/2-$(this).width()/2+'px';
				});
			}
		//图片显示重新排版
		function reMargin(){
			$('#show>ul').each(function(i){
				$(this).css('marginRight','10px');
				if((i+1)%4==0){
					$(this).css('marginRight','0');
					}
			});
			
		}
		//登陆框
		function logDiv(){
			$('#login').css('left',function(){
				var sw=document.documentElement.clientWidth;
				return sw/2-$(this).width()/2+'px';
			});
			$('#login').slideDown();
		}
		/***************************************************************************************/
		$(function() {
			//提交表单登陆
			$('#log input[type="button"]').click(function(){
				var date=$('#log').serialize();
				
				$.get('action.php',date,function(re){
					if(re==1){
						$('#login').append('<p style="color:red;font-size:20px">Successful landing: automatically close the window after 2 seconds.</p>')
						setTimeout(function(){$('#login').slideUp(function(){location.reload(true)})},2000);
						
					}else{
						alert(re);
					}
				});
			});
			//提交表单注册
			$('#reg input[type="button"]').click(function(){
				var date=$('#reg').serialize();
				
				$.get('action.php',date,function(re){
					if(re>=1){
						$('#login').append('<p style="color:red;font-size:20px">注册成功:2秒后自动关闭窗口</p>')
						setTimeout(function(){$('#login').slideUp(function(){location.reload(true)})},2000);
						
					}else{
						alert(re);
					}
					;
				});
			});
			
			//修改备注////////////////////////
			var con;
			$('#show .beizhu').live('click',function(){
				con=$(this).html();
				$(this).html('<input type="text" name="beizhu">');
				$('#show .beizhu input').focus();
			});
			
			$('#show .beizhu input').live('focusout',function(){
				var vals=$(this).val();
				var self=$(this);
				//空的。没有输入
				if(vals==''){
				
				self.parent().html(con);
				$('#show>ul>span:first').remove();
				};
				//不是空的。新的输入
				if(vals!=''){
					$('#show>ul>span:first').remove();
					$.get('action.php',
							{m:'beizhu',id:self.parent().attr('pid'),vals:vals},
							function(re){
								if(re!=''){
								self.parent().html(re);
								}
							});
				};
			} );
			
			//点击图片查看原图
			$('#show>ul .small img').live('click',function(i){
				$('#show_big').css({width:'100%',height:'100%',background:'rgba(0,0,0,.8)',display:'block'})
				$('#show_big .big').html('<img src="images/progress.gif">');
				$.get('action.php',{m:'getone',id:$(this).attr('pid')},function(re){
					$('#show_big .big').html(re);
				});
				
			})
			
			//退出图片查看原图模式
			$('#show_big .cancel').click(function(){
				$('#show_big').css('display','none')
			})
			//鼠标移入删除按钮
			//$('#show>ul').live('hover',function(){
			$('#show>ul').hover(function(){
				
				$(this).prepend('<span class="delete"><img src="images/uploadify-cancel.png"></span>')
			},function(){
				$(this).children('span:first').remove();
			});
			
			//删除图片
			$('#show ul .delete').live('click',function(){
				var id=$(this).parent().attr('pid');	
				if(!confirm('Delete?')){return false};
				$.get('action.php',{m:'delete',id:id},function(re){
						//alert(re);
						if(re==1){
							$('#show ul[pid="'+id+'"]').slideUp(function(){
								$(this).remove();
								reMargin();
								});
							
						}
				});
				
			})
			
			//图片显示重新排版
			$('#show>ul').each(function(i){
			if((i+1)%4==0){$(this).css('marginRight','0')}
			})
			
			//隐藏上传框
			$('#updiv .cancel').click(function(){
				$('#updiv').slideUp();
				
			})
			
			//隐藏登陆框
			$('#login .cancel').click(function(){
				$('#login').slideUp();
			})
			
			
		
			//文件上传
		
			$('#file_upload').uploadify({
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php',
				'buttonText':'浏览',
				'height' : 30,
        		'width' : 120 ,
				'buttonClass':'mybutton',
				'buttonCursor' : 'arrow',
				'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
				'removeTimeout':3,
				'progressData':'percentage',
				'queueID'  : 'queue',
				'queueSizeLimit':3,
				'uploadLimit':10,
				'successTimeout' : 5,

				
        		'onUploadSuccess' : function(file, data, response) {
        			/*
        			file 已成功上传的文件对象
        				index [number]  文件索引(当前完成的是第几个，从0开始)
	        			name [string]	文件名
						size [number]   文件大小
						type [string]   文件类型(扩展名)  如png  jpg
					data 服务端返回的数据
					response boolean 服务端是否有响应。文件发送完后，如果等待一定时间后，
						服务器还是没有返回，则为false。测试时，加一个在接收文件上传的php文件最后，加一个sleep(40)，就能看到结果
						默认是30秒,可以通过successTimeout改变这个值。
						response并不能说明文件是否被成功上传，只是判断是否有服务端响应
					*/
        			alert('已将'+file.name + '上传为：'+ data);
			    	$.get('action.php',{m:'add',imgname:file.name,savename:data},function(re){
						if(re>=1){
							$('#show').prepend('<ul><li class="small" ><img pid="'+re+'" src="./uploads/200/'+data+'"></li><li class="beizhu" pid="'+re+'"><img src="images/edit.gif"></li></ul>');
							reMargin();
						}
					});
			    },

			    //上传完成
			    //会先调用onUploadSuccess然后才调用onUploadComplete
			    'onUploadComplete' : function(file) {
		           // alert(file.name + '完成');
		        },

			   //调用destroy方法时
			    'onDestroy':function(){alert('切换回普通文件上传了')},

			   
			    'onInit'   : function(instance) {
		           // alert('对列id是：' + instance.settings.queueID);
		        },

		        //队列完成
		        'onQueueComplete':function(queueData){
		        	// alert('成功上传：'+queueData.uploadsSuccessful);
		        },
				'onSelect' : function(file) {
                 	//alert( "文件名：" + file.name + "\r\n" +"文件大小：" + file.size + "\r\n" +"文件类型：" + file.type);
			    },

			    'onSelectError' : function() {
		            alert('The file ' + file.name + ' returned an error and was not added to the queue.');
		        },

		        'onSWFReady' : function() {
		           // alert('The Flash file is ready to go.');
		        } ,

		        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
		            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
		        } ,

		        //上传进度
		        'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		            //$('#progress').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
		        },

		        //开始上传
		        'onUploadStart' : function(file) {
		           // alert('开始上传' + file.name);
		        } 
			});
		});
	</script>

</body>
</html>