<?php

include 'config.php';
$m=$_GET['m'];
switch($m){
	case 'add':
		add();
	break;
	case 'getone':
		getone();
	break;
	case 'beizhu':
		beizhu();
	break;
	case 'delete':
		delete();
	break;
	case 'reg':
		reg();
	break;
	case 'logout':
		logout();
	break;
	case 'log':
		login();
	break;
	case 'checklog':
		checklog();
	break;
	
}

function checklog(){
	if(!empty($_SESSION['login'])){echo 8;}
}
function login(){
	//username=33&pass=2232
	$username=$_GET['username'];
	$pass=$_GET['pass'];
	
	$link=db_ini();
	$sql="select * from user where username='$username'";
	$res=mysql_query($sql);
	$user=array();
	while($row=mysql_fetch_assoc($res)){
		$user[]=$row;
	}
	if(!empty($user)){
		if($user[0]['password']==$pass){
			$_SESSION['login']['id']=$user[0]['id'];
			//file_put_contents('d.txt',$_SESSION['login']['id']);
			echo 1;
		}
	}else{
		echo 'Login failed!';
	}
	
}

function logout(){
	unset($_SESSION['login']);
	echo 1;
}

function reg(){
	//username=33&pass=2232&pass=11&m=reg
	$username=$_GET['username'];
	$pass=$_GET['pass'];
	$repass=$_GET['repass'];
	if($pass!=$repass){
		die( 'Different password!');
		
	}else if(strlen($pass) < 5 || strlen($pass) > 12){
		die( "Password length of at least 5, up to 12!");
	}
	$link=db_ini();
	$sql="insert into user(username,password) values('$username','$pass')";
	$res=mysql_query($sql);
	$id=mysql_insert_id($link);
	if($res && mysql_affected_rows($link)){
		$_SESSION['login']['id']=$id;
		echo $id;
	}else{
		echo mysql_error();
	}
	
}
function add(){
	$user_id=$_SESSION['login']['id'];
	$imgname=$_GET['imgname'];
	$savename=$_GET['savename'];
	$time=time();
	
	$link=db_ini();
	$sql="insert into photo(user_id,imgname,savename,time) values($user_id,'$imgname','$savename',$time)";
	$res=mysql_query($sql);
	$id=mysql_insert_id($link);
	if($res && mysql_affected_rows($link)){
		echo $id;
	}else{
		echo mysql_error();
	}
}

function getone(){
	
	$id=$_GET['id'];
	$link=db_ini();
	
	$sql="select * from photo where id=$id";
	$res=mysql_query($sql);
	$list=array();
	while($row=mysql_fetch_assoc($res)){
		$list[]=$row;
	}
	$img=array();
	foreach($list[0] as $k=>$v){
		$img[$k]=$v;
	}
	$time=date('Y-m-d H:i:s',$img['time']);
	//var_dump($img);<img src="uploads/51bd4a8880d8e.jpg">
	sleep(2);
	echo '<img src="uploads/'.$img['savename'].'">';
	echo '<p>'.$time.' '.$img['imgname'].'</p>';
}

function beizhu(){
	$id=$_GET['id'];
	$vals=$_GET['vals'];
	
	$link=db_ini();
	$sql="update photo set beizhu='$vals' where id=$id";
	$res=mysql_query($sql);

	if($res){
		echo $vals;
	}else{
		echo mysql_error();
	}
}
function delete(){
	$id=$_GET['id'];
	$link=db_ini();
	
	$sql="delete from photo where id=$id";
	$res=mysql_query($sql);
	if($res && mysql_affected_rows($link)){
		echo 1;
	}else{
		echo mysql_error();
	}
}