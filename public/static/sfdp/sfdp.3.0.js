	//Tpfd表单控件
	var fb_config_data = {
		name:'',//表单名称
		name_db:'',//数据表名称
		tpfd_id:'SFDP'+dateFormat(new Date(), "mmssS"),//表单ID
		tpfd_class:'',//表单样式
		tpfd_fun:'',//调用方法
		tpfd_script:'',//数据表脚本
		list:{
			//设计数据
		},
		tpfd_time:dateFormat(new Date(), "yyyy-MM-dd hh:mm:ss"),//表单设计时间
		tpfd_ver:'v3.0'//表单设计器版本
	};

	// 格式化时间
	function dateFormat(oDate, fmt) {
		var o = {
			"M+": oDate.getMonth() + 1, //月份
			"d+": oDate.getDate(), //日
			"h+": oDate.getHours(), //小时
			"m+": oDate.getMinutes(), //分
			"s+": oDate.getSeconds(), //秒
			"q+": Math.floor((oDate.getMonth() + 3) / 3), //季度
			"S": oDate.getMilliseconds()//毫秒
		};
		if (/(y+)/.test(fmt)) {
			fmt = fmt.replace(RegExp.$1, (oDate.getFullYear() + "").substr(4 - RegExp.$1.length));
		}
		for (var k in o) {
			if (new RegExp("(" + k + ")").test(fmt)) {
				fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
			}
		}
		return fmt;
	}
	//日志输出区
	function logout(info){
		var szInfo = "<div>[" + dateFormat(new Date(), "mm:ss")+'] ' + info+"</div>";
		$("#logout").html(szInfo + $("#logout").html());
	}
	//表单构建
	function addtr(id,old_data='',showtype=''){ 
		if(!has_item('fb_name')){
			return;
		}
		
		var $targetTbody= $("#table_center tbody");
		var $tr = $targetTbody.children("tr[class='table_tr']:last");
		if(old_data==''){
			var code = 'Tr'+dateFormat(new Date(), "hhmmssS");
		}else{
			var code = old_data['tr'];
		}
		
		switch(id) {
			case 1:
				logs ='新增1*1单元行';
				var html ='<tr class="table_tr" id='+code+'><td id="1" class="fb-fz x-1 code_td" colspan="4"><span class="code">'+code+'</span><span class="code2 fa fa-minus-square"></span></td></tr>';
				break;
			case 2:
				logs ='新增1*2单元行';
				var html ='<tr class="table_tr" id='+code+'><td id="1" class="fb-fz x-2" colspan="2"></td><td id="2" colspan="2"class="fb-fz code_td" ><span class="code">'+code+'</span><span class="code2 fa      fa-minus-square"></span></td></tr>';
				break;
			case 3:
				logs ='新增1*3单元行';
				var html ='<tr class="table_tr" id='+code+'><td id="1" class="fb-fz x-4"></td><td id="2" class="fb-fz x-4"></td><td id="3" colspan="2"class="fb-fz  x-2 code_td" ><span class="code">'+code+'</span><span class="code2 fa fa-minus-square"></span></td></tr>';
				break;
			case 4:
				logs ='新增1*4单元行';
				var html ='<tr class="table_tr" id='+code+'><td id="1" class="fb-fz x-4"></td><td id="2" class="fb-fz x-4"></td><td id="3" class="fb-fz x-4"></td><td id="4" class="fb-fz x-4 code_td"><span class="code">'+code+'</span><span class="code2 fa fa-minus-square"></span></td></tr>';
				break;
			 default:
				var html ='';
		} 
		if(old_data==''){
			save_json({tr:code,data:{},type:id},code,'tr');
			logout(logs);
		}else{
			logout('[恢复]'+logs);
		}
		if(showtype==''){
			$tr.after(html);
			$( ".fb-fz" ).sortable({
					opacity: 0.5,
					revert: true,
					stop: function( event, ui ) {
						var type = $(this).children('a').attr("data");
						var parent_code = $(this).parent().attr("id"); //获取Tr的ID值 
						if($(this).html().indexOf("code") >= 0 ) { 
							var code = '<span class="code">'+parent_code+'</span><span class="code2 fa fa-minus-square"></span>';
						}else{
							var code = '';
						}
						var html =fb_tpl(type,code,parent_code,$(this).attr("id"));
						//禁止本单元格再放置其他控件
						$(this).removeClass("fb-fz");
						$(this).removeClass("ui-sortable");
						//$(this).children('a').attr("data");
						$(this).html(html);
						//console.log($(this).attr("id"));
					}
					
			});
			
			$(".code2").unbind('click').click(function(){
					var tr_id = $(this).parent().parent().attr("id");
					save_json('',tr_id,'tr_del');
					logout('删除了单元行'+tr_id);
					$(this).parent().parent().remove();
			});
		}else{
			return html;
		}
	}
	//文本转换
	function fb_tpl(type,code,parent_code,td_xh,old_data=0){
		
		if(old_data==0){
			var td_id = type+'_'+ dateFormat(new Date(), "mmssS");
		}else{
			var td_id =old_data;
		}
		var labid = 'onclick=showLayer("'+type+'","'+td_id+'","'+parent_code+'") id="label'+td_id+'"';
		var inputid = 'id="input'+td_id+'"';
		switch(type) {
			case 'text':
				var html ='<label '+labid+'>文本控件：</label><input '+inputid+' type="text"  placeholder="请输入信息~" disabled>';
				break;
			case 'upload':    
				var html ='<label '+labid+'>上传控件：</label>上传';
				break;
			case 'checkboxes':
				var html ='<label '+labid+')>多选控件：</label>选项1<input type="checkbox"  placeholder="" disabled> 选项2<input type="checkbox"  placeholder="" disabled>';
				break;
			case 'radio':
				var html ='<label '+labid+')>单选控件：</label>选项1<input type="radio"  placeholder="" disabled> 选项2<input type="radio"  placeholder="" disabled>';
				break;
			case 'date':
				var html ='<label '+labid+')>时间日期：</label><input type="text"  placeholder="" disabled >';
				break;
			case 'dropdown':
				var html ='<label '+labid+'>下拉选择：</label><select disabled><option value ="请选择">请选择</option></select>';
				break;
			case 'textarea':
				var html ='<label '+labid+'>多行控件：</label><textarea  disabled ></textarea>';
				break;
			case 'html':
				var html ='<label '+labid+'>HTML控件：</label><b style="color: blue;">Look this is a HTML</b>';
				break;
			case 'wenzi':
				var html ='<label '+labid+'>文字控件：</label>默认现实的文本';
				break;
			 default:
				var html ='';
		}
		if(old_data==0){
			save_json({td:td_xh,td_type:type,tpfd_id:td_id},parent_code,'tr_data');
		}
		return html+code;
	}
	//
	function fb_set(type,id,parent_code){
		$('#zd_id').html(id);
		var all_data = JSON.parse(localStorage.getItem("json_data"));
		var default_data = all_data['list'][parent_code]['data'][id];
		if(default_data.tpfd_db==undefined){
			var tpfd_db = {tpfd_id: id,tr_id:parent_code, tpfd_db:'',tpfd_name: "", tpfd_zanwei: "", tpfd_moren: "", tpfd_chaxun: "yes",tpfd_list: "no"};
		}else{
			var tpfd_db =default_data;
		}
		return $.tpfd_return(type,tpfd_db);
	}
	function fb_set_return(data){
		//console.log('id='+data.tpfd_id);
		$('#label'+data.tpfd_id).html(data.tpfd_name+'：');
		//$('#input'+data.tpfd_id).val(data.tpfd_moren);
		$('.tpfd-pop').fadeOut();
	}
	//点击保存按钮
	$('.tpfd-ok').on('click', function() {
		var params = $("#myform").serializeObject(); //将表单序列化为JSON对象  
		//console.log(params);
		if($('#showtype').val()=='view'){
			$('.tpfd-pop').fadeOut();
			logout('界面预览成功！');
		}else{
			if(params.name!=undefined){
				$('#fb_name').html(params.name+'(DbTable:'+params.name_db+')');
				var json_data = JSON.parse(localStorage.getItem("json_data"));
					//console.log(json_data);
					json_data['name']=params.name;
					json_data['name_db']=params.name_db;
					json_data['tpfd_class']=params.tpfd_class;
					json_data['tpfd_fun']=params.tpfd_fun;
					json_data['tpfd_script']=params.tpfd_script;
				localStorage.setItem("json_data",JSON.stringify(json_data));
				$('.tpfd-pop').fadeOut();
				logout('初始化配置成功！');
			}else{
				fb_set_return(params);
				save_json(params,params.tr_id,'td_data');
			}
		}
	});
	//修改配置项
	function fb_config(Item){
	  var name=prompt("请输入设计的表单姓名","测试表单");
	  if (name!=null && name!=""){
		 $('#fb_name').html(name);
		 var json_data = JSON.parse(localStorage.getItem("json_data"));
			json_data[Item]=name;
			localStorage.setItem("json_data",JSON.stringify(json_data));
			//console.log(json_data)
		}else{
			alert('请配置参数' + Item + '的信息');
		}
	}
	
	//配置项判断
	function has_item(Item){
		var json_data = JSON.parse(localStorage.getItem("json_data"));
		if(json_data[Item]==''){
			alert('参数' + Item + '未配置！');
			return ; 
		}
		return true;
	}

	
	function showLayer(type,id,parent_code){
		if(type=='config'){
			var json_data = JSON.parse(localStorage.getItem("json_data"));
			var html = '<div>设置表单标题：<input name="name" type="text" value='+json_data.name+'></div>'+
			'<div>数据库表名称：<input name="name_db" type="text" value='+json_data.name_db+'></div>'+
			'<div>设置表单样式：<input name="tpfd_class" type="text" value='+json_data.tpfd_class+'></div>'+
			'<div>表单调用函数：<textarea name="tpfd_fun">'+json_data.tpfd_fun+'</textarea></div>'+
			'<div>设置表单脚本：<textarea name="tpfd_script" rows="4" cols="20">'+json_data.tpfd_script+'</textarea></div>';
		$('#showtype').val('config');
		$('#table').html(html);
		}else if(type=='view'){
			$('#showtype').val('view');
			showview();
			//$('#table').html('<table id="table_view"> </table>');
		}else{
			$('#showtype').val('other');
			$('#table').html(fb_set(type,id,parent_code));
		}
		
		//fb_set
		var layer = $('#pop'),
			layerwrap = layer.find('.tpfd-wrap');
			layer.fadeIn();
		if(type=='view'){
			layerwrap.removeAttr("style");
			layerwrap.css({
			'width':'90%',
			'margin-left': '-10px',
			'top': '5%',
			'left': '5%',
			'height': '450px'
			});
		
		}else{
			layerwrap.removeAttr("style");
			layerwrap.css({
			'margin-top': -layerwrap.outerHeight() / 2
			});
		}
	}
	$('.tpfd-close').on('click', function() {
		$('.tpfd-pop').fadeOut();
	});
	//数据保存方法
	function save_json(data,key,type){
		//读取当前的JSON缓存
		//save_json(type,parent_code,'tr_data');
		var json_data = JSON.parse(localStorage.getItem("json_data"));
		switch(type) {
			case 'tr':
			json_data.list[key]=data;
			break;
			case 'tr_del':
			delete json_data.list[key];
			break;
			case 'tr_data':
				json_data['list'][key]['data'][data.tpfd_id] = data;
			break;
			case 'td_data':
				data.td = json_data['list'][key]['data'][data.tpfd_id]['td'];
				data.td_type = json_data['list'][key]['data'][data.tpfd_id]['td_type'];
				json_data['list'][key]['data'][data.tpfd_id] = data;
			break;
			default:
		} 
		localStorage.setItem("json_data",JSON.stringify(json_data));
	}
	function recovery_input(old_data){
		var td_data = old_data.data;
		//如果有设计数据，则开始恢复表单数据
		if(!$.isEmptyObject(old_data.data)){
			for (x in td_data){
				var type = td_data[x]['td_type'];
				var parent_code = old_data['tr'];
				if($('#'+old_data['tr']).children('td').eq(td_data[x]['td']-1).html().indexOf("code") >= 0 ) { 
					var class_code = '<span class="code">'+parent_code+'</span><span class="code2 fa fa-minus-square"></span>';
				}else{
					var class_code = '';
				}
				var html =fb_tpl(type,class_code,parent_code,td_data[x]['td'],td_data[x]['tpfd_id']);
				$('#'+parent_code).children('td').eq(td_data[x]['td']-1).removeClass("fb-fz");
				$('#'+parent_code).children('td').eq(td_data[x]['td']-1).removeClass("ui-sortable");
				$('#'+parent_code).children('td').eq(td_data[x]['td']-1).html(html);
				if(td_data[x]['tpfd_name']!=undefined){
					fb_set_return(td_data[x]);
				} 
			}
		}
	}
	//用于初始化设计
	function int_data(){
		var int_data = localStorage.getItem("json_data");
		if(int_data==null){
			localStorage.setItem("json_data",JSON.stringify(fb_config_data));
		}else{
			var r=confirm("已缓存有数据，是否继续设计？");
			if (r==true){
				//初始化获取已经设计的缓存数据
				var desc_data = JSON.parse(int_data);
				$('#fb_name').html(desc_data.name+'(DbTable:'+desc_data.name_db+')');
				 for (x in desc_data.list){
					addtr(desc_data.list[x]['type'],desc_data.list[x]);//恢复表单布局设计
					recovery_input(desc_data.list[x]);//用于恢复表单字段内容
				 }
			  localStorage.setItem("json_data",int_data);
			  }else{
			  localStorage.setItem("json_data",JSON.stringify(fb_config_data));
			}
		}
	}
		$.fn.serializeObject = function() {  
        var o = {};  
        var arr = this.serializeArray();  
        $.each(arr,function(){  
            if (o[this.name]) {  //返回json中有该属性
                if (!o[this.name].push) { //将已存在的属性值改成数组
                    o[this.name] = [ o[this.name] ];
                }  
                o[this.name].push(this.value || ''); //将值存放到数组中
            } else {  //返回json中没有有该属性
                o[this.name] = this.value || '';  //直接将属性和值放入返回json中
            }  
        });  
        return o;  
    }
	function addoption(id,type='checkbox'){
		$('#checkboxes'+id).children('span').attr("onclick","editoption("+id+")");
		$('#checkboxes'+id).children('span').html('Del');
		var html ='<div id="checkboxes'+(id+1)+'"><input type="'+type+'" name="tpfd_check" value='+(id+1)+' ><input name="tpfd_data" type="text" value="选项'+(id+2)+'"><span onclick=addoption('+(id+1)+',"'+type+'")>Add</span></div>';
		$('#checkboxes'+id).after(html);
	}
	function editoption(id){
		$('#checkboxes'+id).remove();
	}
	function showview(){
		var int_data = localStorage.getItem("json_data");
		if(int_data==null){
			alert('对不起，您尚未开始设计！');
		}else{
				//初始化获取已经设计的缓存数据
				var desc_data = JSON.parse(int_data);
				$('#table').html('<table id="table_view"><tr class="table_tr_view"><th  colspan="4">正在设计：<b id="fb_name_view"></b></th></tr> </table>');
				//$('#fb_name').html(desc_data.name+'(DbTable:'+desc_data.name_db+')');
				 for (x in desc_data.list){
					var table = addtr(desc_data.list[x]['type'],desc_data.list[x],'showview');//恢复表单布局设计
					$('.table_tr_view').after(table);
					console.log(table);
					//recovery_input(desc_data.list[x]);//用于恢复表单字段内容
				 } 
			  
			 
		}
		
	}
	