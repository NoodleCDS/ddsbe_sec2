<?php 
    namespace App\Http\Controllers;
    
    //use App\User;
    use App\Models\User;    //your model
    use Illuminate\Http\Response; //Response Components
    use App\Traits\ApiResponser; //standardized code for api response
    use Illuminate\Http\Request;  //handling http request in lumen 
    use DB;
    
    Class UserController extends Controller {     
        use ApiResponser;
        
        private $request;
    
        public function __construct(Request $request){
        $this->request = $request;
        }

        public function getUsers(){         
            
            $user = DB::connection('mysql')
            ->select("Select * from tbluser");
            return response() ->json($user,200); 
            return $this->successResponse($user);
        }

        //The responser method
        public function index(){
            $user = User::all();
            return $this->successResponse($user);
        }

        public function showlogin(){
            return view('login');
        }

        public function result(){
            
            $username = $_POST["username"];
            $password = $_POST["password"];

            $login = app('db')->select("SELECT * FROM tbluser WHERE username='$username' and password ='$password'");
                        
            if(empty($login)){
                return $this->errorResponse('User Does not Exist',Response::HTTP_NOT_FOUND);
            }else{
                echo '<script>alert("Successfully logged in!")</script>';
                return view('login');
            }

        }
        
        public function addUser(Request $request){ //CREATE
            $rules =[
                'username' => 'required|max:20',
                'password' => 'required|max:20'
            ];  

            $this->validate($request,$rules);
            $user = User::create($request->all());
            return $this->successResponse($user,Response::HTTP_CREATED);
        }

        public function show($id){ //READ

            $user = User::where('id',$id)->first();
            if($user){
                return $this->successResponse($user); 
            }
            else
            {
                return $this->errorResponse('User Does not Exist',Response::HTTP_NOT_FOUND);
            }
        }

        public function update(Request $request, $id){ //UPDATE
            $rules =[
                'username' => 'max:20',
                'password' => 'max:20'
            ];

            $this->validate($request,$rules);


            $user = User::where('id',$id)->first();
            if($user){
                $user -> fill($request->all());

                //no changes
                if($user->isClean()){
                    return $this->errorResponse('You must change at least one (1) value.', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $user->save();
                return $this->successResponse($user);
             }
            {
                return $this->errorResponse('User Does not Exist',Response::HTTP_NOT_FOUND);
            }
            
        }

        public function delete($id){ //DELET
            
            $user = User::where('id',$id)->first();
            if($user){
                $user->delete();
                return $this->successResponse($user);
            }
            else
            {
                return $this->errorResponse('User Does not Exist',Response::HTTP_NOT_FOUND);
            }
        }


}