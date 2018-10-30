<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Teacher as TeacherModel;
use think\Request;
class Teacher extends Base{
	public function teacherList(){
		$this->assign('title','教师列表');
    	$this->assign('keywords','教师列表');
    	$this->assign('desc','教师列表');
		$teacher=TeacherModel::all();
		$count=TeacherModel::count();
		$teacherlist=[];
		foreach($teacher as $value){
			$data=[
				'id'=>$value->id,
				'name'=>$value->name,
				'degree'=>$value->degree,
				'school'=>$value->school,
				'mobile'=>$value->mobile,
				'hiredate'=>$value->hiredate,
				'status'=>$value->status,
				'grade'=>isset($value->grade->name)?$value->grade->name:'未分配',

			];
			$teacherlist[]=$data;
		}
		$this->assign('count',$count);
		$this->assign('data',$teacherlist);
		return $this->fetch();
	}
	public function addPage(){
		$this->assign('title','教师列表');
		$this->assign('keywords','教师列表');
		$this->assign('desc','教师列表');
		$this->assign('gradeList',\app\admin\model\Grade::all());
		return $this->fetch();
	}
	public function checkName(Request $request){
		$name=$request->param();
		$status=0;
		$message='教师名设置成功';
		$result=TeacherModel::get($name);
		if($result==true){
			$status=1;
			$message="教师名字重复,请重新填写";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function teacherAdd(Request $request){
		$teacher=$request->param();
		$rule=[
    		'name'=>'require',
    		'mobile'=>'require',
    		'school'=>'require',
    	];
    	$msg=[
    		'name.require'=>'教师名不能为空,请检查',
    		'mobile.require'=>'手机不能为空,请检查',
    		'school.require'=>'学校不能为空,请检查',
    	];
    	$status=0;
    	$result=$this->validate($teacher,$rule,$msg);
    	
    	if($result===true){
    		$TeacherModel=new TeacherModel;
    		$TeacherModel->data([
    			'name'=>$teacher['name'],
    			'degree'=>$teacher['degree'],
    			'mobile'=>$teacher['mobile'],
    			'school'=>$teacher['school'],
    			'hiredate'=>$teacher['hiredate'],
    			'grade_id'=>$teacher['grade_id'],
    			'status'=>$teacher['status'],
    		]);

    		$TeacherModel->save();
			$status=1;
			$result='保存成功';
  		}
		return ['status'=>$status,'message'=>$result];
	}
	//教师状态更新
	public function setStatus(Request $request){
		$teacher_id=$request->param();
		$teacher=TeacherModel::get($teacher_id);
		if($teacher->getData('status')==1){
			$teacher->status=0;
			$teacher->save();
		}else{
			$teacher->status=1;
			$teacher->save();
		}
	}
	//编辑模板渲染
	public function editPage(Request $request){
		$this->assign('title','教师信息编辑');
		$this->assign('keywords','教师信息编辑');
		$this->assign('desc','教师信息编辑');
		$teacher_id=$request->param();
		$data=TeacherModel::get($teacher_id);

		$this->assign('gradeList',\app\admin\model\Grade::all());
		$this->assign('list',$data);
		return $this->fetch();
	}
	public function doEdit(Request $request){
		$teacher=$request->param();
		$id=['id'=>$teacher['id']];
		$result=TeacherModel::update($teacher,$id);
		$status=0;
		$message="更新失败";
		if($result==true){
			$status=1;
			$message="更新成功";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function teacherDelete(Request $requset){
		$teacher_id=input('get.id');
		TeacherModel::update(['is_delete'=>1],['id'=>$teacher_id]);
		TeacherModel::destroy($teacher_id);
	}
	public function unDelete(){
		TeacherModel::update(['delete_time'=>NULL],['is_delete'=>1]);
	}
}

















 ?>