<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    // 未登录用户限制： 使用中间件Auth验证用户身份，游客只能看到用户主页
    public function __construct(){
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function show(User $user){
        return view('users.show', compact('user'));
    }

    public function edit(User $user){
        // 用户授权策略: App\Policies\UserPolicy
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user, ImageUploadHandler $uploader){
        // 用户授权策略: App\Policies\UserPolicy
        $this->authorize('update', $user);
        $data = $request->all();

        if($request->avatar){
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }
}
