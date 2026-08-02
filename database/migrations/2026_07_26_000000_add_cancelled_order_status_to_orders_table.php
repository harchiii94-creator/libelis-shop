<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            DB::statement(<<<'SQL'
CREATE TABLE "orders_new" (
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "total_price" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "buyer_name" varchar not null,
  "buyer_phone" varchar not null,
  "buyer_email" varchar not null,
  "city" varchar not null,
  "address" text not null,
  "payment_method" varchar check ("payment_method" in ('cod', 'transfer')) not null default 'cod',
  "payment_status" varchar check ("payment_status" in ('pending', 'paid', 'failed')) not null default 'pending',
  "payment_due_date" datetime,
  "bank_name" varchar,
  "bank_account_number" varchar,
  "bank_account_holder" varchar,
  "order_status" varchar check ("order_status" in ('pending_payment', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')) not null default 'pending_payment',
  "courier_name" varchar,
  "courier_service" varchar,
  "courier_tracking_number" varchar,
  foreign key("user_id") references "users"("id") on delete cascade
);
SQL
            );

            DB::statement('INSERT INTO "orders_new" SELECT id, user_id, total_price, created_at, updated_at, buyer_name, buyer_phone, buyer_email, city, address, payment_method, payment_status, payment_due_date, bank_name, bank_account_number, bank_account_holder, order_status, courier_name, courier_service, courier_tracking_number FROM "orders"');
            DB::statement('DROP TABLE "orders"');
            DB::statement('ALTER TABLE "orders_new" RENAME TO "orders"');
            DB::statement('PRAGMA foreign_keys = ON');

            return;
        }

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `order_status` ENUM('pending_payment','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending_payment'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TYPE order_status ADD VALUE IF NOT EXISTS 'cancelled'");
            return;
        }

        throw new \RuntimeException('Unsupported database driver: ' . $driver);
    }

    public function down(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement(<<<'SQL'
CREATE TABLE "orders_old" (
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "total_price" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "buyer_name" varchar not null,
  "buyer_phone" varchar not null,
  "buyer_email" varchar not null,
  "city" varchar not null,
  "address" text not null,
  "payment_method" varchar check ("payment_method" in ('cod', 'transfer')) not null default 'cod',
  "payment_status" varchar check ("payment_status" in ('pending', 'paid', 'failed')) not null default 'pending',
  "payment_due_date" datetime,
  "bank_name" varchar,
  "bank_account_number" varchar,
  "bank_account_holder" varchar,
  "order_status" varchar check ("order_status" in ('pending_payment', 'confirmed', 'processing', 'shipped', 'delivered')) not null default 'pending_payment',
  "courier_name" varchar,
  "courier_service" varchar,
  "courier_tracking_number" varchar,
  foreign key("user_id") references "users"("id") on delete cascade
);
SQL
            );
            DB::statement('INSERT INTO "orders_old" SELECT id, user_id, total_price, created_at, updated_at, buyer_name, buyer_phone, buyer_email, city, address, payment_method, payment_status, payment_due_date, bank_name, bank_account_number, bank_account_holder, order_status, courier_name, courier_service, courier_tracking_number FROM "orders" WHERE order_status != "cancelled"');
            DB::statement('DROP TABLE "orders"');
            DB::statement('ALTER TABLE "orders_old" RENAME TO "orders"');
            DB::statement('PRAGMA foreign_keys = ON');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `order_status` ENUM('pending_payment','confirmed','processing','shipped','delivered') NOT NULL DEFAULT 'pending_payment'");
            return;
        }

        if ($driver === 'pgsql') {
            throw new \RuntimeException('Cannot safely remove enum value from PostgreSQL order_status type.');
        }

        throw new \RuntimeException('Unsupported database driver: ' . $driver);
    }
};
