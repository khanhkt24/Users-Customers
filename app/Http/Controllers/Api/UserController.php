<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->all();

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|max:255',
            'image'     => 'nullable|image|max:2048',

            'email'     => 'required|email|max:100',
            'password'   => 'required|string|min:8|confirmed',
            'is_active'     => [
                'nullable',
                Rule::in([0, 1])
            ],
        ]);

        try {

            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('images', $request->file('image'));
            }

            $data['password'] = Hash::make($data['password']);

            $users = User::query()->create($data);

            return response()->json($users, 201);
        } catch (\Throwable $th) {
            // Log::error('Lỗi khi tạo người dùng: ' . $th->getMessage());

            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return back()->with('success', 'Thất bại rồi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'image'     => 'nullable|image|max:2048',
            'email'     => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($user->id)
            ],
            'password'   => 'required|string|min:8|confirmed',
            'is_active'     => [
                'nullable',
                Rule::in([0, 1])
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "success",
                'data' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();


        try {
            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('images', $request->file('image'));
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Nếu không thay đổi mật khẩu, bỏ trường này
            }

            $userImage = $user->image;
            $user->update($data);



            if ($request->hasFile('image') && !empty($userImage) && Storage::exists($userImage)) {
                Storage::delete($userImage);
            }


            return response()->json([
                'status' => true,
                "message" => 'thành công',
                'data' => $data

            ], 201);
        } catch (\Throwable $th) {

            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return response()->json([
                "status" => false,
                "message" => "success",
                'data' => $validator->errors()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Xóa hình ảnh nếu có
            if (!empty($user->image) && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }

            $user->delete();

            return response()->json([
                'status' => true,
                "message" => 'Xóa thành công',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Xóa thất bại",
            ], 500);
        }
    }
}
