<?php 
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class User extends Model{
	use SoftDelete;
	protected $deleteTime='delete_time';
	protected $createTime='create_time';
	protected $updateTime='update_time';
	protected function getRoleAttr($value){
		$role=[
			0=>'管理员',
			1=>'超级管理员'
		];
		return $role[$value];
	}
	protected function getStatusAttr($value){
		$status=[
			0=>'已禁用',
			1=>'已启用'
		];
		return $status[$value];
	}
}












 ?>