<?php
namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.address', compact('addresses'));
    }

    public function create()
    {
        return view('user.address.address_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'district'     => 'required|string|max:100',
            'province'     => 'required|string|max:100',
            'zipcode'      => 'required|string|max:10',
            'is_default'   => 'sometimes|boolean',
        ]);

        // ถ้ามีการตั้งค่า is_default = true ต้อง reset ของเก่า
        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id'      => Auth::id(),
            'fullname'    => $request->full_name,
            'phone'        => $request->phone,
            'address_line' => $request->address_line,
            'district'     => $request->district,
            'province'     => $request->province,
            'zipcode'      => $request->zipcode,
            'is_default'   => $request->has('is_default') ? true : false,
        ]);

        return redirect()->back()->with('success', 'เพิ่มที่อยู่เรียบร้อยแล้ว!');
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'fullname'     => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
        ]);

        $address->update($request->all());
        return back()->with('success', 'อัปเดตที่อยู่เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->delete();

        return redirect()->back()->with('success', 'ลบที่อยู่นี้เรียบร้อยแล้ว');
    }

    public function setDefault($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // รีเซ็ต is_default ของ user ทั้งหมดก่อน
        Address::where('user_id', Auth::id())->update(['is_default' => false]);

        // ตั้งค่าใหม่
        $address->update(['is_default' => true]);

        return redirect()->back()->with('success', 'ตั้งที่อยู่นี้เป็นหลักเรียบร้อยแล้ว');
    }
}
