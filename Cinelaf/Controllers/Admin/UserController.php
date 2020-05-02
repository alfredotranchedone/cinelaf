<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 22:08
 */

namespace Cinelaf\Controllers\Admin;


use App\User;
use Cinelaf\Repositories\Rating;
use Cinelaf\Traits\Redirectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{

    use Redirectable;

    public function get_index()
    {

        $users = User::all();

        return view('admin.users.index',compact(
            'users'
        ));

    }


    public function get_add()
    {
        return view('admin.users.add');
    }


    public function post_create(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|max:100|min:3|unique:users,name',
            'email' => 'required|max:100|min:6|email',
            'password' => 'required|confirmed|max:50',
            'admin' => 'required|in:0,1'
        ]);

        DB::beginTransaction();

        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->is_super_admin = $request->admin;
            $user->save();

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success','Utente creato!');


        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorCallback($e);

        }

    }

    public function get_edit(User $user)
    {

        return view('admin.users.edit', compact(
            'user'
        ));

    }

    public function put_update(Request $request, User $user)
    {


        $this->validate($request,[
            'name' => ['required','max:100','min:3',Rule::unique('users','name')->ignore($user->id)],
            'email' => ['required','max:100','min:6','email',Rule::unique('users','email')->ignore($user->id)],
            'password' => 'nullable|confirmed|max:50'
        ]);

        DB::beginTransaction();

        try {

            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password)
                $user->password = Hash::make($request->password);
            $user->is_super_admin = $request->admin;
            $user->save();

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success','Utente modificato!');

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorCallback($e);

        }

    }


    public function delete(User $user)
    {
        try {

            $user_id = $user->id;
            $ratingRepo = new Rating();



            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success','Utente eliminato!');

        } catch (\Exception $e) {

            return $this->errorCallback($e);


        }
    }


    private function errorCallback(\Exception $error){

        logger()->error($error->getMessage());
        return $this->errorRedirect('admin.users.index');

    }


}