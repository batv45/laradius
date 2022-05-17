<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Deligoez\TCKimlikNo\TCKimlikNo;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user.show');
        $this->middleware('permission:user.create')->only('create','store');
        $this->middleware('permission:user.edit')->only('update','delete_profile_photo');
        $this->middleware('role:super-admin')->only('update_permission','update_role');
        $this->middleware('permission:user.delete')->only('destroy');
    }

    public function index(Request $request)
    {

        $users = User::whereKeyNot(auth()->id())
            ->with([
                'permissions' => function ($query) {
                    return $query->cacheFor(3600);
                },
                'roles' => function ($query) {
                    return $query->cacheFor(3600);
                },
            ]);

        if(!auth()->user()->hasRole('super-admin')){
            $users->role(['admin','user']);
        }else{
            $users->withTrashed();
        }

        /**
         * @var User $users
         */
        if($request->input('search') != null ){
            $users->where(function (Builder $query) use ($request) {
                $query->where('first_name','LIKE', '%'.$request->input('search').'%');
                $query->orWhere('last_name','LIKE', '%'.$request->input('search').'%');
                $query->orWhere('email','LIKE', '%'.$request->input('search').'%');
                $query->orWhere('tc_kn','LIKE', '%'.$request->input('search').'%');
            });
        }

        if($request->has('tcverified') && $request->input('tcverified') ==  0){
            $users->whereNull('tc_verified_at');
        }elseif($request->has('tcverified') && $request->input('tcverified') >  0) {
            $users->whereNotNull('tc_verified_at');
        }
        return inertia('Admin/User/Index', [
            'page_users' => $users->paginate()->appends($request->except('page'))
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return redirect()->route('admin.user.edit',$id);
    }

    public function edit($id)
    {
        $user = User::with('roles')->whereKeyNot(auth()->id());


        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin')){
            $user->role(['admin','user']);
        }else{
            $user->withTrashed();
        }

        $perms = [];
        $roles = [];
        if( auth()->user()->hasRole('super-admin')  ){
            $perms = Permission::all();
            $roles = Role::all();
        }

        $page_user = $user->findOrFail($id)->append('permissions_all');
        /**
         * @var Collection $page_activity
         */
        $page_activity = Activity::with('subject','causer')
        ->where(function($query)use($page_user){
            $query->CausedBy($page_user);
        })->orWhere(function($query)use($page_user){
            $query->ForSubject($page_user);
        })->get();

        return inertia('Admin/User/Edit', [
            'page_user' => $page_user,
            'page_permissions' => $perms,
            'page_roles' => $roles ,
            'page_activity' => $page_activity
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        /**
         * @var User $user
         */
        $user = User::whereKeyNot(auth()->id());
        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }

        $user = $user->findOrFail($id);
        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }

        $user->fill($user->hasVerifiedTC() ? $request->except('tc_kn','birth_year') : $request->validated());

        if($user->isDirty()){
            $tcsor = TCKimlikNo::validate(
                $request->input('tc_kn'),
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('birth_year'),
            );
            if (!$tcsor){
                flash('T.C. Kimlik  bilgisi doğrulanamadı!')->error();
                $tcverf = null;
            }else{
                $tcverf = now();
            }

            $up = $user->update(array_merge($request->validated(), ['tc_verified_at' => $tcverf]));

            if ($user->wasChanged())
                flash('Kullanıcı bilgileri güncellendi')->success();
            if (!$up)
                flash('Bilgiler güncellenemedi!')->error();
        }

        // Password Update Procedure
        if ($request->input('new_password') != null) {
            $user->forceFill([
                'password' => Hash::make($request->input('new_password')),
            ])->save();
            flash('Şifre bilgisi güncellendi')->success();
        }


        return back();
    }
    public function update_permission(Request $request, $id)
    {
        if(!$request->has('all')){
            flash('Yetki alanı gerekli.')->error();
            return back();
        }
        $user = User::whereKeyNot(auth()->id());
        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }
        $user = $user->findOrFail($id);
        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }
        $perms = collect($request->input('all'))
            ->diff($user->getPermissionsViaRoles()->pluck('name'));

        activity('user.permission.update')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['old_permissions' => $user->permissions->pluck('name'),'new_permissions' =>  $perms])
            ->log('Kullanıcı yetkileri güncellendi.');

        $user->syncPermissions($perms);

        \App\Models\Permission::flushQueryCache();
        flash('Kullanıcı yetkileri güncellendi')->success();
        return back();
    }
    public function update_role(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $user = User::whereKeyNot(auth()->id());
        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }
        $user = $user->findOrFail($id);
        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }

        activity('user.role.update')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['old_role' => $user->roles->first()->name,'new_role' =>  $request->input('name')])
            ->log('Kullanıcı rolü güncellendi.');

        $user->syncRoles($request->input('name'));

        \App\Models\Role::flushQueryCache();
        flash('Kullanıcı rolü güncellendi')->success();
        return back();
    }

    public function delete_profile_photo($id): \Illuminate\Http\RedirectResponse
    {
        $user = User::whereKeyNot(auth()->id());
        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }
        $user = $user->findOrFail($id);
        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }

        $user->deleteProfilePhoto();

        flash('Kullanıcı profil fotoğrafı silindi.')->success();
        return back();
    }

    public function destroy($id)
    {
        if( !auth()->user()->hasRole('super-admin')){
            flash('Bu işlem sadece yöneticinize ait.')->warning();
            return back();
        }
        $user = User::whereKeyNot(auth()->id());

        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }
        $user = $user->findOrFail($id);

        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }
        if($user->trashed()){
            $user->restore();
        }else
            $user->delete();

        $user->flushQueryCache();
        flash('Kullanıcı hesabı silindi.')->success();
        return redirect()->back();
    }

    /**
     * @throws \Throwable
     */
    public function destroy_force($id): \Illuminate\Http\RedirectResponse
    {
        if( !auth()->user()->hasRole('super-admin')){
            flash('Bu işlem sadece yöneticinize ait.')->warning();
            return back();
        }

        $user = User::whereKeyNot(auth()->id());

        if(auth()->user()->hasRole('super-admin')){
            $user->withTrashed();
        }
        $user = $user->findOrFail($id);
        // SUPER ADMIN PROTECTION
        if(!auth()->user()->hasRole('super-admin') && !$user->hasRole('user')){
            return abort(403);
        }

        \DB::transaction(function() use ($user) {

            $user->deleteProfilePhoto();

            $user->forceDelete();
        });

        $user->flushQueryCache();

        flash('Kullanıcı hesabı tamamen silindi.')->success();
        return redirect()->route('admin.user.index');
    }
}
