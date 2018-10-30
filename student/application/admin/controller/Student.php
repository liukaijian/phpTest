<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Student as StudentModel;
use think\Request;
class Student extends Base{
	public function studentList(){
		$this->assign('title','学生添加');
    	$this->assign('keywords','学生添加');
    	$this->assign('desc','学生添加');
		$student=StudentModel::paginate(1);
		$count=StudentModel::count();
		foreach($student as $value){
			$value->grade=$value->grade->name;
			
		}
		$this->assign('count',$count);
		$this->assign('data',$student);
		return $this->fetch();
	}
	public function addPage(){
		$this->assign('title','学生添加');
		$this->assign('keywords','学生添加');
		$this->assign('desc','学生添加');
		$this->assign('gradeList',\app\admin\model\Grade::all());
		return $this->fetch();
	}
	public function checkName(Request $request){
		$name=$request->param();
		$status=0;
		$message='姓名设置成功';
		$result=StudentModel::get($name);
		if($result==true){
			$status=1;
			$message="姓名字重复,请重新填写";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function studentAdd(Request $request){
		$student=$request->param();
		$rule=[
    		'name'=>'require',
    		'age'=>'number',
    		'mobile'=>'require|length:11|number',
    		'email'=>'require|email',
    	];
    	$msg=[
    		'name.require'=>'姓名不能为空,请检查',
    		'age.number'=>'请输入正确年龄格式',
    		'mobile.require'=>'手机不能为空,请检查',
    		'mobile.length'=>'请输入正确手机号码',
    		'mobile.number'=>'请输入正确手机号码',
    		'email.require'=>'邮箱不能为空,请检查',
    		'email.email'=>'请输入正确邮箱格式',
    	];
    	$status=0;
    	$result=$this->validate($student,$rule,$msg);
    	
    	if($result===true){
    		$studentModel=new StudentModel;
    		$studentModel->data([
    			'name'=>$student['name'],
    			'sex'=>$student['sex'],
    			'age'=>$student['age'],
    			'mobile'=>$student['mobile'],
    			'email'=>$student['email'],
    			'start_time'=>$student['start_time'],
    			'grade_id'=>$student['grade_id'],
    			'status'=>$student['status'],
    		]);

    		$studentModel->save();
			$status=1;
			$result='保存成功';
  		}
		return ['status'=>$status,'message'=>$result];
	}
	//班级状态更新
	public function setStatus(Request $request){
		$student_id=$request->param();
		$student=StudentModel::get($student_id);
		if($student->getData('status')==1){
			$student->status=0;
			$student->save();
		}else{
			$student->status=1;
			$student->save();
		}
	}
	//编辑模板渲染
	public function editPage(Request $request){
		$this->assign('title','学生列表编辑');
		$this->assign('keywords','学生列表编辑');
		$this->assign('desc','学生列表编辑');
		$student_id=$request->param();
		$data=StudentModel::get($student_id);

		$this->assign('gradeList',\app\admin\model\Grade::all());
		$this->assign('list',$data);
		return $this->fetch();
	}
	public function doEdit(Request $request){
		$student=$request->param();
		$id=['id'=>$student['id']];
		$result=StudentModel::update($student,$id);
		$status=0;
		$message="更新失败";
		if($result==true){
			$status=1;
			$message="更新成功";
		}
		return ['status'=>$status,'message'=>$message];
	}
	public function studentDelete(Request $requset){
		$student_id=input('get.id');
		StudentModel::update(['is_delete'=>1],['id'=>$student_id]);
		StudentModel::destroy($student_id);
	}
	public function unDelete(){
		StudentModel::update(['delete_time'=>NULL],['is_delete'=>1]);
	}
}

















 ?>