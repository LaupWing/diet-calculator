<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7-Day Meal Plan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
            background-color: #f4f6f9;
        }

        .page-break {
            page-break-after: always;
        }

        .day-header {
            text-align: center;
            margin-top: 40px;
            font-size: 28px;
            color: #16a085;
            text-transform: uppercase;
        }

        .meal-section {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .meal-section h2 {
            color: #2980b9;
        }

        .macro-info {
            font-size: 14px;
            margin-bottom: 10px;
            color: #7f8c8d;
        }

        ul,
        ol {
            margin-left: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #34495e;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            margin-top: 40px;
        }

        .cta-box {
            background-color: #ecf0f1;
            padding: 15px;
            margin-top: 30px;
            text-align: center;
            border-left: 5px solid #27ae60;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="cta-box" style="margin-top: 0;">
        Tired of crash diets that don’t last? <br>
        <strong>Discover the strategy that got me lean — and kept me lean year-round:</strong><br>
        The <strong>Slingshot Diet</strong> is fast, effective, and sustainable.<br>
        🔗 <a href="https://slingshot.loc-ng.com/">Click here to get it now</a>
    </div>
    @foreach ($days as $day => $meals)
        <div class="day-header">Day {{ $loop->iteration }}</div>

        @foreach ($meals as $meal)
            <div class="meal-section">
                <h2>{{ ucfirst($meal['meal_type']) }}: {{ $meal['name'] }}</h2>
                <p class="macro-info">
                    Calories: {{ $meal['calories'] }} kcal | Protein: {{ $meal['protein'] }}g | Carbs:
                    {{ $meal['carbs'] }}g | Fats: {{ $meal['fats'] }}g
                </p>
                <p>{{ $meal['description'] }}</p>

                <div class="section-title">Ingredients:</div>
                <ul>
                    @foreach ($meal['ingredients'] as $ingredient)
                        <li>{{ $ingredient }}</li>
                    @endforeach
                </ul>

                <div class="section-title">Instructions:</div>
                <ol>
                    @foreach ($meal['instructions'] as $step)
                        <li><strong>{{ $step['title'] }}:</strong> {{ $step['description'] }}</li>
                    @endforeach
                </ol>

                <div class="section-title">Serving Suggestions:</div>
                <ul>
                    @foreach ($meal['serving_suggestions'] as $suggestion)
                        <li>{{ $suggestion }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="cta-box">
        Want to stay lean year-round with minimal effort? <br>
        <strong>Check out my personal strategy — the Slingshot Diet.</strong><br>
        It’s the exact approach that got me shredded and made it easy to maintain.<br>
        🔗 <a href="https://slingshot.loc-ng.com/">Grab it here</a>
    </div>
    <footer>
        Stay strong and consistent — you got this! 💪<br>
        <span>- Loc Nguyen</span>
    </footer>
</body>

</html>
