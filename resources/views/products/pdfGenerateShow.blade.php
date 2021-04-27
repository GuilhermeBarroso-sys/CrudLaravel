<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

    <div class="container w-75 text-center">
        <h2>{{$product->name}} </h2>
        <div class="card">

            <br>
            <br>
            <div class="card-body">

                <img class="img-thumbnail" src = "{{$presignedUrl}}"/></p>
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
        <br>
        <br>

    </div>
    @if ($client_signature != "")
        <span class = "h3 text-left" style = "text-align:left;">Assinatura eletrônica:</span>
        <span>{{$client_signature}}</span>
    @endif



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</html>
