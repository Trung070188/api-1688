<?php

namespace App\Console\Commands;

use App\Models\Elim\Attribute;
use App\Models\Elim\AttributeValue;
use App\Models\Elim\Product;
use App\Models\Elim\ProductAttribute;
use App\Models\Elim\ProductSku;
use App\Models\Elim\ProductSkuAttribute;
use Illuminate\Console\Command;

class DeleteDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::query()->delete();
        ProductAttribute::query()->delete();
        AttributeValue::query()->delete();
        Attribute::query()->delete();
        ProductSkuAttribute::query()->delete();
        ProductSku::query()->delete();
        echo 'done';
    }
}
