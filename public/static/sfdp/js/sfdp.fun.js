/*!
 * SFDP 表单设计器---公用方法库
 * http://cojz8.com
 *
 * 
 * Released under the MIT license
 * http://cojz8.com
 *
 * Date: 2020年3月4日23:34:39
 */
var Debug = false;//是否开启打印模式
var commonfun = {
	ShowTip:function(tip) {
		layer.msg(tip);
	},
	dateFormat : function (oDate, fmt){
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
	},
	
	fromdata : function (froms){
		var o = {};  
        var arr = froms.serializeArray();  
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
	},
	openpage : function(title, url, opt){
		if (typeof opt === "undefined") opt = {nav: true};
		w = opt.w || "80vw";
		h = opt.h || "80vh";
		return layer.open({
			type: opt.type || 2,
			area: [w, h],
			fix: false, // 不固定
			maxmin: true,
			shade: 0.4,
			title: title,
			content: url,
			success: function (layero, index) {
				if (typeof opt.confirm !== "undefined" && opt.confirm === true) {
					layero.find(".layui-layer-close").off("click").on("click", function () {
						layer.alert('您确定要关闭当前窗口吗？', {
							btn: ['确定', '取消'] //按钮
						}, function (i) {
							layer.close(i);
							layer.close(index);
						});
					});
				}
				// 自动添加面包屑导航
				if (true === opt.nav) {
					layer.getChildFrame('#nav-title', index).html($('#nav-title').html() + ' <span class="c-gray en">&gt;</span> ' + $('.layui-layer-title').html());
				}
				if (typeof opt.fn === "function") {
					opt.fn(layero, index);
				}
			}
		});
	},
	openfullpage : function(title, url, opt){
		return commonfun.openpage(title, url, $.extend({w: "100%", h: "100%"}, opt))
	},
	returnShow : function(data, callback, param){
		 if (data.code == 0) {
			layer.msg(data.msg,{icon:1,time: 1500},function(){
					parent.location.reload(); // 父页面刷新
			});          
		} else {
		   layer.alert(data.msg, {title: "错误信息", icon: 2});
		}	
	},
	sAjax : function(url,msg='操作成功'){
       $.ajax({
			type: 'GET',
			url: url,
			dataType: 'json',
			success: function(data){
				if(data.code==0){
					layer.msg('成功！!',{icon: 1,time:1000});
				}else{
					 layer.alert(data.msg, {title: "错误信息", icon: 2});
				}
			},
			error:function(data) {
					console.log(data);
					layer.msg('错误!',{icon: 5,time:1000});
			},
		});		
    },
	sPost : function(url,data){
        if(isDebug){
            console.log('[URL]'+url);
        } 
        $.get(url,function(obj){ obj = JSON.parse(obj); if(isDebug) console.log('[Res]'+JSON.stringify(obj)); success(obj); });
    }  
}