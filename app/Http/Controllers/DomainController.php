<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Domain::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'activo':
                    $query->active();
                    break;
                case 'expirado':
                    $query->expired();
                    break;
                case 'por_vencer':
                    $query->expiringSoon();
                    break;
            }
        }

        $domains = $query->orderBy('expiration_date', 'asc')->paginate(12);

        // Calculate statistics
        $stats = [
            'total' => Domain::count(),
            'active' => Domain::active()->count(),
            'expiring' => Domain::expiringSoon()->count(),
            'expired' => Domain::expired()->count(),
        ];

        return view('domains.index', compact('domains', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('domains.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255|unique:domains',
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date|after:registration_date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:activo,expirado,pendiente,suspendido',
            'price' => 'required|numeric|min:0',
            'hosting_info' => 'nullable|string',
            'dns_servers' => 'nullable|string',
            'notes' => 'nullable|string',
            'plugins' => 'nullable|string',
            'licenses' => 'nullable|string',
            'maintenance_status' => 'required|in:activo,inactivo',
        ]);

        // Find or create user by name
        $user = \App\Models\User::firstOrCreate(
            ['name' => $validated['client_name']],
            [
                'email' => strtolower(str_replace(' ', '', $validated['client_name'])) . '@cliente.local',
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
            ]
        );

        $validated['user_id'] = $user->id;
        $validated['auto_renew'] = $request->has('auto_renew');
        unset($validated['client_name']);

        Domain::create($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Dominio registrado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Domain $domain)
    {
        return view('domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Domain $domain)
    {
        return view('domains.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255|unique:domains,domain_name,' . $domain->id,
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date|after:registration_date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:activo,expirado,pendiente,suspendido',
            'price' => 'required|numeric|min:0',
            'hosting_info' => 'nullable|string',
            'dns_servers' => 'nullable|string',
            'notes' => 'nullable|string',
            'plugins' => 'nullable|string',
            'licenses' => 'nullable|string',
            'maintenance_status' => 'required|in:activo,inactivo',
        ]);

        // Find or create user by name
        $user = \App\Models\User::firstOrCreate(
            ['name' => $validated['client_name']],
            [
                'email' => strtolower(str_replace(' ', '', $validated['client_name'])) . '@cliente.local',
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
            ]
        );

        $validated['user_id'] = $user->id;
        $validated['auto_renew'] = $request->has('auto_renew');
        unset($validated['client_name']);

        $domain->update($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Dominio actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Dominio eliminado exitosamente');
    }
}
