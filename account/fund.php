<?php
session_start();

include('connection.php');
include_once('inc/payment-wallets.php');
qs_ensure_payment_wallets_table($mysqli);


//check if session id is set if it is redirect to login
if(!isset($_SESSION['id'])){
	
	header("location:login");
}else{

$get_user = mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$_SESSION['id']."' ");
$rows = mysqli_fetch_assoc($get_user);
    if(isset($_SESSION['2fa'])){

        if( ($_SESSION['2fa'] =="no" or $_SESSION['2fa'] =="pending") and $rows['2fa']==1){
            header("location:login");
        }


    }


}



$orderid = isset($_GET['orderid']) ? $_GET['orderid'] : (isset($_POST['orderid']) ? $_POST['orderid'] : '');
$orderid = mysqli_real_escape_string($mysqli, $orderid);

if (isset($_POST['mark-as-paid']) && $orderid !== '') {
	$txnHash = trim(mysqli_real_escape_string($mysqli, isset($_POST['txn_hash']) ? $_POST['txn_hash'] : ''));
	if ($txnHash !== '') {
		$uid = (int) $rows['id'];
		mysqli_query($mysqli, "UPDATE pending SET txn_hash='$txnHash' WHERE chargeid='$orderid' AND userid='$uid'");
	}
	header('Location: fund?orderid=' . urlencode($orderid));
	exit;
}

$getorder = mysqli_query($mysqli,"SELECT * FROM pending WHERE chargeid='$orderid'");
$row = mysqli_fetch_assoc($getorder);
if (!$row) {
	header('location:active-purchase');
	exit;
}

$payAmount = (isset($row['crypto']) && $row['crypto'] !== '' && $row['crypto'] !== null) ? $row['crypto'] : $row['amount'];
$payCurrency = isset($row['currency']) ? $row['currency'] : '';
$payWallet = isset($row['wallet']) ? $row['wallet'] : '';
$qrSrc = isset($row['qrcode']) ? $row['qrcode'] : '';
if ($payWallet !== '') {
	$generatedQr = qs_wallet_qr_data_uri($payWallet);
	if ($generatedQr !== '') {
		$qrSrc = $generatedQr;
	}
}
$txnHashSaved = isset($row['txn_hash']) ? trim($row['txn_hash']) : '';


?>
<!DOCTYPE html>

<html lang="en-US">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Payment Funding </title>

  <script src="./plisio_files/1CZiyAB-A5LaD-JKnH0H06st6to.js"></script>
 
  <link rel="icon" sizes="57x57" href="img/favicon.png">


  <script>
    (function (h, o, t, j, a, r) {
      h.hj = h.hj || function () {
        (h.hj.q = h.hj.q || []).push(arguments)
      };
      h._hjSettings = { hjid: 1864049, hjsv: 6 };
      a = o.getElementsByTagName('head')[0];
      r = o.createElement('script');
      r.async = 1;
      r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
      a.appendChild(r);
    })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
  </script>
  <script>
    var lenght = 0.0;



    var timer = 900,
        minutes, seconds;
    setInterval(() => {
        lenght += 0.111;

        document.getElementById('time-bar').style = "width:" + lenght + "%";
        //////////////////////////////
        /////////////////////////////
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        document.getElementById("time-label").innerHTML = minutes + ":" + seconds;


        if (--timer < 0) {
            //time is over
            timer = 0;
            document.getElementById('orderinvalid').style = "display:block";
            document.getElementById('pay1').style = "display:none";
            document.getElementById('pay2').style = "display:none";
        }






    }, 1000);
    </script>
  <script async="" src="./plisio_files/hotjar-1864049.js"></script>


  <style type="text/css">
    .switch {
      position: relative;
      display: -webkit-inline-box;
      display: -ms-inline-flexbox;
      display: inline-flex;
      width: 64px;
      min-width: 64px;
      height: 24px
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0
    }

    .switch input._checked+.switch__slider {
      background-color: #ffc107
    }

    .switch input._checked+.switch__slider .switch__circle {
      -webkit-transform: translateX(100%);
      transform: translateX(100%)
    }

    .switch__slider {
      top: 0;
      right: 0;
      bottom: 0;
      border-radius: 12px;
      background-color: #ddd;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s;
      cursor: pointer
    }

    .switch__circle,
    .switch__slider {
      position: absolute;
      left: 0;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .switch__circle {
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      height: 32px;
      width: 32px;
      margin: auto 0;
      border-radius: 50%;
      background: #fff;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      -webkit-transition: -webkit-transform .5s;
      transition: -webkit-transform .5s;
      -o-transition: transform .5s;
      transition: transform .5s;
      transition: transform .5s, -webkit-transform .5s
    }

    .switch__icon {
      width: 16px;
      height: 16px;
      fill: #ffc107
    }
  </style>
  <style type="text/css">
    .clipboard__btn[data-v-a6f121a6] {
      display: -webkit-inline-box;
      display: -ms-inline-flexbox;
      display: inline-flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      font-weight: 600;
      color: #ffc107;
      cursor: pointer
    }

    .clipboard__icon_copy[data-v-a6f121a6] {
      width: 1em;
      height: 1em;
      fill: #ffc107
    }

    .clipboard .left[data-v-a6f121a6] {
      margin-right: .5em
    }

    .clipboard .right[data-v-a6f121a6] {
      margin-left: .5em
    }
  </style>
  <style type="text/css">
    ._preLoading .invoice__help[data-v-e6bd7c5e] {
      opacity: 0
    }

    .help[data-v-e6bd7c5e] {
      width: 100%;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: horizontal;
      -webkit-box-direction: normal;
      -ms-flex-direction: row;
      flex-direction: row;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      text-align: center;
      margin-top: auto
    }

    .help[data-v-e6bd7c5e],
    .help__link[data-v-e6bd7c5e] {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .help__link[data-v-e6bd7c5e] {
      display: -webkit-inline-box;
      display: -ms-inline-flexbox;
      display: inline-flex;
      font-weight: 600;
      line-height: 1;
      font-size: .875rem
    }

    .help__link._chat[data-v-e6bd7c5e] {
      color: #eb4545
    }

    .help__icon[data-v-e6bd7c5e] {
      width: 1rem;
      height: 1rem;
      margin-right: .5rem;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .help__icon_que[data-v-e6bd7c5e] {
      fill: #ffc107
    }

    .help__icon_chat[data-v-e6bd7c5e] {
      fill: #eb4545
    }

    .help__sep[data-v-e6bd7c5e] {
      width: 1px;
      height: 20px;
      margin: 0 16px;
      background: #ffc107;
      opacity: .2
    }

    @media screen and (max-width:420px) {
      .help[data-v-e6bd7c5e] {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column
      }

      .help__link~.help__link[data-v-e6bd7c5e] {
        margin-top: 1rem
      }

      .help__sep[data-v-e6bd7c5e] {
        display: none
      }
    }
  </style>
  <style type="text/css">
    .step_loading[data-v-178cf384] {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      text-align: center;
      -webkit-box-flex: 1;
      -ms-flex-positive: 1;
      flex-grow: 1;
      margin-bottom: 16px
    }

    .step_loading__icon_loader[data-v-178cf384] {
      width: 80px;
      height: 80px;
      fill: #ffc107;
      -webkit-animation: rotate-data-v-178cf384 1s linear infinite;
      animation: rotate-data-v-178cf384 1s linear infinite;
      -webkit-transform-origin: 50% 50%;
      transform-origin: 50% 50%
    }

    @-webkit-keyframes rotate-data-v-178cf384 {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }

    @keyframes rotate-data-v-178cf384 {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }
  </style>
  <style type="text/css">
    .fade-in-up-enter-active,
    .fade-in-up-leave-active {
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .fade-in-up-enter,
    .fade-in-up-leave-to {
      opacity: 0;
      -webkit-transform: translateY(.5rem);
      transform: translateY(.5rem)
    }

    #onesignal-popover-container,
    #onesignal-slidedown-container {
      display: none !important
    }

    .invoice {
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      border-radius: 10px
    }

    .invoice__header {
      position: relative;
      z-index: 1;
      border-radius: 10px 10px 0 0;
      background: -webkit-gradient(linear, left top, right top, from(#ffc107), to(#ffc107));
      background: -o-linear-gradient(left, #ffc107 0, #ffc107 100%);
      background: linear-gradient(90deg, #ffc107 0, #ffc107)
    }

    .invoice__appLogo {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex
    }

    .invoice__row_sum {
      position: relative;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      min-height: 81px;
      padding: 1rem;
      background: #fff;
      -webkit-box-shadow: 0 5px 15px -7.5px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px -7.5px rgba(43, 45, 49, .1);
      white-space: nowrap
    }

    .invoice__contentWr {
      border-radius: 0 0 10px 10px;
      background: #f5f6fa
    }

    .invoice__content {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      text-align: center;
      min-height: 514px;
      padding: 0 1rem 24px;
      border-radius: 0 0 10px 10px
    }

    .invoice__content._white {
      background: #fff
    }

    .invoice__title {
      margin: 1rem auto;
      text-align: center;
      line-height: 1
    }

    .invoice__hint {
      margin: 0 auto;
      text-align: center
    }

    .invoice__hint strong {
      color: #2b2d31
    }

    .invoice__form {
      width: 100%;
      padding: 24px
    }

    .invoice .step,
    .invoice /deep/ .step {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      text-align: center;
      -webkit-box-flex: 1;
      -ms-flex-positive: 1;
      flex-grow: 1;
      margin-bottom: 16px
    }

    .header__top,
    .invoice .step,
    .invoice /deep/ .step {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .header__top {
      height: 64px;
      padding: 1rem
    }

    .header__icon_logo {
      width: 51px;
      height: 32px;
      fill: #fff
    }

    .header__emph {
      font-weight: 600;
      color: #ffc107
    }

    .row_sum__shop {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .row_sum__shopLogo {
      -ms-flex-preferred-size: 49px;
      flex-basis: 49px;
      width: 49px;
      height: 49px;
      margin-right: 1rem;
      background-position: 50% 50%;
      background-size: contain;
      background-repeat: no-repeat
    }

    .row_sum__shopName {
      max-width: 150px;
      text-transform: capitalize;
      line-height: 1.21429;
      font-size: .875rem;
      font-weight: 600;
      color: #2b2d31;
      white-space: normal
    }

    .row_sum__val {
      padding-left: 8px;
      text-transform: uppercase;
      text-align: right
    }

    .row_sum__crypto {
      line-height: 1.66667;
      font-size: 1.125rem;
      font-weight: 700
    }

    .row_sum__crypto,
    .row_sum__fiat {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-pack: end;
      -ms-flex-pack: end;
      justify-content: flex-end;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
      color: #2b2d31
    }

    .row_sum__curr,
    .row_sum__curr_fiat {
      margin-left: .5em
    }

    .help-block {
      left: calc(100% + 50px)
    }
  </style>
  <style type="text/css">
    .switch[data-v-5e0cd238] {
      display: none
    }

    .wrap[data-v-5e0cd238] {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      position: relative;
      min-height: 100%;
      background-position: 0 50%;
      background-repeat: no-repeat;
      background-size: 100%
    }

    .wrap[data-v-5e0cd238]>.switch input[type=checkbox]+.switch__slider .switch__circle {
      background: url(/assets/img/night.svg) 50% 50% no-repeat #fff
    }

    .wrap[data-v-5e0cd238]>.switch input[type=checkbox]._checked+.switch__slider .switch__circle {
      background: url(/assets/img/day.svg) 50% 50% no-repeat #fff
    }

    .wrap._theme_light[data-v-5e0cd238] {
      background-color: #f5f6fa;
      background-image: url(/assets/img/invoice/bg-invoice-light.svg)
    }

    .wrap._theme_dark[data-v-5e0cd238] {
      background-color: #2b2d31;
      background-image: url(/assets/img/invoice/bg-invoice-dark.svg)
    }

    .invoice[data-v-5e0cd238] {
      width: 100%;
      max-width: 400px;
      min-width: 320px;
      margin: 34px auto
    }

    @media screen and (min-width:768px) {
      .switch[data-v-5e0cd238] {
        position: fixed;
        top: 34px;
        right: 34px;
        display: block
      }
    }

    @media screen and (max-width:768px) {
      .wrap[data-v-5e0cd238] {
        background-image: none !important
      }
    }

    @media screen and (max-width:480px) {
      .invoice[data-v-5e0cd238] {
        margin: 0;
        width: 100%;
        max-width: 100%
      }

      [data-v-5e0cd238] .invoice__contentWr,
      [data-v-5e0cd238] .invoice__header {
        border-radius: 0
      }

      [data-v-5e0cd238] .invoice__content {
        padding: 40px 0;
        border-radius: 0
      }

      [data-v-5e0cd238] .invoice__content>div {
        padding-bottom: 40px
      }
    }
  </style>
  
  <style type="text/css">
    .el-notification {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      width: 330px;
      padding: 14px 26px 14px 13px;
      border-radius: 8px;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      border: 1px solid #ebeef5;
      position: fixed;
      background-color: #fff;
      -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1);
      box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1);
      -webkit-transition: opacity .3s, left .3s, right .3s, top .4s, bottom .3s, -webkit-transform .3s;
      transition: opacity .3s, left .3s, right .3s, top .4s, bottom .3s, -webkit-transform .3s;
      -o-transition: opacity .3s, transform .3s, left .3s, right .3s, top .4s, bottom .3s;
      transition: opacity .3s, transform .3s, left .3s, right .3s, top .4s, bottom .3s;
      transition: opacity .3s, transform .3s, left .3s, right .3s, top .4s, bottom .3s, -webkit-transform .3s;
      overflow: hidden
    }

    .el-notification.right {
      right: 16px
    }

    .el-notification.left {
      left: 16px
    }

    .el-notification__group {
      margin-left: 13px;
      margin-right: 8px
    }

    .el-notification__title {
      font-weight: 700;
      font-size: 16px;
      color: #303133;
      margin: 0
    }

    .el-notification__content {
      font-size: 14px;
      line-height: 21px;
      margin: 6px 0 0;
      color: #606266;
      text-align: justify
    }

    .el-notification__content p {
      margin: 0
    }

    .el-notification__icon {
      height: 24px;
      width: 24px;
      font-size: 24px
    }

    .el-notification__closeBtn {
      position: absolute;
      top: 18px;
      right: 15px;
      cursor: pointer;
      color: #909399;
      font-size: 16px
    }

    .el-notification__closeBtn:hover {
      color: #606266
    }

    .el-notification .el-icon-success {
      color: #67c23a
    }

    .el-notification .el-icon-error {
      color: #f56c6c
    }

    .el-notification .el-icon-info {
      color: #909399
    }

    .el-notification .el-icon-warning {
      color: #e6a23c
    }

    .el-notification-fade-enter.right {
      right: 0;
      -webkit-transform: translateX(100%);
      transform: translateX(100%)
    }

    .el-notification-fade-enter.left {
      left: 0;
      -webkit-transform: translateX(-100%);
      transform: translateX(-100%)
    }

    .el-notification-fade-leave-active {
      opacity: 0
    }
  </style>
  <style type="text/css">
    .v-modal-enter {
      -webkit-animation: v-modal-in .2s ease;
      animation: v-modal-in .2s ease
    }

    .v-modal-leave {
      -webkit-animation: v-modal-out .2s ease forwards;
      animation: v-modal-out .2s ease forwards
    }

    @-webkit-keyframes v-modal-in {
      0% {
        opacity: 0
      }
    }

    @keyframes v-modal-in {
      0% {
        opacity: 0
      }
    }

    @-webkit-keyframes v-modal-out {
      to {
        opacity: 0
      }
    }

    @keyframes v-modal-out {
      to {
        opacity: 0
      }
    }

    .v-modal {
      position: fixed;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: .5;
      background: #000
    }

    .el-popup-parent--hidden {
      overflow: hidden
    }

    .el-dialog {
      position: relative;
      margin: 0 auto 50px;
      background: #fff;
      border-radius: 2px;
      -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
      box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      width: 50%
    }

    .el-dialog.is-fullscreen {
      width: 100%;
      margin-top: 0;
      margin-bottom: 0;
      height: 100%;
      overflow: auto
    }

    .el-dialog__wrapper {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      overflow: auto;
      margin: 0
    }

    .el-dialog__header {
      padding: 20px 20px 10px
    }

    .el-dialog__headerbtn {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 0;
      background: transparent;
      border: none;
      outline: none;
      cursor: pointer;
      font-size: 16px
    }

    .el-dialog__headerbtn .el-dialog__close {
      color: #909399
    }

    .el-dialog__headerbtn:focus .el-dialog__close,
    .el-dialog__headerbtn:hover .el-dialog__close {
      color: #409eff
    }

    .el-dialog__title {
      line-height: 24px;
      font-size: 18px;
      color: #303133
    }

    .el-dialog__body {
      padding: 30px 20px;
      color: #606266;
      font-size: 14px;
      word-break: break-all
    }

    .el-dialog__footer {
      padding: 10px 20px 20px;
      text-align: right;
      -webkit-box-sizing: border-box;
      box-sizing: border-box
    }

    .el-dialog--center {
      text-align: center
    }

    .el-dialog--center .el-dialog__body {
      text-align: initial;
      padding: 25px 25px 30px
    }

    .el-dialog--center .el-dialog__footer {
      text-align: inherit
    }

    .dialog-fade-enter-active {
      -webkit-animation: dialog-fade-in .3s;
      animation: dialog-fade-in .3s
    }

    .dialog-fade-leave-active {
      -webkit-animation: dialog-fade-out .3s;
      animation: dialog-fade-out .3s
    }

    @-webkit-keyframes dialog-fade-in {
      0% {
        -webkit-transform: translate3d(0, -20px, 0);
        transform: translate3d(0, -20px, 0);
        opacity: 0
      }

      to {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        opacity: 1
      }
    }

    @keyframes dialog-fade-in {
      0% {
        -webkit-transform: translate3d(0, -20px, 0);
        transform: translate3d(0, -20px, 0);
        opacity: 0
      }

      to {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        opacity: 1
      }
    }

    @-webkit-keyframes dialog-fade-out {
      0% {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        opacity: 1
      }

      to {
        -webkit-transform: translate3d(0, -20px, 0);
        transform: translate3d(0, -20px, 0);
        opacity: 0
      }
    }

    @keyframes dialog-fade-out {
      0% {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        opacity: 1
      }

      to {
        -webkit-transform: translate3d(0, -20px, 0);
        transform: translate3d(0, -20px, 0);
        opacity: 0
      }
    }
  </style>
  <style type="text/css">
    .el-tooltip:focus:hover,
    .el-tooltip:focus:not(.focusing) {
      outline-width: 0
    }

    .el-tooltip__popper {
      position: absolute;
      border-radius: 4px;
      padding: 10px;
      z-index: 2000;
      font-size: 12px;
      line-height: 1.2;
      min-width: 10px;
      word-wrap: break-word
    }

    .el-tooltip__popper .popper__arrow,
    .el-tooltip__popper .popper__arrow:after {
      position: absolute;
      display: block;
      width: 0;
      height: 0;
      border-color: transparent;
      border-style: solid
    }

    .el-tooltip__popper .popper__arrow {
      border-width: 6px
    }

    .el-tooltip__popper .popper__arrow:after {
      content: " ";
      border-width: 5px
    }

    .el-tooltip__popper[x-placement^=top] {
      margin-bottom: 12px
    }

    .el-tooltip__popper[x-placement^=top] .popper__arrow {
      bottom: -6px;
      border-top-color: #303133;
      border-bottom-width: 0
    }

    .el-tooltip__popper[x-placement^=top] .popper__arrow:after {
      bottom: 1px;
      margin-left: -5px;
      border-top-color: #303133;
      border-bottom-width: 0
    }

    .el-tooltip__popper[x-placement^=bottom] {
      margin-top: 12px
    }

    .el-tooltip__popper[x-placement^=bottom] .popper__arrow {
      top: -6px;
      border-top-width: 0;
      border-bottom-color: #303133
    }

    .el-tooltip__popper[x-placement^=bottom] .popper__arrow:after {
      top: 1px;
      margin-left: -5px;
      border-top-width: 0;
      border-bottom-color: #303133
    }

    .el-tooltip__popper[x-placement^=right] {
      margin-left: 12px
    }

    .el-tooltip__popper[x-placement^=right] .popper__arrow {
      left: -6px;
      border-right-color: #303133;
      border-left-width: 0
    }

    .el-tooltip__popper[x-placement^=right] .popper__arrow:after {
      bottom: -5px;
      left: 1px;
      border-right-color: #303133;
      border-left-width: 0
    }

    .el-tooltip__popper[x-placement^=left] {
      margin-right: 12px
    }

    .el-tooltip__popper[x-placement^=left] .popper__arrow {
      right: -6px;
      border-right-width: 0;
      border-left-color: #303133
    }

    .el-tooltip__popper[x-placement^=left] .popper__arrow:after {
      right: 1px;
      bottom: -5px;
      margin-left: -5px;
      border-right-width: 0;
      border-left-color: #303133
    }

    .el-tooltip__popper.is-dark {
      background: #303133;
      color: #fff
    }

    .el-tooltip__popper.is-light {
      background: #fff;
      border: 1px solid #303133
    }

    .el-tooltip__popper.is-light[x-placement^=top] .popper__arrow {
      border-top-color: #303133
    }

    .el-tooltip__popper.is-light[x-placement^=top] .popper__arrow:after {
      border-top-color: #fff
    }

    .el-tooltip__popper.is-light[x-placement^=bottom] .popper__arrow {
      border-bottom-color: #303133
    }

    .el-tooltip__popper.is-light[x-placement^=bottom] .popper__arrow:after {
      border-bottom-color: #fff
    }

    .el-tooltip__popper.is-light[x-placement^=left] .popper__arrow {
      border-left-color: #303133
    }

    .el-tooltip__popper.is-light[x-placement^=left] .popper__arrow:after {
      border-left-color: #fff
    }

    .el-tooltip__popper.is-light[x-placement^=right] .popper__arrow {
      border-right-color: #303133
    }

    .el-tooltip__popper.is-light[x-placement^=right] .popper__arrow:after {
      border-right-color: #fff
    }
  </style>
  <style type="text/css">
    @font-face {
      font-family: Proxima Nova Bold;
      src: url(/assets/fonts/ProximaNova/ProximaNova-Bold.eot?#iefix) format("embedded-opentype"), url(/assets/fonts/ProximaNova/ProximaNova-Bold.woff) format("woff"), url(/assets/fonts/ProximaNova/ProximaNova-Bold.ttf) format("truetype");
      font-weight: 700;
      font-display: fallback
    }

    @font-face {
      font-family: SF Pro Text;
      font-style: normal;
      font-weight: 400;
      src: url(/assets/fonts/SanFrancisco/SFUIText-Regular.eot?#iefix) format("embedded-opentype"), url(/assets/fonts/SanFrancisco/SFUIText-Regular.woff) format("woff"), url(/assets/fonts/SanFrancisco/SFUIText-Regular.ttf) format("truetype");
      font-display: fallback
    }

    @font-face {
      font-family: SF Pro Text;
      font-style: normal;
      font-weight: 600;
      src: url(/assets/fonts/SanFrancisco/SFUIText-Semibold.eot?#iefix) format("embedded-opentype"), url(/assets/fonts/SanFrancisco/SFUIText-Semibold.woff) format("woff"), url(/assets/fonts/SanFrancisco/SFUIText-Semibold.ttf) format("truetype");
      font-display: fallback
    }

    @font-face {
      font-family: SF Pro Text;
      font-style: normal;
      font-weight: 700;
      src: url(/assets/fonts/SanFrancisco/SFUIText-Bold.eot?#iefix) format("embedded-opentype"), url(/assets/fonts/SanFrancisco/SFUIText-Bold.woff) format("woff"), url(/assets/fonts/SanFrancisco/SFUIText-Bold.ttf) format("truetype");
      font-display: fallback
    }

    html {
      -webkit-box-sizing: border-box;
      box-sizing: border-box
    }

    *,
    :after,
    :before {
      -webkit-box-sizing: inherit;
      box-sizing: inherit
    }

    a,
    abbr,
    acronym,
    address,
    applet,
    article,
    aside,
    audio,
    b,
    big,
    blockquote,
    body,
    canvas,
    caption,
    center,
    cite,
    code,
    dd,
    del,
    details,
    dfn,
    div,
    dl,
    dt,
    em,
    embed,
    fieldset,
    figcaption,
    figure,
    footer,
    form,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    header,
    hgroup,
    html,
    i,
    iframe,
    img,
    ins,
    kbd,
    label,
    legend,
    li,
    mark,
    menu,
    nav,
    object,
    ol,
    output,
    p,
    pre,
    q,
    ruby,
    s,
    samp,
    section,
    small,
    span,
    strike,
    strong,
    sub,
    summary,
    sup,
    table,
    tbody,
    td,
    tfoot,
    th,
    thead,
    time,
    tr,
    tt,
    u,
    ul,
    var,
    video {
      margin: 0;
      padding: 0;
      border: 0;
      outline: 0;
      font-size: 100%;
      vertical-align: baseline;
      background: transparent
    }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    menu,
    nav,
    section {
      display: block
    }

    body {
      line-height: 1
    }

    ol,
    ul {
      list-style: none
    }

    blockquote,
    q {
      quotes: none
    }

    blockquote:after,
    blockquote:before,
    q:after,
    q:before {
      content: "";
      content: none
    }

    table {
      border-collapse: collapse;
      border-spacing: 0
    }

    td img {
      vertical-align: top
    }

    button,
    input,
    select,
    textarea {
      margin: 0;
      font-size: 100%;
      outline: none
    }

    button:focus,
    input:focus,
    select:focus,
    textarea:focus {
      outline: none
    }

    button ::-moz-focus-inner,
    input ::-moz-focus-inner,
    select ::-moz-focus-inner,
    textarea ::-moz-focus-inner {
      border: 0
    }

    button ::-ms-clear,
    input ::-ms-clear,
    select ::-ms-clear,
    textarea ::-ms-clear {
      display: none;
      width: 0;
      height: 0
    }

    input[type=password],
    input[type=text],
    textarea {
      padding: 0
    }

    input[type=checkbox] {
      vertical-align: bottom
    }

    input[type=radio] {
      vertical-align: text-bottom
    }

    sub {
      vertical-align: sub;
      font-size: smaller
    }

    sup {
      vertical-align: super
    }

    nav ul {
      list-style: none
    }

    a {
      margin: 0;
      padding: 0;
      font-size: 100%;
      vertical-align: baseline;
      background: transparent
    }

    a,
    a:focus {
      outline: none
    }

    body,
    html {
      height: 100%
    }

    body {
      font-family: SF Pro Text, helvetica, arial, tahoma, verdana, sans-serif;
      line-height: 1.75;
      font-size: 16px;
      letter-spacing: 0;
      color: #ffc107;
      -moz-osx-font-smoothing: grayscale;
      -webkit-font-smoothing: antialiased;
      font-smoothing: antialiased;
      text-rendering: optimizeLegibility
    }

    * {
      font-family: inherit;
      word-wrap: normal
    }

    ::-moz-selection {
      background: #507cec;
      color: #fff
    }

    ::selection {
      background: #507cec;
      color: #fff
    }

    * {
      -webkit-tap-highlight-color: transparent
    }

    a {
      text-decoration: none;
      cursor: pointer;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    a:focus,
    a:hover,
    a:link,
    a:visited {
      text-decoration: none;
      color: #507cec
    }

    ._link,
    a._link {
      font-weight: 600;
      color: #ffc107;
      cursor: pointer
    }

    img {
      max-width: 100%
    }

    p {
      padding-bottom: 1em;
      font-size: 1rem
    }

    strong {
      font-weight: 600
    }

    h1,
    h2,
    h3 {
      font-family: Proxima Nova Bold, arial, tahoma, verdana, helvetica, sans-serif
    }

    h4,
    h5,
    h6 {
      font-family: SF Pro Text, helvetica, arial, tahoma, verdana, sans-serif;
      color: #2b2d31
    }

    h1,
    h2 {
      line-height: 1.22222;
      font-size: 2.25rem
    }

    h3 {
      line-height: 1.33333;
      font-size: 1.875rem
    }

    h4 {
      line-height: 1.18182;
      font-size: 1.375rem
    }

    ._svg_text {
      width: 100%;
      max-width: 100%
    }

    .help-block {
      display: none !important;
      min-width: 280px;
      line-height: 1.21429;
      font-size: .875rem;
      cursor: default
    }

    label {
      display: inline-block;
      margin-bottom: 16px;
      font-size: .875rem;
      cursor: pointer
    }

    .input_text,
    input.input_text,
    label,
    textarea.input_textarea {
      line-height: 1;
      font-weight: 600;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .input_text,
    input.input_text,
    textarea.input_textarea {
      display: block;
      width: 100%;
      padding: 10px 16px;
      border: none;
      border-radius: 10px;
      background: #f5f6fa;
      font-family: SF Pro Text, helvetica, arial, tahoma, verdana, sans-serif;
      font-size: 1rem;
      color: #ffc107
    }

    .input_text::-webkit-input-placeholder,
    input.input_text::-webkit-input-placeholder,
    textarea.input_textarea::-webkit-input-placeholder {
      font-weight: 400;
      color: #a5a5a5
    }

    .input_text::-moz-placeholder,
    input.input_text::-moz-placeholder,
    textarea.input_textarea::-moz-placeholder {
      font-weight: 400;
      color: #a5a5a5
    }

    .input_text:-ms-input-placeholder,
    input.input_text:-ms-input-placeholder,
    textarea.input_textarea:-ms-input-placeholder {
      font-weight: 400;
      color: #a5a5a5
    }

    .input_text::-ms-input-placeholder,
    input.input_text::-ms-input-placeholder,
    textarea.input_textarea::-ms-input-placeholder {
      font-weight: 400;
      color: #a5a5a5
    }

    .input_text::placeholder,
    input.input_text::placeholder,
    textarea.input_textarea::placeholder {
      font-weight: 400;
      color: #a5a5a5
    }

    .input_text[aria-invalid=true],
    input.input_text[aria-invalid=true],
    textarea.input_textarea[aria-invalid=true] {
      background: #f5f6fa
    }

    .input_text._readonly,
    .input_text[readonly],
    input.input_text._readonly,
    input.input_text[readonly],
    textarea.input_textarea._readonly,
    textarea.input_textarea[readonly] {
      background: #ddd;
      font-weight: 600;
      cursor: default;
      color: #ffc107
    }

    .input_text,
    input.input_text {
      height: 40px
    }

    textarea.input_textarea {
      height: 100px;
      resize: none
    }

    .input_text._rub {
      min-height: inherit;
      height: auto;
      word-break: break-all;
      text-align: left;
      line-height: 1.75
    }

    .input_select {
      position: relative;
      background: #f7f7f7;
      border-radius: 8px;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .input_select select {
      display: none
    }

    .input_select._active {
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      border-color: transparent !important;
      background: transparent !important
    }

    .input_select._active .input_select__selected {
      border-radius: 8px 8px 0 0;
      background: #fff
    }

    .input_select._active .input_select__selected:after {
      opacity: 1;
      -webkit-transform: scaleY(-1);
      transform: scaleY(-1)
    }

    .input_select._active .input_select__list {
      visibility: visible;
      opacity: 1;
      pointer-events: auto;
      -webkit-transform: translateY(0);
      transform: translateY(0)
    }

    .input_select._withAct {
      border: 1px solid #ffc107;
      background: #ffc107
    }

    .input_select._withAct .input_select__selected {
      background: #ffc107;
      color: #fff
    }

    .input_select._withAct .input_select__selected:after {
      opacity: 0;
      visibility: hidden
    }

    .input_select._need_1._dirty .input_select__item:first-child {
      display: block
    }

    .input_select__selected {
      position: relative;
      z-index: 2;
      height: 40px;
      background: #f7f7f7;
      border-radius: 10px;
      font-family: SF Pro Text, helvetica, arial, tahoma, verdana, sans-serif;
      font-weight: 600;
      color: #ffc107;
      -webkit-transition: background .5s;
      -o-transition: background .5s;
      transition: background .5s
    }

    .input_select__selected:after {
      content: "";
      top: 17px;
      right: 10px;
      width: 10px;
      height: 6px;
      background: url(/assets/img/icons/icon-arrow.svg) 50% 50% no-repeat;
      opacity: .5
    }

    .input_select__list,
    .input_select__selected:after {
      position: absolute;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .input_select__list {
      background-color: #fff;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 3;
      border-radius: 0 0 10px 10px;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      visibility: hidden;
      opacity: 0;
      pointer-events: none;
      -webkit-transform: translateY(-10px);
      transform: translateY(-10px);
      max-height: 210px;
      overflow-y: auto
    }

    .input_select__list ._active {
      background: #f7f7f7
    }

    .input_select__item,
    .input_select__selected {
      padding: 13px 30px 11px 18px;
      text-align: left;
      white-space: nowrap;
      cursor: pointer
    }

    .input_select__item {
      z-index: 11;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .input_select__item:first-child {
      display: none
    }

    .input_select__item:last-child {
      border-radius: 0 0 10px 10px
    }

    .input_select__item._sameAsSelected,
    .input_select__item:hover {
      border-radius: 1px;
      background: #f0f0f0;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1)
    }

    .input_select__item._sameAsSelected:last-child,
    .input_select__item:hover:last-child {
      border-radius: 0 0 10px 10px
    }

    .input_select__btn_close,
    .input_select__btn_currency,
    .input_select__btn_status,
    .input_select__btn_type {
      position: absolute;
      top: 50%;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      display: inline-block;
      background-position: 50% 50%;
      background-repeat: no-repeat;
      background-size: contain
    }

    .input_select__btn_close {
      right: 10px;
      z-index: 1;
      width: .8571428571428571em;
      height: .8571428571428571em;
      background-image: url(/assets/img/icons/icon-close.svg)
    }

    .input_select__btn_type {
      background-image: url(/assets/img/icons/icon-list.svg)
    }

    .input_select__btn_status,
    .input_select__btn_type {
      position: static;
      -webkit-transform: none;
      transform: none;
      width: 1.0714285714285714em;
      height: .7857142857142857em;
      margin-right: .5em
    }

    .input_select__btn_status {
      background-image: url(/assets/img/icons/icon-confirmed.svg)
    }

    .input_select__btn_currency {
      position: static;
      -webkit-transform: none;
      transform: none;
      width: 1.0714285714285714em;
      height: 1.0714285714285714em;
      margin-right: .5em;
      background-image: url(/assets/img/icons/icon-currency.svg);
      vertical-align: -.15em
    }

    .form-group {
      position: relative
    }

    .form-group+.form-group {
      margin-top: 16px
    }

    .form-group._hasError label {
      color: #eb4545
    }

    .form-group._hasError label .help-block {
      color: #ffc107
    }

    .form-group._hasError .input_text,
    .form-group._hasError input.input_text,
    .form-group._hasError textarea.input_textarea {
      background: #f5f6fa
    }

    .form-group._hasError .help-block {
      display: block !important;
      opacity: 1;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%)
    }

    .form-group._hasSuccess label {
      color: #4371e5
    }

    .form-group._withTooltip>.row_between {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      margin-bottom: 8px
    }

    .form-group._withTooltip .form__tooltip {
      top: 0
    }

    .form-group._withTooltip label {
      margin: 0
    }

    ._overlay_menu_user,
    .overlay,
    .overlay_menu_top {
      position: fixed;
      left: 0;
      top: 0;
      z-index: 10;
      visibility: hidden;
      width: 100%;
      height: 100%;
      min-height: 100%;
      opacity: 0;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    ._active._overlay_menu_user,
    ._active.overlay_menu_top,
    .overlay._active {
      visibility: visible;
      opacity: .5
    }

    .overlay_menu_top {
      z-index: 1000;
      background: #000
    }

    ._overlay_menu_user {
      z-index: 100
    }

    .dropdown {
      position: relative
    }

    .dropdown__toggle {
      cursor: pointer
    }

    .dropdown__toggle._active~.dropdown__target {
      z-index: 101;
      display: block;
      visibility: visible;
      opacity: 1;
      pointer-events: auto;
      -webkit-transform: translateY(0);
      transform: translateY(0);
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .dropdown__target {
      position: absolute;
      right: 0;
      top: 38px;
      z-index: -1;
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      -webkit-transform: translateY(-20px);
      transform: translateY(-20px);
      -webkit-transition: all .25s;
      -o-transition: all .25s;
      transition: all .25s
    }

    .container {
      max-width: 1440px;
      padding: 0 20px;
      margin: 0 auto
    }

    .row,
    .row_around,
    .row_between,
    .row_center,
    .row_end,
    .row_start {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex
    }

    .row_start {
      -webkit-box-pack: start;
      -ms-flex-pack: start;
      justify-content: flex-start
    }

    .row_end {
      -webkit-box-pack: end;
      -ms-flex-pack: end;
      justify-content: flex-end
    }

    .row_between {
      -webkit-box-pack: justify;
      -ms-flex-pack: justify;
      justify-content: space-between
    }

    .row_around {
      -ms-flex-pack: distribute;
      justify-content: space-around
    }

    .row_center {
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center
    }

    ._hidden {
      position: absolute;
      z-index: -1;
      opacity: 0;
      visibility: hidden
    }

    ._ovh {
      overflow: hidden
    }

    ._table_responsive {
      overflow-x: auto
    }

    ._btn_bordered_blue_1,
    ._btn_bordered_gray_light_2,
    ._btn_bordered_white_1,
    ._btn_filled_blue_1,
    ._btn_filled_red_1,
    ._btn_filled_white_1,
    .btn,
    a._btn_bordered_blue_1,
    a._btn_bordered_gray_light_2,
    a._btn_bordered_white_1,
    a._btn_filled_blue_1,
    a._btn_filled_red_1,
    a._btn_filled_white_1,
    a.btn,
    button._btn_bordered_blue_1,
    button._btn_bordered_gray_light_2,
    button._btn_bordered_white_1,
    button._btn_filled_blue_1,
    button._btn_filled_red_1,
    button._btn_filled_white_1,
    button.btn,
    input._btn_bordered_blue_1,
    input._btn_bordered_gray_light_2,
    input._btn_bordered_white_1,
    input._btn_filled_blue_1,
    input._btn_filled_red_1,
    input._btn_filled_white_1,
    input.btn {
      display: -webkit-inline-box;
      display: -ms-inline-flexbox;
      display: inline-flex;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      border-radius: 10px;
      outline: none;
      padding: 0 1rem;
      text-align: center;
      font-size: 1.125rem;
      font-weight: 600;
      cursor: pointer;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    ._btn_bordered_blue_1::-moz-focus-inner,
    ._btn_bordered_gray_light_2::-moz-focus-inner,
    ._btn_bordered_white_1::-moz-focus-inner,
    ._btn_filled_blue_1::-moz-focus-inner,
    ._btn_filled_red_1::-moz-focus-inner,
    ._btn_filled_white_1::-moz-focus-inner,
    .btn::-moz-focus-inner,
    a._btn_bordered_blue_1::-moz-focus-inner,
    a._btn_bordered_gray_light_2::-moz-focus-inner,
    a._btn_bordered_white_1::-moz-focus-inner,
    a._btn_filled_blue_1::-moz-focus-inner,
    a._btn_filled_red_1::-moz-focus-inner,
    a._btn_filled_white_1::-moz-focus-inner,
    a.btn::-moz-focus-inner,
    button._btn_bordered_blue_1::-moz-focus-inner,
    button._btn_bordered_gray_light_2::-moz-focus-inner,
    button._btn_bordered_white_1::-moz-focus-inner,
    button._btn_filled_blue_1::-moz-focus-inner,
    button._btn_filled_red_1::-moz-focus-inner,
    button._btn_filled_white_1::-moz-focus-inner,
    button.btn::-moz-focus-inner,
    input._btn_bordered_blue_1::-moz-focus-inner,
    input._btn_bordered_gray_light_2::-moz-focus-inner,
    input._btn_bordered_white_1::-moz-focus-inner,
    input._btn_filled_blue_1::-moz-focus-inner,
    input._btn_filled_red_1::-moz-focus-inner,
    input._btn_filled_white_1::-moz-focus-inner,
    input.btn::-moz-focus-inner {
      border: none
    }

    ._btn_bordered_blue_1:disabled,
    ._btn_bordered_gray_light_2:disabled,
    ._btn_bordered_white_1:disabled,
    ._btn_filled_blue_1:disabled,
    ._btn_filled_red_1:disabled,
    ._btn_filled_white_1:disabled,
    .btn:disabled,
    a._btn_bordered_blue_1:disabled,
    a._btn_bordered_gray_light_2:disabled,
    a._btn_bordered_white_1:disabled,
    a._btn_filled_blue_1:disabled,
    a._btn_filled_red_1:disabled,
    a._btn_filled_white_1:disabled,
    a.btn:disabled,
    button._btn_bordered_blue_1:disabled,
    button._btn_bordered_gray_light_2:disabled,
    button._btn_bordered_white_1:disabled,
    button._btn_filled_blue_1:disabled,
    button._btn_filled_red_1:disabled,
    button._btn_filled_white_1:disabled,
    button.btn:disabled,
    input._btn_bordered_blue_1:disabled,
    input._btn_bordered_gray_light_2:disabled,
    input._btn_bordered_white_1:disabled,
    input._btn_filled_blue_1:disabled,
    input._btn_filled_red_1:disabled,
    input._btn_filled_white_1:disabled,
    input.btn:disabled {
      cursor: not-allowed
    }

    ._btn_form_lg,
    a._btn_form_lg,
    button._btn_form_lg,
    input._btn_form_lg {
      width: 100%;
      min-height: 50px;
      margin-top: 24px;
      font-size: 1.125rem !important
    }

    ._btn_filled_blue_1,
    ._btn_filled_red_1,
    ._btn_filled_white_1,
    a._btn_filled_blue_1,
    a._btn_filled_red_1,
    a._btn_filled_white_1 {
      border: none;
      color: #fff
    }

    ._btn_filled_blue_1:disabled,
    ._btn_filled_red_1:disabled,
    ._btn_filled_white_1:disabled {
      background: #ddd !important
    }

    ._btn_filled_blue_1,
    a._btn_filled_blue_1 {
      background: #ffc107
    }

    ._btn_filled_blue_1:focus,
    ._btn_filled_blue_1:hover,
    a._btn_filled_blue_1:focus,
    a._btn_filled_blue_1:hover {
      background: #6a8fef
    }

    ._btn_filled_red_1,
    a._btn_filled_red_1 {
      background: #eb4545
    }

    ._btn_filled_red_1:focus,
    ._btn_filled_red_1:hover,
    a._btn_filled_red_1:focus,
    a._btn_filled_red_1:hover {
      background: #f5a1a1
    }

    ._btn_filled_white_1,
    a._btn_filled_white_1 {
      background: #eb4545
    }

    ._btn_filled_white_1:focus,
    ._btn_filled_white_1:hover,
    a._btn_filled_white_1:focus,
    a._btn_filled_white_1:hover {
      background: #f5a1a1
    }

    ._btn_filled_white_1,
    a._btn_filled_white_1 {
      border: none;
      background: #fff;
      color: #ffc107
    }

    ._btn_filled_white_1:disabled,
    ._btn_filled_white_1:focus,
    ._btn_filled_white_1:hover,
    a._btn_filled_white_1:disabled,
    a._btn_filled_white_1:focus,
    a._btn_filled_white_1:hover {
      background: #dae3fb
    }

    ._btn_bordered_blue_1,
    a._btn_bordered_blue_1 {
      border: 2px solid #ffc107;
      background: transparent;
      color: #ffc107
    }

    ._btn_bordered_blue_1._active,
    ._btn_bordered_blue_1:focus,
    ._btn_bordered_blue_1:hover,
    a._btn_bordered_blue_1._active,
    a._btn_bordered_blue_1:focus,
    a._btn_bordered_blue_1:hover {
      background: #ffc107;
      color: #fff
    }

    ._btn_bordered_gray_light_2,
    a._btn_bordered_gray_light_2 {
      border: 2px solid #a5a5a5;
      background: transparent;
      color: #ffc107
    }

    ._btn_bordered_gray_light_2 .btn__icon,
    a._btn_bordered_gray_light_2 .btn__icon {
      fill: #ffc107
    }

    ._btn_bordered_gray_light_2._active,
    ._btn_bordered_gray_light_2:focus,
    ._btn_bordered_gray_light_2:hover,
    a._btn_bordered_gray_light_2._active,
    a._btn_bordered_gray_light_2:focus,
    a._btn_bordered_gray_light_2:hover {
      border-color: #ffc107;
      background: #ffc107;
      color: #fff
    }

    ._btn_bordered_gray_light_2._active .btn__icon,
    ._btn_bordered_gray_light_2:focus .btn__icon,
    ._btn_bordered_gray_light_2:hover .btn__icon,
    a._btn_bordered_gray_light_2._active .btn__icon,
    a._btn_bordered_gray_light_2:focus .btn__icon,
    a._btn_bordered_gray_light_2:hover .btn__icon {
      fill: #fff
    }

    ._btn_bordered_white_1,
    a._btn_bordered_white_1 {
      border: 2px solid #fff;
      background: transparent;
      color: #fff
    }

    ._btn_bordered_white_1:focus,
    ._btn_bordered_white_1:hover,
    a._btn_bordered_white_1:focus,
    a._btn_bordered_white_1:hover {
      background: #fff;
      color: #ffc107
    }

    ._btn_with_icon,
    a._btn_with_icon {
      display: -webkit-inline-box;
      display: -ms-inline-flexbox;
      display: inline-flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      font-weight: 600;
      color: #ffc107;
      cursor: pointer
    }

    ._btn_with_icon svg,
    a._btn_with_icon svg {
      fill: #ffc107
    }

    .btn__icon {
      width: 1rem;
      height: 1rem;
      margin-left: .5rem
    }

    .btn__icon_add,
    .btn__icon_copy,
    .btn__icon_link_external,
    .btn__icon_view {
      fill: #ffc107
    }

    .btn__icon_link_external {
      width: 1rem;
      height: 1rem;
      margin-left: .5rem
    }

    ._btn_lg {
      min-height: 50px
    }

    ._btn_lg,
    ._btn_md {
      min-width: 200px
    }

    ._btn_sm {
      min-width: 107px
    }

    .form-group._hasError .tooltip {
      z-index: 10
    }

    .tooltip {
      position: relative;
      cursor: pointer
    }

    .tooltip:hover {
      z-index: 11 !important
    }

    .tooltip:hover .help-block,
    .tooltip:hover .tooltip__content {
      display: block;
      visibility: visible;
      opacity: 1
    }

    .tooltip:hover .help-block,
    .tooltip:hover .tooltip__content._to_left {
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%)
    }

    .help-block,
    .tooltip__content {
      position: absolute;
      z-index: 10;
      display: none;
      padding: 13px 16px;
      border-radius: 10px;
      background: #fff;
      -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
      box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
      text-align: left;
      opacity: 0;
      -webkit-transition: all 1s;
      -o-transition: all 1s;
      transition: all 1s
    }

    .help-block:before,
    .tooltip__content:before {
      content: "";
      position: absolute;
      z-index: -1;
      display: block;
      width: 8px;
      height: 8px;
      background: #fff;
      -webkit-transform: rotate(45deg);
      transform: rotate(45deg)
    }

    .help-block,
    .tooltip__content._to_left {
      top: 50%;
      right: 24px;
      -webkit-transform: translate(-16px, -50%);
      transform: translate(-16px, -50%)
    }

    .help-block:before,
    .tooltip__content._to_left:before {
      left: -7px;
      top: 50%;
      -webkit-transform: rotate(45deg) translateY(-50%);
      transform: rotate(45deg) translateY(-50%)
    }

    .el-dialog {
      width: 530px;
      border-radius: 10px;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1)
    }

    .el-dialog__wrapper {
      -webkit-transform: none !important;
      transform: none !important
    }

    .el-dialog__header {
      padding: 32px 64px 16px
    }

    .el-dialog__body {
      padding: 0 64px 32px !important;
      font-size: 1rem;
      word-break: break-word
    }

    .el-dialog__title {
      text-align: center;
      font-family: Proxima Nova Bold, arial, tahoma, verdana, helvetica, sans-serif;
      color: #ffc107;
      line-height: 1.33333;
      font-size: 1.875rem
    }

    .el-dialog__headerbtn {
      top: 24px;
      right: 24px;
      width: 1rem;
      height: 1rem;
      background: url(/assets/img/icons/icon-close.svg) 50% 50% no-repeat
    }

    .el-dialog__close {
      display: none
    }

    .el-tooltip__popper {
      padding: 13px 16px;
      border-radius: 10px;
      line-height: 1.21429;
      font-size: .875rem
    }

    .el-tooltip__popper:not(._allow_fullscreen) {
      max-width: 280px
    }

    .el-tooltip__popper.is-light {
      border-color: #fff !important
    }

    .el-tooltip__popper.is-light,
    .el-tooltip__popper.is-light .popper__arrow {
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1) !important;
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1) !important
    }

    .el-tooltip__popper.is-light[x-placement^=right] .popper__arrow {
      border-right-color: #fff !important
    }

    .el-tooltip__popper.is-light[x-placement^=left] .popper__arrow {
      border-left-color: #fff !important
    }

    .el-tooltip__popper.is-light[x-placement^=top] .popper__arrow {
      border-top-color: #fff !important
    }

    .blockContent_white {
      border-radius: 10px;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      background: #fff
    }

    ._btn_form_lg {
      margin-top: 16px !important;
      line-height: 1.75 !important;
      font-size: 16px !important
    }

    @media screen and (max-width:1024px) {
      .form-group .help-block {
        position: static;
        background: transparent;
        padding: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
        color: #eb4545;
        -webkit-transform: none !important;
        transform: none !important
      }

      .form-group._hasError .help-block {
        margin-top: 10px
      }
    }

    @media screen and (max-width:960px) {
      .blocks__title {
        font-size: 1.7142857142857142em
      }
    }

    @media screen and (max-width:768px) {
      .el-dialog {
        width: 100%;
        height: 100%;
        min-width: 280px;
        margin: 0 !important;
        border-radius: 0;
        -webkit-box-shadow: none;
        box-shadow: none
      }

      .el-dialog.is-fullscreen {
        max-width: 100%
      }

      .el-dialog__wrapper {
        background: #fff
      }

      .el-dialog__header {
        max-width: 448px;
        padding: 40px 24px 16px;
        margin: 0 auto
      }

      .el-dialog__body {
        max-width: 448px;
        padding: 0 24px 32px !important;
        margin: 0 auto;
        font-size: 1rem;
        word-break: break-word
      }
    }
  </style>
  <style type="text/css">
    .el-notification-fade-enter.right {
      right: 0;
      -webkit-transform: translateX(1000px);
      transform: translateX(1000px)
    }

    .el-notification-fade-enter.left {
      left: 0;
      -webkit-transform: translateX(-1000px);
      transform: translateX(-1000px)
    }

    .el-notification-fade-leave-active.right {
      -webkit-transform: translateX(1000px);
      transform: translateX(1000px)
    }

    .el-notification-fade-leave-active.left {
      -webkit-transform: translateX(-1000px);
      transform: translateX(-1000px)
    }

    .alert {
      z-index: 3001 !important;
      width: 378px;
      padding: 0;
      border: none;
      border-radius: 10px;
      -webkit-box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      box-shadow: 0 5px 15px rgba(43, 45, 49, .1);
      background: transparent;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .alert__row {
      width: 100%;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .alert__img {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-flex: 1;
      -ms-flex: 1 1 53px;
      flex: 1 1 53px;
      min-height: 53px;
      height: 100%;
      border-radius: 10px 0 0 10px
    }

    .alert__icon {
      margin: auto;
      width: 1.5rem;
      height: 1.5rem;
      fill: #fff
    }

    .alert__msg {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      width: 100%;
      min-height: 53px;
      padding: 1rem;
      border-radius: 0 10px 10px 0;
      background: #fff;
      text-align: left;
      line-height: 1.21429;
      font-size: .875rem;
      font-weight: 600
    }

    .alert__link,
    .alert a.alert__link {
      margin-top: 8px;
      font-weight: 600
    }

    .alert_info .alert__img,
    .alert_success .alert__img {
      background: #ffc107
    }

    .alert_error .alert__img {
      background: #eb4545
    }

    .alert__btn_close {
      position: absolute;
      top: 50%;
      right: 50%;
      width: 10px;
      height: 10px;
      fill: #aaa;
      cursor: pointer;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%)
    }

    .el-notification__closeBtn {
      top: 14px;
      right: 12px
    }

    @media screen and (max-width:768px) {
      .alert {
        left: 0 !important;
        right: 0 !important;
        margin: 0 auto;
        max-width: 280px
      }
    }
  </style>
  <script async="" src="./plisio_files/modules.9a6619e61150e4449f35.js" charset="utf-8"></script>
  <style type="text/css">
    iframe#_hjRemoteVarsFrame {
      display: none !important;
      width: 1px !important;
      height: 1px !important;
      opacity: 0 !important;
      pointer-events: none !important;
    }
  </style>
  <script charset="utf-8" src="./plisio_files/InvoiceProgressBar.96a6d9acd7ae2660bef9.js"></script>
  <script charset="utf-8" src="./plisio_files/InvoiceStepPay.96a6d9acd7ae2660bef9.js"></script>
  <style type="text/css">
    @-webkit-keyframes rotate-data-v-e2dae8b0 {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }

    @keyframes rotate-data-v-e2dae8b0 {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }

    .progress[data-v-e2dae8b0] {
      position: relative;
      height: 25px;
      line-height: 1;
      font-weight: 600;
      color: #fff
    }

    .progress[data-v-e2dae8b0]:before {
      content: "";
      display: block;
      width: 100%;
      opacity: .2
    }

    .progress[data-v-e2dae8b0]:before,
    .progress__line[data-v-e2dae8b0] {
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      background: #2b2d31
    }

    .progress__line[data-v-e2dae8b0] {
      width: 0;
      opacity: .3;
      -webkit-transition: width 1s linear;
      -o-transition: width 1s linear;
      transition: width 1s linear
    }

    .progress__row[data-v-e2dae8b0] {
      position: relative;
      z-index: 1;
      height: 100%;
      padding: 0 20px;
      color: #fff
    }

    .progress__row[data-v-e2dae8b0],
    .progress__status[data-v-e2dae8b0] {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center
    }

    .progress__icon_loader[data-v-e2dae8b0] {
      width: 15px;
      height: 15px;
      fill: #fff;
      -webkit-animation: rotate-data-v-e2dae8b0 1s linear infinite;
      animation: rotate-data-v-e2dae8b0 1s linear infinite
    }

    .progress__msg[data-v-e2dae8b0] {
      margin-left: .5rem;
      font-size: .75rem
    }

    .progress__msg._expired[data-v-e2dae8b0] {
      margin: 0 auto
    }

    .progress__timer[data-v-e2dae8b0] {
      font-size: .875rem
    }
  </style>
  <style type="text/css">
    .fade-in-up-enter-active[data-v-1e9a806c],
    .fade-in-up-leave-active[data-v-1e9a806c] {
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .fade-in-up-enter[data-v-1e9a806c],
    .fade-in-up-leave-to[data-v-1e9a806c] {
      opacity: 0;
      -webkit-transform: translateY(.5rem);
      transform: translateY(.5rem)
    }

    @-webkit-keyframes rotate-data-v-1e9a806c {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }

    @keyframes rotate-data-v-1e9a806c {
      to {
        -webkit-transform: rotate(1turn);
        transform: rotate(1turn)
      }
    }

    .step_pay__loader[data-v-1e9a806c] {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      text-align: center;
      width: 192px;
      height: 192px;
      margin: 0 auto 1rem
    }

    .step_pay__icon_loading[data-v-1e9a806c] {
      width: 80px;
      height: 80px;
      fill: #ffc107;
      -webkit-animation: rotate-data-v-1e9a806c 1s linear infinite;
      animation: rotate-data-v-1e9a806c 1s linear infinite;
      -webkit-transform-origin: 50% 50%;
      transform-origin: 50% 50%
    }

    .step_pay__qr[data-v-1e9a806c] {
      margin: 8px auto 16px
    }

    .step_pay[data-v-1e9a806c] .step_pay__amount {
      white-space: nowrap
    }

    .step_pay__address[data-v-1e9a806c] {
      margin: 1.5rem auto 1rem;
      word-break: break-all;
      font-weight: 600;
      color: #2b2d31;
      -webkit-transition: all .5s;
      -o-transition: all .5s;
      transition: all .5s
    }

    .step_pay__address._active[data-v-1e9a806c] {
      background: #ffc107;
      color: #fff
    }

    .qs-pay-mark {
      display: flex;
      gap: 8px;
      align-items: stretch;
      margin: 1.25rem auto 0.5rem;
      max-width: 440px;
      padding: 0 12px;
    }
    .qs-pay-mark input {
      flex: 1;
      min-height: 42px;
      border: 1px solid #d0d5dd;
      border-radius: 6px;
      padding: 0 12px;
      font-size: 14px;
    }
    .qs-pay-mark button {
      background: #2e7d32;
      color: #fff;
      border: 0;
      border-radius: 6px;
      padding: 0 16px;
      font-weight: 700;
      white-space: nowrap;
      min-height: 42px;
      cursor: pointer;
    }
    .qs-pay-marked {
      margin: 1rem auto 0.5rem;
      max-width: 440px;
      padding: 10px 12px;
      border-radius: 6px;
      background: #e8f5e9;
      color: #1b5e20;
      font-size: 13px;
      word-break: break-all;
    }
  </style>
</head>

<body class="page_loading innerPage invoice__610dc5ebbb992f5bdb6a5669">
  
  <div data-v-5e0cd238="" class="wrap _theme_dark">
    
    
    

    <div data-v-5e0cd238="" class="invoice">
      <div class="invoice__header header">
        <div class="header__top row_between"><a href="javascript:;" title="plisio"
            target="_blank" rel="noopener" class="" style="color:white">Quantum Scalp</a></div>
        <div data-v-e2dae8b0="" id="timer_invoice" class="header__progress progress">
          <div data-v-e2dae8b0="" class="progress__line" id="time-bar" style="width: 0%;"></div>
          <div data-v-e2dae8b0="" class="progress__row row_between">
            <div data-v-e2dae8b0="" class="progress__status row_start"><svg data-v-e2dae8b0=""
                xmlns="http://www.w3.org/2000/svg" class="progress__icon_loader">
                <use data-v-e2dae8b0="" xlink:href="#icon_loader"></use>
              </svg> <span data-v-e2dae8b0="" class="progress__msg">Awaiting Payment...</span></div>
            <div data-v-e2dae8b0="" class="progress__timer" id="time-label">
              00:00
            </div>
          </div>
        </div>
      </div>
      <div class="invoice__contentWr">
        <div data-v-04d1a81a="" class="invoice__row_sum row_between">
          <div data-v-04d1a81a="" class="row_sum__shop row_start">
            <div data-v-04d1a81a="" class="row_sum__shopLogo"
              style="background-image: url(img/favicon.png);"></div>
            <div data-v-04d1a81a="" class="row_sum__shopName">Payment into <?php echo $row['name']; ?></div>
          </div>
          <div data-v-04d1a81a="" class="row_sum__val">
            <div data-v-04d1a81a="" class="row_sum__crypto">
              <div data-v-a6f121a6="" data-v-04d1a81a="" class="clipboard" style="display: none;">
                <div data-v-a6f121a6="" class="clipboard__value"></div>
                <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$1"
                  data-clipboard-success-handler="$2">
                  <!---->

                  <!---->
                </div>
              </div>
              <div data-v-04d1a81a="" class="row_sum__amount" id="converted"><?php echo htmlspecialchars($payAmount); ?></div>
              <div data-v-a6f121a6="" data-v-04d1a81a="" class="clipboard" style="display: none;">
                <div data-v-a6f121a6="" class="clipboard__value"></div>
                <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler=""
                  data-clipboard-success-handler="">
                  <!---->

                  <!---->
                </div>
              </div>
              <div data-v-04d1a81a="" class="row_sum__curr"><?php echo htmlspecialchars($payCurrency); ?></div>
            </div>
            <div data-v-04d1a81a="" class="row_sum__fiat">
              <div data-v-a6f121a6="" data-v-04d1a81a="" class="clipboard" style="display: none;">
                <div data-v-a6f121a6="" class="clipboard__value"></div>
                <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$5"
                  data-clipboard-success-handler="$6">
                  <!---->

                  <!---->
                </div>
              </div>
              <div data-v-04d1a81a="" class="row_sum__amount_fiat"><?php echo $row['amount']; ?></div>
              <div data-v-a6f121a6="" data-v-04d1a81a="" class="clipboard" style="display: none;">
                <div data-v-a6f121a6="" class="clipboard__value"></div>
                <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$7"
                  data-clipboard-success-handler="$8">
                  <!---->

                  <!---->
                </div>
              </div>
              <div data-v-04d1a81a="" class="row_sum__curr_fiat">USD</div>
            </div>
          </div>
        </div>
        <div class="invoice__content _white">
          <div data-v-1e9a806c="" class="step step_pay">
            <div data-v-1e9a806c="" class="step_pay__qr">
              <!--?xml version="1.0" encoding="UTF-8"?-->
             <img  src="<?php echo htmlspecialchars($qrSrc); ?>" alt="Wallet QR code" />
            </div>
            <div data-v-a6f121a6="" data-v-1e9a806c="" class="clipboard" style="display: none;">
              <div data-v-a6f121a6="" class="clipboard__value"></div>
              <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$9"
                data-clipboard-success-handler="$10">
                <!---->

                <!---->
              </div>
            </div>
            <div data-v-a6f121a6="" data-v-1e9a806c="" class="clipboard" style="display: none;">
              <div data-v-a6f121a6="" class="clipboard__value"></div>
              <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$11"
                data-clipboard-success-handler="$12">
                <!---->

                <!---->
              </div>
            </div>
           
            <div data-v-a6f121a6="" data-v-1e9a806c="" class="clipboard" style="display: none;">
              <div data-v-a6f121a6="" class="clipboard__value"></div>
              <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$13"
                data-clipboard-success-handler="$14">
                <!---->

                <!---->
              </div>
            </div>
            <div data-v-a6f121a6="" data-v-1e9a806c="" class="clipboard" style="display: none;">
              <div data-v-a6f121a6="" class="clipboard__value"></div>
              <div data-v-a6f121a6="" class="clipboard__btn" data-clipboard-click-handler="$15"
                data-clipboard-success-handler="$16">
                <!---->

                <!---->
              </div>
            </div>
            <!---->
            <div data-v-1e9a806c="" class="invoice__hint">To complete your payment, please send <strong
                id="step_pay__amount_payTo" class="step_pay__amount"><?php echo htmlspecialchars($payAmount); ?></strong>
              <strong id="step_pay__curr_payTo" class="step_pay__curr"><?php echo htmlspecialchars($payCurrency); ?></strong>
              to the address below:
            </div>
            <div data-v-1e9a806c="" class="step_pay__address"><?php echo htmlspecialchars($payWallet); ?></div>
            <div data-v-a6f121a6="" class="clipboard__btn" onclick="mylink()">
              <svg data-v-5e0cd238="" width="20" height="20" viewBox="0 0 24 24" id="icon_copy" xmlns="http://www.w3.org/2000/svg"><path data-v-5e0cd238="" d="M6.439 5.927c.408 0 .738.33.738.739v5.078c0 .409.33.738.739.738h5.05c.41 0 .74.33.74.739v9.669c0 .613-.496 1.108-1.109 1.108H1.114l-.12-.007c-.396-.044-.73-.305-.889-.732-.064-.172-.098-.37-.098-.59V6.813c0-.392.494-.887 1.107-.887zM16.696 0c.409 0 .738.33.738.738v4.98c0 .409.33.739.739.739h5.078c.409 0 .738.33.738.738h-.002v9.767c0 .613-.495 1.108-1.108 1.108h-7.707v-7.463c0-.478-.187-.945-.539-1.297L10.323 5c-.01-.013-.022-.023-.032-.033v-3.86c0-.612.495-1.107 1.108-1.107zM9.03 5.927c.09 0 .182.032.258.109l1.002 1.002 3.306 3.308c.076.076.108.17.108.26-.002.19-.148.37-.369.37h-4.31c-.202 0-.37-.165-.367-.37v-4.31c0-.22.182-.369.372-.369zM18.94.372c0-.33.399-.493.63-.261l4.31 4.31c.234.231.07.63-.26.63h-4.31c-.205 0-.37-.165-.37-.37z"></path></svg>
              &nbsp;
              Copy the wallet address
              <!---->
            </div>
            <?php if ($txnHashSaved !== '') { ?>
            <div class="qs-pay-marked">Payment submitted. Hash: <?php echo htmlspecialchars($txnHashSaved); ?></div>
            <?php } else { ?>
            <form method="POST" class="qs-pay-mark">
              <input type="hidden" name="orderid" value="<?php echo htmlspecialchars($orderid); ?>">
              <input type="text" name="txn_hash" placeholder="Enter Transaction Hash" required>
              <button type="submit" name="mark-as-paid">Mark As Paid</button>
            </form>
            <?php } ?>
          </div>

          <aside data-v-e6bd7c5e="" class="invoice__help help"><a data-v-e6bd7c5e=""
              href="javascript:;" 
              target="_blank" rel="noopener" class="help__link _que"><svg data-v-e6bd7c5e=""
                xmlns="http://www.w3.org/2000/svg" class="help__icon help__icon_que">
                <use data-v-e6bd7c5e="" xlink:href="#icon_question"></use>
              </svg>
              Powered By Quantum Scalp
            </a>
        
          </aside>
        </div>
      </div>
    </div>
  </div>
  <!-- built files will be auto injected -->
  
  <script type="text/javascript" src="./plisio_files/commons_74226ea0.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_129e7a51.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_cdf41e64.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_bcdaba36.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_80e93ec1.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_ffdf5190.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_13cee1ed.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_90b9fcf5.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_8d1280f7.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_77ba6f22.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_e50ee5dc.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_23a1a521.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_34e3d95a.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_2bdc73e9.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_1060fe8b.96a6d9acd7ae2660bef9.js"></script>
  <script type="text/javascript" src="./plisio_files/commons_9365e9ba.96a6d9acd7ae2660bef9.js"></script>
  <script src="assets/plugins/jquery/jquery.min.js"></script>

    <input type="text" style="display:none" value="<?php echo htmlspecialchars($payWallet); ?>" id="wallet-address" />
    <script>

function mylink() {
        /* Get the text field */
        var copyText = document.getElementById("wallet-address");

        copyText.style = "display:block";
        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

        copyText.style = "display:none";

        alert('Wallet Address Copied!');

    }

    </script>
</body>

</html>