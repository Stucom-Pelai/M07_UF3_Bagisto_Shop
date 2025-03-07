<?php

namespace Webkul\CartRule\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WelcomeCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $existingRule = DB::table('cart_rules')->where('name', 'Welcome Discount')->first();
        if ($existingRule) {
            DB::table('cart_rule_coupons')->where('cart_rule_id', $existingRule->id)->delete();
            DB::table('cart_rule_channels')->where('cart_rule_id', $existingRule->id)->delete();
            DB::table('cart_rule_customer_groups')->where('cart_rule_id', $existingRule->id)->delete();
            DB::table('cart_rules')->where('id', $existingRule->id)->delete();
        }
        $cartRuleId = DB::table('cart_rules')->insertGetId([
            'name' => 'Welcome Discount',
            'description' => 'Get 30% off on your first purchase',
            'starts_from' => $now,
            'ends_till' => null,
            'status' => 1,
            'coupon_type' => 1,
            // 'coupon_code' => 'WELCOME!',
            'use_auto_generation' => 0,
            'usage_per_customer' => 1000,
            'uses_per_coupon' => 0, 
            'times_used' => 0,
            'condition_type' => 1,
            'conditions' => json_encode([
                'all_conditions' => [],
                'any_conditions' => []
            ]),
            'end_other_rules' => 0,
            'uses_attribute_conditions' => 0,
            'action_type' => 'by_percent',  
            'discount_amount' => 30.00,
            'discount_quantity' => 9999,
            'discount_step' => '1',
            'apply_to_shipping' => 0,
            'free_shipping' => 0,
            'sort_order' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        // Crear el cupón asociado
        DB::table('cart_rule_coupons')->insert([
            'cart_rule_id' => $cartRuleId,
            'code' => 'WELCOME!',
            'type' => 0, 
            'usage_per_customer' => 1000,
            'is_primary' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $channels = DB::table('channels')->pluck('id');
foreach ($channels as $channelId) {
    DB::table('cart_rule_channels')->insert([
        'cart_rule_id' => $cartRuleId,
        'channel_id' => $channelId,
    ]);
}

// Asignar a todos los grupos de clientes
$customerGroups = DB::table('customer_groups')->pluck('id');
foreach ($customerGroups as $customerGroupId) {
    DB::table('cart_rule_customer_groups')->insert([
        'cart_rule_id' => $cartRuleId,
        'customer_group_id' => $customerGroupId,
    ]);
}

    }
}
