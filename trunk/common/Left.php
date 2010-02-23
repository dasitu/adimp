<?php
include "../common/session.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>无标题页</TITLE>
<META http-equiv=Content-Type content="text/html;
 charset=utf-8">
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
var menu_cnt = 13;

//the first level menu
var node = new Array();
for(var i=0;i<menu_cnt;i++)
{
	node[i] = new Array();
}

//the first node used to store the menu name
/*
node[0][0] = new TreeNode('交流与培训管理');
node[0][1] = new TreeNode('年度培训计划', '', 'tree_node.gif', null, 'tree_node.gif', null);
node[0][2] = new TreeNode('交流培训记录', '', 'tree_node.gif', null, 'tree_node.gif', null);
node[0][3] = new TreeNode('人员培训记录', '', 'tree_node.gif', null, 'tree_node.gif', null);
*/

node[1][0] = new TreeNode('防火墙管理');
node[1][1] = new TreeNode('防火墙条款', '../firewall/firewall_rule.php', 'tree_node.gif', null, 'tree_node.gif', null);
node[1][2] = new TreeNode('防火墙事件', '../firewall/firewall.php', 'tree_node.gif', null, 'tree_node.gif', null);

//node[2][0] = new TreeNode('技术情报管理');
//node[3][0] = new TreeNode('规范与标准管理');

node[4][0] = new TreeNode('出差管理', '../trip/trip.php');

node[5][0] = new TreeNode('PBC管理', '../pbc/pbc_show.php');

<?php if($_SESSION['pbc_role_id'] != 3 ) {?>
	node[5][1] = new TreeNode('管理PBC', '../pbc/pbc_admin.php', 'tree_node.gif', null, 'tree_node.gif', null);
	node[5][2] = new TreeNode('数据统计','../pbc/pbc_search.php','tree_node.gif', null, 'tree_node.gif', null);
<?php }?>

node[6][0] = new TreeNode('项目管理', '../project/project.php');


for(var outer=0;outer<node.length;outer++)
{
	if(typeof(node[outer][0]) != 'undefined'){
		var nodeL1 = node[outer][0];
		root.add(node[outer][0]);
		for(var inner=1;inner<node[outer].length;inner++)
		{
			nodeL1.add(node[outer][inner]);
		}
	}
}
tree = new Tree(root);
tree.show('menuTree');
</SCRIPT>
</BODY>
</HTML>
