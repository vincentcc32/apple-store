@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md space-y-10">

        <h2 class="text-2xl font-bold text-center text-gray-800">Hồ sơ người dùng</h2>

        {{-- Đổi tên --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Tên người dùng</label>
                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Cập nhật
                tên</button>
        </form>

        {{-- Đổi mật khẩu --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="current_password" class="block text-gray-700 font-semibold mb-2">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" id="current_password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700 font-semibold mb-2">Mật khẩu mới</label>
                <input type="password" name="new_password" id="new_password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('new_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-gray-700 font-semibold mb-2">Xác nhận mật khẩu
                    mới</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('new_password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">Đổi mật
                khẩu</button>
        </form>

        {{-- Xóa tài khoản --}}
        <form method="POST" action="{{ route('profile.destroy') }}"
            onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">Xóa
                tài khoản</button>
        </form>

    </div>
@endsection