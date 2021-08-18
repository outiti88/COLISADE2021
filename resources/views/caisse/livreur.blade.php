@extends('racine')

@section('title')
   Argent et Caisse
@endsection

@section('content')

<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Livreur: {{$LivreurObject->name}}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Colisade</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ville : {{$LivreurObject->ville}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="text-right upgrade-btn">
            <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#FormStore"><i class="fa fa-plus-square"></i> Ajouter un paiement</a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="FormStore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Envoyer un montant</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-3">
              <form class="form-horizontal form-material" method="POST" action="{{route('caisse.store')}}">
                  @csrf

                  @can('edit-users')

                        <input type="hidden" name="livreur" value="{{$livreur}}">
                  @endcan
                        <div class="form-group">
                            <label for="mode" class="col-sm-12">Mode de paiement :</label>
                            <div class="col-sm-12">
                                <select name="banque" id="mode" class="form-control form-control-line" required>
                                    <option>Paiement CASH</option>
                                    <option selected>Paiement par Virement</option>
                                </select>
                            </div>
                        </div>
                  <div class="form-group">
                    <label class="col-md-12">Montant envoyé :</label>
                    <div class="col-md-12">
                        <input  value="{{ old('montant') }}" name="montant" type="number" placeholder="Montant envoyé" class="form-control form-control-line" required>
                    </div>
                </div>



                  <div class="form-group">
                      <div class="modal-footer d-flex justify-content-center">
                          <button class="btn btn-danger">Envoyer</button>

                      </div>
                  </div>
              </form>

          </div>

        </div>
      </div>
  </div>


.<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Details de la caisse</h4>
                    <div class="feed-widget">
                        <ul class="list-style-none feed-body m-0 p-b-20 row d-flex justify-content-around">
                            <li class="feed-item">
                                <div class="feed-icon bg-info"><i class="far fa-bell"></i></div> {{$caisses}} MAD.
                                <span class="ml-auto font-12 text-muted">   Du Montant Total</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-success"><i class="ti-server"></i></div> {{$livraison}} MAD.
                                 <span class="ml-auto font-12 text-muted">   Part du livreur</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-warning"><i class="ti-shopping-cart"></i></div> {{$envoyers}} MAD.
                                <span class="ml-auto font-12 text-muted">   Montant envoyé</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-danger"><i class="ti-stats-up"></i></div> {{$caisses- $livraison- $envoyers}} MAD.
                                <span class="ml-auto font-12 text-muted">    Reste</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Details de paiements effectués</h4>
                <h6 class="card-subtitle">Nombre total des paiements : <code>{{$total}} Articles</code> .</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" style="font-size: 0.85em;">
                    <thead>
                        <tr>

                            <th scope="col">Date de Paiement</th>
                            <th scope="col">Mode de Paiement</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Etat</th>

                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($paiments as $index => $paiment)
                       <tr>


                        <td>{{$paiment->created_at}}</td>
                        <td>{{$paiment->banque}}</td>
                        <td>{{$paiment->montant}} DH</td>
                        <td>
                            <a  style="color: white"
                            class="badge badge-pill
                            @switch($paiment->etat)
                                @case(0)
                                    badge-warning"

                                    title="Valider le paiement"
                                    @can('edit-users')
                                        href="{{ route('caisse.edit',$paiment->id) }}"
                                    @endcan
                                    ><span style="font-size: 1.25em">Envoyé</span></a>
                                    @break
                                @case(1)
                                badge-success"><span style="font-size: 1.25em">Validé</span></a>
                                    @break
                            @endswitch
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center">Aucun paiement effectué!</td>
                    </tr>

                       @endforelse

                    </tbody>

                </table>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        {{$paiments ->appends($data)-> links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>



@endsection
