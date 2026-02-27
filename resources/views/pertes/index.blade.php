@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Pertes</h2>
            <p class="text-gray-600 mt-1">Gestion des pertes de récoltes</p>

            @if(session('success'))
                <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <a href="{{ route('pertes.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white font-medium px-6 py-3 rounded-full flex items-center space-x-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouvelle perte</span>
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-6">
        <form method="GET" action="{{ route('pertes.index') }}" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par variété ou produit..."
                   class="w-full pl-12 pr-16 py-3 bg-gray-100 border-0 rounded-full text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition shadow-sm">
            <button type="submit" class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @if(request('search'))
                <a href="{{ route('pertes.index') }}" class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-800 text-lg">
                    &times;
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau des pertes -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date perte</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité (kg)</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pertes as $perte)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-5 text-sm text-gray-800">#{{ $perte->perte_id }}</td>
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ $perte->date_perte ? \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-800">
                            {{ $perte->variete?->produit?->nom_produit ?? 'Produit inconnu' }}
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ $perte->variete?->nom_variete ?? 'Variété inconnue' }}
                        </td>
                        <!-- Quantité perdue en rouge, pas d'effet au hover -->
                        <td class="px-8 py-5 text-lg text-red-700">
                            {{ number_format((float)$perte->quantite_kg, 2, ',', ' ') }} kg
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-700 max-w-xs truncate">
                            {{ $perte->motif_perte ?? '-' }}
                        </td>
                        <td class="px-8 py-5 text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('pertes.show', $perte->perte_id) }}" class="text-blue-700 hover:text-blue-900 transition" title="Voir les détails">
                                    👁️
                                </a>
                                <form action="{{ route('pertes.destroy', $perte->perte_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:text-red-900 transition" title="Supprimer" onclick="return confirm('Supprimer cette perte ?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-500">
                            Aucune perte enregistrée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-8 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pertes->links() }}
        </div>
    </div>
</div>
@endsection
