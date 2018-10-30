<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use app\admin\model\User as UserModel;
use think\Session;
class User extends Base{
	//判断用户是否已经登录
	 public function login(){
	 	$this->alreadyLogin();
    	return view();
    }
    //验证信息
    public function checkLogin(Request $request){
    	//默认初始状态
    	$status= 0;
    	$result ='';
    	$data=$request->param();
    	$rule=[
    		'name|用户名'=>'require',
    		'password|密码'=>'require',
    		'verify|验证码'=>'require|captcha',
    	];
    	$msg=[
    		'name.require'=>'用户名不能为空,请检查',
    		'password.require'=>'密码不能为空,请检查',
    		'verify.require'=>'验证码不能为空,请检查',
    		'verify.captcha'=>'验证码错误'
    	];
    	$result=$this->validate($data,$rule,$msg);

    	if($result===true){
    		$map=[
    			'name'=>$data['name'],
    			'password'=>md5($data['password'])
    		];
    		$user=UserModel::get($map);
    		if($user==null){
    			$result = '没有找到该用户';
    		}else{
          UserModel::update(['login_count'=>$user->login_count+1],['id'=>$user->id]);
          UserModel::update(['login_time'=>time()],['id'=>$user->id]);
    			$status=1;
    			$result='验证通过,点击确定开始';
    			//设置用户登录信息
          $users=UserModel::get($map);
    			Session::set('user_id',$users->id);//用户id
    			Session::set('user_info',$users->getData());//获取用户所有信息
    		}
    	}
    	return ['status'=>$status,'message'=>$result,'data'=>$data];
    }
    //退出登录
    public function logout(){
    	Session::delete('user_id');
    	Session::delete('user_info');
    	$this->success('退出成功,返回到登录页面','admin/user/login');
    	
    }
    //获取管理员信息
     public function admin_list(){
    	$this->assign('title','管理员列表');
    	$this->assign('keywords','管理员列表');
    	$this->assign('desc','管理员列表');
    	$count=UserModel::count();
    	$this->assign('count',$count);

    	$userName=Session::get('user_info.name');
    	if($userName=='admin'){
    		$list = UserModel::all();
    	}else{
    		$list = UserModel::all(['name'=>$userName]);
    	}
    	$this->assign('list',$list);
    	return $this->fetch();
    }
    //改变管理员状态
   	public function set_status(Request $request){
   		header('content-type:text/html;charset="utf-8"');
   		$user_id=$request->param('id');
   		$result=UserModel::get($user_id);
   		if($result->getData('status')==1){
   			$result->status=0;
   			$result->save();
   		}else{
   			$result->status=1;
   			$result->save();
   		}
   	}
   	//管理员信息模板
   	public function editPage(Request $request){
   		$user_id=$request->param();
   		$result=UserModel::get($user_id);
   		$this->assign('title','编辑管理员信息');
   		$this->assign('keywords','更新信息');
   		$this->assign('desc','教学管理');
   		$this->assign('list',$result);
   		return $this->fetch();
   	}
   	//检查管理员修改信息用户名是否重复
   	public function check_name(Request $request){
   		$user_name=$request->param('name');
   		$result=UserModel::getByName($user_name);
   		$status=0;
   		$message="用户名设置成功";
   		if(isset($result)){
   			$status=1;
   			$message='用户名重复,请重新填写';
   		}
   		return ['status'=>$status,'message'=>$message];
   	}
   	//检查管理员修改信息邮箱是否重复
   	public function check_email(Request $request){
   		$user_email=$request->param('email');
   		$result=UserModel::getByEmail($user_email);
   		$status=0;
   		$message="邮箱设置成功";
   		if(isset($result)){
   			$status=1;
   			$message='邮箱重复,请重新填写';
   		}
   		return ['status'=>$status,'message'=>$message];
   	}
   	//管理员信息更新
   	public function doEdit(Request $request){
   		$user=$request->param();
   		$data=[];
   		foreach ($user as $key => $value ){
            if ($value!=NULL){
                $data[$key] = $value;
            }
        } 
    	 $result = UserModel::update($data,['id'=>$data['id']]);
        //如果是admin用户,更新当前session中用户信息user_info中的角色role,供页面调用
    	if (true == $result) {
            return ['status'=>1, 'message'=>'更新成功'];
        } else {
            return ['status'=>0, 'message'=>'更新失败,请检查'];
        }
         if (Session::get('user_info.name') == 'admin') {
            Session::set('user_info.role', $data['role']);
        }
   	}
   	//添加成员
   	public function admin_add(){
   		$this->assign('title','编辑管理员信息');
   		$this->assign('keywords','更新信息');
   		$this->assign('desc','教学管理');
   		return $this->fetch();
   	}
   	public function admin_save(Request $request){
   		$user=$request->param();
   		$data=new UserModel;
   		foreach ($user as $key => $value ){
            if ($value!=NULL){
                $data[$key] = $value;
            }
        } 
        $data['password']=md5($data['password']);
        $data['create_time']=time();
    	 $result = $data->save();
        //如果是admin用户,更新当前session中用户信息user_info中的角色role,供页面调用
    	if (true == $result) {
            return ['status'=>1, 'message'=>'添加成功'];
        } else {
            return ['status'=>0, 'message'=>'添加失败,请检查'];
        }

   	}
   	public function admin_delete(Request $request){
   		$user_id=input('get.id');
		UserModel::update(['is_delete'=>1],['id'=>$user_id]);
		UserModel::destroy($user_id);
   	}

   	public function unDelete(){
		UserModel::update(['delete_time'=>NULL],['is_delete'=>1]);
	}
}














 ?>