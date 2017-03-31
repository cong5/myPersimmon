<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/18
 * Time: 22:21
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * Update the password for the user.
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'password'=>'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8'
        ]);
        $result = $request->user()->fill([
            'password' => Hash::make($request->password)
        ])->save();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

}