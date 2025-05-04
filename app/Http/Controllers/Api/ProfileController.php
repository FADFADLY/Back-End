<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponse;

    public function viewProfile()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }
        return $this->successResponse(new ProfileResource($user), 'تم عرض البيانات بنجاح');

    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }

       try{
           $request->validate([
               'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
           ],
               [
                   'avatar.required' => 'الصورة مطلوبة',
                   'avatar.image' => 'يجب أن تكون الصورة صورة',
                   'avatar.mimes' => 'يجب أن تكون الصورة من نوع jpeg, png, jpg, gif',
                   'avatar.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
               ]);
       }catch (\Exception $e){
           return $this->validationErrorResponse($e,['avatar']);
           }

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $avatarPath]);

        return $this->successResponse(new ProfileResource($user), 'تم تحديث الصورة بنجاح');
    }

    public function updateName(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }
        try{
            $request->validate([
                'name' => 'required|string|max:255',
            ],
                [
                    'name.required' => 'الاسم مطلوب',
                ]);
        }catch (\Exception $e){
            return $this->validationErrorResponse($e,['name']);
        }
        $user->update(['name' => $request->name,]);
        return $this->successResponse(new ProfileResource($user), 'تم تحديث البيانات بنجاح');
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }
        try{
            $request->validate([
                'email' => 'required|email|unique:users,email,'.$user->id,
            ],
                [
                    'email.required' => 'البريد الإلكتروني مطلوب',
                    'email.email' => 'البريد الإلكتروني غير صالح',
                    'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
                ]);
        }catch (\Exception $e){
            return $this->validationErrorResponse($e,['email']);
        }
        $user->update(['email' => $request->email,]);
        return $this->successResponse(new ProfileResource($user), 'تم تحديث البيانات بنجاح');
    }

    public function updateBio(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }
        try{
            $request->validate([
                'bio' => 'required|string|max:255',
            ],
                [
                    'bio.required' => 'الحالة مطلوبة',
                ]);
        }catch (\Exception $e){
            return $this->validationErrorResponse($e,['bio']);
        }
        $user->update(['bio' => $request->bio,]);
        return $this->successResponse(new ProfileResource($user), 'تم تحديث الحالة بنجاح');
    }

    public function userPosts()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse([],'خطأ في عرض البيانات', 404);
        }
        $posts = $user->posts();
        if ($posts->isEmpty()) {
            return $this->errorResponse([],'لا توجد منشورات', 404);
        }
        return $this->successResponse(PostResource::collection($posts), 'تم عرض البيانات بنجاح');
    }




}
