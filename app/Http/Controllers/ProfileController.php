<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->has('name')) {
            $request->user()->name = $request->name;
            $request->user()->save();
        }

        if ($request->has('current_password') && $request->has('new_password') && $request->has('new_password_confirmation')) {
            $request->validate(
                [
                    'new_password' => 'required|min:8|confirmed', // Kiểm tra độ dài và xác nhận mật khẩu
                    'new_password_confirmation' => 'required|min:8', // Kiểm tra mật khẩu xác nhận có đủ điều kiện
                ],
                [
                    // Thông báo lỗi cho 'new_password'
                    'new_password.required' => 'Mật khẩu mới là bắt buộc.',
                    'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                    'new_password.confirmed' => 'Mật khẩu mới và mật khẩu xác nhận không khớp.',

                    // Thông báo lỗi cho 'new_password_confirmation'
                    'new_password_confirmation.required' => 'Bạn phải xác nhận mật khẩu mới.',
                    'new_password_confirmation.min' => 'Mật khẩu xác nhận phải có ít nhất 8 ký tự.',
                ]
            );

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Mật khẩu hiện tại không đúng!');
            }

            $user->password = Hash::make($request->new_password);
            // $user->save();
        }

        return Redirect::route('profile.edit')->with('success', 'Cập nhật thành công');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
