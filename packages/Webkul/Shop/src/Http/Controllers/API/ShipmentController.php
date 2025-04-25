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
        try {
            $order = $orderRepository->findOrFail($orderId);

            if (! $order->canShip()) {
                return new JsonResponse([
                    'error' => 'Shipment could not be created.',
                ], 404);
            }

            $inventory = InventorySource::where('code', request()->input('shipment.source'))->first();

            $this->validate(request(), [
                'shipment.source'    => 'required',
                'shipment.items.*.*' => 'required|numeric|min:0',
            ]);

            $data = request()->only(['shipment', 'carrier_name']);

            if (! $this->shipmentController->isInventoryValidate($data)) {
                return new JsonResponse([
                    'error' => 'Shipment could not be created because of inventory.',
                ], 404);
            }

            $this->shipmentRepository->create(array_merge($data, [
                'order_id' => $orderId,
                'inventory_source_id' => $inventory
            ]), $order->state);

            return new JsonResponse([
                'message' => 'Shipment created successfully.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Shipment not found or an error occurred.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $shipment = $this->shipmentRepository->findOrFail($id);
            
            $allowedFields = ['carrier_title', 'track_number', 'status', 'email_sent'];

            $data = $request->only($allowedFields);

            if (empty($data)) {
                return response()->json([
                    'message' => 'No valid fields provided for update.',
                ], 422);
            }

            $shipment->update($data);
            
            return new JsonResponse([
                'message' => 'Shipment updated successfully.',
                'data' => $shipment,
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Shipment not found or an error occurred.',
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}