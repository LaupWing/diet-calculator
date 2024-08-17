<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
    <div>
        <h1>Hi, {{ $data['email'] }}</h1>
        <p>Here is your diet information:</p>
        <p>Calories: {{ $data['calories'] }}</p>
        <p>Current Bodyfat: {{ $data['current_bodyfat'] }}</p>
        <p>Goal Bodyfat: {{ $data['goal_bodyfat'] }}</p>
        <p>Protein: {{ $data['protein'] }}</p>
        <h2>Meals:</h2>
        @foreach ($data['meal_plan'] as $meal)
            <p>Meal: {{ $meal['meal_type'] }}</p>
            <p>Meal: {{ $meal['recipe_name'] }}</p>
            <p>Meal: {{ $meal['calories'] }}</p>
        @endforeach
    </div>
</div>
