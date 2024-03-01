<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'product_name' => $this->faker->word, //ダミー商品名
            'price' => $this->faker->numberBetween(100, 10000), //100～10000範囲ダミー価格
            'stock' => $this->faker->randomDigit, //0～9のランダムダミー在庫数
            'comment' => $this->faker->sentence, //ダミー説明文
            'img_path' => 'https://picsum.photos/200/300', //200*300ランダム画像
        ];
    }
}
