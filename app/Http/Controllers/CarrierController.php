<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarrierController extends Controller
{
    public function index(): View
    {
        $carriers = Carrier::latest()->paginate(10);
        return view('carriers.index', compact('carriers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:carriers,code'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'transport_mode' => ['required', 'in:sea,air,land,multimodal'],
        ]);

        Carrier::create($validated);

        return redirect()->route('carriers.index')->with('success', 'Carrier ekspedisi baru berhasil ditambahkan.');
    }
}
