<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Webkul\Shop\Http\Requests\Customer\LoginRequest;
use Webkul\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends APIController
{
    /**
     * Login Customer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (! auth()->guard('customer')->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => trans('shop::app.customers.login-form.invalid-credentials'),
            ], Response::HTTP_FORBIDDEN);
        }

        if (! auth()->guard('customer')->user()->status) {
            auth()->guard('customer')->logout();

            return response()->json([
                'message' => trans('shop::app.customers.login-form.not-activated'),
            ], Response::HTTP_FORBIDDEN);
        }

        if (! auth()->guard('customer')->user()->is_verified) {
            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', $request->get('email'), 1));

            auth()->guard('customer')->logout();

            return response()->json([
                'message' => trans('shop::app.customers.login-form.verify-first'),
            ], Response::HTTP_FORBIDDEN);
        }

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', auth()->guard()->user());

        return response()->json([]);
    }
    public function index() {
        $customers = Customer::all();
        return response()->json($customers);
    }
    public function show($id) {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }
    public function store (Request $request) {
        $validateData = $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name'  => 'required|string|max:191',
            'email'      => 'required|email|unique:customers,email',
            'password'   => 'required|string|min:6',
            //Optionals fields
            'gender'                 => 'nullable|string|in:male,female,other',
            'date_of_birth'          => 'nullable|date',
            'phone'                  => 'nullable|string|max:9',
            'image'                  => 'nullable|image|max:2048',
            'customer_group_id'      => 'nullable|integer',
            'channel_id'             => 'nullable|integer',

        ]);
        if (isset($validateData['password'])) {
            $validateData['password'] = bcrypt($validateData['password']);
        }
        if ($request->hasFile('image')) {
            $validateData['image'] = $request->file('image')->store('customers', 'public');
        }
        $customer = Customer::create($validateData);
        return response()->json($customer, 201);
    }
    public function update(Request $request, $id) {
        $customer = Customer::findOrFail($id);
        $validateData = $request->validate([
            'first_name' => 'sometimes|required|string|max:191',
            'last_name'  => 'sometimes|required|string|max:191',
            'email'      => 'sometimes|required|email|unique:customers,email,' . $customer->id,
            'password'   => 'sometimes|required|string|min:6',
            //Optionals fields
            'gender'                 => 'nullable|string|in:male,female,other',
            'date_of_birth'          => 'nullable|date',
            'phone'                  => 'nullable|string|max:9',
            'image'                  => 'nullable|image|max:2048',
            'customer_group_id'      => 'nullable|integer',
            'channel_id'             => 'nullable|integer',
        ]);
        if (isset($validateData['password'])) {
            $validateData['password'] = bcrypt($validateData['password']);
        }
        if ($request->hasFile('image')) {
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }
    
            $validateData['image'] = $request->file('image')->store('customers', 'public');
        }
        
        $customer->update($validateData);
        
        // Recargar el modelo desde la base de datos
        $customer = $customer->fresh();
        
        return response()->json($customer, 200);
    }
    public function destroy($id) {
        $customer = Customer::findOrFail($id);
        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }
        $customer->delete();
        return response()->json(null, 204);
    }
}
