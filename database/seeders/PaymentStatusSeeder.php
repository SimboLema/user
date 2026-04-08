<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentStatuses = [
            [
                'name' => 'Not Paid',
                'color' => '#FF6347', // Red for unpaid
            ],
            [
                'name' => 'Partially',
                'color' => '#FFA500', // Orange for partially paid
            ],
            [
                'name' => 'Paid',
                'color' => '#008000', // Green for paid
            ],
            [
                'name' => 'Overdue', 
                'color' => '#FF0000', // Tomato for overdue
            ],
        ];

        // Insert statuses into the database
        foreach ($paymentStatuses as $index => $status) {
            PaymentStatus::updateOrCreate(
                ['id' => ($index + 1)],
                [
                    'name' => $status['name'],
                    'color' => $status['color'],
                    'created_by' => 1, // Replace with actual user ID as needed
                    'created_at' => now(),
                    'updated_at' => now(),
                    'archive' => 0,
                ]
            );
        }
    }
}
