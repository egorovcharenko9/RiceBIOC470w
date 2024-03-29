<?php
/**
  *+------------------
  * SFDP-超级表单开发平台V5.0
  *+------------------
  * Sfdp 适配器设计器数据类
  *+------------------
  * Copyright (c) 2018~2020 https://cojz8.com All rights reserved.
  *+------------------
  * Author: guoguo(1838188896@qq.com)
  *+------------------ 
  */
namespace sfdp\custom;

use think\facade\Db;

use sfdp\fun\BuildFun;
use sfdp\fun\SfdpUnit;
use sfdp\fun\BuildTable;

use sfdp\lib\unit;


class AdapteeScript{

    function getVer($id){
        $info = Db::name('sfdp_script')->where('id',$id)->value('add_time');
        if($info){
            return  $info;
        }else{
            return  false;
        }
    }

	function findWhere($map){
		$info = Db::name('sfdp_script')->where($map)->find();
		if($info){
			return  $info;
		}else{
			return  false;
		}
	}
	function update($data){
		$info = Db::name('sfdp_script')->update($data);
		if($info){
			return  $info;
		}else{
			return  false;
		}
	}
	function insert($data){
		$info = Db::name('sfdp_script')->insertGetId($data);
		if($info){
			return  $info;
		}else{
			return  false;
		}
	}
}