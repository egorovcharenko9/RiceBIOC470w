<?php
/**
 *+------------------
 * SFDP-超级表单开发平台V5.0
 *+------------------
 * Sfdp 工具类
 *+------------------
 * Copyright (c) 2018~2020 https://cojz8.com All rights reserved.
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
namespace sfdp\lib;

use sfdp\adaptive\Design;

class lib{
	/**
	 * 设计器列表模板
	 *
	 * @param  Array $data 列表数据
	 */
	public static function index($data){
		$tmp = self::commontmp('Sfdp超级表单设计器 V5.0');
		$urls= unit::gconfig('url');
		$fun_save = $urls['api'].'?act=btable';
		$tr = '';
		
		if(unit::gconfig('node_mode')==2){
			$className = unit::gconfig('node_action');
			if(!class_exists($className)){
				return 'Sorry,未找到node_action类，请先配置~';
			}
			$Node = (new $className())->GetNode();//获取目录节点信息	
		}else{
			$Node = unit::gconfig('node_data');
		}
		$node_url =$urls['api'].'?act=node';
		$create_url =$urls['api'].'?act=create';
		foreach($data as $k=>$v){
			$status = ['未锁定','已锁定'];
			$status_zt = [0=>'未部署',1=>'未部署',2=>'已部署'];
			$btn = '<a onClick=sfdp.openfullpage("设计——'.$v['s_bill'].'","'.$urls['api'].'?act=desc&sid='.$v['id'].'") class="button">设计</a>';
			if($v['s_field'] <> 1){
				$fix = $urls['api'].'?act=fix&sid='.$v['id'];
				$btn .= ' <a onClick=sfdp.Askshow("'.$fix.'","部署后将生成最新版本,确定是否执行?") class="button">部署</a><a onClick=sfdp.openpage("定义管理——'.$v['s_bill'].'","'.$urls['api'].'?act=custom&sid='.$v['id'].'") class="button">定义</a>';
			}
			if($v['s_db_bak']==1){
				$btn .='<a onClick=sfdp.Askshow("'.$urls['api'].'?act=deldb&sid='.$v['id'].'","删除备份数据库,是否执行?")  class="button">DelDb</a>';
				$btn .='<a onClick=add_fun('.$v['id'].')  class="button">Menu</a>';
			}
			$tr .='<tr class="text-c"><td>'.$v['id'].'</td><td>'.$v['s_bill'].'</td><td>'.$v['s_title'].'</td><td>'.date('Y/m/d H:i',$v['add_time']).'</td><td>'.$status_zt[$v['s_design']].'</td><td>'.$status[$v['s_look']].'（'.$v['s_db'].'）</td><td>'.$btn.'</td></tr>';
		}
		return <<<php
		{$tmp['head']}{$tmp['js']}
			</head>
		<body>
		<div class="page-container">
			<div style='float: left;width:5%;margin-top: 10px;text-align: center;'>
				<a onClick='sfdp.Askshow("{$create_url}","是否创建新表单？")' class="button">创&#12288;建</a><hr/><a onclick="add_btable()" class="button ">黑名单</a><hr/><a onclick="location.reload();" class="button ">刷&#12288;新</a>
			</div>
			<div style='float: left;width:95%;margin-top: 10px;'>
				<table class="table" >
					<tr class="text-c"><th >sid</th><th >编码</th><th>标题</th><th>发布时间</th><th>启用状态</th><th>锁定状态</th><th>操作</th></tr>
					{$tr}
				</table>
			</div>
		</div>
		<div id='menu' style='display:none'>
		{$Node['html']}	
		</div>
		<script>
		function add_fun(sid){
			var htmls = '<div style="margin:10px;">节点：<select id="nodesss" class="select" ><option value="">请选择挂带节点</option>'+$('#menu').html()+'</select>　<a onClick=getmenu('+sid+')  class="button">创建节点</a></div>';
				layer.open({
				  title: '选择挂带节点信息',
				  type: 1,
				  area: ['520px', '150px'], //宽高
				  content: htmls
				});
		}
		function add_btable(title='',name='',id=''){
			var html ='<form action="" method="post" name="form" id="form">'+
					  '<table class=table id="table_view"><tr><td>表名称</td><td style="text-align:left"><input type="text" id="title"></td></tr>'+
					  '<tr><td>表别名</td><td style="text-align:left"><input type="text" id="name" ></td></tr>'+
					  '<tr><td colspan=2 style="text-align: center;"><a class="button" onclick="save_btable()">提交</a></td></tr><tr><td colspan=2>*黑名单表是为了防止重复而设计；<br/>*表名称：不含表前缀；如：sfdp_table<br/>表别名：如新闻信息表</td></tr></table></form>';
				layer.open({
				  type: 1,
				  area: ['320px', '280px'], //宽高
				  content: html
				});
		}
		function save_btable(){
			var title = $('#title').val();
			var name = $('#name').val();
			if(title =='' || name==''){
				sfdp.ShowTip('表名称和内容必须填写！');
				return;
			}
			var url = "{$fun_save}";
			sfdp.sAjax(url,{title:title,name:name});
		}
		function getmenu(sid){
			var node = $('#nodesss').val();
			if(node==''){
				sfdp.ShowTip('请选择节点信息！');
				return;
			}
			var url = "{$node_url}&sid="+sid+"&node="+node;
			sfdp.Askshow(url,"再次确认是否创建目录,是否执行?");
		}
		</script>
		</body>
		</html>
php;
	}
	/**
	 * 函数方法
	 *
	 * @param  Array $data 列表数据
	 */
	public static function fun($data){
		$tr = '';
		$tmp = self::commontmp('Sfdp超级表单设计器');
		$urls= unit::gconfig('url');
		$fun_save = $urls['api'].'?act=fun_save&sid=';
		foreach($data as $k=>$v){
			$status = ['编辑中','已启用'];
			if($v['status']==0){
				$btn ='<a onClick=sfdp.Askshow("'.$urls['api'].'?act=fun_update&status=1&id='.$v['id'].'","是否启用,是否执行?")  class="button">启用</a>';
			}else{
				$btn ='<a onClick=sfdp.Askshow("'.$urls['api'].'?act=fun_update&status=0&id='.$v['id'].'","是否禁用,是否执行?")  class="button" style="    background-color: indianred;">禁用</a>';
			}
			$tr .='<tr class="text-c"><td>'.$v['bill'].'</td><td>'.$v['title'].'</td><td>'.$v['fun_name'].'</td><td>'.date('Y/m/d H:i',$v['add_time']).'</td><td>'.$v['add_user'].'</td><td>'.$status[$v['status']].'</td><td><input id="fun_'.$v['fun_name'].'" value="'.$v['function'].'" type=hidden><a onClick=add_fun("'.$v['title'].'","'.$v['fun_name'].'","'.$v['id'].'")	class="button">编辑</a>'.$btn.'</td></tr>';
		}
		return <<<php
		{$tmp['css']}{$tmp['head']}{$tmp['js']}
<div class="page-container">
<div style='float: left;width:8%;text-align:center;margin-top:10px'>
	<a onClick='add_fun()' class="button">创建</a><a onclick="location.reload();" class="button ">刷新</a><hr/>
</div>
<div style='float: left;width:92%;margin-top:10px'>
	<table class="table">
			<tr class="text-c"><th width="150">编码</th><th width="150">标题</th><th width="100">函数名</th><th width="80">发布时间</th><th width="50">添加人</th><th width="50">状态</th><th width="200">操作</th>
			</tr>{$tr}
	</table>
</div>
</div>
</body>
</html>
<script>
function add_fun(title='',name='',id=''){
	var fun = $('#fun_'+name).val() ?? '';
	var html ='<form action="" method="post" name="form" id="form">'+
			  '<table class=table id="table_view"><tr><td>函数标题</td><td style="text-align:left"><input type="text" id="title" value="'+title+'"></td></tr>'+
			  '<tr><td>函数名称</td><td style="text-align:left"><input type="text" id="name" value="'+name+'"></td></tr>'+
			  '<tr><td>填写函数</td><td><textarea placeholder="请填写SQL代码！" id="fun" type="text/plain" style="width:100%;height:280px;">'+fun+'</textarea></td></tr><tr><td colspan=2><a class="button" onclick="save_fun('+id+')">提交</a></td></tr></table></form>';
		layer.open({
		  type: 1,
		  area: ['620px', '540px'], //宽高
		  content: html
		});
}
function save_fun(id){
	var NameExp = /^(?!_)(?!.*?_$)[a-z_]+$/;
	var title = $('#title').val();
	var name = $('#name').val();
	if(!NameExp.test(name)){
		sfdp.ShowTip('函数名称只能为英文小写字母加下划线组合！');
		return;
	}
	var fun = $('#fun').val();
	var url = "{$fun_save}";
	sfdp.sAjax(url,{title:title,name:name,fun:fun,id:id});
}
</script>
php;
	}
	/**
	 * 定义函数
	 *
	 * @param  Array $data 列表数据
	 * @param  int   $sid 设计器的ID值
	 * @param  Array $list 字段数据
	 * @param  array $listtrue 选中数据
	 * @param  array $field 所有字段数据
	 * @param  array $modue 模块数据
	 */
	public static function custom($sid,$field,$modue){
		$patch = unit::gconfig('static_url');
		$urls= unit::gconfig('url');
		$url =$urls['api'];
		$fun_save = $urls['api'].'?act=customSave&sid='.$sid;
		$access = json_decode($modue['access'],true);
        $show_type=  $modue['show_type'];
        $show_fun=  $modue['show_fun'];
		$show_field=  $modue['show_field'];
        $fielcount = explode(",",$modue['count_field']);
		$search ='';
		$field_html ='';
		$access_html ='';
		foreach($field as $k=>$v){
			$search .=' <option value="'.$v['id'].'">'.$v['name'].'('.$v['field'].')</option>';
		}
		foreach($field as $k=>$v){
			if($v['is_search']==1){
				$field_html .='<div id="checkboxes_id'.$v['id'].'">查询字段：<select style="width:200px" class="smalls" name="search_ids"><option value="'.$v['id'].'">'.$v['name'].'('.$v['field'].')</option></select>  查询条件：<input name="search_value" type="text" value="'.$v['search_type'].'">    <span class="button" onclick=editoption("_id'.$v['id'].'")>Del</span></div>';
			}
		}
		$user = ['uid'=>'当前用户','role'=>'当前角色'];
		$eq = ['='=>'等于','<>'=>'不等于','in'=>'包含','no in'=>'不包含'];
		foreach((array)$access as $k=>$v){
			$access_html .='<div id="access_id'.$v[0].'">控制字段：<select style="width:200px" name="accsee_ids"><option value="'.$v[0].'">'.$v[3].'</option></select> <select style="width:200px" name="accsee_eq"><option value="'.$v[1].'">'.$eq[$v[1]].'</option></select> <select style="width:200px" name="accsee_user"><option value="'.$v[2].'">'.$user[$v[2]].'</option></select> <select style="width:200px" name="accsee_fun"><option value="'.$v[4].'">'.$v[4].'</option></select> <span class="button" onclick=editaccess("_id'.$v[0].'")>del</span></div>';
		}
        $alltable ='';
        $listfield = explode(',',$modue['field']);
        foreach($field as $k=>$v){
            if(!in_array($v['field'], ['id','status'])) {
                $countchecked = '';
                $checked = $v['is_list'] == 1 ? 'checked' : '';
                if (in_array($v['field'], $fielcount)) {
                    $countchecked = 'checked';
                }
                $alltable .= ' <tr><th><b>' . $v['name'] . '('.$v['field'].')<b> </th><th><input class="sfdp-input" placeholder="需显示填写序号" name="'.$v['field'].'[list]" value="' . array_search($v['field'],$listfield) . '" type="number" ></th><th><input name="'.$v['field'].'[count]" ' . $countchecked . ' type="checkbox" value="1"></th><th><input name="'.$v['field'].'[search]" class="sfdp-input" placeholder="如：=,>,like"  value="' . $v['search_type'] . '"></th><th><input width="50px" name="'.$v['field'].'[width]" class="sfdp-input"  name="" value="' . $v['width'] . '" type="number"> <input type="hidden" name="'.$v['field'].'[id]"  value="' . $v['id'] . '"><input name="'.$v['field'].'[title]"  value="' . $v['name'] . '" type="hidden"></th></tr>';
            }
        }
        $lenth = count($field);
		return <<<php
  <link rel="stylesheet" href="{$patch}sfdp.5.0.css?v=5.0.1" />
  <script src="{$patch}lib/jquery-1.12.4.js"></script>
  <script src="{$patch}lib/jquery-ui.js"></script>
	<script src="{$patch}lib/layer/3.1.1/layer.js"></script>
	<script src="{$patch}sfdp.5.0.js?v=5.0.1"></script>
</head>
<body style="padding:20px">
 <table>
        <tr><td colspan=5><input id="order" class="sfdp-input"  placeholder="排序规则如：id desc" value='{$modue["order"]}'></td><td style="text-align:center"><a onclick='save_order()'  class='button' >保存排序</a></td></tr>
        <tr><td colspan=5>
        {$access_html}
	<div id="access1">权限字段：<select style="width:200px" name="accsee_ids"><option value="">请选择</option>{$search}</select>
	<select style="width:200px" name="accsee_eq"><option value="=">等于</option><option value="<>">不等于</option><option value="in">包含</option><option value="no in">不包含</option></select>
	<select style="width:200px" name="accsee_user"><option value="uid">当前用户</option><option value="role">当前角色</option></select>
	<select style="width:200px" name="accsee_fun"><option value="and">and</option><option value="or">or</option></select>
    <span class='button' onclick=addaccess(1)>Add</span></div></td><td style="text-align:center"><a onclick='save_access()'  class='button' >保存权限</a></td></tr>
    <tr><td colspan=5>列表形式：<select style="width:200px" name="show_type" id="show_type"><option value="0">普通列表</option><option value="1">树形列表</option><option value="2">Tab列表</option><option value="3">商品列表</option><option value="4">分级列表</option></select><input class="sfdp-input" placeholder="关联字段信息" id="show_field" name="show_field" type="text" value="{$show_field}" style="width: 220px;display: inline;"><input class="sfdp-input" placeholder="元素信息：用来额外展示相关的树或者Tab数据" style="width: 40%;display: inline;" id="show_fun" name="show_fun" type="text" value="{$show_fun}"></div>
	</td><td style="text-align:center"><a onclick='save_show()'  class='button'>保存布局</a></td></tr>
        <tr><th  style='width: 20%;text-align: center;'><b>字段<b> </th>
            <th  style='width: 8%;text-align: center;'><b>列表<b> </th>
            <th  style='width: 5%;text-align: center;'><b>统计<b> </th>
            <th  style='width: 5%;text-align: center;'><b>查询<b> </th>
            <th  style='width: 5%;text-align: center;'><b>宽度<b> </th>
            <th rowspan={$lenth} style="width: 5%;text-align:center"><b><a onclick='update()' class='button'>保存属性</a></b> </th>
        </tr>
        <form id='form'>
         {$alltable}
         <input name='sid' value='{$sid}' type="hidden">
        </form>    
</table>
<script type="text/javascript" language="javascript">
    $("#show_type").find("option[value='{$show_type}']").attr("selected",true);
    function update(){
    var searchdata = [];
     var arr = $('#form').serializeArray();
        $.each(arr, function(index,vars) {
            var dbname = this.name;
                searchdata[dbname] = vars.value || '';
        });
     sfdp.sAjax("{$url}?act=customSave",arr);
    }
    
	function addaccess(id){
		 $('#access'+id).children('span').attr("onclick","editaccess("+id+")");
		 $('#access'+id).children('span').html('Del');
		 var html ='<div id="access'+(id+1)+'">字段：<select style="width:200px" name="accsee_ids"><option value="">请选择</option>{$search}</select> <select style="width:200px" name="accsee_eq"><option value="=">等于</option><option value="<>">不等于</option><option value="in">包含</option><option value="no in">不包含</option></select> <select style="width:200px" name="accsee_user"><option value="uid">当前用户</option><option value="role">当前角色</option></select> <span class="button" onclick=addaccess('+(id+1)+')>Add</span></div>';
		 $('#access'+id).after(html);
	}
	function addoption(id){
		 $('#checkboxes'+id).children('span').attr("onclick","editoption("+id+")");
		 $('#checkboxes'+id).children('span').html('Del');
		 var html ='<div id="checkboxes'+(id+1)+'">查询字段：<select style="width:200px" name="search_ids"><option value="">请选择</option>{$search}</select> 查询条件：<input name="search_value" type="text" value="">如：=,>,like     <span onclick=addoption('+(id+1)+') class="button">Add</span></div>';
		 $('#checkboxes'+id).after(html);
	}
	function editaccess(id){
		$('#access'+id).remove();
	}
	function editoption(id){
		$('#checkboxes'+id).remove();
	}
	function save_access(){
		var ids = [] , value =[] , user =[], fun =[];
		$("select[name='accsee_ids']").each(function () {
			ids.push(this.value);
		});
		$("select[name='accsee_eq']").each(function () {
			value.push(this.value);
		});
		$("select[name='accsee_user']").each(function () {
			user.push(this.value);
		});
		$("select[name='accsee_fun']").each(function () {
			fun.push(this.value);
		});
		sfdp.sAjax("{$url}?act=customAccess",{sid:{$sid},ids_val:ids.join(','),value_val:value.join(','),user_val:user.join(','),fun_val:fun.join(',')});
	}
	function save_order(){
		sfdp.sAjax("{$url}?act=customOrder",{sid:{$sid},order:$('#order').val()});
	}
	function save_show(){
		sfdp.sAjax("{$url}?act=customShow",{sid:{$sid},show_field:$('#show_field').val(),show_type:$('#show_type').val(),show_fun:$('#show_fun').val()});
	}
 </script>
</body>
</html>
php;
	}
	/**
	 * 设计器界面
	 *
	 * @param  json $json 设计数据
	 * @param  int   $fid 设计ID
	 * @param  int $look 是否锁定
	 */
	public static function desc($json,$fid,$look){
		$patch = unit::gconfig('static_url');
		$urls= unit::gconfig('url');
		$save = $urls['api'].'?act=save';
		$server_save = $urls['api'].'?act=field&sid='.$fid;
		$script = $urls['api'].'?act=script&sid='.$fid;
        $mysql = $urls['api'].'?act=mysql&sid='.$fid;
        $fix = $urls['api'].'?act=fix2&sid='.$fid;
		return <<<php
	<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
	<link rel="stylesheet" href="{$patch}sfdp.5.0.css?v=5.0.1" /> 
	<body>
        <div>
			<div id="ctlMenus" class="sfdp-con" style="float: left; width: 240px;">
				<div class="sfdp-tool-title">容器中枢 Design control area</div>
				&#8194;&#8194;
				<div class="button" onclick='sfdp.sys_config()'>配置</div><div class="button" id='up_save'>保存</div><div onclick='window.location.reload()' class="button">刷新</div><div class="button" onClick=sfdp.Askshow("{$fix}","【更新】对布局或字段进行细微调整,确定是否执行?【不支持子表调整！】")>更新</div>
				<div class="sfdp-cl" ></div>
				<div class="sfdp-tool-title sfdp-mt10">页面布局 Form control library</div>
				<div class='sfdp-tool-fix'onclick='sfdp.build_bj()'><a>&#8194;<b class='ico'>↭</b>&#8194;栅格布局 </a></div>
				<div class='sfdp-tool-fix'onclick='sfdp.openfullpage("构建助手","{$mysql}")'><a>&#8194;<b class='ico'>ς</b>&#8194;构建助手 </a></div>
				<div class="sfdp-cl"></div>
				<div class="sfdp-tool-title sfdp-mt10">表单控件 Form control library</div>
					<div class="sfdp-tool-con" ><a data="text">&#8194;<b class='ico'>Α</b>&#8194;文本组件</a></div>
					<div class="sfdp-tool-con" ><a data="number">&#8194;<b class='ico'>½</b>&#8194;数字组件</a></div>
					<div class="sfdp-tool-con" ><a data="money">&#8194;<b class='ico'>￥</b>&#8194;金额组件</a></div>
					<div class="sfdp-tool-con"><a data="checkboxes">&#8194;<b class='ico'>☑</b>&#8194;多选框</a></div>
					<div class="sfdp-tool-con"><a data="radio">&#8194;<b class='ico'>◉</b>&#8194;单选框</a></div>
					<div class="sfdp-tool-con"><a data="dropdown">&#8194;<b class='ico'>≡</b>&#8194;下拉组件 </a></div>
					<div class="sfdp-tool-con"><a data="date">&#8194;<b class='ico'>◑</b>&#8194;日期时间</a></div>
					<div class="sfdp-tool-con"><a data="textarea">&#8194;<b class='ico'>〓</b>&#8194;多行文本</a></div>
					<div class="sfdp-tool-con"><a data="html">&#8194;<b class='ico'>☪</b>&#8194;Html控件</a></div>
					<div class="sfdp-tool-con"><a data="wenzi">&#8194;<b class='ico'>あ</b>&#8194;文字显示</a></div>
					<div class="sfdp-tool-con"><a data="upload">&#8194;<b class='ico'>➼</b>&#8194;附件上传</a></div>
					<div class="sfdp-tool-con"><a data="time_range">&#8194;<b class='ico'>≈</b>&#8194;时间范围</a></div>
					<div class="sfdp-tool-con"><a data="links">&#8194;<b class='ico'>↔</b>&#8194;超级链接</a></div>
					<div class="sfdp-tool-con"><a data="upload_img">&#8194;<b class='ico'>Ρ</b>&#8194;单图组件</a></div>
					<div class="sfdp-tool-con"><a data="edit">&#8194;<b class='ico'>Ε</b>&#8194;富文本框</a></div>
					<div class="sfdp-tool-con"><a data="dropdowns">&#8194;<b class='ico'>S</b>&#8194;下拉多选</a></div>
					<div class="sfdp-tool-con"><a data="cascade">&#8194;<b class='ico'>§</b>&#8194;级联组件</a></div>
					<div class="sfdp-tool-con"><a data="process">&#8194;<b class='ico'>～</b>&#8194;进度组件</a></div>
				<div class="sfdp-cl"></div>
				<div class="sfdp-tool-title sfdp-mt10">内置组件 System control library</div>
					<div class="sfdp-tool-con" ><a data="system_user">&#8194;<b class='ico'>ρ</b>&#8194;系统用户</a></div>
					<div class="sfdp-tool-con" ><a data="system_role">&#8194;<b class='ico'>Θ</b>&#8194;系统角色</a></div>
				<div class="sfdp-cl"></div>
				<div class="sfdp-tool-title sfdp-mt10">子表单设计 Form control library</div>
				<div class="sfdp-tool-con"><a data="group">&#8194;<b class='ico'>§</b>&#8194; 分组线条 </a></div>
				<div class='sfdp-tool-fix'onclick='sfdp.build_fb()'><a>&#8194;<b class='ico'>§</b>&#8194; 添加附表 </a></div>
				<div class="sfdp-cl"></div>
			</div>
			<div class="sfdp-con" style="float: left; width: calc(100% - 600px);">
				<div id="sfdp-main"></div>
			</div>
			<div class="sfdp-con" style="float: right; width: 360px;">
				<div class="sfdp-att">
				<br/><br/><div style="padding: 24px 48px;"> <h1>\ (•◡•) / </h1><p> Sfdp V6.0正式版<br/><span style="font-size:19px;">超级表单开发</span></p><span style="font-size:15px;">[ © 流之云 <a href="https://www.cojz8.com/">Sfdp</a> 版权所有 ]</span></div>
				</div>
			</div>
			<div class="sfdp-cl"></div>
		</div>
		<script src="{$patch}lib/jquery-3.4.1.min.js"></script>
		<script src="{$patch}lib/layer/3.1.1/layer.js"></script>
		<script src="{$patch}lib/pingyin.js"></script>
		<script type="text/javascript" src="{$patch}sfdp.5.0.js?v=5.0.322222"></script>
		<script type="text/javascript" src="{$patch}sfdp.config.js?v=5.0.322222"></script>
		<script type="text/javascript" src="{$patch}lib/jquery-ui.js"></script>
		<script type="text/javascript" language="javascript">
			var look_db = {$look};
			var s_type = 0;
			const server_url ='{$server_save}';
		    $(function(){
				sfdp.int_data({$json});
			});
			$( ".sfdp-tool-con" ).draggable({
				  connectToSortable: ".fb-fz",
				  helper: "clone",
				  revert: "invalid",
				  cursor: "move"
			});
			
			$( "#sfdp-main" ).sortable({
			    stop: function( event, ui ) {
			        let obj = {}
			        let obj2 = []
			        const json_data = JSON.parse(localStorage.getItem("json_data"));
			        const sortedList = $("#sfdp-main div.sfdp-rows ")
			        for(let i= 0; i< sortedList.length ;i++){
			            let item = sortedList[i]
			            //删除TR数据
			            sfdp.dataSave('', item.id , 'tr_del');
			            //重新写入数据库
			            sfdp.dataSave(json_data.list[item.id], String(item.id), 'tr')
			        }
			    }
			
			});
            $( "#sfdp-main" ).disableSelection();
            
			$( "#fb-fz" ).sortable({
			  cancel: ".fb-disabled"
			});
            $(document).on("input propertychange", "#tpfd_name", function (e) {
                var formclass = $(this).parent().parent().parent().attr('class');
                $("."+formclass+" #tpfd_db").val($("."+formclass+" #tpfd_name").toPinyin().toLowerCase());
            });
			$("#up_save").click(function(){
				var int_data = localStorage.getItem("json_data");
				var id='{$fid}';
				$.ajax({  
					 url:'{$save}',
					 data:{ziduan:int_data,id:id},  
					 type:'post',  
					 cache:true,  
					dataType:'json',			 
					 success:function(data) {  
						if(data.code==0){
							sfdp.ShowTip('success');
						}else{
							sfdp.ShowTip(data.msg);
						}
					  }
				 }); 
			})
  </script>
php;
	}
    public static function center($sid){
        $patch = unit::gconfig('static_url');
        $urls= unit::gconfig('url');
        $info = Design::find($sid);
        return view(ROOT_PATH.'/center.html',['patch'=>$patch,'urls'=>$urls,'sid'=>$sid,'info'=>$info]);
    }
	/**
	 * 设计器界面
	 *
	 * @param  json $json 设计数据
	 * @param  int   $fid 设计ID
	 * @param  int $look 是否锁定
	 */
	public static function desc2($json,$fid,$look){
		$patch = unit::gconfig('static_url');
		$urls= unit::gconfig('url');
		$save = $urls['api'].'?act=save';
		$script = $urls['api'].'?act=script&sid='.$fid;
		return view(ROOT_PATH.'/desc.html',['patch'=>$patch,'script'=>$script,'save'=>$save,'urls'=>$urls,'json'=>$json,'fid'=>$fid,'look'=>$look]);
	}
    /**
     * 设计器界面
     *
     * @param  json $json 设计数据
     * @param  int   $fid 设计ID
     * @param  int $look 是否锁定
     */
    public static function desc3($json,$fid,$look){
        $patch = unit::gconfig('static_url');
        $urls= unit::gconfig('url');
        $save = $urls['api'].'?act=save';
        $script = $urls['api'].'?act=script&sid='.$fid;
        return view(ROOT_PATH.'/desc2.html',['patch'=>$patch,'script'=>$script,'save'=>$save,'urls'=>$urls,'json'=>$json,'fid'=>$fid,'look'=>$look]);
    }
	/**
	 * 脚本函数
	 *
	 * @param  Array $info 设计数据
	 * @param  int   $sid 设计ID
	 */
	public static function script($info,$sid){
		$tmp = self::commontmp('Sfdp超级表单设计器');
		$urls= unit::gconfig('url');
		$info['s_fun'] = $info['s_fun'] ?? '';
		$action = $urls['api'].'?act=script&sid='.$sid;
		$patch = unit::gconfig('static_url');
		return <<<php
	{$tmp['css']}
		<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/codemirror.css" />
		<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/dracula.css" />
		<script src="{$patch}lib/codemirror/codemirror.js"></script>
		<script src="{$patch}lib/codemirror/javascript.js"></script>
		
			<form action="{$action}" method="post" name="form" id="form" style="padding: 10px;">
			<input type="hidden" name="sid" value="{$sid}">
		<table class="table">
			<tr valign="center">
			<td style='width:35px;text-align:center'>脚本助手</td>
			<td style='width:330px;text-align: left;'>
                <a class="button" onclick="install(load_satr_fun)">编辑前事件脚本<a/> 
                <a class="button" onclick="install(load_end_fun)">页面加载后脚本<a/> 
                <a class="button" onclick="install(load_form_check)">提交验证脚本<a/> 
                <a class="button" onclick="install(load_end_view)">查看页面脚本<a/> 
                <a class="button" onclick="install(load_list_fun)">列表页面脚本<a/>
				<a class="button" onclick="install(load_end_time_done)">日期回调函数<a/>
			</td>
			</tr>
			<tr valign="center">
			<td style='width:35px;text-align:center'>单据脚本</td><td style='width:330px' >
			<textarea placeholder="请填写JQ脚本代码！" name='function'  type="text/plain" style="width:100%;height:450px;display:inline-block;" id='code'>{$info['s_fun']}</textarea> </td>
			</tr>
			<tr valign="center">
			<td style='text-align:center' colspan='2'><button  class="button" type="submit">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>
				<button  class="button" type="button" onclick="sfdp.layer_close()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></td>
			</tr>
		</table>
	</form>

	{$tmp['js']}
	<script type="text/javascript" language="javascript">
		  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
			lineNumbers: true,
         lineWrapping: true,
			mode: "text/typescript",
			theme: "dracula",	//设置主题
			 matchBrackets: true,
		  });
		   editor.setSize('auto',document.body.clientHeight - 140 +"px");
	</script>
	<script src="{$patch}sfdp.tpl.js"></script>
</body>
</html>
php;
	}
    /**
     * 脚本函数
     *
     * @param  Array $info 设计数据
     * @param  int   $sid 设计ID
     */
    public static function mysql($info,$sid){
        $tmp = self::commontmp('Sfdp超级表单设计器');
        $urls= unit::gconfig('url');
        $info['s_fun'] = $info['s_fun'] ?? '';
        $action = $urls['api'].'?act=mysql&sid='.$sid;
        $patch = unit::gconfig('static_url');
        return <<<php
	{$tmp['css']}
		<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/codemirror.css" />
		<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/dracula.css" />
		<script src="{$patch}lib/codemirror/codemirror.js"></script>
		<script src="{$patch}lib/codemirror/javascript.js"></script>
		
		<table class="table">
			<tr valign="center">
			<td style='width:35px;text-align:center'>构建助手说明</td>
			<td style='width:330px;text-align: left;'>
			    *你可以使用 导出的数据表，快速构建一个项目模型
			</td>
			</tr>
			<tr valign="center">
			<td style='width:35px;text-align:center'>建表语句</td><td style='width:330px' >
			<textarea placeholder="请填写JQ脚本代码！" type="text/plain" style="width:100%;height:450px;display:inline-block;" id='code'></textarea> </td>
			</tr>
			<tr valign="center">
			<td style='width:35px;text-align:center'>字段完善</td>
			<td style='width:330px;text-align: left;' id='field'>
			        
			</td>
			</tr>
			<tr valign="center">
			<td style='text-align:center' colspan='2' id="btn">
			    <button  class="button" onclick="save()">&nbsp;&nbsp;数据分析执行&nbsp;&nbsp;</button>
			    
			</td>
			</tr>
		</table>
	{$tmp['js']}
	<script type="text/javascript" language="javascript">
	    var field =[]; //组成字段数组
		  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
			lineNumbers: true,
         lineWrapping: true,
			mode: "text/typescript",
			theme: "dracula",	//设置主题
			 matchBrackets: true,
		  });
		  function build(){
		      $('#btn').html(``);
		      var data = [];
		      var search = $('#fields').serializeArray();
		      $.each(search, function(index,vars) {
                    var dbname = this.name;
                    if(data[dbname] == undefined){
                        data[dbname] = [];
                        data[dbname].push(vars.value || '');
                    }else{
                        data[dbname].push(vars.value);
                    }
              });
             var field_json ={}; //组成字段数组  
		     $.each(field,function(index,value){
		             var type = data[value.Field+'[t1]'][0]
		             var code = 'Id' + sfdp.dateFormat(new Date(), "hhmmssS")+index;
		             var td_id = type + '_' + sfdp.dateFormat(new Date(), "yyyyMMddhhmmssS")+index;
		             field_json[code] = {
		             data:{},
		             tr: code,
                     type: 1
		             };
		             var tpfd_dbcd = value.FieldType.match(/\(([^)]*)\)/);
		             if(tpfd_dbcd==null){
		                var tpfd_dblx = value.FieldType
		                var tpfd_dbcd = '0';
		                }else{
		                var tpfd_dblx = value.FieldType.replace(tpfd_dbcd[0], '')
		                var tpfd_dbcd = tpfd_dbcd[1];
		             }
		             console.log(tpfd_dblx);
		             field_json[code].data[td_id] = {
                        td: 1,
                        td_type: type,
                        tpfd_chaxun: "no",
                        tpfd_check: "1",
                        tpfd_db: value.Field,
                        tpfd_dbcd: tpfd_dbcd,
                        tpfd_dblx: tpfd_dblx,
                        tpfd_id: td_id,
                        tpfd_list: "no",
                        tpfd_must: data[value.Field+'[t3]'][0],
                        tpfd_name: value.FieldNote,
                        tpfd_read: data[value.Field+'[t2]'][0],
                        tr_id: code,
                        tpfd_moren: "",
                        tpfd_zanwei:""

		             }
		             if(jQuery.inArray(type, [ 'dropdown', 'radio','checkboxes']) !== -1){
		                field_json[code].data[td_id].checkboxes_func = ''
		                field_json[code].data[td_id].tpfd_data = ["类别1", "类别2"]
		                field_json[code].data[td_id].xx_type = 0
		             }
             });
             var json_data = JSON.parse(localStorage.getItem("json_data"));
                json_data.list = field_json;
		        localStorage.setItem("json_data", JSON.stringify(json_data));
		        parent.$("#up_save").trigger("click");
		        layer.msg('构建成功，页面即将刷新',{icon:1,time: 3500},function(){
                    parent.location.reload(); // 父页面刷新
                }); 
		        
		  }
		   function save(){
		        $('#btn').html(`<button  class="button" onclick="build()" style="background-color: #e55417;">&nbsp;&nbsp;立即构建&nbsp;&nbsp;</button>`);
		        var Reg = /`(?<Field>\S+)` (?<FieldType>[a-z\d()]+) .*?COMMENT '(?<FieldNote>.*?)'/;
		        var data = editor.getValue().split(',');;
		        $.each(data,function(index,value){
		            var f = Reg.exec(value);
		            if(f != null){
                        if(jQuery.inArray(f.groups.Field, [ 'id', 'uid','status','create_time','update_time','uptime']) == -1){
                            field.push(f.groups);
                        }
		            }
                 });
                 var html ='<form id="fields"><table class="table"><tr><td>字段名称</td><td>字段描述</td><td>字段类型</td><td>组件类型</td><td>是否只读</td><td>是否必填</td></tr>';
                 //field转为数组，并渲染给用户确认组件模块 edit
                 $.each(field,function(index,value){
                        html += `<tr><td>`+value.FieldNote+`</td><td>`+value.Field+`</td><td>`+value.FieldType+`</td><td><select name="`+value.Field+`[t1]"><option value="text">输入框</option><option  value="dropdown">下拉框</option><option value="radio">单选框</option><option value="date">时间日期</option><option value="textarea">多行文本</option><option value="edit">富文本编辑器</option><option value="upload">上传组件</option><option value="upload_img">图片上传</option><option value="dropdowns">下拉多选</option></select></td><td><select name="`+value.Field+`[t2]"><option value="0">是</option><option value="1" selected="">否</option></select></td><td><select name="`+value.Field+`[t3]"><option value="0">是</option><option value="1" selected="">否</option></select></td></tr>`;
                 });
                 $('#field').append(html+'</tbale><form>');
		   }
	</script>
</body>
</html>
php;
    }
	/**
	 * 公用模板方法
	 *
	 **/
	static function commontmp($title){
		$patch = unit::gconfig('static_url');
		$css = '<link rel="stylesheet" type="text/css" href="'.$patch.'sfdp.5.0.css?v=5.0.1" />';
		$js = '<script src="'.$patch.'lib/jquery-1.12.4.js"></script>
		<script src="'.$patch.'lib/layer/3.1.1/layer.js"></script>
		<script src="'.$patch.'sfdp.5.0.js?v=5.0.1"></script>';
		$head ='<title>'.$title.'</title><head>'.$css.'</head><body style="background-color: white;">';
		return ['head'=>$head,'css'=>$css,'js'=>$js];
	}
}