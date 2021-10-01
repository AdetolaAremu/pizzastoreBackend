<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Failed Payment</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Payment Failed</h1>
        <p>You will be redirected to your order page in 5 seconds</p>
        <p>If you aren't redirected click the link below to be redirected</p>
        <a href="{{ URL::to('/dashboard/order-history')}}">Click here</a>
    </div>
    <script>
        setTimeout(function(){
            window.location.href = "{{ URL::to('/dashboard/order-history')}}";
        }, 5000);
    </script>
</body>
</html>