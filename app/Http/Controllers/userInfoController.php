<?php

namespace App\Http\Controllers;

use App\Models\UserInFo;
use Illuminate\Http\Request;

class userInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $params = $request->except('_token');

        // Kiểm tra xem có bản ghi với user_id hay không
        $userInfo = UserInFo::where('user_id', $id)->first();

        if ($userInfo) {
            // Nếu đã có bản ghi, chỉ cập nhật các trường khác (trừ 'user_id')
            $userInfo->update([
                'PhoneNumber' => $params['phone'],
                'Address' => $params['address'],
                'DistrictCode' => $params['provinces'],
                'WardCode' => $params['ward'],
            ]);
        } else {
            // Nếu không có bản ghi, tạo mới với tất cả các trường (bao gồm 'user_id')
            UserInFo::create([
                'user_id' => $id,
                'PhoneNumber' => $params['phone'],
                'Address' => $params['address'],
                'DistrictCode' => $params['provinces'],
                'WardCode' => $params['ward'],
            ]);
        }
        return back()->with('success', 'Cập nhật thành công!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
