<?php

namespace Webkul\Product\Listeners;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;
use Webkul\Admin\Http\Resources\WishlistItemResource;
use Webkul\Admin\Mail\Customer\DropPriceNotification;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;
use Webkul\Notification\Models\Notification;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Jobs\ElasticSearch\DeleteIndex as DeleteElasticSearchIndexJob;
use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex as UpdateCreateElasticSearchIndexJob;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;
use Webkul\Product\Jobs\UpdateCreatePriceIndex as UpdateCreatePriceIndexJob;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Controllers\Customer\Account\WishlistController;

use function PHPSTORM_META\map;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository,
        protected FlatIndexer $flatIndexer
    ) {
    }

    /**
     * Update or create product indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        $this->flatIndexer->refresh($product);

        $productIds = $this->getAllRelatedProductIds($product);

        UpdateCreateElasticSearchIndexJob::dispatch($productIds);
    }

    /**
     * Update or create product indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {

        $this->flatIndexer->refresh($product);

        $productIds = $this->getAllRelatedProductIds($product);
        $customerEmails = [];

        Bus::chain([
            new UpdateCreateInventoryIndexJob($productIds),
            new UpdateCreatePriceIndexJob($productIds),
            new UpdateCreateElasticSearchIndexJob($productIds),
        ])->dispatch();

        $beforePrice = session("beforePrice");

        if ($product['price'] < $beforePrice) {
            $customers = Customer::whereIn('id', Wishlist::where('product_id', $product['id'])->pluck('customer_id'))->get();


            $customerEmails = $customers->map(fn($customer) => $customer['email'])->toArray();

            Mail::to($customerEmails)->send(new DropPriceNotification(
                $product
            ));
        }
    }

    /**
     * Delete product indices
     *
     * @param  int  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        if (core()->getConfigData('catalog.products.search.engine') != 'elastic') {
            return;
        }

        $product = $this->productRepository->find($productId);

        if (!$product) {
            return;
        }

        $productIds = $this->getAllRelatedProductIds($product);

        DeleteElasticSearchIndexJob::dispatch($productIds);
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProductIds($product)
    {
        $productIds = [$product->id];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $productIds[] = $product->parent_id;
            }

            $productIds = array_merge(
                $productIds,
                $this->getParentBundleProductIds($product),
                $this->getParentGroupProductIds($product)
            );
        } elseif ($product->type == 'configurable') {
            $productIds = [
                ...$product->variants->pluck('id')->toArray(),
                ...$productIds,
            ];
        }

        return $productIds;
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentBundleProductIds($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $productIds = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $productIds[] = $bundleOptionProduct->bundle_option->product_id;
        }

        return $productIds;
    }

    /**
     * Returns parents group product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentGroupProductIds($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        return $groupedOptionProducts->pluck('product_id')->toArray();
    }
}
