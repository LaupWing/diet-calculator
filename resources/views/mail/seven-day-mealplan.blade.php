<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Weekly Meal Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h1,
        h2,
        h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .section {
            margin-bottom: 30px;
        }

        .badge {
            background-color: #2c3e50;
            color: #fff;
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 5px;
        }

        .meal {
            background-color: #f2f4f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .meal h4 {
            margin: 0 0 6px 0;
            color: #34495e;
        }

        .macro {
            font-size: 14px;
            margin-top: 5px;
            color: #555;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
        }

        .highlight {
            color: #16a085;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello, {{ $data['email'] }}</h1>
        <p>Here is your personalized weekly meal plan along with your health data and fitness goals.</p>

        <div class="section">
            <h2>User Info</h2>
            <p><strong>Age:</strong> {{ $data['age'] }}</p>
            <p><strong>Height:</strong> {{ $data['height'] }} {{ $data['unit'] }}</p>
            <p><strong>Weight:</strong> {{ $data['weight'] }} {{ $data['unit'] }}</p>
            <p><strong>Activity Level:</strong> {{ ucfirst($data['activity']) }}</p>
            <p><strong>Preferred Cuisine:</strong> {{ $data['preferred_cuisine'] }}</p>
            <p><strong>Dietary Preference:</strong> {{ ucfirst($data['dietary_preference']) }}</p>
            <p><strong>Current Body Fat:</strong> {{ $data['current_bodyfat'] }}%</p>
            <p><strong>Goal Body Fat:</strong> {{ $data['goal_bodyfat'] }}%</p>
            <p><strong>Goal Weight:</strong> {{ $data['goal_weight'] }} {{ $data['unit'] }}</p>
            <p><strong>Goal Duration:</strong> {{ $data['goal_months'] }} months</p>
            <p><strong>Daily Calories:</strong> {{ $data['calories'] }}</p>
            <p><strong>Daily Protein Target:</strong> {{ $data['protein'] }}g</p>
        </div>

        <div class="section">
            <h2>Meal Plan (Days 1–6)</h2>

            @foreach (range(1, 6) as $i)
                <h3>Day {{ $i }}</h3>
                @if (isset($data['meal_plan']['day' . $i]))
                    @foreach ($data['meal_plan']['day' . $i] as $mealType => $meal)
                        <div class="meal">
                            <h4>{{ ucfirst($mealType) }} <span class="badge">{{ $meal['name'] }}</span></h4>
                            <p>{{ $meal['description'] }}</p>
                            <div class="macro">
                                <strong>Calories:</strong> <span class="highlight">{{ $meal['calories'] }} kcal</span> |
                                <strong>Protein:</strong> <span class="highlight">{{ $meal['protein'] }}g</span> |
                                <strong>Carbs:</strong> <span class="highlight">{{ $meal['carbs'] }}g</span> |
                                <strong>Fats:</strong> <span class="highlight">{{ $meal['fats'] }}g</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No meal data available for this day.</p>
                @endif
            @endforeach
        </div>

        <div class="section">
            <h2>Day 7 – Freestyle</h2>
            <p>This is your <strong>freestyle day</strong> — feel free to enjoy your favorite healthy meals while
                staying mindful of your goals. Stay balanced and keep hydrated!</p>
        </div>

        <footer>
            Stay strong and consistent — you got this! 💪<br>
            <span>- Your Diet Coach</span>
        </footer>
    </div>
</body>

</html>
