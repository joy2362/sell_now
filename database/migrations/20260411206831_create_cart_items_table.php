<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCartItemsTable extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('cart_items')) {
            return;
        }

        $this->table('cart_items')
            ->addColumn('cart_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('quantity', 'integer', ['default' => 1])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addIndex(['cart_id'], [
                'name' => 'idx_cart_items_cart_id'
            ])
            ->addIndex(['product_id'], [
                'name' => 'idx_cart_items_product_id'
            ])
            ->addIndex(['cart_id', 'product_id'], [
                'unique' => true,
                'name' => 'idx_cart_items_unique'
            ])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('cart_items')) {
            $this->table('cart_items')->drop()->save();
        }
    }
}
