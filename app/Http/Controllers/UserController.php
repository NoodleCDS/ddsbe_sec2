<?php 
    namespace App\Http\Controllers;
    
    //use App\User;
    use App\Models\User;    //your model
    use App\Models\UserJob;  
    use Illuminate\Http\Response;
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
            // $user = User::all();
            // return response() ->json($user,200);         
            // //return $this->response($user, 200);
            
            $user = DB::connection('mysql')
            ->select("Select * from tbluser");
            //return response() ->json($user,200); 
            return $this->successResponse($user);
        }

        public function index(){
            $user = User::all();
            return $this->successResponse($user);
        }
        
        public function addUser(Request $request){
            $rules =[
                'username' => 'required|max:20',
                'password' => 'required|max:20',
                'jobid' => 'required|numeric|min:1|not_in:0',
            ];  

            $this->validate($request,$rules);
            $usersjob = UserJob::findorFail($request->jobid); // validate if jobid is found in tbluserjob
            
            $user = User::create($request->all());  //this the data you will fill in your users fillable
            return $this->successResponse($user,Response::HTTP_CREATED);
        }

        public function show($id){
            // $user = User::findOrFail($id);
            // return $this->successResponse($user);

            $user = User::where('id',$id)->first();
            if($user){
                return $this->successResponse($user);
            }
            else
            {
                return $this->errorResponse('User does not exist.',Response::HTTP_NOT_FOUND);
            }
        }

        public function update(Request $request, $id){
            $rules =[
                'username' => 'max:20',
                'password' => 'max:20',
                'jobid' => 'required|numeric|min:1|not_in:0',
            ]; //not required so we could use patch

            $this->validate($request,$rules);

            // $user = User::findOrFail($id);
            $usersjob = UserJob::findorFail($request->jobid);
            $user = User::where('id',$id)->first();
            if($user){
                $user -> fill($request->all());

                //no changes
                if($user->isClean()){
                    return $this->errorResponse('At least one (1) value must change!', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $user->save();
                return $this->successResponse($user);
             }
            {
                return $this->errorResponse('User does not exist.',Response::HTTP_NOT_FOUND);
            }
            
        }

        public function delete($id){
            
            // $user = User::findOrFail($id);
            
            $user = User::where('id',$id)->first();
            if($user){
                $user->delete();
                return $this->successResponse($user);
            }
            else
            {
                return $this->errorResponse('User does not exist.',Response::HTTP_NOT_FOUND);
            }
        }


}