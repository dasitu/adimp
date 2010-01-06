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

//the first level menu
var node1 = new TreeNode('交流与培训管理');
var node2 = new TreeNode('防火墙管理');
var node3 = new TreeNode('技术情报管理');
var node4 = new TreeNode('规范与标准管理');
root.add(node1);
root.add(node2);
root.add(node3);
root.add(node4);

//the second level
var node1_1 = new TreeNode('年度培训计划', 'BranchMgr.aspx', 'tree_node.gif', null, 'tree_node.gif', null);
var node1_2 = new TreeNode('交流培训记录', 'BranchMgr.aspx', 'tree_node.gif', null, 'tree_node.gif', null);
var node1_3 = new TreeNode('人员培训记录', 'BranchMgr.aspx', 'tree_node.gif', null, 'tree_node.gif', null);
node1.add(node1_1);
node1.add(node1_2);
node1.add(node1_3);

var node2_1 = new TreeNode('防火墙条款', '../firewall/index.php', 'tree_node.gif', null, 'tree_node.gif', null);
var node2_2 = new TreeNode('防火墙事件', 'DepartmentMgr.aspx', 'tree_node.gif', null, 'tree_node.gif', null);
node2.add(node2_1);
node2.add(node2_2);

tree = new Tree(root);
tree.show('menuTree');
</SCRIPT>
</BODY>
</HTML>
