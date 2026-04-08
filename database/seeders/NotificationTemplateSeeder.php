<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
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
                'content' => 'Hi {user_name}, reset your password using this link: {reset_link} (expires in {expiration_time} mins).',
                'variables' => ['user_name','token', 'reset_link', 'expiration_time'],
                'created_by' => 1,
            ],
            [
                'name' => 'User Registration',
                'slug' => 'user_registration',
                'content' => 'Hi {user_name}, your account has been successfully created! Use your email ({email}) and password ({password}) to log in.',
                'variables' => ['user_name', 'email', 'password'],
                'created_by' => 1,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
