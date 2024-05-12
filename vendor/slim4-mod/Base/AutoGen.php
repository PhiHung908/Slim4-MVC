<?php
declare(strict_types=1);
// /vendor/slim4-mod/Base/auto_gen.php

/**
    $output->writeln('<comment>Slim4-Mod console help:</comment>');
	
	$output->writeln('Chức năng chính là dùng để tự động tạo mới model.');
	
	$output->writeln(' ');
	$output->writeln('<comment>Gồm các lệnh như ví dụ ở dưới:</comment>');
	
	$output->writeln(' ');
	$output->writeln('<info>console new-model <<NEW_MODEL>>	//tạo mới model</info>');
	$output->writeln('<info>console product-list //liệt kê toàn bộ bảng product</info>');
	$output->writeln(' ');
	$output->writeln('<comment>==================</comment>');

*/
return  function($newModuleName, &$psr4PathHelper) {
		
		
		$dirS = $psr4PathHelper->aliasToFull("hSlim\\Base\\template\\controllers\\", true);
		$dir  = $psr4PathHelper->aliasToFull("App\\controllers\\", true);

		$u = explode('/',ltrim($_SERVER['REQUEST_URI'],'/'));
		$u[0] = strtolower($u[0] ?? ''); $u[1] = strtolower($u[1] ?? '');
		if (!empty($u[0])) {
			$dir  = $psr4PathHelper->aliasToFull($u[0]."\\controllers\\", true);
			if (!is_dir($dir)) {
				echo "\nModule Alias không tìm thấy, hãy nhập lại hoặc bỏ trống phần module.\n\n";
				die;
			}
			$u[0].= '/';
		}
				
		$newModuleName = strtolower($newModuleName);
		
		
		//==========Action
		if (!file_exists($dir . $newModuleName . '/auto_gen/' . $newModuleName . 'Controller.php')
			&& !file_exists($dir . $newModuleName . '/auto_gen/' . 'Asset.php')) {
			
			echo "AutoGen Controller ...";
			$f = $dir . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= '\\auto_gen';
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . ucfirst($newModuleName) . 'Controller.php';
			$filename =  $dirS . "\\product\\auto_gen\\ProductController.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------
			$filename =  $dirS . "\\product\\auto_gen\\Asset.php";
			$f = dirname($f) . "\\Asset.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//--
			$f = dirname($f);
			if (!is_dir($f . "\\assets")) mkdir($f . "\\assets");
			if (!is_dir($f . "\\assets\\js")) mkdir($f . "\\assets\\js");
			if (!is_dir($f . "\\assets\\css")) mkdir($f . "\\assets\\css");
			if (!is_dir($f . "\\assets\\img")) mkdir($f . "\\assets\\img");
			//-------
			$filename = $dirS . "\\product\\ListAction.php";
			$f = $dir . $newModuleName . "\\ListAction.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//-------
			$filename = $dirS . "\\product\\RowAction.php";
			$f = $dir . $newModuleName . "\\RowAction.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//-------
			$filename = $dirS . "\\product\\IndexAction.php";
			$f = $dir . $newModuleName . "\\IndexAction.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			echo " Hoàn tất.\nAutoGen Module ...";
			
			//=========Models
			$filename = dirname($dirS) . "\\models\\product\\Product.php";
			$f = dirname($dir) . "\\models";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\". $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . ucfirst($newModuleName) . '.php';
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			$filename = dirname($dirS) . "\\models\\product\\InMemoryProductRepository.php";
			$f = dirname($dir) . "\\models";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\". $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\InMemory" . ucfirst($newModuleName) . 'Repository.php';
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			
			
			echo " Hoàn tất.\nAutoGen Views ...";
			//=============Views
			$filename = dirname($dirS) . "\\views\\product\\Home.twig";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Home.twig";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			$filename = dirname($dirS) . "\\views\\product\\Layout.twig";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Layout.twig";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//...................
			$filename = dirname($dirS) . "\\views\\product\\Home.tpl";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Home.tpl";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			$filename = dirname($dirS) . "\\views\\product\\Layout.tpl";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Layout.tpl";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//...................
			$filename = dirname($dirS) . "\\views\\product\\Home.php";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Home.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			$filename = dirname($dirS) . "\\views\\product\\Layout.php";
			$f = dirname($dir) . "\\views";
			if (!is_dir($f)) mkdir($f);
			$f .= "\\" . $newModuleName;
			if (!is_dir($f)) mkdir($f);
			$f .= "\\Layout.php";
			$handle = fopen($filename, "rb");
			$h = fopen($f, "w");
			while (($ln = fgets($handle, 4096)) !== false) {
				fwrite($h, str_replace('#TPL_PRODUCT#', $newModuleName, str_replace('#U_TPL_PRODUCT#', ucfirst($newModuleName), $ln)));
			}
			fclose($h);
			fclose($handle);
			//----------------
			echo " Hoàn tất.\n";
			echo "\nModule $newModuleName đã được thiết lập, hãy gõ vào trình duyệt 'localhost/$u[0]$newModuleName' để thử ngay.\n";
			return 0;
//var_dump('da AutoGen...', $f, $filename); die;
		} else {
			echo "Module $newModuleName đã tồn tại. Ứng dụng AutoGen đã ngưng sớm.\n";
			return 1;
		}
	}
;