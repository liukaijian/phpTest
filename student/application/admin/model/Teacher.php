<?php 
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Teacher extends Model{
	//引用软删除
	use SoftDelete;
	protected $dataFormat='Y/m/d';
	protected $deleteTime='delete_time';
	protected $autoWriteTimestamp=true;
	protected $createTime='create_time';
	protected $updateTime='update_time';
	 protected $type = [
        'hiredate'=>'datatime',
    ];
	public function grade(){
		return $this->belongsTo('Grade');
	}
	protected function getStatusAttr($v){
		$status=[
			0=>'未启用',
			1=>'已启用'
		];
		return $status[$v];
	}
	protected function getDegreeAttr($v){
		$degree=[
			1=>'专科',
			2=>'本科',
			3=>'研究生'
		];
		return $degree[$v];
	}
}














 ?>