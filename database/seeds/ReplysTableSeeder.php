<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户 ID 数组 e.e. [1, 2, 3, 4]
        $user_ids = User::all()->pluck('id')->toArray();

        $topic_ids = Topic::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
            ->times(50)
            ->make()
            ->each(function ($reply, $index) use($user_ids, $topic_ids, $faker){
                // 从用户 ID 数组中随机取出一个
                $reply->user_id = $faker->randomElement($user_ids);
                $reply->topic_id = $faker->randomElement($topic_ids);
            });

        Reply::insert($replys->toArray());
    }

}

