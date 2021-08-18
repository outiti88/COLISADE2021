<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Statut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request){

        $encours = array("En cours", "Modifiée", "Reporté");
        $expedier = array("Expidiée", "Ramassée", "Reçue");
        $nonlivrer = array("Annulée", "Refusée", "Retour","Injoignable","Pas de Réponse");



        $state = 1;

        $commande = Commande::where('numero',$request->numero)->first();
        if($commande != null){
            if($commande->statut == 'envoyée')  $state = 1;
            if(in_array($commande->statut,$expedier))  $state = 2;
            if(in_array($commande->statut,$encours))  $state = 3;
            if($commande->statut == 'Livré')  $state = 4;
            if(in_array($commande->statut,$nonlivrer))  $state = 5;


            return view('tracking.index',['commande' => $commande , 'state' => $state]);
        }

        else {
            $request->session()->flash('notfound', $request->numero);

            return view('tracking.form');
        }
    }

    public function search(){

        return view('tracking.form');
    }


}
