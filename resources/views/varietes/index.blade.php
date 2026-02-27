@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Variétés</h2>
            <p class="text-gray-600 mt-1">Gérez vos variétés de produits agricoles</p>

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

        <a href="{{ route('varietes.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white font-medium px-6 py-3 rounded-full flex items-center space-x-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Ajouter une variété</span>
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-6">
        <form method="GET" action="{{ route('varietes.index') }}" class="relative">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Rechercher une variété..."
                   class="w-full pl-12 pr-16 py-4 bg-gray-100 border-0 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition shadow-sm">
            <button type="submit" class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @if(request('search'))
                <a href="{{ route('varietes.index') }}" class="absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-800 text-2xl">
                    &times;
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau des variétés -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom de la variété</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix standard</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($varietes as $variete)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-5 text-sm text-gray-500">#{{ $variete->variete_id }}</td>
                        <td class="px-8 py-5 text-lg font-semibold text-gray-900">{{ $variete->nom_variete }}</td>
                        <td class="px-8 py-5 text-sm text-gray-700">
                            {{ optional($variete->produit)->nom_produit ?? 'Aucun produit' }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($variete->prix_vente_standard, 2, ',', ' ') }}
                            </div>
                            <div class="text-sm text-gray-500">FCFA</div>
                        </td>
                        <td class="px-8 py-5 space-x-4">
                            <a href="{{ route('varietes.edit', $variete->variete_id) }}" class="text-amber-600 hover:text-amber-900" title="Modifier">
                                ✏️
                            </a>
                            <form action="{{ route('varietes.destroy', $variete->variete_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Supprimer cette variété ?')">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                            Aucune variété enregistrée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
