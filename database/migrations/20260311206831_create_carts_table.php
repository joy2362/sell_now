<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCartsTable extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('carts')) {
            return;
        }

        $this->table('carts')
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addIndex(['user_id'], [
                'name' => 'idx_carts_user_id'
            ])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable('carts')) {
            $this->table('carts')->drop()->save();
        }
    }
}
