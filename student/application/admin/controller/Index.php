<?php
namespace app\admin\controller;
use app\admin\controller\Base;
class Index extends Base
{
    public function index()
    {	
    	$this->isLogin();
    	$this->assign('title','教学管理系统');
    	$this->assign('keywords','教学管理,统计');
    	$this->assign('desc','教学管理');
    	return $this->fetch();
    }
   
   
}
