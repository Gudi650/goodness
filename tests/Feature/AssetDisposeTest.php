<?php

use App\Models\BankTransactions;
use App\Models\Company;
use App\Models\CreateAssets;
use App\Models\Role;
use App\Models\User;
use App\Models\VirtualAccounts;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();
    });
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('status')->default('Active');
        $table->timestamps();
    });
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->unsignedBigInteger('role_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->timestamps();
    });
    Schema::create('virtual_accounts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('account_name')->nullable();
        $table->decimal('balance', 15, 2)->default(0);
        $table->string('status')->default('active');
        $table->timestamps();
    });
    Schema::create('bank_transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->decimal('balance_after', 15, 2)->default(0);
        $table->decimal('affecting_balance', 15, 2)->default(0);
        $table->string('transaction_type')->nullable();
        $table->timestamps();
    });
    Schema::create('create_assets', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->string('name');
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('category_id')->nullable();
        $table->string('type')->default('Fixed Asset');
        $table->string('term')->default('Long-term');
        $table->decimal('original_value', 20, 2)->default(0);
        $table->decimal('current_value', 20, 2)->default(0);
        $table->decimal('depreciation_value', 10, 2)->nullable();
        $table->date('acquired')->nullable();
        $table->string('status')->default('Active');
        $table->date('disposal_date')->nullable();
        $table->decimal('disposal_proceeds', 20, 2)->nullable();
        $table->unsignedBigInteger('disposal_bank_id')->nullable();
        $table->text('disposal_notes')->nullable();
        $table->timestamps();
    });
});

afterEach(function () {
    foreach (['create_assets', 'bank_transactions', 'virtual_accounts', 'users', 'companies', 'roles'] as $table) {
        Schema::dropIfExists($table);
    }
});

test('disposing an asset with proceeds credits bank and clears book value', function () {
    $role = Role::query()->create(['name' => 'Admin']);
    $company = Company::query()->create(['name' => 'Goodness Mining', 'status' => 'Active']);
    $user = User::query()->create([
        'name' => 'Admin',
        'email' => 'admin'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);
    $bank = VirtualAccounts::query()->create([
        'company_id' => $company->id,
        'account_name' => 'Main',
        'balance' => 1000,
        'status' => 'active',
    ]);
    $asset = CreateAssets::query()->create([
        'code' => 'FA-1',
        'name' => 'Truck',
        'company_id' => $company->id,
        'type' => 'Fixed Asset',
        'term' => 'Long-term',
        'original_value' => 5000,
        'current_value' => 3000,
        'status' => 'Active',
        'acquired' => now()->subYear()->toDateString(),
    ]);

    $response = $this->actingAs($user)->post(route('far.assets.dispose', $asset), [
        'disposal_date' => now()->toDateString(),
        'disposal_method' => 'Sold',
        'disposal_proceeds' => 2500,
        'disposal_bank_id' => $bank->id,
        'disposal_notes' => 'Sold truck',
    ]);

    $response->assertRedirect(route('far'));
    $asset->refresh();
    $bank->refresh();

    expect($asset->status)->toBe('Sold')
        ->and((float) $asset->current_value)->toBe(0.0)
        ->and((float) $asset->disposal_proceeds)->toBe(2500.0)
        ->and((float) $bank->balance)->toBe(3500.0)
        ->and(BankTransactions::query()->where('transaction_type', 'asset_disposal')->count())->toBe(1);
});
