<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Plan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        h1,
        h2,
        h3 {
            color: #2c3e50;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .grocery-list,
        .meal-plan {
            margin: 20px;
        }

        .grocery-list ul {
            list-style: none;
            padding: 0;
        }

        .grocery-list li {
            margin: 5px 0;
            font-size: 14px;
        }

        .meal {
            margin-bottom: 30px;
        }

        .meal h3 {
            margin-bottom: 10px;
        }

        .instructions {
            margin-left: 20px;
        }

        footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #95a5a6;
        }
    </style>
</head>

<body>
    <!-- Grocery List Page -->
    <div class="header">
        <h1>Daily Meal Plan</h1>
        <p>Your personalized grocery list and meal plan instructions.</p>
    </div>
    <div class="grocery-list">
        <h2>Grocery List</h2>
        <ul>
            @foreach ($data->grocery_list as $item)
                <li><strong>{{ $item->name }}</strong>: {{ $item->quantity }}</li>
            @endforeach
        </ul>
    </div>
    <footer>
        Page 1
    </footer>
    <div class="page-break"></div>

    <!-- Meal Plan Pages -->
    <div class="meal-plan">
        @foreach ($data->meal_plan_with_instructions as $index => $meal)
            <div class="meal">
                <h2>{{ ucfirst($meal->meal_type) }}: {{ $meal->recipe_name }}</h2>
                <p><strong>Calories:</strong> {{ $meal->calories }}</p>
                <h3>Instructions</h3>
                <ol class="instructions">
                    @foreach ($meal->instructions as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
            <footer>
                Page {{ $index + 2 }}
            </footer>
            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>

</html>
