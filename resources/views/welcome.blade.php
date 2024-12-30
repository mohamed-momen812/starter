{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel - PayPal Integration</title>
</head>
<body>
    <h2>Product: Laptop</h2>
    <h3>Price: $5</h3>
    <form action="{{ route('payment') }}" method="post">
        @csrf
        <input type="hidden" name="amount" value="53">
        <input type="hidden" name="product_name" value="Laptop">
        <input type="hidden" name="quantity" value="1">
        <button type="submit">Pay with payPal</button>
    </form>

    <a href="{{ route('GoogleRedirect') }}"><strong>Google Login</strong></a>
</body>
</html> --}}


<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('5828136a2a087c71e72d', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('my-channel');

    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
