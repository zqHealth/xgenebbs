<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户 ID 数组 e.g. [1, 2, 3, 4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有分类 ID 数组 e.g. [1, 2, 3, 4]
        $category_ids = Category::all()->pluck('id')->toArray();

        // Faker 实例
        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)
            ->times(50)
            ->make()
            ->each(function ($topic, $index) use ($user_ids, $category_ids, $faker) {
                // 随机分配一个用户 ID
                $topic->user_id = $faker->randomElement($user_ids);
                $topic->category_id = $faker->randomElement($category_ids);
            }
        );

        Topic::insert($topics->toArray());
    }

}

