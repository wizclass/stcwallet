<?php
    include_once('./_common.php');

    $url = isset($_GET['url']) ? urldecode($_GET['url']) : false;

    if(!$url) exit;

    if($member['mb_id'] == 'test'){
        $datetime = G5_TIME_YMDHIS;
        sql_query("INSERT INTO useragent (device, mb_id, dt_date) VALUES ('{$_SERVER['HTTP_USER_AGENT']}','{$member['mb_id']}' ,'{$datetime}') ");
    }

    $http_device = 'Browser';
    $device_check = strpos($_SERVER['HTTP_USER_AGENT'],'webview');

    if($device_check !== false){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.wallet.esg"){
            $http_device = 'Android / webview';
        }else{
            $http_device = 'IOS / webview';
        }
        

        $mb_no = $member['mb_no'];
        $fcm_token = $member['fcm_token'] ? $member['fcm_token'] : "null";

    // if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.wallet.esg"){
    
?>
    <script>
        window.addEventListener('flutterInAppWebViewPlatformReady', function(event) {
            window.flutter_inappwebview.callHandler('save_fcm_token', '<?=$mb_no?>','<?=$fcm_token?>');            
            location.href= '<?=$url?>'; 
        }); 
    </script>

<?}else{?>
    <script>location.href='<?=$url?>';</script>;
<?}
    sql_query("UPDATE useragent set os = '{$http_device}' WHERE no = (SELECT max(no) FROM useragent)");
?>
