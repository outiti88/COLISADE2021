<?php

namespace App\Http\Controllers;

use App\Facture;

use App\BonLivraison;
use App\Commande;
use App\Produit;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = []; //tableau des clients existe dans la base de données
        $users = []; //les users qui seront affichés avec leur bon de livraison
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->orderBy('created_at', 'DESC');
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->whereHas('commandes', function ($q) {
                $q->where('facturer', '0')->where('statut','Livré');
            })->orderBy('name')->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        }

        // $userTmp = User::withTrashed()->find($facture->user_id);
        //     $commandeNonFacrurer = $userTmp->whereHas('commandes', function ($q) {
        //             $q->where('facturer', '0')->where('commande','Livré');
        //         })->count();
        //     if($commandeNonFacrurer>0) $users[] = $userTmp;

        foreach ($factures->paginate(10) as $facture) {
            $users[] =  User::withTrashed()->find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = null;
        return view('facture', [
            'nouveau' => $nouveau, 'factures' => $factures,
            'total' => $total,
            'users' => $users,
            'clients' => $clients, 'data' => $data
        ]);
    }

    public function filter(Request $request)
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = []; //tableau des clients existe dans la base de données
        $users = []; //les users qui seront affichés avec leur bon de livraison
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->orderBy('created_at', 'DESC');
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->orderBy('name')->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        }

        if ($request->filled('client')) {
            $factures->where('user_id', $request->client);
        }


        if ($request->filled('date_facture_min')) {
            $factures->whereDate('created_at', '>=', $request->date_facture_min);
        }
        if ($request->filled('date_facture_max')) {
            $factures->whereDate('created_at', '<=', $request->date_facture_max);
        }

        foreach ($factures->paginate(10) as $facture) {
            $users[] =  User::withTrashed()->find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = $request->all();

        return view('facture', [
            'nouveau' => $nouveau, 'factures' => $factures,
            'total' => $total,
            'users' => $users,
            'clients' => $clients, 'data' => $data
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->client;
        if (!Gate::denies('manage-users')) {
            $nbrCmdLivre =  DB::table('commandes')->where('statut', 'Livré')->where('user_id', $user)->where('facturer', '0')->count();
            $nbrCmdRamasse =  DB::table('commandes')->where('statut', 'En cours')->where('user_id', $user)->where('facturer', '0')->count();

            if ($nbrCmdLivre == 0) {

                $request->session()->flash('nbrCmdRamasse', $nbrCmdRamasse);
            } else {
                $facture = new Facture();
                $facture->numero = 'FAC_' . date("mdis");
                $facture->colis = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Refusée'])->where('facturer', '0')->sum('colis');
                $facture->livre = $nbrCmdLivre;
                $fprixRefuser = DB::table('commandes')->where('user_id', $user)->where('statut', 'Refusée')->where('facturer', '0')->sum('refusePart');
                $facture->prix = DB::table('commandes')->where('user_id', $user)->where('statut', 'Livré')->where('facturer', '0')->sum('prix') + $fprixRefuser; //prix des commandes livrées

                $facture->montant = DB::table('commandes')->where('user_id', $user)->where('statut', 'Livré')->where('facturer', '0')->sum('montant');
                $facture->commande = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Refusée'])->where('facturer', '0')->count(); //nbr de commanddes non livrée
                $facture->user()->associate($user)->save();
                $affected = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Livré', 'Refusée'])->where('facturer', '=', '0')->update(array('facturer' => $facture->id));

                $request->session()->flash('ajoute');
            }
        } //rammsage-commande
        return redirect(route('facture.index'));
    } //fin fonction ajouter facture



    public function commandes(Facture $facture, $n, $i)
    {
        $user = $facture->user_id;
        $commandes = DB::table('commandes')->where('user_id', $user)->where('facturer', $facture->id)->get();
        $content =
            '
        <div class="invoice">
             <table id="customers">
                 <tr>
                 <th>Numéro</th>
                 <th>Destinataire</th>
                 <th>Ville</th>
                 <th>Téléphone</th>
                 <th>Montant</th>
                 <th>Statut</th>
                 <th>Prix de livraison</th>

                 <th>Date de livraison</th>
                 </tr>
        ';
        foreach ($commandes as $index => $commande) {

            if (($index >= $i * 6) && ($index < 6 * ($i + 1))) { //les infromations de la table depe,d de la page actuelle
                $price = ($commande->statut == 'Livré')? $commande->prix : $commande->refusePart;
                if ($commande->montant == 0) {
                    $montant = "Payée Par CB";
                } else {
                    $montant = $commande->montant;
                }
                $content .= '<tr>' . '
            <td>' . $commande->numero . '</td>
            <td>' . $commande->nom . '</td>
            <td>' . $commande->ville . '</td>
            <td>' . $commande->telephone . '</td>
            <td>' . $montant . '</td>
            <td>' . (($commande->statut == 'Retour en stock') ? 'Refusée' : $commande->statut) . '</td>
            <td>' . $price . '</td>
            <td>' . $commande->updated_at . '</td>
            ' . '</tr>';
            }
        }
        return $content . '</table>  </div>';
    }

    //fonction qui renvoie le contenue du bon de livraison

    public function content(Facture $facture, $n, $i)
    {
        $user = $facture->user_id;
        $user = DB::table('users')->find($user);
        $livraisonNonPaye = 0;
        $prixLivrer = DB::table('commandes')->where('user_id', $user->id)->where('facturer', $facture->id)->where('statut', 'livré')->sum('prix');
        $prixRefuser =  DB::table('commandes')->where('user_id', $user->id)->where('facturer', $facture->id)->whereIn('statut', ['Retour en stock','Refusée'])->sum('refusePart');

        $livraisonNonPaye += $prixRefuser + $prixLivrer;

        $net = $facture->montant - $livraisonNonPaye;

        //les information du fournisseur (en-tete)
        $info_client = '
            <div class="info_client">
                <h1>' . $user->name . '</h1>
                <h3>ADRESSE : ' . $user->adresse . '</h3>
                <h3>TELEPHONE : ' . $user->telephone . '</h3>
                <h3>VILLE : ' . $user->ville . '</h3>
                <h3>ICE: ' . $user->description . '</h3>
            </div>
            <div class="date_num">
                <h3>' . $facture->numero . '</h3>
                <h3>' . $facture->created_at . '</h3>


            </div>
        ';
        // pied du bon d'achat (calcul du total)
        $total = '
            <div class="total">
            <table id="customers">

            <tr class="totalfacture">
            <th>TOTAL BRUT : </th>
            <td>' . $facture->montant . '  DH</td>
            </tr>
            <tr class="totalfacture">
            <th>Livraison : </th>
            <td>' . $livraisonNonPaye . '  DH</td>
            </tr>
            <tr class="totalfacture">
            <th>TOTAL NET : </th>
            <td>' . $net . '  DH</td>
            </tr>

            </table>
            </div>
            ';


        $content = $this->commandes($facture, $n, $i);
        $content = $info_client . $content;
        if ($n == ($i + 1)) { //le total seulement dans la derniere page (n est le nbr de page / i et la page actuelle)
            $content .= $total;
        }
        return $content;
    }


    public function gen($id)
    {

        $facture = Facture::findOrFail($id);
        $user = $facture->user_id;

        if ($user !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        $user = DB::table('users')->find($user);
        //dd($facture->id);
        $pdf = App::make('dompdf.wrapper');

        $style = '
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Facture_' . $facture->numero . '</title>

            <style type="text/css">
            @page {
                margin: 0px;
            }

                body{
                    margin: 0px;
                    background-image: url("https://colisade.ma/images/FactureColisade.png");
                    width: 790px;
                    height: auto;
                    background-position: center;
                    background-repeat: repeat;
                    padding-bottom : 200px;
                    background-size: 100% 1070px;
                    background-size: cover;
                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    font-size: 0.75em;
                }
                .info_client{
                    position:relative;
                    left:45px;
                    top:190px;
                }
                .date_num{
                    position:relative;
                    left:640px;
                    top:155px;
                }
                .total{
                    position:relative;
                    left:200px;
                    top:15px;
                }
                .invoice {
                   margin : 8px;
                    position: relative;
                    min-height: auto;

                }
                #customers {
                    border-collapse: collapse;
                    width: 100%;
                    position: relative;
                    top: 170px;
                    }

                    #customers td, #customers th {
                    border: 1px solid #ddd;
                    padding: 8px;
                    }

                    #customers tr:nth-child(even){background-color: #f2f2f2;}

                    #customers th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #e85f03;
                    color: white;
                    }

                </style>

            </head>
            <body>
        ';
        $m = (($facture->livre) / 6);
        $n = (int)$m; // nombre de page
        if ($n != $m) {
            $n++;
        }
        //dd($n);
        $content = '';
        for ($i = 0; $i < $n; $i++) {
            $content .= $this->content($facture, $n, $i);
        }

        $content = $style . $content . ' </body></html>';

        //dd($this->content($facture));
        $pdf->loadHTML($content)->setPaper('A4');


        return $pdf->stream('Facture_' . $facture->numero . 'pdf');
    }


    public function search($id)
    {
        $data = null;
        $facture = Facture::findOrFail($id);
        $user = $facture->user_id;
        $villes = DB::table('villes')->orderBy('name')->get();


        if ($user !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        //dd(Auth::user()->id );
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->orderBy('name')->get();

        $users = [];
        $produits = [];

        if (!Gate::denies('ecom')) {
            $produits_total = Produit::where('user_id', Auth::user()->id)->get();
            foreach ($produits_total as $produit) {
                $stock = DB::table('stocks')->where('produit_id', $produit->id)->get();
                if ($stock[0]->qte > 0) {
                    $produits[] = $produit;
                }
            }
            //dd($produits);
        }

        if (!Gate::denies('manage-users')) {
            //session administrateur donc on affiche tous les commandes
            $total = Commande::where('deleted_at', NULL)->where('facturer', $id)->count();
            $commandes = Commande::where('deleted_at', NULL)->where('facturer', $id)->orderBy('created_at', 'DESC')->paginate(10);
            //dd($commandes);

            //dd($clients[0]->id);
        } else {
            $commandes = Commande::where('deleted_at', NULL)->where('facturer', $id)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = Commande::where('deleted_at', NULL)->where('facturer', $id)->where('user_id', Auth::user()->id)->count();

            //dd("salut");
        }


        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }
        //$commandes = Commande::all()->paginate(3) ;
        $statuts = [];
        $statutStat = [];

        if(!Gate::denies('manage-users')){
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where(function ($q) {
                   $q->whereDate('updated_at', '>=', now()->subMonth())
                       ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
               })
                ->groupBy('statut')
                ->get();
        }
        else if(!Gate::denies('livreur')){
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where('livreur', Auth::user()->id)
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue'])
                ->groupBy('statut')
                ->get();
        }else{
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('user_id', Auth::user()->id)
                ->where('deleted_at', NULL)
                ->where(function ($q) {
                   $q->whereDate('updated_at', '>=', now()->subMonth())
                       ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
               })
                ->groupBy('statut')
                ->get();
        }

        foreach ($statuts as $statut){
            $statutStat[$statut->statut] = $statut->total;
        }

        return view('commande.colis', [
            'commandes' => $commandes,
            'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'produits' => $produits,
            'livreurs' => $livreurs,
            'villes' => $villes,
            'data' => $data, 'checkBox' => null,
            'statutStat' => $statutStat

        ]);
    }

    public function infos($id)
    {
        $facture = Facture::findOrFail($id);
        $user = $facture->user_id;
        $villes = DB::table('villes')->orderBy('name')->get();

        if ($user !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = [];
        $users = [];
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->where('id', $id);
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->where('id', $id);
        }

        foreach ($factures->paginate(10) as $facture) {
            if (!empty(User::find($facture->user_id)))
                $users[] =  User::find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = null;

        if ($total > 0) {
            //dd($factures);
            return view('facture', [
                'nouveau' => $nouveau, 'factures' => $factures,
                'total' => $total,
                'users' => $users,
                'clients' => $clients,
                'villes' => $villes, 'data' => $data
            ]);
        } else {
            return redirect()->route('facture.index');
        }
    }
}
