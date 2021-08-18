<?php

use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', 'DashboardController@dash')->name('dashboard');

Route::get('/commandes/{id}/statut', 'CommandeController@changeStatut')->name('commandeStatut')->middleware('can:valide');

Route::patch('/commandes/{id}/statut', 'CommandeController@statutAdmin')->name('statut.admin')->middleware('can:valide');

Route::get('/commandes/{id}/valide', 'CommandeController@retourStock')->name('commande.valideRetour')->middleware('can:manage-users');

Route::post('/stock/{id}/inventaire', 'StockController@corriger')->name('stock.corriger')->middleware('can:manage-users');

Route::get('pdf/{id}/A6', 'CommandeController@gen')->name('pdf.gen')->middleware('can:valide');

Route::get('pdf/{id}/A8', 'CommandeController@genA8')->name('pdf.genA8')->middleware('can:valide');

Route::get('export', 'CommandeController@export')->name('export');
Route::post('import', 'CommandeController@import')->name('import');


Route::get('/search', 'CommandeController@search')->name('commande.search')->middleware('can:valide');

Route::get('/commandes/filter', 'CommandeController@filter')->name('commande.filter')->middleware('can:valide');

Route::patch('/commandes/{id}/livreur', 'CommandeController@affecterLivreur')->name('commande.livreur')->middleware('can:manage-users');

Route::get('/order/status/update', 'CommandeController@updateSatuts')->name('commande.statut.update')->middleware('can:manage-users');

Route::get('/order/status/expedier', 'CommandeController@expedier')->name('commande.expedier')->middleware('can:manage-users');
Route::get('/order/status/recevoir', 'CommandeController@recevoir')->name('commande.recevoir')->middleware('can:manage-users');
Route::get('/order/status/recevoir', 'CommandeController@recevoir')->name('commande.recevoir')->middleware('can:manage-users');

Route::get('/order/{commande}/horszone', 'CommandeController@outRange')->name('commande.outRange')->middleware('can:manage-users');


Route::get('/receptions/filter', 'ReceptionController@filter')->name('reception.filter')->middleware('can:gestion-stock');

Route::get('/stock/filter', 'ProduitController@filter')->name('stock.filter')->middleware('can:gestion-stock');

Route::get('/facture/filter', 'FactureController@filter')->name('facture.filter')->middleware('can:valide');


Route::post('/commandes/{id}/relance', 'RelanceController@relancer')->name('relance.relancer')->middleware('can:ramassage-commande');

Route::get('/user/new', 'Auth\RegisterController@nouveau')->name('user.nouveau');

Route::get('/tracking/numero', 'TrackingController@index')->name('tracking.index');
Route::get('/tracking', 'TrackingController@search')->name('tracking.search');


Route::resource('/commandes', 'CommandeController')->except([
    'create', 'edit'
])->middleware('can:valide');

Route::get('/archive', 'ArchiveController@index')->name('archive.index')->middleware('can:delete-commande');

Route::get('/archive/filter', 'ArchiveController@filter')->name('archive.filter')->middleware('can:valide');

Route::get('/caisse', 'CaisseController@index')->name('caisse.index')->middleware('can:edit-users');

Route::get('/caisse/{id}', 'CaisseController@show')->name('caisse.livreur')->middleware('can:livreur-admin');

Route::get('/caisse/{id}/valide', 'CaisseController@edit')->name('caisse.edit')->middleware('can:edit-users');

Route::get('/commande/caisse/commande', 'CaisseController@create')->name('caisse.create')->middleware('can:edit-users');

Route::post('/caisse', 'CaisseController@store')->name('caisse.store')->middleware('can:livreur-admin');

Route::post('/reclamation', 'ReclamationController@store')->name('reclamation.store')->middleware('can:fournisseur');
Route::get('/reclamation', 'ReclamationController@index')->name('reclamation.index')->middleware('can:delete-commande');
Route::delete('/reclamation', 'ReclamationController@destroy')->name('reclamation.destroy')->middleware('can:delete-commande');
Route::get('/reclamation/{id}', 'ReclamationController@traiter')->name('reclamation.traiter')->middleware('can:delete-commande');
Route::get('/filter/reclamation', 'ReclamationController@filter')->name('reclamation.filter')->middleware('can:delete-commande');;



Route::put('/ville/{id}', 'VilleController@updateVille')->name('ville.updateVille')->middleware('can:edit-users');

Route::resource('/ville', 'VilleController')->middleware('can:edit-users');

Route::resource('/produit', 'ProduitController')->except([
    'create', 'edit'
])->middleware('can:gestion-stock');

Route::resource('/reception', 'ReceptionController')->except([
    'create', 'edit'
])->middleware('can:gestion-stock');

Route::get('/reception/{id}/valide', 'ReceptionController@valide')->name('reception.valide')->middleware('can:manage-users');

Route::get('showFromNotify/{commande}/{notification}', 'CommandeController@showFromNotify')->name('commandes.showFromNotify')->middleware('can:valide');

Route::get('showFromNotify/{reception}/{notification}', 'ReceptionController@showFromNotify')->name('reception.showFromNotify')->middleware('can:valide');


Route::get('/profil', 'ProfilController@index')->name('profil.index')->middleware('can:valide');

Route::match(['put', 'patch'], '/profil/{user}', 'ProfilController@update')->name('profil.update')->middleware('can:valide');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/bonlivraison', 'BonLivraisonController')->only([
    'index', 'store'
])->middleware('can:delete-commande');

Route::resource('/Relance', 'RelanceController')->only([
    'index', 'edit'
])->middleware('can:manage-users');

Route::get('/bonlivraison/{id}/infos', 'BonLivraisonController@infos')->name('bon.infos')->middleware('can:delete-commande');

Route::get('/bonlivraison/{id}/pdf', 'BonLivraisonController@gen')->name('bon.gen')->middleware('can:delete-commande');

Route::get('/bonlivraison/{id}/details', 'BonLivraisonController@search')->name('bon.search')->middleware('can:delete-commande');

Route::resource('/facture', 'FactureController')->only([
    'index', 'store'
])->middleware('can:client-admin');

Route::get('/facture/{id}/pdf', 'FactureController@gen')->name('facture.gen')->middleware('can:client-admin');

Route::get('/BonCommande/pdf', 'BonCommandeController@gen')->name('bonCommande.index')->middleware('can:manage-users');

Route::get('/tickets/pdf', 'CommandeController@ticketsBuilder')->name('ticket.index')->middleware('can:valide');

Route::get('/facture/{id}/details', 'FactureController@search')->name('facture.search')->middleware('can:client-admin');

Route::get('/facture/{id}/infos', 'FactureController@infos')->name('facture.infos')->middleware('can:client-admin');

Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function () {
    Route::resource('/users', 'UsersController', ['except' => ['show', 'create', 'store']])->middleware('can:valide');
});

Route::get('/facture/{id}/send', 'EmailController@sendFacture')->name('email.facture')->middleware('can:manage-users');

Route::get('/inbox', 'NotificationController@index')->name('inbox.index')->middleware('can:valide');
Route::get('/{notifications}/show', 'NotificationController@show')->name('inbox.show')->middleware('can:valide');
Route::get('/{notifications}/delete', 'NotificationController@destroy')->name('inbox.destroy')->middleware('can:valide');
