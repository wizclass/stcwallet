<?php
include_once('./_common.php');
include_once('./bonus_inc.php');
include_once(G5_THEME_PATH."/_include/coin_price.php");

check_admin_token();

$od_id = isset($_POST['od_id']) ? $_POST['od_id'] : false;

// $od_id = 2022120118330901;
$to_day = date('Y-m-d');
$to_datetime = date('Y-m-d H:i:s');

if($od_id){

    $sql = "select *, count(od_id) as cnt from {$g5['g5_shop_order_table']} where od_id = {$od_id}";
    $row = sql_fetch($sql);

    if($row['pay_count'] >= $row['pay_end']){
        echo json_encode(array("code"=>"300","msg"=>"이미 지급이 완료된 상품입니다."));
        return false;
    }

    $ASSETS_CURENCY = ASSETS_CURENCY;

    $member_sql = "select mb_name from {$g5['member_table']} where mb_id = '{$row['mb_id']}'";
    $mb_name = sql_fetch($member_sql)['mb_name'];
    $type = $row['od_settle_case'];
    if($row['cnt'] > 0){
        if($type == "ETH"){
            $pay_calc = shift_coin($row['pv']*$coin['esgc_eth']);
            $shift_pay_calc = shift_auto($pay_calc);
            $pay_where = 'pay_acc_'.strtolower($type);
            $member_target = 'mb_balance_eth';
        }else{
            $type = $ASSETS_CURENCY;
            $pay_calc = $row['pv'];
            $shift_pay_calc = shift_auto($pay_calc,$ASSETS_CURENCY);
            $pay_where = 'pay_acc';
            $member_target = 'mb_balance';
        }

        $left_nth = $row['pay_end'] - $row['pay_count'];
        
        $rec = "Bonus by {$row['od_tax_mny']}% ({$row['od_cart_price']} ESGC - {$row['od_settle_case']} :: {$pay_calc}";
          

        for($i = 0; $i < $left_nth; $i++){
            $k = $i + $row['pay_count']+1;

            $total_paid = $row[$pay_where] + ($pay_calc*($i+1));
            $shift_total_paid = $type == "ETH" ? shift_auto($total_paid) : shift_auto($total_paid,$ASSETS_CURENCY);
            $exc_date = $to_day;
            $rec_adm = "{$rec} / {$shift_total_paid} ( {$k} / {$row['pay_end']} )";

            if($i >= $left_nth - 1){
                $rec_adm .= " [ 스테이킹 만기 : {$shift_od_cart_price} {$ASSETS_CURENCY} 반환 ]";
            }

            $history_value_sql .= "('{$to_day}', '{$row['od_name']}', '{$row['mb_id']}','{$mb_name}',{$pay_calc}, '{$type}', {$rate}, '{$rec} {$refund_member}', '{$rec_adm} {$refund_member}', '{$to_datetime}', {$row['od_id']}, 0),";
        }
        
        $history_sql = "insert into {$g5['mining']} (`day`,`allowance_name`,`mb_id`,`mb_name`,`mining`,`currency`,`rate`,`rec`,`rec_adm`,`datetime`,`shop_order_id`,`overcharge`)
        values {$history_value_sql}";

        $exc_query = substr($history_sql,0,-1);
        $exc_result = sql_query($exc_query);
        echo "<br>".$exc_query;
        // $exc_result = 1;
        
        if($exc_result){
            $total_exc_bonus = $pay_calc*($i);
            $update_member_sql = "UPDATE g5_member SET {$member_target} = ($member_target + $total_exc_bonus), mb_deposit_calc = mb_deposit_calc + {$row['od_cart_price']} WHERE mb_id = '{$row['mb_id']}' ";
            $update_result1 = sql_query($update_member_sql);
            echo "<br>".$update_member_sql;
            
            $item_names = get_item_table_names();

            $update_order_sql = "UPDATE g5_shop_order s JOIN package_{$item_names[$row['od_tno']]} p ON s.od_id = p.od_id SET s.pay_count = {$row['pay_end']}, s.{$pay_where} = {$total_paid}, s.od_refund_price = 1, p.promote = 1 WHERE s.od_id = {$row['od_id']} ";
            $update_result2 = sql_query($update_order_sql);
            echo "<br>".$update_order_sql;

            // $update_result2 = 1;

            if($update_result2){
                $code = "200";
                $msg = "정상처리되었습니다.";
                ob_clean();
                echo json_encode(array("code"=>$code, "msg"=>$msg),JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

function get_item_table_names(){
    global $g5;
    $sql = "select it_id, lower(it_maker) as it_maker from {$g5['g5_shop_item_table']}";
    $result = sql_query($sql);
    $items = [];

    for($i = 0; $i < $row = sql_fetch_array($result); $i++){
        $items[$row['it_id']] = $row['it_maker'];
    }

    return $items;
}


?>