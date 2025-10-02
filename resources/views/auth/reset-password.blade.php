@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-20 bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">🔐 Đặt lại mật khẩu</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">📧 Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="email" autofocus>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">🔑 Mật khẩu mới</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="new-password">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">✅ Xác nhận mật
                    khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="new-password">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                    Đặt lại mật khẩu
                </button>
            </div>
        </form>
    </div>
@endsection