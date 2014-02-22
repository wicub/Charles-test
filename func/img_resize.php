<?php

	//测试函数 	resize(134,134,'../uploade/original/'.date('Ymd').'/'.$ufilename);
	
			//调整图像大小, 成功返回新的文件名  失败返回false
		function resize($w,$h,$imgname){
			///获取需要调整大小的图片
			
			//根据文件名创建不同画板
			switch(strrchr($imgname,'.')){
			
				case '.jpg':
					$inimg=imagecreatefromjpeg($imgname);
					break;
				case '.png':
					$inimg=imagecreatefrompng($imgname);
					break;
				case '.gif':
					$inimg=imagecreatefromgif($imgname);
					break;
				default:
					echo '文件类型错误';
					die();
			}
			//创建一个新的画布
			$img=imagecreatetruecolor($w,$h);
			imagefill($img,0,0,imagecolorallocate($img,255,255,255));
			//获取源图像尺寸
			$iw=imagesx($inimg);
			$ih=imagesy($inimg);
			
			$nx=0;
			$ny=0;
			$dw=$w;
			$dh=$h;
			
			if($iw<$ih){
				$dw=$dh*$iw/$ih;
				//echo $dw;
				$nx=($w-$dw)/2;
			}else{
				$dh=$dw*$ih/$iw;
				$ny=($h-$dh)/2;
			
			}
			//echo $nx;die;
			
			//拷贝原图像到目标图像中，并调整大小显示的位置
			/////////////////////目标/原////目标坐标、原坐标，目标尺寸，，原尺寸
			imagecopyresampled($img,$inimg,$nx,$ny  , 0,0,  $dw,$dh,    $iw,$ih);
			
			//创建图像路径
			//$savedir   = './uploads/'.$w.'/'.date('Ymd');带日期
			$savedir   = './uploads/'.$w;
			
			if(!file_exists('./uploads/'.$w) ){mkdir('./uploads/'.$w);}
			//if(!file_exists($savedir) ){mkdir($savedir);}
			
			
			//保存图像,会根据扩展名调用不同的函数来处理
		
				//$ext=strrchr($filename,'.'); //类似于 .jpg
				$savetype = strrchr($imgname,'.');//取得文件后缀
				$basen    = basename($imgname,$savetype);//取得文件名，没有后缀
				//echo $ext;exit;		
				switch($savetype){
					case '.jpg':				
						return imagejpeg($img, $savedir.'/'.$basen.'.jpg');
					case '.png':
						return imagepng($img, $savedir.'/'.$basen.'.png');
					case '.gif':
						return imagegif($img, $savedir.'/'.$basen.'.gif');
					default:
						return false;
				}
			
	
			
			imagedestroy ($img);//销毁图像
		}




