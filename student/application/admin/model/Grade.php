<?php 
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Grade extends Model{
	use SoftDelete;
	protected $dataFormat='Y/m/d';
	protected $deleteTime='delete_time';
	protected $autoWriteTimestamp=true;
	protected $createTime='create_time';
	protected $updateTime='update_time';
	protected function getStatusAttr($v){
		$status=[
			0=>'已禁用',
			1=>'已启用'
		];
		return $status[$v];
	}
	public function teacher(){
		return $this->hasOne('teacher');
	}
	public function student(){
		return $this->hasMany('student');
	}
}













 ?>