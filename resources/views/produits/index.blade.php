@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Produits</h2>
            <p class="text-gray-600 mt-1">Gérez vos produits agricoles</p>
            <!-- Messages flash -->
            @if(session('success'))
          <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
           {{ session('success') }}
         </div>
          @endif

          @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
             {{ session('error') }}
           </div>
            @endif
        </div>
        <a href="{{ route('produits.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white font-medium px-6 py-3 rounded-full flex items-center space-x-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Ajouter un produit</span>
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-6">
    <form method="GET" action="{{ route('produits.index') }}" class="relative">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Rechercher un produit..."
               class="w-full pl-12 pr-6 py-4 bg-gray-100 border-0 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition shadow-sm">
        <button type="submit" class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        @if(request('search'))
            <a href="{{ route('produits.index') }}" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                ✕
            </a>
        @endif
    </form>
</div>

    <!-- Tableau -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Produit</th>
                    <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variétés</th>

                    <th class="px-8 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($produits as $produit)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-5 text-sm text-gray-500">#{{ $produit->produit_id }}</td>
                        <td class="px-8 py-5 text-lg font-semibold text-gray-900">{{ $produit->nom_produit }}</td>
                        <td class="px-8 py-5">
                          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                             {{ $produit->varietes->count() }} variété{{ $produit->varietes->count() > 1 ? 's' : '' }}
                             </span>
                        </td>

                        <td class="px-8 py-5 text-right space-x-4">
                            <a href="{{ route('produits.edit', $produit->produit_id) }}"
                               class="text-gray-600 hover:text-gray-900 transition">
                                ✏️
                            </a>
                            <form action="{{ route('produits.destroy', $produit->produit_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Supprimer ce produit ?')"
                                        class="text-red-600 hover:text-red-800 transition">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                            Aucun produit enregistré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
