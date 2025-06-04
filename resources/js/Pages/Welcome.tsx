import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Progress } from "@/Components/ui/progress"
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group"
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select"
import { Tabs, TabsList, TabsTrigger } from "@/Components/ui/tabs"
import { cn } from "@/lib/utils"
import { Head, useForm } from "@inertiajs/react"
import {
    ChevronDown,
    ChevronUp,
    LoaderCircle,
    Send,
    Sparkles,
    X,
} from "lucide-react"
import { useState } from "react"

type PhysicalActivityType =
    | "sedentary"
    | "lightly"
    | "moderately"
    | "very"
    | "extra"
    | null

type CuisineType =
    | "mediterranean"
    | "asian"
    | "american"
    | "middleEastern"
    | "latinAmerican"
    | "iLoveEverything"
    | null

type DietaryPreferenceType =
    | "vegetarian"
    | "vegan"
    | "carnivore"
    | "pescatarian"
    | "omnivore"
    | null

export default function Welcome() {
    const form = useForm<{
        gender: "male" | "female"
        age: number | null
        height: number | null
        weight: number | null
        activity: PhysicalActivityType
        goal_weight: number | null
        goal_months: number | null
        preferred_cuisine: CuisineType
        dietary_preference: DietaryPreferenceType
        unit: "lbs" | "kg"
    }>({
        gender: "male",
        age: null,
        height: null,
        weight: null,
        activity: null,
        goal_weight: null,
        preferred_cuisine: "iLoveEverything",
        dietary_preference: "omnivore",
        goal_months: null,
        unit: "lbs",
    })

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        form.post(route("generate"))
    }
    console.log(form.errors)

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen w-screen py-10 flex flex-col gap-6 items-center justify-center relative">
                {form.processing && (
                    <div className="absolute flex items-center justify-center inset-0 bg-background/80 z-50">
                        <LoaderCircle size={40} className="animate-spin" />
                    </div>
                )}
                <form
                    onSubmit={handleSubmit}
                    className="grid gap-10 max-w-[300px]"
                >
                    <div className="grid gap-3">
                        <h2 className="uppercase font-bold text-sm text-slate-300">
                            Info
                        </h2>
                        <RadioGroup
                            className="grid grid-cols-2"
                            defaultValue="male"
                            value={form.data.gender}
                            onValueChange={(e) =>
                                form.setData("gender", e as "female" | "male")
                            }
                        >
                            <div className="flex items-center space-x-2">
                                <RadioGroupItem value="male" id="male" />
                                <Label htmlFor="male">Male</Label>
                            </div>
                            <div className="flex items-center space-x-2">
                                <RadioGroupItem value="female" id="female" />
                                <Label htmlFor="female">Female</Label>
                            </div>
                        </RadioGroup>

                        <div className="grid gap-2 grid-cols-2">
                            <div className="grid gap-1 items-start">
                                <Label
                                    className={
                                        form.errors.age && "text-red-400"
                                    }
                                >
                                    Age
                                </Label>
                                <Input
                                    value={form.data.age || ""}
                                    onChange={(e) =>
                                        form.setData("age", +e.target.value)
                                    }
                                    className={cn(
                                        "w-36",
                                        form.errors.age && "border-red-400"
                                    )}
                                    type="number"
                                />
                            </div>
                            <div className="grid gap-1 items-start">
                                <Label
                                    className={
                                        form.errors.height && "text-red-400"
                                    }
                                >
                                    Height (cm)
                                </Label>
                                <Input
                                    onChange={(e) =>
                                        form.setData("height", +e.target.value)
                                    }
                                    value={form.data.height || ""}
                                    className={cn(
                                        "w-36",
                                        form.errors.height && "border-red-400"
                                    )}
                                    type="number"
                                />
                            </div>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={form.errors.weight && "text-red-400"}
                            >
                                Current Weight
                            </Label>
                            <div className="flex gap-2">
                                <Input
                                    value={form.data.weight || ""}
                                    onChange={(e) =>
                                        form.setData("weight", +e.target.value)
                                    }
                                    className={cn(
                                        "w-36",
                                        form.errors.weight && "border-red-400"
                                    )}
                                    type="number"
                                />
                                <Select
                                    value={form.data.unit}
                                    onValueChange={(e) =>
                                        form.setData("unit", e as "lbs" | "kg")
                                    }
                                    defaultValue={form.data.unit}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Weight" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="kg">KG</SelectItem>
                                        <SelectItem value="lbs">LBS</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={
                                    form.errors.activity && "text-red-400"
                                }
                            >
                                Physical Activity
                            </Label>
                            <Select
                                value={form.data.activity || ""}
                                onValueChange={(e) =>
                                    form.setData(
                                        "activity",
                                        e as PhysicalActivityType
                                    )
                                }
                                defaultValue={form.data.unit}
                            >
                                <SelectTrigger
                                    className={
                                        form.errors.activity && "border-red-400"
                                    }
                                >
                                    <SelectValue placeholder="Physical Activity" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="sedentary">
                                        Sedentary: Little or no exercise.
                                    </SelectItem>
                                    <SelectItem value="lightly">
                                        Lightly: Light exercise 1-3 days a week.
                                    </SelectItem>
                                    <SelectItem value="moderately">
                                        Moderately: Moderate 3-5 days a week.
                                    </SelectItem>
                                    <SelectItem value="very">
                                        Very: Hard exercise 6-7 days a week.
                                    </SelectItem>
                                    <SelectItem value="extra">
                                        Extra: Very hard exercise or physical
                                        job.
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={
                                    form.errors.dietary_preference &&
                                    "text-red-400"
                                }
                            >
                                Dietary Preference
                            </Label>
                            <Select
                                value={form.data.dietary_preference || ""}
                                onValueChange={(e) =>
                                    form.setData(
                                        "dietary_preference",
                                        e as DietaryPreferenceType
                                    )
                                }
                                defaultValue={form.data.dietary_preference!}
                            >
                                <SelectTrigger
                                    className={
                                        form.errors.dietary_preference &&
                                        "border-red-400"
                                    }
                                >
                                    <SelectValue placeholder="Select a Dietary Preference" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="vegetarian">
                                        Vegetarian: No meat, may include dairy
                                        and eggs.
                                    </SelectItem>
                                    <SelectItem value="vegan">
                                        Vegan: No animal products.
                                    </SelectItem>
                                    <SelectItem value="carnivore">
                                        Carnivore: Primarily meat-based.
                                    </SelectItem>
                                    <SelectItem value="pescatarian">
                                        Pescatarian: No meat except fish.
                                    </SelectItem>
                                    <SelectItem value="omnivore">
                                        Omnivore: No dietary restrictions.
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={
                                    form.errors.preferred_cuisine &&
                                    "text-red-400"
                                }
                            >
                                Preferred Cuisine
                            </Label>
                            <Select
                                value={form.data.preferred_cuisine || ""}
                                onValueChange={(e) =>
                                    form.setData(
                                        "preferred_cuisine",
                                        e as CuisineType
                                    )
                                }
                                defaultValue={form.data.preferred_cuisine!}
                            >
                                <SelectTrigger
                                    className={
                                        form.errors.preferred_cuisine &&
                                        "border-red-400"
                                    }
                                >
                                    <SelectValue placeholder="Select a Cuisine" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="mediterranean">
                                        Mediterranean
                                    </SelectItem>
                                    <SelectItem value="asian">Asian</SelectItem>
                                    <SelectItem value="american">
                                        American
                                    </SelectItem>
                                    <SelectItem value="middleEastern">
                                        Middle Eastern
                                    </SelectItem>
                                    <SelectItem value="latinAmerican">
                                        Latin American
                                    </SelectItem>
                                    <SelectItem value="iLoveEverything">
                                        I love every cuisine!
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <div className="grid gap-3">
                        <h2 className="uppercase font-bold text-sm text-slate-300">
                            Goal
                        </h2>
                        <p className="text-xs">
                            Please be realistic with your goals. A healthy
                            weight loss is 1lbs or 0.5kg per week.
                        </p>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={
                                    form.errors.goal_weight && "text-red-400"
                                }
                            >
                                Desired Weight
                            </Label>
                            <div className="flex gap-2">
                                <Input
                                    value={form.data.goal_weight || ""}
                                    onChange={(e) => {
                                        form.setData(
                                            "goal_weight",
                                            +e.target.value
                                        )
                                    }}
                                    className={cn(
                                        "w-36",
                                        form.errors.goal_weight &&
                                            "border-red-400"
                                    )}
                                    type="number"
                                />
                                <Select
                                    value={form.data.unit}
                                    onValueChange={(e) =>
                                        form.setData("unit", e as "lbs" | "kg")
                                    }
                                    defaultValue={form.data.unit}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Weight" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="kg">KG</SelectItem>
                                        <SelectItem value="lbs">LBS</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label
                                className={
                                    form.errors.goal_months && "text-red-400"
                                }
                            >
                                Total months to achieve goal
                            </Label>
                            <Input
                                onChange={(e) => {
                                    form.setData("goal_months", +e.target.value)
                                }}
                                className={cn(
                                    "w-36",
                                    form.errors.goal_months && "border-red-400"
                                )}
                                value={form.data.goal_months || ""}
                                type="number"
                            />
                        </div>
                    </div>
                    <Button className="flex gap-2">
                        Generate <Sparkles size={18} />
                    </Button>
                </form>
            </div>
        </>
    )
}

interface Meal {
    name: string
    calories: number
    protein: number
    carbs: number
    fats: number
    instructions: string[]
}

interface DayPlan {
    breakfast: Meal
    lunch: Meal
    dinner: Meal
    snack: Meal
}

const SAMPLE_PLAN: Record<string, DayPlan> = {
    day1: {
        breakfast: {
            name: "Oatmeal w/ Banana & Almonds",
            calories: 300,
            protein: 8,
            carbs: 40,
            fats: 10,
            instructions: [
                "Cook 1/2 cup oats with water or almond milk",
                "Top with sliced banana and 10 almonds",
                "Add a drizzle of honey if desired",
            ],
        },
        lunch: {
            name: "Grilled Chicken Shawarma",
            calories: 400,
            protein: 35,
            carbs: 25,
            fats: 15,
            instructions: [
                "Marinate chicken in shawarma spices for 30 minutes",
                "Grill until cooked through (165°F internal temp)",
                "Serve with a small pita and fresh vegetables",
            ],
        },
        dinner: {
            name: "Middle Eastern Lamb Chops",
            calories: 500,
            protein: 40,
            carbs: 15,
            fats: 30,
            instructions: [
                "Season lamb chops with herbs and spices",
                "Grill or pan-sear to desired doneness",
                "Serve with a side of roasted vegetables",
            ],
        },
        snack: {
            name: "Greek Yogurt with Berries",
            calories: 200,
            protein: 15,
            carbs: 20,
            fats: 5,
            instructions: [
                "Use 1 cup of plain Greek yogurt",
                "Add 1/2 cup mixed berries",
                "Optional: sprinkle with cinnamon",
            ],
        },
    },
    day2: {
        breakfast: {
            name: "Protein Smoothie Bowl",
            calories: 320,
            protein: 25,
            carbs: 35,
            fats: 8,
            instructions: [
                "Blend protein powder, frozen berries, and almond milk",
                "Pour into bowl and top with granola and sliced fruit",
                "Add chia seeds for extra nutrition",
            ],
        },
        lunch: {
            name: "Beef Kebabs with Quinoa",
            calories: 450,
            protein: 30,
            carbs: 30,
            fats: 20,
            instructions: [
                "Marinate beef chunks in olive oil and herbs for 1 hour",
                "Skewer with vegetables (bell peppers, onions)",
                "Grill for 10-12 minutes and serve over 1/2 cup cooked quinoa",
            ],
        },
        dinner: {
            name: "Baked Salmon with Asparagus",
            calories: 480,
            protein: 35,
            carbs: 10,
            fats: 30,
            instructions: [
                "Season salmon fillet with lemon and dill",
                "Bake at 375°F for 15-20 minutes",
                "Serve with roasted asparagus drizzled with olive oil",
            ],
        },
        snack: {
            name: "Hummus with Vegetable Sticks",
            calories: 180,
            protein: 6,
            carbs: 15,
            fats: 10,
            instructions: [
                "Use 1/4 cup hummus as dip",
                "Cut carrot, cucumber, and bell pepper into sticks",
                "Add a sprinkle of paprika on top of hummus",
            ],
        },
    },
}

interface DietPlanModalProps {
    isOpen: boolean
    onClose: () => void
    currentBodyFat: number
    goalBodyFat: number
    timeframe: number // in months
}

function DietPlanModal({
    isOpen,
    onClose,
    currentBodyFat = 25,
    goalBodyFat = 15,
    timeframe = 3,
}: DietPlanModalProps) {
    const [email, setEmail] = useState("")
    const [expandedMeal, setExpandedMeal] = useState<string | null>(null)
    const [currentDay, setCurrentDay] = useState("day1")
    const [showAlternatives, setShowAlternatives] = useState(false)
    const [isSubmitting, setIsSubmitting] = useState(false)

    if (!isOpen) return null

    const toggleMealExpansion = (mealType: string) => {
        if (expandedMeal === mealType) {
            setExpandedMeal(null)
        } else {
            setExpandedMeal(mealType)
        }
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        setIsSubmitting(true)

        // Simulate sending email
        setTimeout(() => {
            setIsSubmitting(false)
            // Here you would typically handle the success state
            // For now we'll just close the modal
            onClose()
        }, 1500)
    }

    const dayPlan = SAMPLE_PLAN[currentDay]
    const progressPercentage = Math.round(
        ((currentBodyFat - goalBodyFat) / currentBodyFat) * 100
    )

    return (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div className="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div className="p-4 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                    <h2 className="text-xl font-bold">
                        Here's a Sample Day Based on Your Goals
                    </h2>
                    <button
                        onClick={onClose}
                        className="text-gray-500 hover:text-gray-700"
                    >
                        <X size={20} />
                    </button>
                </div>

                <div className="p-4">
                    <p className="text-gray-600 mb-4">
                        This is a personalized example diet plan based on your
                        current body fat ({currentBodyFat}%) and your goal body
                        fat ({goalBodyFat}%). Remember, this is just an example
                        and you should always consult a professional before
                        starting a diet.
                    </p>

                    <div className="mb-6 space-y-2">
                        <div className="flex justify-between text-sm text-gray-600">
                            <span>Current: {currentBodyFat}%</span>
                            <span>Goal: {goalBodyFat}%</span>
                        </div>
                        <Progress value={progressPercentage} className="h-2" />
                        <p className="text-sm text-center text-emerald-600 font-medium">
                            You're aiming to drop {currentBodyFat - goalBodyFat}
                            % body fat in {timeframe} months
                        </p>
                    </div>

                    <div className="mb-4 flex space-x-2">
                        <Button
                            variant={showAlternatives ? "default" : "outline"}
                            size="sm"
                            onClick={() =>
                                setShowAlternatives(!showAlternatives)
                            }
                            className="text-xs"
                        >
                            Show Alternatives
                        </Button>

                        <Tabs defaultValue="day1" className="flex-1">
                            <TabsList className="grid grid-cols-2">
                                <TabsTrigger
                                    value="day1"
                                    onClick={() => setCurrentDay("day1")}
                                >
                                    Day 1
                                </TabsTrigger>
                                <TabsTrigger
                                    value="day2"
                                    onClick={() => setCurrentDay("day2")}
                                >
                                    Day 2
                                </TabsTrigger>
                            </TabsList>
                        </Tabs>
                    </div>

                    <div className="space-y-4 mb-6">
                        {Object.entries(dayPlan).map(([mealType, meal]) => (
                            <div
                                key={mealType}
                                className="border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors"
                                onClick={() => toggleMealExpansion(mealType)}
                            >
                                <div className="flex justify-between items-center">
                                    <div>
                                        <div className="flex items-center gap-2">
                                            {mealType === "breakfast" && (
                                                <span>🥣</span>
                                            )}
                                            {mealType === "lunch" && (
                                                <span>🍲</span>
                                            )}
                                            {mealType === "dinner" && (
                                                <span>🍽️</span>
                                            )}
                                            {mealType === "snack" && (
                                                <span>🥪</span>
                                            )}
                                            <h3 className="font-medium capitalize">
                                                {mealType}: {meal.name}
                                            </h3>
                                        </div>
                                        <div className="text-sm text-gray-600 mt-1">
                                            Calories: {meal.calories} kcal |
                                            Protein: {meal.protein}g | Carbs:{" "}
                                            {meal.carbs}g | Fats: {meal.fats}g
                                        </div>
                                    </div>
                                    <div>
                                        {expandedMeal === mealType ? (
                                            <ChevronUp size={18} />
                                        ) : (
                                            <ChevronDown size={18} />
                                        )}
                                    </div>
                                </div>

                                {expandedMeal === mealType && (
                                    <div className="mt-3 pt-3 border-t text-sm">
                                        <h4 className="font-medium mb-2">
                                            Instructions:
                                        </h4>
                                        <ol className="list-decimal pl-5 space-y-1 text-gray-600">
                                            {meal.instructions.map(
                                                (
                                                    step: string,
                                                    index: number
                                                ) => (
                                                    <li key={index}>{step}</li>
                                                )
                                            )}
                                        </ol>

                                        {showAlternatives && (
                                            <div className="mt-3 pt-3 border-t">
                                                <h4 className="font-medium mb-2">
                                                    Alternative Options:
                                                </h4>
                                                <ul className="list-disc pl-5 space-y-1 text-gray-600">
                                                    <li>
                                                        Similar macros with
                                                        Mediterranean flavors
                                                    </li>
                                                    <li>
                                                        Higher protein, lower
                                                        carb version
                                                    </li>
                                                    <li>
                                                        Vegetarian alternative
                                                        with same nutrition
                                                        profile
                                                    </li>
                                                </ul>
                                            </div>
                                        )}
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>

                    <div className="bg-gray-50 p-4 rounded-lg mb-4">
                        <h3 className="font-medium mb-2">
                            Want your full 7-day plan, grocery list, and prep
                            guide?
                        </h3>
                        <p className="text-sm text-gray-600 mb-4">
                            We'll send it right to your inbox, along with tips
                            to help you stay on track.
                        </p>

                        <form onSubmit={handleSubmit}>
                            <div className="space-y-3">
                                <Input
                                    type="email"
                                    placeholder="Enter your email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    required
                                    className="w-full"
                                />
                                <Button
                                    type="submit"
                                    className="w-full"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? (
                                        <span className="flex items-center gap-2">
                                            <div className="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                                            Sending...
                                        </span>
                                    ) : (
                                        <span className="flex items-center gap-2">
                                            <Send size={16} />
                                            Send My Plan
                                        </span>
                                    )}
                                </Button>
                            </div>
                        </form>
                    </div>

                    <p className="text-center text-sm text-emerald-600 font-medium">
                        Day 1 of your transformation starts here!
                    </p>
                </div>
            </div>
        </div>
    )
}
