<?php
require_once("../common/session.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD id=Head1>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<STYLE type=text/css> 
*{
	FONT-SIZE: 12px; COLOR: white
}
#logo {
	COLOR: white
}
#logo A {
	COLOR: white
}
FORM {
	MARGIN: 0px
}
</STYLE>
<SCRIPT src="../js/Clock.js" type=text/javascript></SCRIPT>
</HEAD>
<BODY style="BACKGROUND-IMAGE: url(../images/bg.gif); MARGIN: 0px; BACKGROUND-REPEAT: repeat-x">
<form id="form1">
  <DIV id=logo style="BACKGROUND-IMAGE: url(../images/logo.png); BACKGROUND-REPEAT: no-repeat">
    <DIV style="PADDING-RIGHT: 50px; BACKGROUND-POSITION: right 50%; DISPLAY: block; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 3px; BACKGROUND-REPEAT: no-repeat; HEIGHT: 33px; TEXT-ALIGN: right">
	<!--
	<A href="http://localhost:1479/Web/sys/Top.aspx#">
	<IMG src="../images/mail.gif" align=absMiddle border=0>
	</A> 您有新消息
	<A id=HyperLink1 href="http://localhost:1479/Web/sys/Top.aspx#">5</A>
	条 <IMG src="../images/menu_seprator.gif" align=absMiddle>
	<A id=HyperLink3 href="javascript:parent.close();window.opener=null;">退出系统</A>
	-->
	</DIV>
	<DIV style="DISPLAY: block; HEIGHT: 54px"></DIV>
    <DIV style="BACKGROUND-IMAGE: url(../images/bg_nav.gif); BACKGROUND-REPEAT: repeat-x; HEIGHT: 30px">
      <TABLE cellSpacing=0 cellPadding=0 width="100%">
        <TBODY>
          <TR>
            <TD>
              <DIV><IMG src="../images/nav_pre.gif" align=absMiddle /> 欢迎 
			  <SPAN id=lblBra><?php echo $_SESSION['user_name'];?></SPAN> 
			  <SPAN id=lblDep></SPAN>
			  [  <?php echo $_SESSION['depart_name'];?> ] 
			  </DIV>
            </TD>
            <TD align=right width="70%">
			<SPAN style="PADDING-RIGHT: 50px">
				<A href="javascript:history.go(-1);"><IMG src="../images/nav_back.gif" align=absMiddle border=0>后退</A>
				<A href="javascript:history.go(1);"><IMG  src="../images/nav_forward.gif" align=absMiddle border=0>前进</A>
				<A href="../common/logout.php" target=_top>
				<IMG src="../images/nav_changePassword.gif" align=absMiddle border=0>重新登录</A> 
				<A href="../common/chpass.php" target=mainFrame>
				<IMG src="../images/nav_resetPassword.gif" align=absMiddle border=0>修改密码</A>
				<IMG src="../images/menu_seprator.gif" align=absMiddle> 
				<SPAN id=clock></SPAN>
			</SPAN>
			</TD>
          </TR>
        </TBODY>
      </TABLE>
    </DIV>
  </DIV>
  <SCRIPT type=text/javascript>
    var clock = new Clock();
    clock.display(document.getElementById("clock"));
</SCRIPT>
</form>
</BODY>
</HTML>
