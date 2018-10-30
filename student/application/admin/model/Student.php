<?php 
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Student extends Model{
	//引用软删除
	use SoftDelete;
	protected $dataFormat='Y/m/d';
	protected $deleteTime='delete_time';
	protected $autoWriteTimestamp=true;
	protected $createTime='create_time';
	protected $updateTime='update_time';
	protected $type=[
		'start_time'=>'datatime',
	];
	protected function getSexAttr($v){
		$sex=[
			0=>'男',
			1=>'女'
		];
		return $sex[$v];
	}
	protected function getStatusAttr($v){
		$status=[
			0=>'未启用',
			1=>'已启用'
		];
		return $status[$v];
	}
	public function grade(){
		return $this->belongsTo('grade');
	}
	
}









 ?>