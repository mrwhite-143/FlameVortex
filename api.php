<?php
require "config.php";
// Simple rate limit (file based)
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$limit_file = sys_get_temp_dir().'/flame_api_'.md5($ip);
$limit = ['ts'=>time(),'count'=>0];
if (file_exists($limit_file)) {
    $raw = @file_get_contents($limit_file);
    $d = @json_decode($raw, true);
    if ($d && isset($d['ts'],$d['count'])) $limit = $d;
}
if ($limit['ts'] + 60 < time()) $limit = ['ts'=>time(),'count'=>0];
$limit['count']++;
file_put_contents($limit_file, json_encode($limit));
if ($limit['count'] > 30) json_exit(['status'=>false,'message'=>'rate_limited'],429);

$key = trim((string)($_GET['key'] ?? ''));
if ($key === '') json_exit(['status'=>false,'message'=>'no_key'],400);

$db = get_db();
$stmt = $db->prepare("SELECT approved FROM device_keys WHERE device_key = :k LIMIT 1");
$stmt->execute([':k'=>$key]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$logStmt = $db->prepare("INSERT INTO api_logs (device_key, ip, path, response_code) VALUES (:k,:ip,:p,:r)");
$response_code = 200;

if (!$row) {
    $ins = $db->prepare("INSERT OR IGNORE INTO device_keys (device_key, approved) VALUES (:k,0)");
    $ins->execute([':k'=>$key]);
    $logStmt->execute([':k'=>$key, ':ip'=>$ip, ':p'=>$_SERVER['REQUEST_URI'], ':r'=>$response_code]);
    json_exit(['status'=>false,'message'=>'not_found'],200);
}

$logStmt->execute([':k'=>$key, ':ip'=>$ip, ':p'=>$_SERVER['REQUEST_URI'], ':r'=>$response_code]);
if ((int)$row['approved'] === 1) json_exit(['status'=>true,'message'=>'approved'],200);
json_exit(['status'=>false,'message'=>'not_approved'],200);
?>