<?php

namespace App\Http\Controllers;

use App\Models\Variete;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class VarieteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Variete::with('produit');

        if ($search) {
            $term = strtoupper($search);

            $query->where(function ($q) use ($term) {
                $q->whereRaw("UPPER(nom_variete) LIKE ?", ["%{$term}%"])
                  ->orWhereHas('produit', function ($q) use ($term) {
                      $q->whereRaw("UPPER(nom_produit) LIKE ?", ["%{$term}%"]);
                  });
            });
        }

        $varietes = $query->orderBy('variete_id','desc')->get();

        return view('varietes.index', compact('varietes', 'search'));
    }

    public function create()
    {
        $produits = Produit::orderBy('nom_produit')->get();
        return view('varietes.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id'          => 'required|exists:PRODUIT,produit_id',
            'nom_variete'         => 'required|string|max:255',
            'prix_vente_standard' => 'required|numeric',
        ]);

        try {
            DB::statement("BEGIN sp_ajouter_variete(?, ?, ?); END;", [
                $request->produit_id,
                $request->nom_variete,
                $request->prix_vente_standard,
            ]);

            return redirect()->route('varietes.index')
                ->with('success', 'Variété ajoutée avec succès.');

        } catch (QueryException | Oci8Exception $e) {
            if ($e->getCode() == 20005 || str_contains($e->getMessage(), 'Doublon')) {
                return back()->withInput()->withErrors(['error' => 'Une variété avec ce nom existe déjà pour ce produit.']);
            }

            if ($e->getCode() == 20007 || str_contains($e->getMessage(), 'Prix negatif')) {
                return back()->withInput()->withErrors(['error' => 'Prix négatif interdit ! Le prix doit être positif ou nul.']);
            }

            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout de la variété.']);
        }
    }

    public function edit($id)
    {
        $variete = Variete::findOrFail($id);
        $produits = Produit::orderBy('nom_produit')->get();

        return view('varietes.edit', compact('variete', 'produits'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produit_id'          => 'required|exists:PRODUIT,produit_id',
            'nom_variete'         => 'required|string|max:255',
            'prix_vente_standard' => 'required|numeric',
        ]);

        try {
            DB::statement("BEGIN sp_modifier_variete(?, ?, ?, ?); END;", [
                $id,
                $request->produit_id,
                $request->nom_variete,
                $request->prix_vente_standard,
            ]);

            return redirect()->route('varietes.index')
                ->with('success', 'Variété mise à jour avec succès.');

        } catch (QueryException | Oci8Exception $e) {
            if ($e->getCode() == 20006 || str_contains($e->getMessage(), 'Variete inexistante')) {
                return redirect()->route('varietes.index')->with('error', 'Variété inexistante.');
            }

            if ($e->getCode() == 20005 || str_contains($e->getMessage(), 'Doublon')) {
                return back()->withInput()->withErrors(['error' => 'Une variété avec ce nom existe déjà pour ce produit.']);
            }

            if ($e->getCode() == 20007 || str_contains($e->getMessage(), 'Prix negatif')) {
                return back()->withInput()->withErrors(['error' => 'Prix négatif interdit ! Le prix doit être positif ou nul.']);
            }

            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement("BEGIN sp_supprimer_variete(?); END;", [$id]);

            return redirect()->route('varietes.index')
                ->with('success', 'Variété supprimée avec succès.');

        } catch (QueryException | Oci8Exception $e) {
            if ($e->getCode() == 20008 || str_contains($e->getMessage(), 'utilisée')) {
                return back()->withErrors(['error' => 'Impossible de supprimer cette variété car elle est utilisée dans des récoltes, ventes ou pertes.']);
            }

            if ($e->getCode() == 20006 || str_contains($e->getMessage(), 'Variete inexistante')) {
                return redirect()->route('varietes.index')->with('error', 'Variété inexistante.');
            }

            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}

