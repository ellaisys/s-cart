<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
class CreateTablesShop extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::create('shop_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255)->nullable();
            $table->string('url', 100)->nullable();
            $table->string('target', 50)->nullable();
            $table->text('html')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
            $table->tinyInteger('click')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->timestamps();
        });

        Schema::create('admin_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group', 50)->nullable();
            $table->string('code', 50)->index();
            $table->string('key', 50)->unique();
            $table->string('value', 200)->nullable();
            $table->string('store_id', 200)->default(1);
            $table->tinyInteger('sort')->default(0);
            $table->string('detail', 300)->nullable();

        });

        Schema::create('admin_store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo', 255)->nullable();
            $table->tinyInteger('site_status')->default(1);
            $table->string('phone', 20)->nullable();
            $table->string('long_phone', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('time_active', 200);
            $table->string('address', 300);
            $table->string('office', 300)->nullable();
            $table->string('warehouse', 300)->nullable();
            $table->string('template', 100)->nullable();
        });

        Schema::create('admin_store_description', function (Blueprint $table) {
            $table->integer('config_id');
            $table->string('lang', 10)->index();
            $table->string('title', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->text('maintain_content')->nullable();
            $table->primary(['config_id', 'lang']);
        });

        Schema::create('shop_email_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('group', 50);
            $table->text('text');
            $table->tinyInteger('status')->default(0);
        });

        Schema::create('shop_language', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('icon', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('rtl')->nullable()->default(0)->comment('Layout RTL');
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_block_content', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('position', 100);
            $table->string('page', 200)->nullable();
            $table->string('type', 200);
            $table->text('text')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_layout_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100)->unique();
            $table->string('name', 100);
        });

        Schema::create('shop_layout_position', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100)->unique();
            $table->string('name', 100);
        });

        Schema::create('shop_layout_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100)->unique();
            $table->string('name', 100);
        });

        Schema::create('shop_link', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('url', 100);
            $table->string('target', 100);
            $table->string('group', 100);
            $table->string('module', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email', 150);
            $table->string('token', 255);
            $table->dateTime('created_at');
            $table->index('email');
        });

        Schema::create('shipping_standard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee');
            $table->integer('shipping_free');
        });

        Schema::create('shop_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('alias', 120)->unique();
            $table->string('image', 255)->nullable();
            $table->string('url', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255)->nullable();
            $table->string('alias', 120)->unique();
            $table->integer('parent')->default(0);
            $table->integer('top')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_category_description', function (Blueprint $table) {
            $table->integer('category_id');
            $table->string('lang', 10)->index();
            $table->string('name', 200)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->primary(['category_id', 'lang']);
        });

        Schema::create('shop_currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->string('symbol', 10);
            $table->float('exchange_rate');
            $table->tinyInteger('precision')->default(2);
            $table->tinyInteger('symbol_first')->default(0);
            $table->string('thousands')->default(',');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_discount', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->integer('reward')->default(2);
            $table->string('type', 10)->default('point')->comment('point - Point; percent - %');
            $table->string('data', 300)->nullable();
            $table->integer('limit')->default(1);
            $table->integer('used')->default(0);
            $table->integer('login')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('shop_discount_user', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('discount_id');
            $table->string('log', 300);
            $table->dateTime('used_at');
        });

        Schema::create('shop_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('subtotal')->nullable()->default(0);
            $table->integer('shipping')->nullable()->default(0);
            $table->integer('discount')->nullable()->default(0);
            $table->integer('payment_status')->default(1);
            $table->integer('shipping_status')->default(1);
            $table->integer('status')->default(0);
            $table->integer('tax')->nullable()->default(0);
            $table->integer('total')->nullable()->default(0);
            $table->string('currency', 10);
            $table->float('exchange_rate')->nullable();
            $table->integer('received')->nullable()->default(0);
            $table->integer('balance')->nullable()->default(0);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('address1', 100);
            $table->string('address2', 100);
            $table->string('country', 10)->default('VN');
            $table->string('company', 100)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('phone', 20);
            $table->string('email', 150);
            $table->string('comment', 300)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->string('shipping_method', 100)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('ip', 100)->nullable();
            $table->string('transaction', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('shop_order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('name', 100);
            $table->integer('price')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('total_price')->default(0);
            $table->string('sku', 50);
            $table->string('currency', 10);
            $table->float('exchange_rate')->nullable();
            $table->string('attribute', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('shop_order_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('content', 300);
            $table->integer('admin_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('order_status_id')->default(0);
            $table->dateTime('add_date');
        });

        Schema::create('shop_order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);

        });

        Schema::create('shop_order_total', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('title', 100);
            $table->string('code', 100);
            $table->integer('value')->default(0);
            $table->string('text', 200)->nullable();
            $table->integer('sort')->default(1);
            $table->timestamps();
        });

        Schema::create('shop_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255)->nullable();
            $table->string('alias', 120)->unique();
            $table->integer('status')->default(0);
        });

        Schema::create('shop_page_description', function (Blueprint $table) {
            $table->integer('page_id');
            $table->string('lang', 10)->index();
            $table->string('title', 200)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->text('content')->nullable();
            $table->primary(['page_id', 'lang']);
        });

        Schema::create('shop_payment_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);

        });

        Schema::create('shop_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku', 50)->unique();
            $table->string('image', 255)->nullable();
            $table->integer('brand_id')->nullable()->default(0)->index();
            $table->integer('vendor_id')->nullable()->default(0)->index();
            $table->integer('price')->nullable()->default(0);
            $table->integer('cost')->nullable()->nullable()->default(0);
            $table->integer('stock')->nullable()->default(0);
            $table->integer('sold')->nullable()->default(0);
            $table->tinyInteger('type')->nullable()->default(0)->index();
            $table->tinyInteger('kind')->nullable()->default(0)->comment('0:single, 1:bundle, 2:group')->index();
            $table->tinyInteger('virtual')->nullable()->default(0)->comment('0:physical, 1:download, 2:only view, 3: Service')->index();
            $table->tinyInteger('status')->default(0)->index();
            $table->tinyInteger('sort')->default(0);
            $table->integer('view')->default(0);
            $table->string('alias', 120)->unique();
            $table->dateTime('date_lastview')->nullable();
            $table->date('date_available')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_product_description', function (Blueprint $table) {
            $table->integer('product_id');
            $table->string('lang', 10)->index();
            $table->string('name', 200)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->text('content')->nullable();
            $table->primary(['product_id', 'lang']);
        });

        Schema::create('shop_product_image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255);
            $table->integer('product_id')->default(0)->index();
        });

        Schema::create('shop_product_build', function (Blueprint $table) {
            $table->integer('build_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->primary(['build_id', 'product_id']);
        });

        Schema::create('shop_product_group', function (Blueprint $table) {
            $table->integer('group_id');
            $table->integer('product_id');
            $table->primary(['group_id', 'product_id']);
        });

        Schema::create('shop_product_category', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('category_id');
            $table->primary(['product_id', 'category_id']);
        });

        Schema::create('shop_attribute_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('sort')->default(0);
            $table->string('type', 50)->comment('radio,select,checkbox');
        });

        Schema::create('shop_product_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('attribute_group_id');
            $table->integer('product_id');
            $table->tinyInteger('sort')->default(0);
            $table->index(['product_id', 'attribute_group_id']);
        });

        Schema::create('shop_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->default(0);
            $table->integer('value')->default(0);
            $table->integer('free')->default(0);
            $table->integer('status')->default(1);
        });

        Schema::create('shop_shipping_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);

        });

        Schema::create('shop_shoppingcart', function (Blueprint $table) {
            $table->string('identifier', 100);
            $table->string('instance', 100);
            $table->text('content');
            $table->timestamps();
            $table->index(['identifier', 'instance']);
        });

        Schema::create('shop_product_promotion', function (Blueprint $table) {
            $table->integer('product_id')->primary();
            $table->integer('price_promotion');
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->integer('status_promotion')->default(1);
            $table->timestamps();
        });

        Schema::create('shop_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('email', 150)->unique();
            $table->tinyInteger('sex')->default(0)->comment('0:women, 1:men');
            $table->date('birthday')->nullable();
            $table->string('password', 100);
            $table->string('postcode', 10)->nullable();
            $table->string('address1', 100)->nullable();
            $table->string('address2', 100)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('country', 10)->default('VN');
            $table->string('phone', 20);
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('group')->default(1);
            $table->timestamps();
        });

        Schema::create('shop_vendor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('alias', 120)->unique();
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('url', 100)->nullable();
            $table->tinyInteger('sort')->default(0);
        });

        Schema::create('shop_subscribe', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 150)->unique();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('shop_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10)->unique();
            $table->string('name', 100);
        });
        
        Schema::create('shop_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 200)->nullable();
            $table->string('alias', 120)->unique();
            $table->tinyInteger('sort')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('shop_news_description', function (Blueprint $table) {
            $table->integer('shop_news_id');
            $table->string('lang', 10);
            $table->string('title', 200)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->text('content')->nullable();
            $table->primary(['shop_news_id', 'lang']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_banner');
        Schema::dropIfExists('admin_config');
        Schema::dropIfExists('admin_store');
        Schema::dropIfExists('admin_store_description');
        Schema::dropIfExists('shop_email_template');
        Schema::dropIfExists('shop_language');
        Schema::dropIfExists('shop_block_content');
        Schema::dropIfExists('shop_layout_page');
        Schema::dropIfExists('shop_layout_position');
        Schema::dropIfExists('shop_layout_type');
        Schema::dropIfExists('shop_link');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('shipping_standard');
        Schema::dropIfExists('shop_api');
        Schema::dropIfExists('shop_api_process');
        Schema::dropIfExists('shop_brand');
        Schema::dropIfExists('shop_category');
        Schema::dropIfExists('shop_category_description');
        Schema::dropIfExists('shop_currency');
        Schema::dropIfExists('shop_discount');
        Schema::dropIfExists('shop_discount_user');
        Schema::dropIfExists('shop_order');
        Schema::dropIfExists('shop_order_detail');
        Schema::dropIfExists('shop_order_history');
        Schema::dropIfExists('shop_order_status');
        Schema::dropIfExists('shop_order_total');
        Schema::dropIfExists('shop_page');
        Schema::dropIfExists('shop_page_description');
        Schema::dropIfExists('shop_payment_status');
        Schema::dropIfExists('shop_product');
        Schema::dropIfExists('shop_product_description');
        Schema::dropIfExists('shop_product_image');
        Schema::dropIfExists('shop_product_build');
        Schema::dropIfExists('shop_product_attribute');
        Schema::dropIfExists('shop_attribute_group');
        Schema::dropIfExists('shop_product_group');
        Schema::dropIfExists('shop_product_category');
        Schema::dropIfExists('shop_shipping');
        Schema::dropIfExists('shop_shipping_status');
        Schema::dropIfExists('shop_shoppingcart');
        Schema::dropIfExists('shop_product_promotion');
        Schema::dropIfExists('shop_user');
        Schema::dropIfExists('shop_vendor');
        Schema::dropIfExists('shop_subscribe');
        Schema::dropIfExists('shop_country');
        Schema::dropIfExists('shop_news');
        Schema::dropIfExists('shop_news_description');
    }

}
