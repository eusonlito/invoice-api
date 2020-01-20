<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Base extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        $this->drop();
        $this->tables();
        $this->keys();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::create('backup', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('action');
            $table->unsignedBigInteger('related_id')->index();
            $table->string('related_table')->index();
            $table->text('content');

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('client', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->default('');
            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('web')->default('');
            $table->string('tax_number')->default('');
            $table->string('type')->default('company');

            $table->string('contact_name')->default('');
            $table->string('contact_surname')->default('');
            $table->string('contact_phone')->default('');
            $table->string('contact_email')->default('');

            $table->text('comment');

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('client_address', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->default('');
            $table->string('address')->default('');
            $table->string('city')->default('');
            $table->string('state')->default('');
            $table->string('postal_code')->default('');
            $table->string('country')->default('');
            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('tax_number')->default('');

            $table->text('comment');

            $table->boolean('billing')->default(1);
            $table->boolean('shipping')->default(1);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('company', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('address');
            $table->string('postal_code');
            $table->string('city');
            $table->string('state');
            $table->string('tax_number');
            $table->string('email');
            $table->string('phone');

            $this->timestamps($table);

            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('configuration', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('key')->unique();
            $table->string('value')->default('');
            $table->string('description')->default('');

            $table->boolean('public')->default(0);

            $this->timestamps($table);
        });

        Schema::create('country', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('iso');
            $table->string('name');
            $table->string('native');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);
        });

        Schema::create('discount', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('type');
            $table->unsignedDecimal('value', 10, 2)->default(0);

            $table->text('description');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('fail', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('action')->default('');
            $table->string('value')->default('');
            $table->string('ip')->default('');

            $this->timestamps($table);
        });

        Schema::create('failed_job', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');

            $table->datetime('failed_at')->useCurrent();
        });

        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('number')->default('');

            $table->string('company_name')->default('');
            $table->string('company_address')->default('');
            $table->string('company_city')->default('');
            $table->string('company_state')->default('');
            $table->string('company_postal_code')->default('');
            $table->string('company_country')->default('');
            $table->string('company_tax_number')->default('');
            $table->string('company_phone')->default('');
            $table->string('company_email')->default('');

            $table->string('billing_name')->default('');
            $table->string('billing_address')->default('');
            $table->string('billing_city')->default('');
            $table->string('billing_state')->default('');
            $table->string('billing_postal_code')->default('');
            $table->string('billing_country')->default('');
            $table->string('billing_tax_number')->default('');

            $table->string('shipping_name')->default('');
            $table->string('shipping_address')->default('');
            $table->string('shipping_city')->default('');
            $table->string('shipping_state')->default('');
            $table->string('shipping_postal_code')->default('');
            $table->string('shipping_country')->default('');

            $table->unsignedTinyInteger('quantity')->default(0);

            $table->unsignedDecimal('percent_discount', 5, 2)->default(0);
            $table->unsignedDecimal('percent_tax', 5, 2)->default(0);

            $table->unsignedDecimal('amount_subtotal', 10, 2)->default(0);
            $table->unsignedDecimal('amount_discount', 10, 2)->default(0);
            $table->unsignedDecimal('amount_tax', 10, 2)->default(0);
            $table->unsignedDecimal('amount_shipping_subtotal', 10, 2)->default(0);
            $table->unsignedDecimal('amount_shipping_tax_percent', 5, 2)->default(0);
            $table->unsignedDecimal('amount_shipping_tax_amount', 10, 2)->default(0);
            $table->unsignedDecimal('amount_shipping', 10, 2)->default(0);
            $table->unsignedDecimal('amount_total', 10, 2)->default(0);
            $table->unsignedDecimal('amount_paid', 10, 2)->default(0);
            $table->unsignedDecimal('amount_due', 10, 2)->default(0);

            $table->date('date_at');
            $table->date('paid_at')->nullable();
            $table->date('required_at')->nullable();
            $table->date('recurring_at')->nullable();

            $table->text('comment_private');
            $table->text('comment_public');

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_address_billing_id');
            $table->unsignedBigInteger('client_address_shipping_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('invoice_recurring_id')->nullable();
            $table->unsignedBigInteger('invoice_serie_id');
            $table->unsignedBigInteger('invoice_status_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('invoice_file', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('file');

            $table->boolean('main')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('invoice_item', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedTinyInteger('line')->default(0);

            $table->string('reference')->default('');
            $table->string('description')->default('');

            $table->unsignedDecimal('quantity', 10, 2)->default(0);

            $table->unsignedTinyInteger('percent_discount')->default(0);
            $table->unsignedDecimal('percent_tax', 5, 2)->default(0);

            $table->unsignedDecimal('amount_price', 10, 2)->default(0);
            $table->unsignedDecimal('amount_discount', 10, 2)->default(0);
            $table->unsignedDecimal('amount_tax', 10, 2)->default(0);
            $table->unsignedDecimal('amount_subtotal', 10, 2)->default(0);
            $table->unsignedDecimal('amount_total', 10, 2)->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('invoice_recurring', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->default('');
            $table->string('every')->default('');

            $table->boolean('enabled')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('invoice_serie', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->default('');
            $table->string('number_prefix')->default('');
            $table->string('number_fill')->default('');
            $table->string('number_next')->default('');
            $table->string('css')->default('');
            $table->string('certificate_file')->default('');
            $table->string('certificate_password')->default('');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('invoice_status', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->default('');

            $table->unsignedTinyInteger('order')->default(0);

            $table->boolean('paid')->default(0);
            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('ip_lock', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ip')->default('');

            $table->datetime('end_at')->nullable();

            $this->timestamps($table);
        });

        Schema::create('language', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('iso')->unique();
            $table->string('locale')->unique();

            $table->unsignedTinyInteger('order')->default(0);

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);
        });

        Schema::create('log', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('table');
            $table->string('action');

            $this->timestamps($table);

            $table->unsignedBigInteger('from_user_id');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('client_address_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('invoice_file_id')->nullable();
            $table->unsignedBigInteger('invoice_recurring_id')->nullable();
            $table->unsignedBigInteger('invoice_serie_id')->nullable();
            $table->unsignedBigInteger('invoice_status_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('notification', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code');
            $table->string('title');
            $table->string('status');

            $table->datetime('readed_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->text('description');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('reference')->default('');
            $table->string('name');

            $table->unsignedDecimal('price', 10, 2)->default(0);

            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('shipping', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');

            $table->unsignedDecimal('subtotal', 10, 2)->default(0);
            $table->unsignedDecimal('tax_percent', 5, 2)->default(0);
            $table->unsignedDecimal('tax_amount', 5, 2)->default(0);
            $table->unsignedDecimal('value', 10, 2)->default(0);

            $table->text('description');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('tax', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->unsignedDecimal('value', 5, 2)->default(0);
            $table->text('description');

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(1);

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('user')->unique();
            $table->string('password')->nullable();

            $table->boolean('enabled')->default(0);

            $table->datetime('confirmed_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('language_id');
        });

        Schema::create('user_password_reset', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('hash')->index();
            $table->string('ip');

            $table->datetime('finished_at')->nullable();
            $table->datetime('canceled_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user_session', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('action');
            $table->string('ip');

            $this->timestamps($table);

            $table->unsignedBigInteger('social_provider_id')->nullable();
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user_session_fail', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('user');
            $table->string('ip');

            $this->timestamps($table);
        });
    }

    /**
     * Set the foreign keys.
     *
     * @return void
     */
    protected function keys()
    {
        Schema::table('backup', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('SET NULL');
        });

        Schema::table('client', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('discount_id')
                ->references('id')->on('discount')
                ->onDelete('SET NULL');

            $table->foreign('payment_id')
                ->references('id')->on('payment')
                ->onDelete('SET NULL');

            $table->foreign('shipping_id')
                ->references('id')->on('shipping')
                ->onDelete('SET NULL');

            $table->foreign('tax_id')
                ->references('id')->on('tax')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('client_address', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')->on('client')
                ->onDelete('CASCADE');

            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('company', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')->on('country')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('discount', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')->on('client')
                ->onDelete('CASCADE');

            $table->foreign('client_address_billing_id')
                ->references('id')->on('client_address')
                ->onDelete('CASCADE');

            $table->foreign('client_address_shipping_id')
                ->references('id')->on('client_address')
                ->onDelete('SET NULL');

            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('discount_id')
                ->references('id')->on('discount')
                ->onDelete('SET NULL');

            $table->foreign('invoice_recurring_id')
                ->references('id')->on('invoice_recurring')
                ->onDelete('SET NULL');

            $table->foreign('invoice_serie_id')
                ->references('id')->on('invoice_serie')
                ->onDelete('CASCADE');

            $table->foreign('invoice_status_id')
                ->references('id')->on('invoice_status')
                ->onDelete('CASCADE');

            $table->foreign('payment_id')
                ->references('id')->on('payment')
                ->onDelete('SET NULL');

            $table->foreign('shipping_id')
                ->references('id')->on('shipping')
                ->onDelete('SET NULL');

            $table->foreign('tax_id')
                ->references('id')->on('tax')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice_file', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('invoice_id')
                ->references('id')->on('invoice')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice_item', function (Blueprint $table) {
            $table->foreign('invoice_id')
                ->references('id')->on('invoice')
                ->onDelete('CASCADE');

            $table->foreign('product_id')
                ->references('id')->on('product')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice_recurring', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice_serie', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('invoice_status', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('log', function (Blueprint $table) {
            $table->foreign('from_user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');

            $table->foreign('client_id')
                ->references('id')->on('client')
                ->onDelete('SET NULL');

            $table->foreign('client_address_id')
                ->references('id')->on('client_address')
                ->onDelete('SET NULL');

            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('SET NULL');

            $table->foreign('discount_id')
                ->references('id')->on('discount')
                ->onDelete('SET NULL');

            $table->foreign('invoice_id')
                ->references('id')->on('invoice')
                ->onDelete('SET NULL');

            $table->foreign('invoice_file_id')
                ->references('id')->on('invoice_file')
                ->onDelete('SET NULL');

            $table->foreign('invoice_recurring_id')
                ->references('id')->on('invoice_recurring')
                ->onDelete('SET NULL');

            $table->foreign('invoice_serie_id')
                ->references('id')->on('invoice_serie')
                ->onDelete('SET NULL');

            $table->foreign('invoice_status_id')
                ->references('id')->on('invoice_status')
                ->onDelete('SET NULL');

            $table->foreign('payment_id')
                ->references('id')->on('payment')
                ->onDelete('SET NULL');

            $table->foreign('product_id')
                ->references('id')->on('product')
                ->onDelete('SET NULL');

            $table->foreign('shipping_id')
                ->references('id')->on('shipping')
                ->onDelete('SET NULL');

            $table->foreign('tax_id')
                ->references('id')->on('tax')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('SET NULL');
        });

        Schema::table('notification', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('payment', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('product', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('shipping', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('tax', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('user', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('SET NULL');

            $table->foreign('language_id')
                ->references('id')->on('language')
                ->onDelete('CASCADE');
        });

        Schema::table('user_password_reset', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });

        Schema::table('user_session', function (Blueprint $table) {
            $table->foreign('social_provider_id')
                ->references('id')->on('social_provider')
                ->onDelete('SET NULL');

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('CASCADE');
        });
    }

    /**
     * @param \Illuminate\Database\Schema\Blueprint $table
     *
     * @return void
     */
    protected function timestamps(Blueprint $table)
    {
        $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    }

    /**
     * @return void
     */
    protected function drop()
    {
        foreach ($this->getTables() as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * @return array
     */
    protected function getTables(): array
    {
        preg_match_all("/Schema::create\('([^']+)/", file_get_contents(__FILE__), $tables);

        return $tables[1] ?? [];
    }
}
