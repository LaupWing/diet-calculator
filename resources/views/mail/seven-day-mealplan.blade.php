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
        <h1>Hello, {{ $userInfo['email'] }}</h1>
        <p>Here is your personalized weekly meal plan along with your health data and fitness goals.</p>

        <div class="section">
            <h2>User Info</h2>
            <p><strong>Age:</strong> {{ $userInfo['age'] }}</p>
            <p><strong>Height:</strong> {{ $userInfo['height'] }} {{ $userInfo['unit'] }}</p>
            <p><strong>Weight:</strong> {{ $userInfo['weight'] }} {{ $userInfo['unit'] }}</p>
            <p><strong>Activity Level:</strong> {{ ucfirst($userInfo['activity']) }}</p>
            <p><strong>Preferred Cuisine:</strong> {{ $userInfo['preferred_cuisine'] }}</p>
            <p><strong>Dietary Preference:</strong> {{ ucfirst($userInfo['dietary_preference']) }}</p>
            <p><strong>Current Body Fat:</strong> {{ $userInfo['current_bodyfat'] }}%</p>
            <p><strong>Goal Body Fat:</strong> {{ $userInfo['goal_bodyfat'] }}%</p>
            <p><strong>Goal Weight:</strong> {{ $userInfo['goal_weight'] }} {{ $userInfo['unit'] }}
            </p>
            <p><strong>Goal Duration:</strong> {{ $userInfo['goal_months'] }} months</p>
            <p><strong>Daily Calories:</strong> {{ $userInfo['calories'] }}</p>
            <p><strong>Daily Protein Target:</strong> {{ $userInfo['protein'] }}g</p>
        </div>
        <div
            style="background-color: #e9f7ef; padding: 20px; border-left: 6px solid #16a085; border-radius: 8px; margin-bottom: 30px;">
            <h2 style="margin-top: 0; color: #16a085;">🔥 My Personal Fat Loss Strategy That Changed Everything</h2>
            <p style="margin-bottom: 10px;">
                I went from feeling stuck and frustrated with my body to being <strong>lean year-round</strong> —
                without starving, without cardio hell, and without giving up the foods I love.
            </p>
            <p style="margin-bottom: 10px;">
                The <strong>Slingshot Diet</strong> is the exact method I used — and it’s now helping others do the
                same.
            </p>
            <p style="margin-bottom: 10px;">
                If you’re tired of endless dieting, inconsistent results, and gaining all the weight back...
                this strategy is for you.
            </p>
            <p>
                👉 <a href="https://slingshot.loc-ng.com/"
                    style="color: #16a085; font-weight: bold; text-decoration: underline;">Get The Slingshot Diet
                    Now</a> — and finally lose fat the sustainable way.
            </p>
        </div>

        <div class="section">
            <h2>Meal Plan (Days 1–6)</h2>

            @foreach (range(1, 6) as $i)
                <h3>Day {{ $i }}</h3>
                @if (isset($sevenDayMealplan['day' . $i]))
                    @foreach ($sevenDayMealplan['day' . $i] as $mealType => $meal)
                        <div class="meal">
                            <h4>{{ ucfirst($meal['meal_type']) }} <span class="badge">{{ $meal['name'] }}</span></h4>
                            <p>{{ $meal['description'] }}</p>
                            <div class="macro">
                                <strong>Calories:</strong> <span class="highlight">{{ $meal['calories'] }} kcal</span>
                                |
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
