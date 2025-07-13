<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users (only regular users, not admins)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user'); // Only show regular users

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        if (in_array($sort, ['name', 'email', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }



    /**
     * Display the specified user (only regular users)
     */
    public function show(User $user)
    {
        // Only allow viewing regular users
        if ($user->role !== 'user') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Chỉ có thể xem thông tin người dùng thường!');
        }

        // Load user relationships for detailed view
        $user->load(['reviews' => function($query) {
            $query->with('product')->latest()->take(10);
        }]);

        // Get user statistics
        $stats = [
            'total_reviews' => $user->reviews()->count(),
            'average_rating_given' => $user->reviews()->avg('rating') ?? 0,
            'total_orders' => $user->orders()->count() ?? 0,
            'total_spent' => $user->orders()->where('status', 'delivered')->sum('total_amount') ?? 0,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user (only regular users)
     */
    public function edit(User $user)
    {
        // Only allow editing regular users
        if ($user->role !== 'user') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Chỉ có thể chỉnh sửa thông tin người dùng thường!');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Only allow updating regular users
        if ($user->role !== 'user') {
            return redirect()->back()
                ->with('error', 'Không thể chỉnh sửa tài khoản quản trị viên!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->only(['name', 'email']);

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Only allow deleting regular users
        if ($user->role !== 'user') {
            return redirect()->back()
                ->with('error', 'Không thể xóa tài khoản quản trị viên!');
        }

        // Check if user has orders before deletion
        if ($user->orders()->exists()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa khách hàng có đơn hàng. Hãy xử lý các đơn hàng trước!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Khách hàng đã được xóa thành công!');
    }
}
