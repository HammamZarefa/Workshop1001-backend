<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Product;
class ProductPriceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    //mutator
    public function test_price_mutator()
    {
        $product = new Product();

        $product->price = 1.23; 

        
        $this->assertEquals(123, $product->getAttributes()['price']);
    }

    //accessor
    public function test_price_accessor()
    {
        $product = new Product();

        
        $product->setRawAttributes(['price' => 123]);

        $this->assertEquals(1.23, $product->price);
    }

    //update price
    public function test_price_updates_correctly()
    {
        $product = new Product();

        $product->price = 10.50;
        $this->assertEquals(1050, $product->getAttributes()['price']);

        $product->price = 20.75;
        $this->assertEquals(2075, $product->getAttributes()['price']);
    }

    

}
