<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Grade as GradeModel;
use think\Request;
class Grade extends Base{
	public function gradeList(){
		$this->assign('title','班级列表修改');
    	$this->assign('keywords','班级列表修改');
    	$this->assign('desc','班级列表修改');
		$grade=GradeModel::all();
		$count=GradeModel::count();
		$gradelist=[];
		foreach($grade as $value){
			$data=[
				'id'=>$value->id,
				'name'=>$value->name,
				'length'=>$value->length,
				'price'=>$value->price,
				'status'=>$value->status,
				'create_time'=>$value->create_time,
				'teacher'=>isset($value->teacher->name)?$value->teacher->name:'未分配',

			];
			$gradelist[]=$data;
		}
		$this->assign('count',$count);
		$this->assign('data',$gradelist);
		return $this->fetch();
	}
	public function addPage(){
		$this->assign('title','班级列表添加');
		$this->assign('keywords','班级列表添加');
		$this->assign('desc','班级列表添加');
		return $this->fetch();
	}
	public function checkName(Request $request){
		$name=$request->param();
		$status=1;
		$message='班级名设置成功';
		$result=GradeModel::get($name);
		if($result==true){
			$status=0;
			$message="班级名字重复,请重新填写";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function gradeAdd(Request $request){
		$grade=$request->param();
		$rule=[
    		'name|班级名'=>'require',
    		'length|学制'=>'require',
    		'price|学费'=>'require',
    	];
    	$msg=[
    		'name.require'=>'班级名不能为空,请检查',
    		'length.require'=>'学制不能为空,请检查',
    		'price.require'=>'学费不能为空,请检查',
    	];
    	$status=0;
    	$result=$this->validate($grade,$rule,$msg);
    	if($result===true){
    		$gradeModel=new GradeModel;
    		$gradeModel->data([
    			'name'=>$grade['name'],
    			'length'=>$grade['length'],
    			'price'=>$grade['price'],
    			'status'=>$grade['status'],
    		]);
    		$gradeModel->save();
			$status=1;
			$result='保存成功';
  		}
		return ['status'=>$status,'message'=>$result];
	}
	//班级状态更新
	public function setStatus(Request $request){
		$grade_id=$request->param();
		$grade=GradeModel::get($grade_id);
		if($grade->getData('status')==1){
			$grade->status=0;
			$grade->save();
		}else{
			$grade->status=1;
			$grade->save();
		}
	}
	//编辑模板渲染
	public function editPage(Request $request){
		$this->assign('title','班级列表修改');
		$this->assign('keywords','班级列表修改');
		$this->assign('desc','班级列表修改');
		$grade_id=$request->param();
		$data=GradeModel::get($grade_id);

		$data->teacher=$data-> teacher->name;
		$this->assign('list',$data);
		return $this->fetch();
	}
	public function doEdit(Request $request){
		$grade=$request->except('teacher');
		$id=['id'=>$grade['id']];
		$result=GradeModel::update($grade,$id);
		$status=0;
		$message="更新失败";
		if($result==true){
			$status=1;
			$message="更新成功";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function gradeDelete(Request $requset){
		$grade_id=input('get.id');
		GradeModel::update(['is_delete'=>1],['id'=>$grade_id]);
		GradeModel::destroy($grade_id);
	}
	public function unDelete(){
		GradeModel::update(['delete_time'=>NULL],['is_delete'=>1]);
	}
}

















 ?>