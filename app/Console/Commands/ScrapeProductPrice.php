<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class ScrapeProductPrice extends Command
{
    protected $signature = 'scrape:product-price {product}';
    protected $description = 'Scrape product price from specific websites';

    public function handle()
    {
        $productName = $this->argument('product');
        $this->info("Searching for product:  $productName");

        $websites = [
            // 'https://www.ebay.com',
            // 'https://www.amazon.com',
           'https://alkhunaizan.sa'
        ];

        foreach ($websites as $website) {
            $this->info("Scraping $website...");

            try {
                $this->scrapeWebsite($website, $productName);
            } catch (\Exception $e) {
                $this->error("Error scraping $website: " . $e->getMessage());
            }
        }

        return 'Scraping completed!';
    }

    private function scrapeWebsite($website, $productName)
    {
        $client = new Client();

        // Adjust the search URL for each website
        $searchUrl = $this->buildSearchUrl($website, $productName);
        $this->info("Searching URL: $searchUrl");

        $crawler = $client->request('GET', $searchUrl);

        // Update the selector based on the website's structure
        $productDetails = $crawler->filter('.product-item-details')->each(function ($node) {
            return [
                'name' => $node->filter('.product-item-name')->text(),
                'price' => $node->filter('.price-final_price')->text(),
                'link' => $node->filter('a')->attr('href'),
            ];
        });

        if (!empty($productDetails)) {
            foreach ($productDetails as $product) {
                $this->info("Product: " . $product['name']);
                $this->info("Price: " . $product['price']);
                $this->info("Link: " . $product['link']);
            }
        } else {
            $this->info("No products found on $website.");
        }
    }

    private function buildSearchUrl($website, $productName)
    {
        // Customize search URLs based on the website's format
        $query = urlencode($productName);

        if (strpos($website, 'amazon') !== false) {
            return "$website/s?k=$query"; // Amazon's search pattern
        } elseif (strpos($website, 'ebay') !== false) {
            return "$website/sch/i.html?_nkw=$query"; // eBay's search pattern
        } elseif (strpos($website, 'alkhunaizan.sa') !== false) {
            return "$website/catalogsearch/result/?q=$query"; // alkhunaizan's search pattern
        } else {
            return "$website/search?q=$query"; // Default search pattern
        }
    }
}
