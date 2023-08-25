<?php

/*
require_once "./inc/db.php";
*/


if ($_POST['upflg'] == 1) {


// POSTデータのSQLの為のエスケープ処理
foreach ( (array)$_POST as $key => $val ) {
	if (!is_array($val)) $_POST[$key] = htmlentities(pg_escape_string($val), ENT_QUOTES, mb_internal_encoding());
}


	//チェックボックス
	$item = "";

	foreach ( (array)$_POST['item'] as $key => $val ) {
		$item .= $val."  ";
	}


	// ホームページ運営者に返信するメール
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	$today = date("Y/m/d H:i:s");

	/* 案件によって調整
	------------------------------------------------------------ */

	/* 件名、本文冒頭 */
	$mail_title = "";
	//$mail_title = $passmaster_array['hptitle'];

	/* クライアントメールアドレス */
	$mailto   = "info@pleceed.com";
	//$mailto = $infomail;

	/* エンドユーザー返信メール末尾の案件アドレス */
	//$site_url_mail = "http://pleceed.com";
	$site_url_mail = $site_url;
	

	//CC、BCCでメールを送信させる 20170807 kajiwara 編集
	$mailCc  = "info@pleceed.com";
	//$mailCc  = "$infomail_cc;

	/* "メインの送信先アドレスをExtlink発行のメールアドレスにしない場合、
		BCC枠に「backup@{各ドメインアドレス　例：backup@extlink.co.jp}」を入れてください。
		メインアドレスがExtlink発行のメールの場合は別途BCC用のメールアドレスを
		発行し、そちらを設定してください */
	$mailBcc = "matsumoto@pleceed.com,s.ueda@pleceed.com";
	
	//$mailBcc  = "$infomail_bcc;
	
	//送信エラー時の送信先
	//$efrom = "-f"."support@at-gungun.co.jp";

	/* --------------------------------------------------------- */


	$subject = "HPよりお問い合わせがありました";

	$message = <<< maildata
HPよりお問い合わせがありました

送信日時：{$today}

＜送信内容＞

お名前：{$_POST['name']}
フリガナ：{$_POST['kana']}
会社名・屋号：{$_POST['company']}

ご住所：〒{$_POST['zip1']}-{$_POST['zip2']} {$_POST['pref']} {$_POST['city']} {$_POST['add']}
お電話番号：{$_POST['tel']}
メールアドレス：{$_POST['mail']}

お問合せ項目：{$item}

ご相談・ご要望記入欄：{$_POST['msg']}

maildata;

	$fromName = mb_encode_mimeheader($mail_title);
	$header    = "From:{$fromName} <{$mailto}>";


	//CC、BCCでメールを送信させる 20170808 kajiwara 編集
	$header .= "\r\n";
	$header .= "Cc:{$mailCc}";
	$header .= "\r\n";
	$header .= "Bcc:{$mailBcc}" .PHP_EOL;

	//メール送信
	mb_send_mail($mailto, $subject, $message, $header, $efrom);

	// ユーザー宛
	$header = "From:".mb_encode_mimeheader($mail_title)."<".$mailto.">\r\n";
	$mailto_guest = $_POST['mail'];
	$subject = "【プレシード】お問い合わせフォーム確認メール";
	$message = <<<_message
『プレシード』ホームページ より、
お問い合わせ頂きまして、誠にありがとうございます。
お問い合わせ内容については、後日こちらよりご連絡をさせて頂きます。

このメールは、メールサーバより自動送信しています。

送信日時：{$today}

----------------------------------------------------------------------

＜お問い合わせ内容＞

お名前：{$_POST['name']}
フリガナ：{$_POST['kana']}
会社名・屋号：{$_POST['company']}

ご住所：〒{$_POST['zip1']}-{$_POST['zip2']} {$_POST['pref']} {$_POST['city']} {$_POST['add']}
お電話番号：{$_POST['tel']}
メールアドレス：{$_POST['mail']}

お問合せ項目：{$item}

ご相談・ご要望記入欄：{$_POST['msg']}



----------------------------------------------------------------------

プレシードホームページの『お問い合わせ』より送信
URL:{$site_url_mail}

_message;


	//メール送信
	mb_send_mail($mailto_guest,$subject,$message,$header,$efrom);

} else {

	header("Location: ./index.php");
	exit;

}