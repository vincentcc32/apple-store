<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\registerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->has('search')) {
            $search = $request->input('search');

            $users = User::where('name', 'like', '%' . $search . '%')
                ->where('email', 'like', '%' . $search . '%')
                ->orderByDesc('id')->paginate(10);
        } else {
            $users = User::orderByDesc('id')->paginate(10);
        }
        return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(registerRequest $request)
    {
        //

        if ($request->isMethod('POST')) {

            $params = $request->except('_token');

            try {
                //code...
                DB::beginTransaction();
                User::create([
                    'name' => $params['name'],
                    'email' => $params['email'],
                    'password' => bcrypt($params['password']),
                    'role' => $params['role']
                ]);
                DB::commit();
                return redirect()->route('admin.users.index')->with('success', 'Thêm thành công!');
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return back()->with('error', 'Thêm thất bại!');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Người dùng không tồn tại!');
        }
        return view('admin.pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');
            $user = User::find($id);
            if (!$user) {
                return redirect()->route('admin.users.index')->with('error', 'Người dùng không tồn tại!');
            }

            try {
                //code...
                DB::beginTransaction();
                $user->update([
                    'name' => $params['name'],
                    'role' => $params['role'],
                ]);
                $user->save();
                DB::commit();
                return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
            } catch (\Throwable $th) {
                // throw $th;
                DB::rollBack();
                return back()->with('error', 'Cập nhật thất bại!');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'Người dùng không tồn tại!');
        }
        try {
            //code...
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
