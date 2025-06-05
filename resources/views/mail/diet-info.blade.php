<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div
        style="max-width: 700px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
        <h1 style="font-size: 24px; color: #2c3e50; text-align: center;">Hi, {{ $dietInfo['email'] }}</h1>
        <p style="font-size: 16px; text-align: center; margin-top: -10px;">Here is your personalized diet plan:</p>

        {{-- User Profile Section --}}
        <div
            style="margin-top: 20px; padding: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background-color: #ffffff;">
            <h2 style="font-size: 18px; color: #2c3e50; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">Your
                Profile</h2>
            <p><strong>Age:</strong> {{ $dietInfo['age'] }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($dietInfo['gender']) }}</p>
            <p><strong>Height:</strong> {{ $dietInfo['height'] }} cm</p>
            <p><strong>Weight:</strong> {{ $dietInfo['weight'] }} {{ $dietInfo['unit'] }}</p>
            <p><strong>Activity Level:</strong> {{ ucfirst($dietInfo['activity']) }}</p>
            <p><strong>Diet Preference:</strong> {{ ucfirst($dietInfo['dietary_preference']) }}</p>
            <p><strong>Preferred Cuisine:</strong> {{ $dietInfo['preferred_cuisine'] }}</p>
        </div>

        {{-- Diet Summary Section --}}
        <div
            style="margin-top: 20px; padding: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background-color: #ffffff;">
            <h2 style="font-size: 18px; color: #2c3e50; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">Diet
                Summary</h2>
            <p><strong>Calories:</strong> {{ $dietInfo['calories'] }} kcal</p>
            <p><strong>Protein:</strong> {{ $dietInfo['protein'] }} g</p>
            <p><strong>Current Bodyfat:</strong> {{ $dietInfo['current_bodyfat'] }}%</p>
            <p><strong>Goal Bodyfat:</strong> {{ $dietInfo['goal_bodyfat'] }}%</p>
            <p><strong>Goal Weight:</strong> {{ $dietInfo['goal_weight'] }} {{ $dietInfo['unit'] }}</p>
            <p><strong>Goal Timeline:</strong> {{ $dietInfo['goal_months'] }} months</p>
        </div>

        {{-- Meal Plan Section --}}
        <div style="margin-top: 30px;">
            <h2
                style="font-size: 18px; color: #2c3e50; text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">
                Meal Plan (Day 1 & Day 2)</h2>

            @foreach ($dietInfo['meal_plan'] as $day => $meals)
                <div style="margin-bottom: 20px; padding: 10px; background-color: #eef2f3; border-radius: 6px;">
                    <h3 style="font-size: 16px; color: #2c3e50;">{{ ucfirst($day) }}</h3>

                    @foreach ($meals as $type => $meal)
                        <div
                            style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #ffffff;">
                            <h4 style="margin-bottom: 5px;">{{ ucfirst($type) }} - {{ $meal['name'] }}</h4>
                            <p><strong>Calories:</strong> {{ $meal['calories'] }} kcal</p>
                            <p><strong>Protein:</strong> {{ $meal['protein'] }} g</p>
                            <p><strong>Carbs:</strong> {{ $meal['carbs'] }} g</p>
                            <p><strong>Fats:</strong> {{ $meal['fats'] }} g</p>
                            <p><strong>Instructions:</strong></p>
                            <ol style="padding-left: 20px;">
                                @foreach ($meal['instructions'] as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <footer style="margin-top: 20px; text-align: center; font-size: 12px; color: #7f8c8d;">
            <p>Stay consistent and achieve your goals! 💪</p>
            <p style="margin-top: -5px;">– Loc Nguyen</p>
        </footer>
    </div>
</div>
