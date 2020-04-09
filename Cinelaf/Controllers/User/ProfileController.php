<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:24
 */

namespace Cinelaf\Controllers\User;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class ProfileController extends BaseController
{


    public function get_me()
    {
        $myAvg = (new \Cinelaf\Repositories\User())->myAverage();
        $user = auth()->user();
        return view('user.edit', compact(
            'user', 'myAvg'
        ));

    }


    public function put_me(Request $request, User $user)
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
            $user->save();

            DB::commit();

            return redirect()
                ->route('me')
                ->with('success','Utente modificato!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('me')
                ->with('warning','Errore durante il salvataggio!');

        }

    }

}