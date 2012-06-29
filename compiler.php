<?php
$commitMessage = 'Compiled functions.'; 
$ips = array('207.97.227.253', '50.57.128.197', '108.171.174.178');

if(in_array($_SERVER['REMOTE_ADDR'], $ips)){
	if(isset($_POST['payload'])){
		$data = json_decode($_POST['payload']);
		$compile = false;
		foreach($data->commits as $commit){
			if(strtolower($commit) == $commitMessage){
				$compile = true;
				continue;
			}
		}
		if(!$compile){
			shell_exec('git pull');
			unlink('./ultimateLibrary.php');
			$functions = glob('functions/*.php');
			if(count($functions) > 0){
				foreach($functions as $function){			
					$code = file_get_contents($function);
					file_put_contents('./ultimateLibrary.php', "{$code}\n", FILE_APPEND);
				}
				shell_exec('git add ultimateLibrary.php');
				shell_exec("git commit -m \"{$commitMessage}\"");
				shell_exec('git push origin master');
			}
		}
	}
}else
	header('HTTP/1.1 403 Forbidden');