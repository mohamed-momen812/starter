<?php

namespace App\Http\Controllers\Api;

use App\Models\ShippingAddress;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ShippingAddressController
{
    use ApiTrait;

    public function index()
    {
        $userId = auth()->id();
        $shippingAddresses = ShippingAddress::where('user_id', $userId)->get();

        return $this->responseJsonSuccess($shippingAddresses);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $validated['user_id'] = auth()->id(); // Attach the authenticated user

        $shippingAddress = ShippingAddress::create($validated);

        return $this->responseJsonSuccess($shippingAddress, 'Shipping Address Created');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $shippingAddress = ShippingAddress::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $shippingAddress->update($validated);

        return $this->responseJsonSuccess( $shippingAddress, 'Shipping details updated successfully.',);
    }

    public function destroy($id)
    {
        $shippingAddress = ShippingAddress::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $shippingAddress->delete();

        return $this->responseJsonSuccess(null, 'Shipping Address Deleted');
    }


}
