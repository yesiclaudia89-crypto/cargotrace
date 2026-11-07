<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('carrier_id')->constrained('carriers');
            $table->foreignId('origin_id')->constrained('locations');
            $table->foreignId('destination_id')->constrained('locations');
            
            // Cargo details
            $table->string('cargo_type')->default('General Cargo');
            $table->decimal('total_weight', 10, 2)->default(0); // in kg
            $table->decimal('total_volume', 10, 2)->nullable(); // in m3
            
            // Lifecycle status (Slide 6)
            $table->enum('status', [
                'CREATED',
                'BOOKED',
                'PICKED_UP',
                'IN_TRANSIT',
                'DELIVERED',
                'CANCELLED',
            ])->default('CREATED');
            
            // Payment gateway support
            $table->enum('payment_status', ['UNPAID', 'PENDING', 'PAID', 'FAILED'])->default('UNPAID');
            $table->decimal('freight_cost', 14, 2)->default(0); // Freight charges in IDR
            $table->string('payment_reference')->nullable();
            
            // ETA, schedules & delay rules (Slide 8)
            $table->dateTime('expected_departure');
            $table->dateTime('expected_arrival');
            $table->dateTime('estimated_arrival');
            $table->integer('delay_hours')->default(0);
            $table->boolean('is_delayed')->default(false);
            $table->enum('delay_severity', ['NONE', 'LOW', 'MEDIUM', 'HIGH'])->default('NONE');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
