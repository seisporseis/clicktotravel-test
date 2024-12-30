<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch products from an external API and saved them to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Trayendo productos desde una API externa...');

        $response = Http::get('https://fakestoreapi.com/products');

        if($response->successful()) {
            $products = $response->json();

            foreach($products as $productData) {
                $category = Category::first();

                Product::create([
                    'name' => $productData['title'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => rand(10,100),
                    'category_id' => $category->id,
                    'image' => $productData['image'],
                ]);

                $this->info('El producto "' . $productData['title'] . '" se agregó a la base de datos.');
            }

            $this->info('Producto agregado correctamente');

        } else {
            $this->error('No se pudo agregar el producto, algo ha fallado...');
        }
    }
}
