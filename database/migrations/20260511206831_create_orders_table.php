<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrdersTable extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('orders')) {
            return;
        }

        $this->table('orders')
            ->addColumn('user_id', 'integer')
            ->addColumn('total', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('status', 'string', [
                'limit' => 30,
                'default' => 'pending'
            ])
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id'], [
                'name' => 'idx_orders_user_id'
            ])
            ->addIndex(['status'], [
                'name' => 'idx_orders_status'
            ])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('orders')) {
            $this->table('orders')->drop()->save();
        }
    }
}
