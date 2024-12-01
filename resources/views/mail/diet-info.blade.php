<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div
        style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
        <h1 style="font-size: 24px; color: #2c3e50; text-align: center;">Hi, {{ $dietInfo['email'] }}</h1>
        <p style="font-size: 16px; text-align: center; margin-top: -10px;">Here is your personalized diet information:
        </p>

        <div
            style="margin-top: 20px; padding: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background-color: #ffffff;">
            <h2 style="font-size: 18px; color: #2c3e50; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">Diet
                Summary</h2>
            <p><strong>Calories:</strong> {{ $dietInfo['calories'] }}</p>
            <p><strong>Current Bodyfat:</strong> {{ $dietInfo['current_bodyfat'] }}%</p>
            <p><strong>Goal Bodyfat:</strong> {{ $dietInfo['goal_bodyfat'] }}%</p>
            <p><strong>Protein:</strong> {{ $dietInfo['protein'] }}g</p>
        </div>

        <div style="margin-top: 20px;">
            <h2
                style="font-size: 18px; color: #2c3e50; text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">
                Meal Plan</h2>
            @foreach ($dietInfo['meal_plan'] as $meal)
                <div
                    style="margin-bottom: 15px; padding: 10px; border: 1px solid #e1e1e1; border-radius: 6px; background-color: #ffffff;">
                    <h3 style="font-size: 16px; color: #2c3e50; margin-bottom: 5px;">{{ ucfirst($meal['meal_type']) }}
                    </h3>
                    <p style="margin: 0;"><strong>Dish:</strong> {{ $meal['recipe_name'] }}</p>
                    <p style="margin: 0;"><strong>Calories:</strong> {{ $meal['calories'] }}</p>
                </div>
            @endforeach
        </div>

        <footer style="margin-top: 20px; text-align: center; font-size: 12px; color: #7f8c8d;">
            <p>Stay consistent and achieve your goals! 💪</p>
            <p style="margin-top: -5px;">- Your Diet Coach</p>
        </footer>
    </div>
</div>
