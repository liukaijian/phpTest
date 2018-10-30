<?php 
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Base extends Controller{
	protected function _initialize(){
		define('USER_ID',Session::get('user_id'));
	}
	protected function isLogin(){
		if(USER_ID===NULL){
			$this->error('请先登录','admin/user/login');
		}
	}
	protected function alreadyLogin(){
		if(USER_ID!==NULL){
			$this->error('请不要重复登录','admin/index/index');
		}
	}
}  











 ?>