<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Password Reset',
                'slug' => 'password_reset',
                'content' => '
                    <p>Dear {user_name},</p>
                    <p>You have requested to reset your password. Please click the link below to reset it:</p>
                    <p><a href="{reset_link}" target="_blank">{reset_link}</a></p>
                    <p>This link will expire in <strong>{expiration_time}</strong>.</p>
                    <p>If you did not request this, please ignore this email or contact support.</p>
                    <p>Thank you,</p>
                    <p>Your Support Team</p>
                ',
                'variables' => ['user_name', 'reset_link', 'expiration_time'],
                'created_by' => 1,
            ],
            [
                'name' => 'User Registration',
                'slug' => 'user_registration',
                'content' => '
                    <p>Dear {user_name},</p>
                    <p>Welcome to our platform! Your account has been successfully created.</p>
                    <p>Here are your login details:</p>
                    <ul>
                        <li><strong>Email:</strong> {email}</li>
                        <li><strong>Password:</strong> {password}</li>
                    </ul>
                    <p>Please keep this information secure and change your password after your first login.</p>
                    <p>If you have any questions, feel free to reach out to our support team.</p>
                    <p>Thank you for joining us!</p>
                    <p>Your Support Team</p>
                ',
                'variables' => ['user_name', 'email', 'password'],
                'created_by' => 1,
            ],


        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }

    }
}
