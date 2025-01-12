<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Price;

class ScrapeProductPrice extends Command
{
    protected $signature = 'scrape:price {url}';
    protected $description = 'Scrape the price of a product from a URL';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = $this->argument('url');
        
        // Create a new Goutte client
        $client = new Client();

        // Request the page
        $crawler = $client->request('GET', $url);

        // Extract the price (update this based on the HTML structure of the target website)
        $priceNode = $crawler->filter(".a-price-whole"); // Example selector

        if ($priceNode->count() > 0) {
            $price = $priceNode->text();
            $convertedValue = str_replace(',', '.', $price);
        } else {
            $this->error('Price not found.');
            return;
        }
           
        // Store the price in the database
        Price::create([
            'product_url' => $url,
            'price' => (double)$convertedValue,
        ]);

        $this->info("Price for {$url} is {$convertedValue}");
    }
}
