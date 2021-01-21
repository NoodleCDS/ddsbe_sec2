<?php

    namespace App\Http\Controllers;

    use App\Models\UserJob;    //your model
    use Illuminate\Http\Response;
    use App\Traits\ApiResponser; //standardized code for api response
    use Illuminate\Http\Request;  //handling http request in lumen 
    use DB;

    Class UserJobController extends Controller {     
        use ApiResponser;

        private $request;
    
        public function __construct(Request $request){
        $this->request = $request;
        }

        // returns list of usersjob
        public function index(){
            $usersjob = UserJob::all();
            return $this->successResponse($usersjob);
        }

        public function show($id){
            $userjob = UserJob::findOrFail($id);
            return $this->successResponse($userjob);
        }
    }