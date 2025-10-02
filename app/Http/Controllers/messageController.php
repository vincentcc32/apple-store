<?php

namespace App\Http\Controllers;

use App\Events\MessageBroadcast;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

class messageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $groupedMessages = Message::get()->groupBy(function ($msg) {
            $ids = [$msg->user_from_id, $msg->user_to_id];
            sort($ids); // Sắp xếp để luôn có thứ tự cố định
            return implode('-', $ids); // Tạo key: "1-2"
        });

        $firstChatKey = $groupedMessages->keys()->first();
        $authUserId = Auth::id();
        // dd($groupedMessages);

        return view("admin.pages.message.index", compact('groupedMessages', 'firstChatKey', 'authUserId'));
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

        $params = $request->except('_token');

        if (!isset($params['user_from_id']) || !isset($params['message']) || $params['message'] === '' || $params['user_from_id'] === null) {
            return response()->json([
                "status" => "error",
                "message" => "Vui lòng nhập đầy đủ thông tin!"
            ], 400);
        }

        try {
            //code...
            DB::beginTransaction();
            Message::create([
                'user_from_id' => $params['user_from_id'],
                'user_to_id' => $params['user_to_id'] ?? 1,
                'message' => $params['message'],
            ]);
            DB::commit();
            broadcast(new MessageBroadcast($params['user_from_id'], $params['user_to_id'] ?? 1, $params['message']));
            return response()->json([
                "status" => "success",
                "message" => "Gửi tin nhắn thành công!"
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => "Gửi tin nhắn thất bại!"
            ], 500);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
        $groupedMessages = Message::get()->groupBy(function ($msg) {
            $ids = [$msg->user_from_id, $msg->user_to_id];
            sort($ids); // Sắp xếp để luôn có thứ tự cố định
            return implode('-', $ids); // Tạo key: "1-2"
        });
        return response()->json([
            "status" => "success",
            "data" => $groupedMessages
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
