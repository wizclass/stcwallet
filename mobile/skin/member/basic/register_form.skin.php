<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

?>
<style type="text/css">
    .mbskin {}
    .mbskin input[type="text"],
    .mbskin input[type="password"] {width:60%;height:22px;line-height:22px;}

    .btn {display:inline-block;*display:inline;*zoom:1;padding:0;margin:0;color:#fff;padding:3px;background-color:rgba(59,105,178,1);vertical-align:middle;}

    p.explain {line-height: 30px;color: #95a5a6;margin-bottom:40px;}
    div.acc_here {font-size: 24px;color: #3498db;padding: 10px 20px 20px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.btn_confirm {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 0px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);overflow: hidden;}
    .tbl_frm01 {margin: 0;}
    .tbl_frm01 td {padding: 0px;border: 0px;}
    div.agree_txt {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);line-height: 30px;}
    div.regi_box {padding: 40px 20px 40px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.regi_box input {margin-bottom:40px;padding:5px 0 6px 30px;background: none !important;}
    #ajax_rcm_search {float: right;border-radius: 7px;padding: 4px 10px 9px 10px;}
    .mbskin .btn_cancel{background-color: #2980b9;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;}
    .mbskin .btn_submit {background-color: #2ecc71;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;}
</style>
<div class="mbskin">
    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js"></script>
    <?php } ?>

    <form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="url" value="<?php echo $urlencode ?>">
    <input type="hidden" name="agree" value="<?php echo $agree ?>">
    <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
    <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
    <input type="hidden" name="cert_no" value="">
    <?php if (isset($member['mb_sex'])) { ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php } ?>
    <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면 ?>
    <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
    <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
    <?php } ?>


	<? if ($member['mb_id']) { ?>
	<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>" />
    <? } ?>
    <p class="explain">
        Fill out the form below to create your FIJI Mining account. 
        You will need to set up a Username and Password along with a valid email 
        address so that your account can be verified. After you submit the form you will 
        be redirected inside our secure Members Area to complete your membership enrollment. 
        Your account will be created and set to "Pending" until you pay your $99 membership enrollment.
    </p>
    <div class="acc_here">
        Get your account here
    </div>
    <div class="regi_box">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tr>
            <!-- <th scope="row"><label for="reg_mb_name">이름<strong class="sound_only">필수</strong></label></th> -->
            <td colspan="2">
                <?php if ($config['cf_cert_use']) { ?>
                <span class="frm_info">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                <?php } ?>
                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> placeholder="FullName" class="frm_input <?php echo $required ?> <?php echo $readonly ?>">
                <?php
                if($config['cf_cert_use']) {
                    if($config['cf_cert_ipin'])
                        echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>'.PHP_EOL;
                    if($config['cf_cert_hp'] && $config['cf_cert_hp'] != 'lg')
                        echo '<button type="button" id="win_hp_cert" class="btn_frmline">휴대폰 본인확인</button>'.PHP_EOL;

                    echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                }
                ?>
                <?php
                if ($config['cf_cert_use'] && $member['mb_certify']) {
                    if($member['mb_certify'] == 'ipin')
                        $mb_cert = '아이핀';
                    else
                        $mb_cert = '휴대폰';
                ?>
                <div id="msg_certify">
                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                </div>
                <?php } ?>
<script>
$(function(){
	$('#reg_mb_name').change(function () {
		var $val = $(this).val();
		$('#reg_mb_nick').val($val);
	});
});
</script>
				<input type="hidden" name="mb_nick" id="reg_mb_nick" value="<?=$member['mb_nick']?>" />
            </td>
        </tr>
        <!-- // <?php if ($req_nicks) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>
            <td>
                <span class="frm_info">
                    공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br>
                    닉네임을 바꾸시면 앞으로 <?php echo (int)$config['cf_nick_modify'] ?>일 이내에는 변경 할 수 없습니다.
                </span>
                <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
                <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="frm_input required nospace" maxlength="20">
                <span id="msg_mb_nick"></span>
            </td>
        </tr>
        <?php } ?> // -->

        <tr>
            <td>
                <?php if ($config['cf_use_email_certify']) {  ?>
                <span class="frm_info">
                    <?php if ($w=='') { echo "Verify your email address to complete the sign-up"; }  ?>
                    <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
                </span>
                <?php }  ?>
                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                <input type="email" name="mb_email" placeholder="Email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email required" size="50" maxlength="100" style="width:80%;">
            </td>
        </tr>

        <?php if ($config['cf_use_homepage']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_homepage">홈페이지<?php if ($config['cf_req_homepage']){ ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td><input type="url" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" class="frm_input <?php echo $config['cf_req_homepage']?"required":""; ?>" maxlength="255" <?php echo $config['cf_req_homepage']?"required":""; ?>></td>
        </tr>
        <?php } ?>

        <?php if ($config['cf_use_tel']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_tel">전화번호<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td><input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" class="frm_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" <?php echo $config['cf_req_tel']?"required":""; ?>></td>
        </tr>
        <?php } ?>

        <?php if ($config['cf_use_hp']) {  ?>
        <tr>
            <th scope="row"><label for="reg_mb_hp">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td>
                <input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20">
                <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

        <?php if ($config['cf_use_addr']) { ?>
        <tr>
            <th scope="row">
                주소
                <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php } ?>
            </th>
            <td>
                <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6">
                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                <label for="reg_mb_addr1" class="sound_only">주소<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"><br>
                <label for="reg_mb_addr2" class="sound_only">상세주소</label>
                <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address" size="50">
                <br>
                <label for="reg_mb_addr3" class="sound_only">참고항목</label>
                <input type="text" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address" size="50" readonly="readonly">
                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
            </td>
        </tr>
        <?php } ?>
        </table>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>

        <tr>
            <td colspan="2"><input type="password" name="mb_password" placeholder="Password" id="reg_mb_password" class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?>></td>
        </tr>
        <tr>
            <td colspan="2"><input type="password" name="mb_password_re" placeholder="Confirm-Password" id="reg_mb_password_re" class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" <?php echo $required ?>></td>
        </tr>
        </table>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        
        <?php if ($config['cf_use_signature']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_signature">서명<?php if ($config['cf_req_signature']){ ?><strong class="sound_only">필수</strong><?php } ?></label></th>
            <td><textarea name="mb_signature" id="reg_mb_signature" class="<?php echo $config['cf_req_signature']?"required":""; ?>" <?php echo $config['cf_req_signature']?"required":""; ?>><?php echo $member['mb_signature'] ?></textarea></td>
        </tr>
        <?php } ?>

        <?php if ($config['cf_use_profile']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_profile">자기소개</label></th>
            <td><textarea name="mb_profile" id="reg_mb_profile" class="<?php echo $config['cf_req_profile']?"required":""; ?>" <?php echo $config['cf_req_profile']?"required":""; ?>><?php echo $member['mb_profile'] ?></textarea></td>
        </tr>
        <?php } ?>

        <?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_icon">회원아이콘</label></th>
            <td>
                <span class="frm_info">
                    이미지 크기는 가로 <?php echo $config['cf_member_icon_width'] ?>픽셀, 세로 <?php echo $config['cf_member_icon_height'] ?>픽셀 이하로 해주세요.<br>
                    gif만 가능하며 용량 <?php echo number_format($config['cf_member_icon_size']) ?>바이트 이하만 등록됩니다.
                </span>
                <input type="file" name="mb_icon" id="reg_mb_icon" class="frm_input">
                <?php if ($w == 'u' && file_exists($mb_icon_path)) { ?>
                <img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
                <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
                <label for="del_mb_icon">삭제</label>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

        <!-- <tr>
            <th scope="row"><label for="reg_mb_mailling">메일링서비스</label></th>
            <td>
                <input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?>>
                정보 메일을 받겠습니다.
            </td>
        </tr> -->

        <?php if ($config['cf_use_hp']) { ?>
        <tr>
            <th scope="row"><label for="reg_mb_sms">SMS 수신여부</label></th>
            <td>
                <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?>>
                휴대폰 문자메세지를 받겠습니다.
            </td>
        </tr>
        <?php } ?>

        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능 ?>
        <!-- <tr>
            <th scope="row"><label for="reg_mb_open">정보공개</label></th>
            <td>
                <span class="frm_info">
                    정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>일 이내에는 변경이 안됩니다.
                </span>
                <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
                <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?>>
                다른분들이 나의 정보를 볼 수 있도록 합니다.
            </td>
        </tr>-->
        <?php } else { ?>
        <!--<tr>
            <th scope="row">정보공개</th>
            <td>
                <span class="frm_info">
                    정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
                    이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
                </span>
                <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
            </td>
        </tr>-->
        <?php } ?> 

        <?php if ($config['cf_use_recommend']) { ?>
        <tr>
            
            <td colspan="2">
				<? if ($member['mb_id']) { ?>
				<?=$member['mb_recommend']?>
				<? } else { ?>
				<input type="text" name="mb_recommend" id="reg_mb_recommend" placeholder="Sponsor UserName" class="frm_input required" required value="<?=$member['mb_recommend']?>" style="width:60%;" placeholder="이름을 검색해주세요!">
				<span id="ajax_rcm_search" class="btn">
                    <i class="fa fa-search"></i>
                </span>				
				<? } ?>
			</td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="2"><?php echo captcha_html(); ?></td>
        </tr>
        </table>
    </div>
    </div>
    <div class="agree_txt">
        <label><input id="chkCondition" type="checkbox" style="" value=""> I have read and agree to the <a id="linkTermsConditions" href="#"><u>Terms of Conditions</u></a></label>
    </div>

    <div class="btn_confirm">
        <a href="<?php echo G5_URL; ?>/" class="btn_cancel pull-left">Back to Login</a>
        <input type="submit" value="<?php echo $w==''?'Register now':'정보수정'; ?>" id="btn_submit" class="btn_submit pull-right" accesskey="s">
    </div>
    </form>

    <script>
    $(function() {
        $("#reg_zip_find").css("display", "inline-block");

        <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
        // 아이핀인증
        $("#win_ipin_cert").click(function() {
            if(!cert_confirm())
                return false;

            var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
            certify_win_open('kcb-ipin', url);
            return;
        });

        <?php } ?>
        <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
        // 휴대폰인증
        $("#win_hp_cert").click(function() {
            if(!cert_confirm())
                return false;

            <?php
            switch($config['cf_cert_hp']) {
                case 'kcb':
                    $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                    $cert_type = 'kcb-hp';
                    break;
                case 'kcp':
                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                    $cert_type = 'kcp-hp';
                    break;
                default:
                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return false;';
                    break;
            }
            ?>

            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
            return;
        });
        <?php } ?>
    });

    // 인증체크
    function cert_confirm()
    {
        var val = document.fregisterform.cert_type.value;
        var type;

        switch(val) {
            case "ipin":
                type = "아이핀";
                break;
            case "hp":
                type = "휴대폰";
                break;
            default:
                return true;
        }

        if(confirm("이미 "+type+"으로 본인확인을 완료하셨습니다.\n\n이전 인증을 취소하고 다시 인증하시겠습니까?"))
            return true;
        else
            return false;
    }

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == '') {
            if (f.mb_password.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert('비밀번호가 같지 않습니다.');
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
        if (f.w.value=='') {
            if (f.mb_name.value.length < 1) {
                alert('이름을 입력하십시오.');
                f.mb_name.focus();
                return false;
            }
        }

        <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
        // 본인확인 체크
        if(f.cert_no.value=="") {
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            return false;
        }
        <?php } ?>

        // 닉네임 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
        // 휴대폰번호 체크
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }
        <?php } ?>

        if (typeof f.mb_icon != 'undefined') {
            if (f.mb_icon.value) {
                if (!f.mb_icon.value.toLowerCase().match(/.(gif)$/i)) {
                    alert('회원아이콘이 gif 파일이 아닙니다.');
                    f.mb_icon.focus();
                    return false;
                }
            }
        }

        if (typeof(f.mb_recommend) != 'undefined' && f.mb_recommend.value) {
			/*
            if (f.mb_id.value == f.mb_recommend.value) { // mb_id 사용안함
                alert('본인을 추천할 수 없습니다.');
                f.mb_recommend.focus();
                return false;
            }
			*/

            var msg = reg_mb_recommend_check();

            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }

        <?php echo chk_captcha_js(); ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</div>
