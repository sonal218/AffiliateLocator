<!DOCTYPE html>
<html>

<head>
    <title>Matching Affiliates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .affiliates-list {
            width: 50%;
            margin: auto;
        }

        .no-matching-affiliates {
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>Matching Affiliates within 100km of Dublin Office</h1>

    <div class="affiliates-list">
        @if (count($matchingAffiliates) > 0)
            <ul>

                @foreach ($matchingAffiliates as $affiliate)
                    <li>
                        <strong>{{ $affiliate['name'] }}</strong>
                        <br>
                        ID: {{ $affiliate['affiliate_id'] }}<br>
                        Latitude: {{ $affiliate['latitude'] }}<br>
                        Longitude: {{ $affiliate['longitude'] }}<br>
                        Distance from Dublin: {{ number_format($affiliate['distance'], 2) }} km
                    </li>
                @endforeach
            </ul>
            <!-- Display only "Next" and "Previous" buttons -->
            <div class="pagination">
                @if ($matchingAffiliates->onFirstPage())
                    <span>&laquo; Previous</span>
                @else
                    <a href="{{ $matchingAffiliates->previousPageUrl() }}">&laquo; Previous</a>
                @endif

                @if ($matchingAffiliates->hasMorePages())
                    <a href="{{ $matchingAffiliates->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @else
            <p class="no-matching-affiliates">No matching affiliates found within 100km of the Dublin office.</p>
        @endif
    </div>
</body>

</html>
