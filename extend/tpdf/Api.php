<?php
namespace tpdf;

use think\Request;
use think\Db;
use think\view;

class Api
{
	static function lists($map='')
	{
		
		return view(env('root_path') . 'extend/tpdf/template/index.html');
	}
	public function edit()
	{
		if ($this->request->isPost()) {
		$data = input('post.');
		$ret=controller('Base', 'event')->commonedit('Newss',$data);
	    if($ret['code']==0){
			return msg_return('修改成功！');
			}else{
			return msg_return($ret['data'],1);
		}
	   }
		$this->assign("type",Db::query("SELECT id,type as name FROM `wf_news_type`"));
		$this->assign("type2",Db::query("SELECT id,type as name FROM `wf_news_type`"));
		
		$this->assign('vo', db('Newss')->find(input('id')));
		return $this->fetch();
	}
	public function add()
	{
		$sfdp_id = db('sfdp_link')->where('work_id',2)->value('sfdp_id');
		$json = db('fb')->find($sfdp_id);
		$this->assign('data', $json['ziduan']);
		return $this->fetch('edit');
	}
	public function view()
	{
		$this->assign('vo', db('Newss')->find(input('id')));
		return $this->fetch();
	}
}
