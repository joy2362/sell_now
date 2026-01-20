<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{
    // Define foreign key constant
    private const USER_FOREIGN_KEY = 'fk_products_user_id';

    public function up(): void
    {
        if ($this->hasTable('products')) {
            return;
        }

        $this->table('products')
            ->addColumn('user_id', 'biginteger', ['null' => false, 'signed' => false])
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('slug', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('image_path', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('file_path', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['title'], ['name' => 'idx_products_title'])
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                ['delete' => 'CASCADE', 'update' => 'NO_ACTION', 'constraint' => self::USER_FOREIGN_KEY]
            )
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('products')) {
            $table = $this->table('products');
            if ($table->hasForeignKey('user_id')) {
                $table->dropForeignKey('user_id')->save();
            }
            $table->drop()->save();
        }
    }
}
