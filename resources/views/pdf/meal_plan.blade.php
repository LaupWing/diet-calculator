<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>2-Day Meal Plan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        h1,
        h2,
        h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .grocery-list ul {
            list-style-type: none;
            padding-left: 0;
        }

        .grocery-list li {
            margin-bottom: 5px;
        }

        .meal-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .instructions,
        .ingredients,
        .servings {
            margin-left: 20px;
            margin-top: 5px;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>

<body>

    {{-- PAGE 1: Grocery List --}}
    <div class="header">
        <h1>2-Day Meal Plan</h1>
        <p>Your grocery list and detailed meal instructions</p>
    </div>

    <div class="section grocery-list">
        <h2>🛒 Grocery List</h2>
        <ul>
            @foreach ($data->grocery_list as $item)
                <li><strong>{{ $item->name }}</strong>: {{ $item->quantity }}</li>
            @endforeach
        </ul>
    </div>

    <footer>Page 1</footer>
    <div class="page-break"></div>

    {{-- PAGES 2+: Meal Instructions by Day and Meal --}}
    @php $page = 2; @endphp
    @foreach ($data->meal_plan as $day => $meals)
        <div class="section">
            <h2>{{ ucfirst($day) }}</h2>

            @foreach ($meals as $type => $meal)
                <div class="meal-box">
                    <h3>{{ ucfirst($type) }}: {{ $meal->name }}</h3>
                    <p><strong>Calories:</strong> {{ $meal->calories }} kcal</p>
                    <p><strong>Protein:</strong> {{ $meal->protein }}g |
                        <strong>Carbs:</strong> {{ $meal->carbs }}g |
                        <strong>Fats:</strong> {{ $meal->fats }}g
                    </p>

                    <p><strong>Ingredients:</strong></p>
                    <ul class="ingredients">
                        @foreach ($meal->ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>

                    <p><strong>Instructions:</strong></p>
                    <ol class="instructions">
                        @foreach ($meal->instructions as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>

                    <p><strong>Serving Suggestions:</strong></p>
                    <ul class="servings">
                        @foreach ($meal->serving_suggestions as $tip)
                            <li>{{ $tip }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <footer>Page {{ $page++ }}</footer>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
