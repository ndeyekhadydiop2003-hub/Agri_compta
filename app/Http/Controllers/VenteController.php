<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Recolte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Throwable;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class VenteController extends Controller
{
    /**
     * Affiche la liste des ventes avec recherche
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Vente::with(['recolte.variete.produit']);

        if ($search) {
            $term = strtoupper($search);
            $query->where(function ($q) use ($term) {
                $q->whereHas('recolte.variete', fn($q) => $q->whereRaw("UPPER(nom_variete) LIKE ?", ["%{$term}%"]))
                  ->orWhereHas('recolte.variete.produit', fn($q) => $q->whereRaw("UPPER(nom_produit) LIKE ?", ["%{$term}%"]));
            });
        }

        $ventes = $query->orderBy('date_vente', 'desc')->get();

        // Calcul du chiffre d'affaires total
        $totalCA = Vente::sum(DB::raw('quantite_vendue_kg * prix_unitaire_kg'));

        return view('ventes.index', compact('ventes', 'search', 'totalCA'));
    }

    /**
     * Formulaire de création d'une vente
     */
    public function create()
    {
        $recoltes = Recolte::with(['variete.produit'])
            ->whereRaw("quantite_kg > NVL((SELECT SUM(quantite_vendue_kg) FROM VENTE WHERE recolte_id = RECOLTE.recolte_id), 0)")
            ->orderBy('date_recolte', 'desc')
            ->get();

        return view('ventes.create', compact('recoltes'));
    }

    /**
     * Enregistre une nouvelle vente
     */


public function store(Request $request)
{
    $request->validate([
        'recolte_id' => 'required|exists:RECOLTE,RECOLTE_ID',
        'date_vente' => 'required|date',
        'quantite_vendue_kg' => 'required|numeric|gt:0',
        'prix_unitaire_kg' => 'required|numeric|gte:0',
    ]);

    try {
        Vente::create($request->only([
            'recolte_id',
            'date_vente',
            'quantite_vendue_kg',
            'prix_unitaire_kg'
        ]));

        return redirect()->route('ventes.index')
                         ->with('success', 'Vente enregistrée avec succès.');

    } catch (Oci8Exception $e) {
        $error = $this->oracleMessage($e->getMessage());
        return back()->withInput()->withErrors(['error' => $error]);
    } catch (\Throwable $e) { // attrape tout le reste
        return back()->withInput()->withErrors(['error' => 'Erreur inattendue : ' . $e->getMessage()]);
    }
}

public function update(Request $request, Vente $vente)
{
    $request->validate([
        'recolte_id' => 'required|exists:RECOLTE,RECOLTE_ID',
        'date_vente' => 'required|date',
        'quantite_vendue_kg' => 'required|numeric|gt:0',
        'prix_unitaire_kg' => 'required|numeric|gte:0',
    ]);

    try {
        $vente->update($request->only([
            'recolte_id',
            'date_vente',
            'quantite_vendue_kg',
            'prix_unitaire_kg'
        ]));

        return redirect()->route('ventes.index')
                         ->with('success', 'Vente modifiée avec succès.');

    } catch (Oci8Exception $e) {
        $error = $this->oracleMessage($e->getMessage());
        return back()->withInput()->withErrors(['error' => $error]);
    } catch (\Throwable $e) {
        return back()->withInput()->withErrors(['error' => 'Erreur inattendue : ' . $e->getMessage()]);
    }
}




    /**
     * Formulaire d'édition d'une vente
     */
    public function edit(Vente $vente)
    {
        $recoltes = Recolte::with(['variete.produit'])
            ->orderBy('date_recolte', 'desc')
            ->get();

        return view('ventes.edit', compact('vente', 'recoltes'));
    }



    /**
     * Supprime une vente
     */
    public function destroy(Vente $vente)
    {
        try {
            $vente->delete();
            return redirect()->route('ventes.index')
                             ->with('success', 'Vente supprimée avec succès.');
        } catch (Throwable $e) {
            return redirect()->route('ventes.index')
                             ->with('error', 'Impossible de supprimer cette vente : ' . $e->getMessage());
        }
    }

    /**
     * Extraire le message précis d'un trigger Oracle
     */
    private function extractTriggerMessage(string $message): string
    {
        // Regex pour capturer le texte après ORA-XXXXX:
        if (preg_match("/ORA-\d{5}:\s*(.*?)(?:ORA-06512|$)/s", $message, $matches)) {
            return trim($matches[1]);
        }
        return 'Erreur Oracle : ' . $message;
    }

    private function oracleMessage(string $message): string
{
    if (preg_match('/ORA-\d{5}:\s*(.*?)(ORA-06512|$)/s', $message, $m)) {
        return trim($m[1]);
    }
    return 'Erreur Oracle inconnue';
}

}
