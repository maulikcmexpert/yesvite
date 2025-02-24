<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.stripe.com/v3/"></script>
    <title>Purchase Credits</title>
</head>
<body>
    <h1>Purchase Credits</h1>
    <form method="POST" action="{{ route('process.payment') }}">
        @csrf
        <label for="credits">Select Credits:</label>
        <select name="priceId" id="credits" required>
            @foreach($prices as $credits => $price)
                <option value="{{ $price['priceId'] }}">{{ $credits }} Credits</option>
            @endforeach
        </select>
        <button type="submit">Buy Now</button>
    </form>
</body>
</html>
