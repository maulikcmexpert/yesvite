<!DOCTYPE html>
<html>
<head>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Purchase Credits</h1>
    <form method="POST" action="{{ route('process.payment') }}">
        @csrf
        <label for="credits">Select Credits:</label>
        <select name="priceId" id="credits" required>
            @foreach($prices as $credits => $priceId)
                <option value="{{ $priceId }}">{{ $credits }} Credits</option>
            @endforeach
        </select>
        <button type="submit">Buy Now</button>
    </form>
</body>
</html>
