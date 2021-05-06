@extends('layouts.app')

@section('content')

    <div class="container w-25">
        <div class="card">
            <div class="card-header text-center">
                <h5 class = "card-title">{{$product->name}}</h5>
            </div>
            <div class="card-body">
                <img class="img-thumbnail" src = "{{$signedUrl}}"/></p>

                <p class="card-text"> <strong> Description:</strong> {{$product->description}}</p>
                <p class="card-text"> <strong> Price:</strong> $ {{$product->price}}</p>
                <p class="card-text"> <strong> Amount in stock:</strong>

                    @if($product->amount == 0)
                    <span style = "color:red"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount > 1000)
                    <span style = "color:green"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount < 1000 && $product->amount > 0)
                    <span style = "color:rgb(253, 186, 0)"> {{$product->amount}} </span>
                    @endif
                </p>

            </div>
        </div>
        <br>

        @if ($product->pdf_generate == 0)
        <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">
            <input hidden value = "{{$signedUrl}}" name = "signedUrl" />
            <input hidden value = "{{$product->id}}" name = "id" />
            <input hidden value = "0" name = "view"/>
            <input hidden value = "0" name = "signed"/>
            <button type = "submit" class="btn btn-info float-right">
                @csrf

                <strong>Gerar pdf</strong>
            </button>
        </form>
        @else
        <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">
            <button type = "submit" class="btn btn-info float-right">
                @csrf
                <input hidden value = "{{$signedUrl}}" name = "signedUrl" />
                <input hidden value = "{{$product->id}}" name = "id" />
                <input hidden value = "1" name = "view"/>
                <input hidden value = "0" name = "signed"/>
                <strong>Ver pdf</strong>
            </button>
        </form>
        @endif
        @if($product->pdf_generate == 1 && $product->pdf_signed == 0)
            <br>
            <br>

            <button type = "submit" id = "signed_button" class="btn btn-success float-right">


                <strong>Assinar</strong>
            </button>
            <br>
            <br>
            <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">



                <div style = " display:none;" id = "field_signed">
                    <br>
                <p style = "text-align:justify; font-size:17px">Você pode assinar somente se concordar com nossos <span class = "link text-primary" data-toggle="modal" data-target="#exampleModalLong">termos de serviços.</span> Aceitando esses termos você declara e garante que a empresa pmovel está autorizada realizar tal ação para ...</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"  name = "servicesTerms" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                    Eu concordo e estou ciente com os termos de serviços.
                    </label>
                </div>

                        @csrf
                        <input hidden value = "{{$product->id}}" name = "id" />
                        <input hidden value = "0" name = "view"/>
                        <input hidden value = "1" name = "signed"/>

                </div>
                <div style = "display:none" id="field">
                    <input type = "text" placeholder = "Sua assinatura" name = "signature" class = "form-control w-100" required />
                    <button type = "submit" id = "signed_button" class="btn btn-success mt-1">Enviar</button>
                </div>




            </form>


            <!-- Modal -->
            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title"  id="exampleModalLongTitle">Termos de serviço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                            <li>Lorem ipsum dolor  amet, consecte sit amet, consectetur adipiscing elit.</li>
                            <li>Lorem ipsum dolor sit amet, consectetur adip  amet, consecte iscing elit.</li>
                            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                            <li>Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.  Dolor sit amet. Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</li>

                        </ul>
                    </div>

                </div>
                </div>
            </div>
        @endif


    </div>

<script>
    $(document).ready(function() {
        $('#signed_button').click(() => {
            $('#field_signed').slideToggle();
        })
        $('#flexCheckDefault').change(() => {
            $('#field').slideToggle();
        })
    })

</script>
@endsection
