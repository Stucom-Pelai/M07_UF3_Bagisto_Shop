<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

// Removed unnecessary import

class ShipmentController extends APIController
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ShipmentRepository $shipmentRepository,
        protected \Webkul\Admin\Http\Controllers\Sales\ShipmentController $shipmentController
    ) {}

    /**
     * Shipment listings.
     */
    public function index(): JsonResponse
    {
        $shipments = $this->shipmentRepository->all();

        if ($shipments->isEmpty()) {
            return new JsonResponse([
                'message' => 'No shipments found.',
            ], 404);
        }

        return new JsonResponse([
            'data' => $shipments,
        ], 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $shipment = $this->shipmentRepository->findOrFail($id);

            return new JsonResponse([
                'data' => $shipment,
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Shipment not found or an error occurred.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $shipment = $this->shipmentRepository->findOrFail($id);
            $shipment->delete();
            return new JsonResponse([
                'message' => 'Shipment deleted successfully.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return new JsonResponse([
                'error' => 'Shipment not found.',
            ], 404);
        }
    }

    public function store(int $orderId, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->findOrFail($orderId);

        if (! $order->canShip()) {
            return new JsonResponse([
                'error' => 'Shipment could not be created.',
            ], 404);
        }

        $inventory = InventorySource::where('code', request()->input('shipment.source'))->first();

        // $this->validate(request(), [
        //     'shipment.source'    => 'required',
        //     'shipment.items.*.*' => 'required|numeric|min:0',
        // ]);

        $data = request()->only(['shipment', 'carrier_name']);

        // if (! $this->shipmentController->isInventoryValidate($inventory)) {
        //     return new JsonResponse([
        //         'error' => 'Shipment could not be created because of inventory.',
        //     ], 404);
        // }

        $this->shipmentRepository->create(array_merge($data, [
            'order_id' => $orderId,
            'inventory_source_id' => $inventory->id,
            'inventory_source_name' => $inventory->name,
        ]));

        return new JsonResponse([
            'message' => 'Shipment created successfully.',
            'data' => $data,
        ]);
    }

    // public function store(Request $request): JsonResponse
    // {
    //    $validatedData = $request->validate([
    //         'status' => 'nullable|string',
    //         'total_qty' => 'required|integer|min:1',
    //         'total_weight' => 'required|numeric|min:0',
    //         'carrier_code' => 'nullable|string',
    //         'carrier_title' => 'nullable|string',
    //         'track_number' => 'nullable|string',
    //         'email_sent' => 'required|boolean',
    //         'customer_id' => 'required|integer|exists:customers,id',
    //         'customer_type' => 'required|string',
    //         'order_id' => 'required|integer|exists:orders,id',
    //         'order_address_id' => 'required|integer|exists:order_addresses,id',
    //         'inventory_source_id' => 'required|integer|exists:inventory_sources,id',
    //         'inventory_source_name' => 'required|string'
    //     ]);

    //     // order validation
    //     $order = $this->shipmentRepository->getOrder($validatedData['order_id']);

    //     if (!$order) {
    //         return new JsonResponse([
    //             'error' => 'Order not found.',
    //         ], 404);
    //     }

    //     $shipment = $this->shipmentRepository->create($validatedData);

    //     return new JsonResponse([
    //         'data' => $shipment,
    //         'message' => 'Shipment created successfully.',
    //     ]);
    // }

    // public function update(Request $request, $id): JsonResponse
    // {
    //     $product = $this->productRepository->update(request()->all(), $id);
    //     if ($product) {
    //         return new JsonResponse([
    //             'data' => new ProductResource($product),
    //         ], 200);
    //     } else {
    //         return new JsonResponse([
    //             'error' => 'Product not found or an error occurred.',
    //         ], 404);
    //     }
    // }
}