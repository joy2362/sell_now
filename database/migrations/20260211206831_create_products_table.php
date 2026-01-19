<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('products')) {
            return;
        }

        $this->table('products')
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('stock', 'integer', ['default' => 0])
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['name'], [
                'name' => 'idx_products_name'
            ])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('products')) {
            $this->table('products')->drop()->save();
        }
    }
}
