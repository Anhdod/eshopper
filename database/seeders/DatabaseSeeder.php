<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $categories = collect([
            'Women Dresses',
            'Men Shirts',
            'Kids Fashion',
            'Accessories',
        ])->mapWithKeys(fn ($name) => [
            $name => Category::firstOrCreate(['name' => $name], ['parent_id' => null]),
        ]);

        $menus = [
            ['name' => 'Home', 'route' => 'home', 'order' => 1],
            ['name' => 'Shop', 'route' => 'shop', 'order' => 2],
            ['name' => 'Cart', 'route' => 'cart', 'order' => 3],
            ['name' => 'Checkout', 'route' => 'checkout', 'order' => 4],
            ['name' => 'Contact', 'route' => 'contact', 'order' => 5],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['route' => $menu['route']],
                [
                    'name' => $menu['name'],
                    'type' => 'link',
                    'parent_id' => null,
                    'order' => $menu['order'],
                ]
            );
        }

        $products = [
            [
                'name' => 'Colorful Stylish Shirt',
                'category' => 'Women Dresses',
                'description' => 'Lightweight fashion shirt for daily outfits.',
                'price' => 123.00,
                'original_price' => 150.00,
                'image' => 'product-1.jpg',
                'color' => ['black', 'white', 'red'],
                'sizes' => ['S', 'M', 'L'],
                'stock' => 25,
            ],
            [
                'name' => 'Modern Casual Top',
                'category' => 'Women Dresses',
                'description' => 'Soft casual top with a clean modern fit.',
                'price' => 99.00,
                'original_price' => 129.00,
                'image' => 'product-2.jpg',
                'color' => ['blue', 'white'],
                'sizes' => ['M', 'L', 'XL'],
                'stock' => 30,
            ],
            [
                'name' => 'Classic Men Shirt',
                'category' => 'Men Shirts',
                'description' => 'Classic shirt for work and weekend wear.',
                'price' => 115.00,
                'original_price' => 140.00,
                'image' => 'product-3.jpg',
                'color' => ['white', 'gray'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 18,
            ],
            [
                'name' => 'Elegant Summer Dress',
                'category' => 'Women Dresses',
                'description' => 'Elegant dress with breathable fabric.',
                'price' => 149.00,
                'original_price' => 189.00,
                'image' => 'product-4.jpg',
                'color' => ['red', 'pink'],
                'sizes' => ['S', 'M', 'L'],
                'stock' => 20,
            ],
            [
                'name' => 'Kids Comfortable Outfit',
                'category' => 'Kids Fashion',
                'description' => 'Comfortable everyday outfit for kids.',
                'price' => 79.00,
                'original_price' => 99.00,
                'image' => 'product-5.jpg',
                'color' => ['yellow', 'blue'],
                'sizes' => ['XS', 'S', 'M'],
                'stock' => 35,
            ],
            [
                'name' => 'Urban Fashion Hoodie',
                'category' => 'Men Shirts',
                'description' => 'Streetwear-inspired hoodie for cool days.',
                'price' => 135.00,
                'original_price' => 165.00,
                'image' => 'product-6.jpg',
                'color' => ['black', 'gray'],
                'sizes' => ['M', 'L', 'XL'],
                'stock' => 22,
            ],
            [
                'name' => 'Premium Fashion Bag',
                'category' => 'Accessories',
                'description' => 'Stylish bag for daily shopping and travel.',
                'price' => 89.00,
                'original_price' => 120.00,
                'image' => 'product-7.jpg',
                'color' => ['brown', 'black'],
                'sizes' => ['One Size'],
                'stock' => 15,
            ],
            [
                'name' => 'Minimal Accessories Set',
                'category' => 'Accessories',
                'description' => 'Simple accessory set to complete your outfit.',
                'price' => 59.00,
                'original_price' => 79.00,
                'image' => 'product-8.jpg',
                'color' => ['silver', 'gold'],
                'sizes' => ['One Size'],
                'stock' => 40,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'original_price' => $product['original_price'],
                    'image' => $product['image'],
                    'color' => $product['color'],
                    'sizes' => $product['sizes'],
                    'stock' => $product['stock'],
                ]
            );
        }
    }
}
