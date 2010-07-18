<?php
include "../common/session.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>无标题页</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<STYLE type=text/css> 
{
	FONT-SIZE: 12px
}
#menuTree A {
	COLOR: #566984;
 TEXT-DECORATION: none
}
</STYLE>
<SCRIPT src="../js/TreeNode.js" type=text/javascript></SCRIPT>
<SCRIPT src="../js/Tree.js" type=text/javascript></SCRIPT>
</HEAD>
<BODY style="BACKGROUND-POSITION-Y: -120px;
 BACKGROUND-IMAGE: url(../images/bg.gif);
 BACKGROUND-REPEAT: repeat-x">
<TABLE height="100%" cellSpacing=0 cellPadding=0 width="100%">
  <TBODY>
    <TR>
      <TD width=10 height=29><IMG src="../images/bg_left_tl.gif"></TD>
      <TD style="FONT-SIZE: 18px; BACKGROUND-IMAGE: url(../images/bg_left_tc.gif); COLOR: white; FONT-FAMILY: system">Main Menu</TD>
      <TD width=10><IMG src="../images/bg_left_tr.gif"></TD>
    </TR>
    <TR>
      <TD style="BACKGROUND-IMAGE: url(../images/bg_left_ls.gif)"></TD>
      <TD id=menuTree style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px; HEIGHT: 100%;
 BACKGROUND-COLOR: white" vAlign=top></TD>
      <TD style="BACKGROUND-IMAGE: url(../images/bg_left_rs.gif)"></TD>
    </TR>
    <TR>
      <TD width=10><IMG src="../images/bg_left_bl.gif"></TD>
      <TD style="BACKGROUND-IMAGE: url(../images/bg_left_bc.gif)"></TD>
      <TD width=10><IMG src="../images/bg_left_br.gif"></TD>
    </TR>
  </TBODY>
</TABLE>
<SCRIPT type=text/javascript>
var tree = null;
var root = new TreeNode('系统菜单');
var menu_cnt = 4;

//the first level menu
var node = new Array();
for(var i=0;i<menu_cnt;i++)
{
	node[i] = new Array();
}

node[0][0] = new TreeNode('防火墙管理');
node[0][1] = new TreeNode('防火墙条款', '../firewall/firewall_rule.php', 'tree_node.gif', null, 'tree_node.gif', null);
node[0][2] = new TreeNode('防火墙事件', '../firewall/firewall.php', 'tree_node.gif', null, 'tree_node.gif', null);

node[1][0] = new TreeNode('出差管理', '../trip/trip.php');

node[2][0] = new TreeNode('PBC管理', '../pbc/pbc_show.php');
<?php if($_SESSION['pbc_role_id'] != 3 ) {?>
	node[2][1] = new TreeNode('管理PBC', '../pbc/pbc_admin.php', 'tree_node.gif', null, 'tree_node.gif', null);
	node[2][2] = new TreeNode('数据统计','../pbc/pbc_search.php','tree_node.gif', null, 'tree_node.gif', null);
<?php }?>

node[3][0] = new TreeNode('项目管理', '../project/project.php');
<?php if($_SESSION['pbc_role_id'] != 3 ) {?>
	node[3][1] = new TreeNode('用户管理', '../user/list.php');
    node[3][2] = new TreeNode('工程组管理', '../depart/list.php');
<?php }?>


for(var outer=0;outer<node.length;outer++)
{
	if(typeof(node[outer][0]) != 'undefined'){
		var nodeL1 = node[outer][0];
		root.add(node[outer][0]);
		for(var inner=1;inner<node[outer].length;inner++)
		{
			if(typeof(node[outer][inner]) != 'undefined'){
				nodeL1.add(node[outer][inner]);
			}
		}
	}
}
tree = new Tree(root);
tree.show('menuTree');
</SCRIPT>
</BODY>
</HTML>
