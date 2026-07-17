<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('payment_method')->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('discount', 10, 2)->default(0)->after('shipping');
            $table->string('payment_status')->default('unpaid')->after('status');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn([
                'coupon_code',
                'discount',
                'payment_status',
                'transaction_id',
                'paid_at',
            ]);
        });
    }
};
