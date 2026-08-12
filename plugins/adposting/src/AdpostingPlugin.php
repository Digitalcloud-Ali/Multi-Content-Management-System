<?php
class AdpostingPlugin {
    public static function onActivate() {
        $repoRoot = realpath(__DIR__ . '/../../..');
        $src = $repoRoot . '/adposting';
        $dest = __DIR__ . '/www';
        if (!is_dir($src)) return ['success'=>false,'message'=>'Legacy adposting folder not found'];
        if (is_dir($dest)) return ['success'=>true,'message'=>'Already migrated'];
        if (!self::rcopy($src,$dest)) return ['success'=>false,'message'=>'Failed to copy adposting files'];
        @file_put_contents($dest.'/.migrated',"migrated_on=".date('c'));
        return ['success'=>true,'message'=>'Adposting copied to plugins/adposting/www'];
    }
    private static function rcopy($src,$dst){
        $dir = opendir($src);
        if (!@mkdir($dst,0755,true) && !is_dir($dst)) return false;
        while(false !== ($file = readdir($dir))){
            if ($file=='.'||$file=='..') continue;
            $s=$src.'/'.$file; $d=$dst.'/'.$file;
            if (is_dir($s)) { if(!self::rcopy($s,$d)) return false; }
            else { if(!copy($s,$d)) return false; }
        }
        closedir($dir); return true;
    }
}
