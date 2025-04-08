<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketing\Jobs\UpdateCreateSearchTerm as UpdateCreateSearchTermJob;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\ProductResource;
use Webkul\Core\Rules\Slug;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class ProductController extends APIController
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Product listings.
     */
    public function index(): JsonResource
    {
        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $products = $this->productRepository
            ->setSearchEngine($searchEngine ?? 'database')
            ->getAll(array_merge(request()->query(), [
                'channel_id'           => core()->getCurrentChannel()->id,
                'status'               => 1,
                'visible_individually' => 1,
            ]));

        if (! empty(request()->query('query'))) {
            /**
             * Update or create search term only if
             * there is only one filter that is query param
             */
            if (count(request()->except(['mode', 'sort', 'limit'])) == 1) {
                UpdateCreateSearchTermJob::dispatch([
                    'term'       => request()->query('query'),
                    'results'    => $products->total(),
                    'channel_id' => core()->getCurrentChannel()->id,
                    'locale'     => app()->getLocale(),
                ]);
            }
        }

        return ProductResource::collection($products);
    }

    /**
     * Related product listings.
     *
     * @param  int  $id
     */
    public function relatedProducts($id): JsonResource
    {
        $product = $this->productRepository->findOrFail($id);

        $relatedProducts = $product->related_products()
            ->take(core()->getConfigData('catalog.products.product_view_page.no_of_related_products'))
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Up-sell product listings.
     *
     * @param  int  $id
     */
    public function upSellProducts($id): JsonResource
    {
        $product = $this->productRepository->findOrFail($id);

        $upSellProducts = $product->up_sells()
            ->take(core()->getConfigData('catalog.products.product_view_page.no_of_up_sells_products'))
            ->get();

        return ProductResource::collection($upSellProducts);
    }

    public function getAllProducts(): JsonResponse
    {
        $products = $this->productRepository->all();

        return new JsonResponse([
            'data' => ProductResource::collection($products),
        ], 200);
    }

    public function getOneProduct($id): JsonResponse
    {
        try {
            $product = $this->productRepository->findOrFail($id);

            return new JsonResponse([
                'data' => new ProductResource($product),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Product not found or an error occurred.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function deleteOneProduct($id): JsonResponse
    {
        try {
            $product = $this->productRepository->findOrFail($id);
            $product->delete();
            return new JsonResponse([
                'data' => new ProductResource($product),
                'message' => 'Product deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return new JsonResponse([
                'error' => 'Product not found.',
            ], 404);
        }
    }


    public function addProduct(): JsonResponse
    {
        try {
            $this->validate(request(), [
                'type'                => 'required',
                'attribute_family_id' => 'required',
                'sku'                 => ['required', 'unique:products,sku', new Slug],
                'super_attributes'    => 'array|min:1',
                'super_attributes.*'  => 'array|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exception
            $errors = $e->validator->errors();
            return new JsonResponse([
                'error' => 'Validation failed.',
                'messages' => $errors,
            ], 422);
        }

        $product = $this->productRepository->findOneByField('sku', request()->sku);
        if ($product) {
            return new JsonResponse([
                'error' => 'Product with this SKU already exists.',
            ], 422);
        }
        $product = $this->productRepository->create(request()->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));



        return new JsonResponse([
            'data' => new ProductResource($product),
        ]);
    }


    public function updateProduct(Request $request, $id): JsonResponse
    {
        $product = $this->productRepository->update(request()->all(), $id);
        if ($product) {
            return new JsonResponse([
                'data' => new ProductResource($product),
            ], 200);
        } else {
            return new JsonResponse([
                'error' => 'Product not found or an error occurred.',
            ], 404);
        }
    }
}
