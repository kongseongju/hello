<meta charset="utf-8">
<?
// ####### 폼메일 '입력양식' and '발송코드' ########
	// 이것은 바로 사용 가능한 독립된 폼메일 소스입니다.
	// 본 파일 내에서 [내용입력]->[메일발송]이 모두 완료됩니다.
	// php 파일이므로 웹서버에 직접 올려야 정상동작 합니다.

	// 메일 받는사람을 고정시키거나 추가할 수 있습니다.
	// 입력란 추가항목은 50개 이상 추가할 수 있습니다.
	// 별표(★) 표시된 설정항목 확인후 서버에 올려서 테스트 하십시오

	// [★ 설정 1] 스팸메일에 악용될 우려 때문에 기본상태는 메일발송 차단되게 되어 있으니
	// 본 파일을 실제 사용하려면 이 설정항에 'yes' 를 대입하십시오.
		$it_uses = "yes";

	// [★ 설정 2] 메일을 받을 사람을 특정인(관리자)으로 고정하려면 그 메일주소와  이름을 대입하십시오.
		$to_mail_set = "ksj_design@naver.com";		// 메일주소
		$to_name_set = "강성주";		// 이름

	// [★ 설정 3] 메일전송 완료후의 출력페이지를 지정하려면 URL 주소 대입(기본상태는 메일 입력폼이 다시 출력됨)
		$after_url="http://kongseongju.com/#lastPage";

	if($_POST['f_mailsend']){

		// 메일발송
			send_mail_action($to_mail_set,$to_name_set);

		// [★ 설정 4] 메일 받을 사람을 여럿 추가하려면
		// send_mail_action("xxyyzz@abcd.net","홍길동");
		// 위의 코드를 메일주소,이름만 바꿔서 여기에 반복해 넣어 주면 됨.

			alert_msg("메일발송 완료되었습니다.",$after_url);
	}
?>

    <?
// 메일내용 html 구성
function mail_body_html($body){
    
	$join_html= <<<EOF_LINE
    
	<html>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
		<style type=text/css>
			body,td,input,div,select,textarea{font-size:9pt; font-family:굴림,Tahoma; line-height:140%; word-break:break-all;}
		</style>
		</head>
		<body bgcolor='#ffffff' topmargin=5>
			<table width=90% border=1 cellspacing=0 cellpadding=1 frame=void bordercolordark='#ffffff' bordercolorlight='#eeeeee' style='margin-bottom:8px;'>
				<tr><td width=120><b>ㆍ</b>보낸사람</td><td> $_POST[mail_from_name]  &lt;$_POST[mail_from_email]&gt;</td></tr>
				<tr><td width=120><b>ㆍ</b>받은사람</td><td> $_POST[mail_to_name]  &lt;$_POST[mail_to_email]&gt;</td></tr>
EOF_LINE;
			// 추가입력란 삽입
			for($i=1; $i<50; $i++){ // 입력추가항목이 50개 이상 된다면 '50'을 수정할것
				if(!$_POST['add_title_'.$i]) continue;
				$title=$_POST['add_title_'.$i];  $value=$_POST['add_value_'.$i];
				$join_html.="
						<tr>
							<td width=120><b>ㆍ</b>$title</td><td> $value</td>
						</tr>
					";
			}
	$join_html.= <<<EOF_LINE
			</table>
			<table width=98% cellpadding=0 cellspacing=0 border=0 bgcolor='#ffffff' style='border:1px solid #1578FF;'>
				<tr><td height=1 bgcolor='#A9CDFF'></td></tr>
				<tr><td height=1 bgcolor='#67A7FF'></td></tr>
				<tr>
					<td bgcolor='#1578FF' style='padding:2px; padding-left:6px; color:#ffffff; font-weight:bold;'>
						&nbsp; $_POST[mail_subject]
					</td>
				</tr>
				<tr><td height=1 bgcolor='#67A7FF'></td></tr>
				<tr><td height=1 bgcolor='#A9CDFF'></td></tr>
				<tr><td height=1 bgcolor='#1578FF'></td></tr>
				<tr>
					<td style='padding:5px'>
						<!--메일 본문 내용-->
						$body
					</td>
				</tr>
			</table>

		</body>
	</html>
    
EOF_LINE;
	return $join_html;
}
       
function send_mail_action($snd_mail,$snd_name){
	if($GLOBALS['it_uses'] != 'yes') alert_msg("메일발송 실패 했습니다. 소스내의 '설정항목1' 을 우선 확인하십시오.");

	if($snd_mail) $_POST['mail_to_email'] = $snd_mail;
	if($snd_name) $_POST['mail_to_name'] = $snd_name;

	if(!org_mail($_POST['mail_to_email'])) alert_msg('받는 사람 메일주소가 잘못 되었습니다.');
	if(!org_mail($_POST['mail_from_email'])) alert_msg('보내는 사람 메일주소가 잘못 되었습니다.');

	if(!$_POST['mail_subject']) alert_msg('제목을 입력하십시오.');
	if(!$_POST['mail_to_name']) alert_msg('받는사람 이름을 입력하십시오.');
	if(!$_POST['mail_from_name']) alert_msg('보낸사람 이름을 입력하십시오.');

	if(!$_POST['mail_body']) alert_msg('본문 내용을 입력하십시오.');

	$mail_to = "\"$_POST[mail_to_name]\" <$_POST[mail_to_email]>";
	$mail_from = "\"$_POST[mail_from_name]\" <$_POST[mail_from_email]>";

	$head  = "From:$_POST[mail_from_email]\n";
	$head .="Content-Type: text/html\n";
	$head .="Reply-To:$_POST[mail_from_email]\n";
	$head .="X-Mailer:PHP/".phpversion();

	$body=nl2br($_POST['mail_body']);
	$body=stripslashes($body);
	$body=mail_body_html($body);

	return @mail($mail_to,$_POST['mail_subject'],$body,$head);
}

function org_mail($mail){
	if(!preg_match("/\S+@(\S+\.\S+)/",$mail,$Tmp)) return ;
	//	if(!checkdnsrr($Tmp[1], "MX") and !checkdnsrr($Tmp[1], "A")) return ;
	return 1;
}
       
function alert_msg($msg,$after_url=""){
	$msg=preg_replace("/\"/","'",$msg);
	echo " <script language='JavaScript'> alert(\"$msg\"); ";
	if($after_url) echo " location.href='$after_url'; ";
	else echo " history.go(-1); ";
	echo "</script>";
	exit;
}
       
?>