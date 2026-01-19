<?php

// database/seeders/CustomerServiceChatSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerServiceChatSeeder extends Seeder
{
    public function run(): void
    {
        $chats = [
            [
                'id' => 1,
                'sender_id' => 3,
                'receiver_id' => 1,
                'message' => 'Apakah stok masih ada?',
                'message_type' => 'TEXT',
                'sent_at' => '2025-12-15 22:36:22',
                'is_read' => null,
            ],
            [
                'id' => 2,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'Stok masih tersedia',
                'message_type' => 'TEXT',
                'sent_at' => '2025-12-15 22:36:22',
                'is_read' => null,
            ],
            [
                'id' => 3,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'test',
                'message_type' => 'text',
                'sent_at' => '2026-01-12 07:10:07',
                'is_read' => 0,
            ],
            [
                'id' => 4,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'test',
                'message_type' => 'text',
                'sent_at' => '2026-01-12 07:10:08',
                'is_read' => 0,
            ],
            [
                'id' => 5,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'test',
                'message_type' => 'text',
                'sent_at' => '2026-01-13 06:31:20',
                'is_read' => 0,
            ],
            [
                'id' => 6,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'Halo! Ada yang bisa kami bantu?',
                'message_type' => 'text',
                'sent_at' => '2026-01-13 06:34:35',
                'is_read' => 0,
            ],
            [
                'id' => 7,
                'sender_id' => 1,
                'receiver_id' => 3,
                'message' => 'test',
                'message_type' => 'text',
                'sent_at' => '2026-01-14 07:47:38',
                'is_read' => 0,
            ],
            [
                'id' => 8,
                'sender_id' => 11,
                'receiver_id' => 5,
                'message' => 'Apakah stok masih ada?',
                'message_type' => 'TEXT',
                'sent_at' => '2025-12-15 22:36:22',
                'is_read' => null,
            ],
            [
                'id' => 9,
                'sender_id' => 1,
                'receiver_id' => 11,
                'message' => 'ya masih ada',
                'message_type' => 'text',
                'sent_at' => '2026-01-16 00:21:42',
                'is_read' => 0,
            ],
        ];

        foreach ($chats as $chat) {
            DB::table('customer_service_chats')->insert(array_merge($chat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}