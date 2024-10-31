<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->paginate(5);
        return view("users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("users.create");
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
            'address'=> 'nullable',
            'phoneNumber'=>'nulladble|max:10',
            'gender'     => [
                'nullable',
                Rule::in([0, 1])
            ],
        ]);


        try {

            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('images', $request->file('image'));
            }

            $data['password'] = Hash::make($data['password']);

            User::query()->create($data);

            return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công');
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
    public function show(User $user) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|max:255',
            'image'     => 'nullable|image|max:2048',
            'email'     => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($user->id)
            ],
            'is_active'     => [
                'nullable',
                Rule::in([0, 1])
            ],
            'address'=> 'required',
            'phoneNumber'=>['required','max:10',Rule::unique('users')->ignore($user->id)],
            'gender'     => [
                'nullable',
                Rule::in([0, 1])
            ],
        ]);

        try {
            $data['is_active'] ??= 0;
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

            return back()->with('success', 'Sửa thành công');

        } catch (\Throwable $th) {

            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }

            return back()->with('success', 'Sửa ko thành công');
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
                $backupPath = 'backup/images/' . basename($user->image);
                Storage::copy($user->image, $backupPath); // Sao chép hình ảnh
                $user->image = $backupPath;
            }

            // Xóa hình ảnh gốc
            if (!empty($user->image) && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }

            $user->delete();

            return redirect()->route('users.index')->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with("success", "Xóa không thành công");
        }
    }

    public function forceDelete(User $user)
    {
        try {
            // Xóa hình ảnh nếu có
            if (!empty($user->image) && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }

            $user->forceDelete();

            return redirect()->route('users.index')->with('success', 'Xóa thành công');

        } catch (\Throwable $th) {

            return back()->with("success", "Xóa không thành công");
            
        }
    }


    public function restore($id)
    {

        try {

            $user = User::withTrashed()->findOrFail($id);

            // Khôi phục người dùng
            $user->restore();

            // Khôi phục hình ảnh từ backup nếu có
            $backupImagePath = 'backup/images/' . basename($user->image);
            if (Storage::exists($backupImagePath)) {
                $restoredImagePath = 'images/' . basename($user->image);
                // Khôi phục hình ảnh về thư mục images
                Storage::copy($backupImagePath, $restoredImagePath);
                $user->image = $restoredImagePath; // Cập nhật đường dẫn hình ảnh
                $user->save(); // Lưu người dùng
            }

            return redirect()->route('users.index')->with('success', 'Khôi phục thành công');
        } catch (\Throwable $th) {
            return back()->with("success", "Xóa không thành công");
        }

    }



    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return redirect()->route('users.index'); // Trả về trang cũ
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate(5);

        return view('users.index', compact('users'));
    }

    public function filter(Request $request)
    {
        $filterBy = $request->input('filter_by');

        // Thực hiện truy vấn lọc
        $users = User::query();

        if ($filterBy) {
            // Lọc theo loại tài khoản (ví dụ: active/inactive)
            if ($filterBy === 'active') {
                $users->where('is_active', 1);
            } elseif ($filterBy === 'inactive') {
                $users->where('is_active', 0);
            }elseif ($filterBy === 'male') {
                $users->where('gender', 1);
            }elseif ($filterBy === 'female') {
                $users->where('gender', 0);
            }
        }

        $users = $users->paginate(5);

        return view('users.index', compact('users', 'filterBy'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $request->input('is_active');
        $user->save();

        return back()->with('alooo','ok');
    }

    public function recycle(){
        $users = User::onlyTrashed()->paginate(5);
        return view('users.recycle', compact('users'));
    }
}

