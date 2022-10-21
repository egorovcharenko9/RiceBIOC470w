/*!
 * SFDP 表单设计器--字段构建
 * http://cojz8.com
 *
 * 
 * Released under the MIT license
 * http://cojz8.com
 *
 * Date: 2020年3月4日23:34:39
 */
$(function(){
    $.extend({
		tpfd_field_return:function(type,data){
			
			if (typeof(data['tpfd_name']) == 'undefined') {
				return $.tpfd_default(type,data);
			}else{
				switch(type) {
					case 'text':
					var html ='<label>'+data.tpfd_name+'：</label><input type="text" name="'+data.tpfd_db+'"  placeholder="" id="'+data.tpfd_id+'">';
					break;
					case 'radio':
					var html ='<label>'+data.tpfd_name+'：</label>'+tpfd_checkboxes_clss(data,'radio');
					case 'checkboxes':
					var html ='<label>'+data.tpfd_name+'：</label>'+tpfd_checkboxes_clss(data);
					break;
					case 'dropdown':
					var html ='<label>'+data.tpfd_name+'：</label>'+$.tpfd_select(data.tpfd_data,data.tpfd_db,0);
					break;
					case 'textarea':
					var html ='<label>'+data.tpfd_name+'：</label><textarea  name="'+data.tpfd_db+'"  placeholder="" ></textarea>';
					break;
					case 'upload':
					var html ='<label>'+data.tpfd_name+'：</label>'+$.tpfd_upload(data,data.tpfd_db,0);
					break;
					case 'date':
					var html ='<label>'+data.tpfd_name+'：</label>';
					break;
				}
			}
			return html;
        },
		tpfd_select:function(data,field,value){
			var datas = [];
			for (y in data){
				
				datas[y] = { cid:y,clab:data[y]};
			}
			var json_data =JSON.parse(JSON.stringify(datas));
				var html ='<select name="'+field+'" style="width: 80px">';
				for (z in json_data){
					html += '<option value="'+json_data[z]['cid']+'" '+((value) == 'yes' ? 'selected' : '') +'>'+json_data[z]['clab']+'</option>';
				}
				return html+'</select>';
			
        },
		tpfd_upload:function(data,field,value){
			
			var html = '<input type="text" name="'+data.tpfd_db+'"  placeholder="" id="'+data.tpfd_id+'"><span id="drag" style="width:80px;margin-left:5px">'+
						'<label for="file-input"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAPCAMAAADJev/pAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABa1BMVEUAAAAAAP8jKdYiJ9gmJtkZHuEiKdYiJtkiKNckKtUlKtUjKNcjKtczM8wkJdojKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYkKdYjKdYjKdYjKdcjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdcjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYjKdYAAACRoT2eAAAAeHRSTlMAAAAAAAAAAAAAAAAAAAALNFVXOA4DT7LHubjGt1kGgMxrIQodY8mMDSwzeTEowHsFXLrIxdNEFznNMHzKXx8WIAS2AZ2HAUix71TZoAJ6mZcILo/EJhK/QBWGmhM9iEW+EKdwPiUcg58HkEYGZ887c7TCSh5LXUkCRTE5AAAAAWJLR0QAiAUdSAAAAAlwSFlzAADqYAAA6mABhMnedgAAAAd0SU1FB+QDEQMWFOoAZu0AAAEbSURBVBjTTU/5OwJRFH0OUraEIaIwYynMiEiWyWQrokY0SlKWGLJv/777ar4v55d73znn3nseYzU0AXD2uHr7+qlpZg0AwsCge2jYMzLqBVosspVcXt/Y+IQoSpNT0yJgmTHjD8x65lDDvKzYUOcXgotLoeUVIBwG2rAaWQPsJAjrG5tqdEuDFItJNLK9s7tHhWE/EqjtUOOJRFyFAweHST8JR8ljrtvalVQ6nVIcgH6SOT0DyxrnPIUNOTGfF3PoIJt2kelkBfmSC3Z6F4vgZ7uAq1KZadc3tJdxyefjaXjQW7nA4HLf1X+ASsVqcP8gMJiPxlNZN01Tr1Z5Mc3s88srxYXzLWS8/8dH6ZPOdtPk13fwp4Hgb5S4P2sdNjKhHYdUAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDIwLTAyLTI2VDA5OjExOjEwKzAwOjAwmvYnqAAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wNC0yNVQwMjoxMDoyMCswMDowMAG1iHYAAAAgdEVYdHNvZnR3YXJlAGh0dHBzOi8vaW1hZ2VtYWdpY2sub3JnvM8dnQAAABh0RVh0VGh1bWI6OkRvY3VtZW50OjpQYWdlcwAxp/+7LwAAABh0RVh0VGh1bWI6OkltYWdlOjpIZWlnaHQAMTkyQF1xVQAAABd0RVh0VGh1bWI6OkltYWdlOjpXaWR0aAAzMDjhP6cxAAAAGXRFWHRUaHVtYjo6TWltZXR5cGUAaW1hZ2UvcG5nP7JWTgAAABd0RVh0VGh1bWI6Ok1UaW1lADE1NTYxNTgyMjDnCLouAAAAEXRFWHRUaHVtYjo6U2l6ZQA0ODUxQoxAmPUAAABadEVYdFRodW1iOjpVUkkAZmlsZTovLy9kYXRhL3d3d3Jvb3Qvd3d3LmVhc3lpY29uLm5ldC9jZG4taW1nLmVhc3lpY29uLmNuL2ZpbGVzLzEyMy8xMjMyOTc5LnBuZ6ul+WcAAAAASUVORK5CYII="></label></span>'+
						'<input type="file" accept="*/*" name="file[]" data-attr="'+data.tpfd_id+'" id="file-input" multiple class="input-file" style="display: none">';
			return html;
			
        },
		tpfd_default:function(type,data){
			switch(type) {
				case 'text':
					var html ='<label>文本控件：</label><input type="text"  placeholder="请输入信息~" >';
					break;
				case 'upload':    
					var html ='<label>上传控件：</label>上传';
					break;
				case 'checkboxes':
					var html ='<label)>多选控件：</label>选项1<input type="checkbox"  placeholder="" > 选项2<input type="checkbox"  placeholder="" >';
					break;
				case 'radio':
					var html ='<label)>单选控件：</label>选项1<input type="radio"  placeholder="" > 选项2<input type="radio"  placeholder="" >';
					break;
				case 'date':
					var html ='<label)>时间日期：</label><input type="text"  placeholder=""  >';
					break;
				case 'dropdown':
					var html ='<label>下拉选择：</label><select ><option value ="请选择">请选择</option></select>';
					break;
				case 'textarea':
					var html ='<label>多行控件：</label><textarea   ></textarea>';
					break;
				case 'html':
					var html ='<label>HTML控件：</label><b style="color: blue;">Look this is a HTML</b>';
					break;
				case 'wenzi':
					var html ='<label>文字控件：</label>默认现实的文本';
					break;
				 default:
					var html ='1';
				}
			return html;
		}
		
    })
	function tpfd_checkboxes_clss(data,type='checkbox'){
			var datas = [];
			for (y in data.tpfd_data){
				if(isInArray(data.tpfd_check,y)){
					var check='checked';
				}else{
					var check='';
				}
				datas[y] = { cid:y,clab:data.tpfd_data[y],checked:check};
			}
			var json_data =JSON.parse(JSON.stringify(datas));
			var html ='';
			for (z in json_data){
				html += '<input '+json_data[z]['checked']+' name="tpfd_check" value='+z+' type="'+type+'">'+json_data[z]['clab']+'';
			}
			return html;
		}
	function isInArray(arr,value){
		for(var i = 0; i < arr.length; i++){
		if(value === arr[i]){
		return true;
		}
		}
		return false;
	}
})