<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paystach Payment Completed</title>
	<link href="{{asset('assets/plugins/bootstrap-4.4.1-dist/css/bootstrap.min.css')}}" rel="stylesheet" />
</head>
<style>
.image-div {
    max-width: 20rem;
    max-height: 20rem;
    margin: 1rem auto 0; 
    }
.payment-success {max-width: 100%; height:auto;}

</style>

<body>
    <div class="container text-center">
        <div class ='image-div'>
            <img  class= 'payment-success' src = "{{asset('images/payment-success.png')}}" />
        </div>

        <p class="font-weight-bold my-3">Your Payment is successful</p>
        <p>You will be redirected to your order page in <span id ='countdown'>5 </span>  seconds</p>
        <p>If you aren't redirected click the link below to be redirected</p>
        <a class= 'btn btn-sm btn-success' href="{{ URL::to('/dashboard/order-history')}}">Click here</a>
    </div>

    <script>

    let timeLeft = 5;
let elem = document.getElementById('countdown');

let timerId = setInterval(countdown, 1000);

function countdown() {
  if (timeLeft == 0) {
    clearTimeout(timerId);
    window.location.href = "{{ URL::to('/dashboard/order-history')}}";
  } else {
    elem.innerHTML = timeLeft;
    timeLeft--;
  }
}
    </script>
</body>
</html>