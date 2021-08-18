<?php

namespace App\Http\Controllers;

use App\Notifications\newReception;
use App\Produit;
use App\Reception;
use App\ReceptionProduit;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;


class ReceptionController extends Controller
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
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ecom']);
        })->orderBy('name')->get();
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $users = [];
        $produits = null;
        if (!Gate::denies('ramassage-commande')) {
            //session administrateur donc on affiche tous les commandes
            $total = DB::table('receptions')->count();
            $receptions = DB::table('receptions')->orderBy('created_at', 'DESC')->paginate(10);
            foreach ($receptions as $reception) {
                if (!empty(User::withTrashed()->find($reception->user_id)))
                    $users[] =  User::withTrashed()->find($reception->user_id);
            }
            //dd($clients[0]->id);
        } else {
            $receptions = DB::table('receptions')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = DB::table('receptions')->where('user_id', Auth::user()->id)->count();
            $produits = Produit::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        }

        $details = [];
        $i = 0;
        $test = [];


        foreach ($receptions as $index => $recep) {
            $reception_produits = DB::table('produit_reception')->where('reception_id', $recep->id)->get();
            $test[] = $reception_produits;
            //dd($reception_produits);
            foreach ($reception_produits as $recet) {
                //dd($recet->produit_id);
                $produit = Produit::where('id', $recet->produit_id)->first();
                //dd($produit);
                $details[$index][] = array(
                    "produit" => $produit,
                    "qte" => $recet->qte
                );
            }
        }

        // dd($receptions[2]->id);
        // dd($test,$receptions,$details);


        return view('reception.index', [
            'nouveau' => $nouveau,
            'produits' => $produits,
            'receptions' => $receptions,
            'total' => $total,
            'users' => $users,
            'details' => $details,
            'clients' => $clients
        ]);
    }

    public function filter(Request $request)
    {

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ecom']);
        })->orderBy('name')->get();
        $receptions = DB::table('receptions')->orderBy('created_at', 'DESC');
        $users = [];
        $produits = null;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();


        if ($request->filled('valide')) {
            $receptions->where('etat', 'Validé');
        }

        if ($request->filled('envoyer')) {
            $receptions->where('etat', 'Envoyé');
        }

        if (!Gate::denies('ramassage-commande')) {
            if ($request->filled('client')) {
                $receptions->where('user_id', $request->client);
            }
            $total = $receptions->count();

            $receptions = $receptions->paginate(10);
            foreach ($receptions as $reception) {
                if (!empty(User::withTrashed()->find($reception->user_id)))
                    $users[] =  User::withTrashed()->find($reception->user_id);
            }
            //dd($clients[0]->id);
        } else {
            $receptions = $receptions->where('user_id', Auth::user()->id)->paginate(10);
            $total = DB::table('receptions')->where('user_id', Auth::user()->id)->count();
            $produits = Produit::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        }

        $details = [];

        //dd($receptions);
        foreach ($receptions as $index => $recep) {
            $reception_produits = DB::table('produit_reception')->where('reception_id', $recep->id)->get();
            //dd($reception_produits);

            foreach ($reception_produits as $recet) {
                //dd($recet->produit_id);
                $produit = Produit::where('id', $recet->produit_id)->get();
                //dd($produit);
                $details[$index][] = array(
                    "produit" => $produit[0],
                    "qte" => $recet->qte
                );
            }
        }

        return view('reception.index', [
            'nouveau' => $nouveau,
            'produits' => $produits,
            'receptions' => $receptions,
            'total' => $total,
            'users' => $users,
            'details' => $details,
            'clients' => $clients
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
        if (Gate::denies('gestion-stock')) {
            return redirect()->route('produit.index');
        } else {
            //dd("salut");
            $reception = new Reception();
            $reception->company = $request->company;
            $reception->etat = 'Envoyé';
            $reception->prevu_at = $request->prevu_at;
            $reception->reference =  "rec-" . bin2hex(substr($reception->company, -strlen($reception->company), 3)) . date("mdis");
            $reception->qte = array_sum($request->qte);
            $reception->colis = count($request->produit);
            $reception->user()->associate(Auth::user())->save();


            foreach ($request->produit as $index => $produit) {

                $stock = DB::table('stocks')->where('produit_id', $produit)->first();
                $stock = Stock::find($stock->id);
                $stock->cmd += $request->qte[$index];
                $stock->save();

                $reception_produits = [];
                foreach ($request->produit as $index => $produit) {
                    $reception_produit = new ReceptionProduit();

                    $reception_produit->qte =  $request->qte[$index];
                    $reception_produit->produit_id =  $request->produit[$index];
                    $reception_produits[] = $reception_produit;
                }

                $reception->produits()->sync($this->mapQuantity($reception_produits));

            }

            //notification
            $user_notify = \App\User::find(1);
            $user_notify->notify(new newReception(Auth::user(), $reception));

            return redirect()->route('reception.index');
        }
    }

    private function mapQuantity($quantity)
    {
        return collect($quantity)->map(function ($i) {
            return ['qte' => $i->qte , 'produit_id'=> $i->produit_id];
        });
    }

    public function showFromNotify(Reception $reception, DatabaseNotification $notification)
    {

        $notification->markAsRead();

        return redirect()->route('reception.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }



    public function valide(Request $request, $id)
    {
        if (!Gate::denies('manage-users')) {
            $reception = Reception::findOrFail($id);
            if ($reception->etat == 'Envoyé') {
                $reception->etat = "Validé";
                $reception->save();

                $reception_produits = DB::table('produit_reception')->where('reception_id', $id)->get();
                foreach ($reception_produits as $index => $reception_produit) {
                    $stock = DB::table('stocks')->where('produit_id', $reception_produit->produit_id)->first();
                    $stock = Stock::findOrFail($stock->id);
                    $stock->qte += $reception_produit->qte;
                    $stock->cmd -= $reception_produit->qte;
                    $stock->etat = "Apro";
                    $stock->save();
                }
                //dd($reception_produits[0]);

            }
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
