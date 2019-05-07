<?php
namespace App\Http\Controllers\Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    /* login user and return user data */
    public function login(Request $request) {
       
        $credentials = request(['email', 'password']);
        //authenticate user
        if(Auth::attempt($credentials)){
           // $user = $request->user();

            //create token
            //$tokenResult = $user->createToken('coffeedrop');
            //$token = $tokenResult->token;
            //$token->save();
            // $name=$user->name;
            
            //return user information after successfull authentication
            return response()->json([
                'status'=>'success'
            ]);
        }
        else{
            //return error message after failed authentication
            return response()->json([
                'status'=>'unauthorized','message' => 'Yo do not have authorized access '
            ]);
        }
    }

    /* Regsiter New User */
    public function register(Request $request)
    {
        //validate user information before storing it
        $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string'
        ]);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        //store user information after verification
        if($user->save()){
            return response()->json([
            'status'=>"success",
            'message' => 'Successfully created user!'
            ], 201);
        }
        else{
            //return error message for failed insertion
            return response()->json([
            'status'=>"error",
            'message' => 'could not created user!'
            ]);
        }
    }

    /* logout user */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
        'status'=>"success",
        'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
