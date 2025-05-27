<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::take(20)->get();

        foreach ($users as $user) {
            Customer::factory()
            ->count(5)
            ->hasInvoices(1)
            ->create();

            Customer::factory()
                ->count(10)
                ->hasInvoices(5)
                ->create();

            Customer::factory()
                ->count(10)
                ->hasInvoices(3)
                ->create();

            Customer::factory()
                ->count(2)
                ->create();

        }

    }
}
