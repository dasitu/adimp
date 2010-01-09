<?php
require_once("../common/session.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<HTML>
	<HEAD>
		<TITLE>MyOffice 首页</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=utf-8">
	</HEAD>
	<FRAMESET id=index border=0 frameSpacing=0 rows=120,* frameBorder=no>
		<FRAME id=topFrame name=topFrame src="../common/top.php" noResize scrolling=no>
		<FRAMESET border=0 frameSpacing=0 frameBorder=no cols=20%,*>
			<FRAME id=leftFrame name=leftFrame src="../common/left.php" noResize scrolling=no>
			<FRAME id=mainFrame name=mainFrame src="../common/welcome.php" noResize scrolling=no>
		</FRAMESET>
	</FRAMESET>
</HTML>
