<?php

namespace App\Http\Controllers;

use App\Models\Recolte;
use App\Models\Variete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class RecolteController extends Controller
{
    public function index()
    {
        $recoltes = Recolte::with('variete.produit')
            ->orderBy('date_recolte', 'desc')
            ->get();

        $totalHarvests = DB::selectOne("SELECT fn_total_recoltes() AS total FROM DUAL")->total;
        $harvestsCount = DB::selectOne("SELECT fn_nombre_recoltes() AS nb FROM DUAL")->nb;
        $averageHarvest = DB::selectOne("SELECT fn_moyenne_recolte() AS moyenne FROM DUAL")->moyenne;

        return view('recoltes.index', compact('recoltes', 'totalHarvests', 'harvestsCount', 'averageHarvest'));
    }

    public function create()
    {
        $varietes = Variete::orderBy('nom_variete')->get();
        return view('recoltes.create', compact('varietes'));
    }

 public function store(Request $request)
{
    $request->validate([
        'variete_id'   => 'required|exists:VARIETE,VARIETE_ID',
        'date_recolte' => 'required|date',
        'quantite_kg'  => 'required|numeric',
    ]);

    try {
        DB::statement("BEGIN sp_ajouter_recolte(?, ?, ?); END;", [
            $request->variete_id,
            $request->date_recolte,
            $request->quantite_kg,
        ]);

        return redirect()->route('recoltes.index')
            ->with('success', 'Récolte enregistrée avec succès !');

    } catch (QueryException | Oci8Exception $e) {
        if ($e->getCode() == 20105 || str_contains($e->getMessage(), 'future')) {
            return back()
                ->withInput()
                ->withErrors(['date_recolte' => 'Date de récolte future interdite.']);
        }

        if ($e->getCode() == 20104 || str_contains($e->getMessage(), 'positive')) {
            return back()
                ->withInput()
                ->withErrors(['quantite_kg' => 'La quantité récoltée doit être positive.']);
        }

        return back()
            ->withInput()
            ->withErrors(['error' => 'Erreur lors de l’ajout de la récolte.']);
    }
}

        public function update(Request $request, Recolte $recolte)
        {
            $request->validate([
                'variete_id'   => 'required|exists:VARIETE,VARIETE_ID',
                'date_recolte' => 'required|date',
                'quantite_kg'  => 'required|numeric',
            ]);

            try {
                DB::statement("BEGIN sp_modifier_recolte(?, ?, ?, ?); END;", [
                    $recolte->recolte_id,
                    $request->variete_id,
                    $request->date_recolte,
                    $request->quantite_kg,
                ]);

                return redirect()->route('recoltes.index')
                    ->with('success', 'Récolte mise à jour avec succès !');

            } catch (QueryException | Oci8Exception $e) {
                if ($e->getCode() == 20106 || str_contains($e->getMessage(), 'inexistante')) {
                    return redirect()->route('recoltes.index')->with('error', 'Récolte inexistante.');
                }

                if ($e->getCode() == 20105 || str_contains($e->getMessage(), 'future')) {
                    return back()
                        ->withInput()
                        ->withErrors(['date_recolte' => 'Date de récolte future interdite.']);
                }

                if ($e->getCode() == 20104 || str_contains($e->getMessage(), 'positive')) {
                    return back()
                        ->withInput()
                        ->withErrors(['quantite_kg' => 'La quantité récoltée doit être positive.']);
                }

                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Erreur lors de la mise à jour.']);
            }
        }

    public function edit(Recolte $recolte)
    {
        $varietes = Variete::orderBy('nom_variete')->get();
        return view('recoltes.edit', compact('recolte', 'varietes'));
    }


    public function destroy(Recolte $recolte)
    {
        try {
            DB::statement("BEGIN sp_supprimer_recolte(?); END;", [$recolte->recolte_id]);

            return redirect()->route('recoltes.index')
                ->with('success', 'Récolte supprimée avec succès !');

        } catch (QueryException | Oci8Exception $e) {
            if ($e->getCode() == 20108 || str_contains($e->getMessage(), 'ventes')) {
                return back()->withErrors(['error' => 'Impossible de supprimer cette récolte car elle est liée à des ventes.']);
            }

            if ($e->getCode() == 20106 || str_contains($e->getMessage(), 'inexistante')) {
                return redirect()->route('recoltes.index')->with('error', 'Récolte inexistante.');
            }

            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}



