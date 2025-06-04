import { Button } from "@/Components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Progress } from "@/Components/ui/progress"
import { Tabs, TabsList, TabsTrigger } from "@/Components/ui/tabs"
import { useToast } from "@/Components/ui/use-toast"
import { Head, router, useForm, usePage } from "@inertiajs/react"
import {
    ChevronDown,
    ChevronUp,
    CircleAlert,
    Loader2,
    Send,
    X,
} from "lucide-react"
import { useState } from "react"
import CountUp from "react-countup"

export default function Welcome() {
    const page = usePage<{
        flash: {
            data: {
                protein: number
                current_bodyfat: number
                calories: number
                goal_bodyfat: number
                meal_plan: {
                    recipe_name: string
                    calories: number
                    meal_type: "breakfast" | "lunch" | "diner" | "snack"
                }[]
            }
            guest_id: string
        }
    }>()
    const [open, setOpen] = useState(false)

    if (!page.props.flash.data) {
        router.replace("/")
    }
    console.log(page.props.flash.data)

    const form = useForm({
        email: "",
        protein: page.props.flash.data.protein,
        current_bodyfat: page.props.flash.data.current_bodyfat,
        calories: page.props.flash.data.calories,
        goal_bodyfat: page.props.flash.data.goal_bodyfat,
        meal_plan: page.props.flash.data.meal_plan,
        guest_id: page.props.flash.guest_id,
    })
    const { toast } = useToast()

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        if (form.data.email === "") {
            toast({
                title: "Error",
                description: "Email is required",
                variant: "destructive",
            })
            return
        }
        form.post(route("submit-email"), {
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Success",
                    description: "Email sent",
                })
                setOpen(false)
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "An error occurred",
                    variant: "destructive",
                })
                setOpen(false)
            },
        })
    }

    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
                <div className="grid gap-2">
                    <div className="flex flex-col">
                        <h2>Current Bodyfat %</h2>
                        <CountUp
                            duration={1}
                            className="font-bold text-2xl text-red-400"
                            end={page.props.flash.data.current_bodyfat}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Goal Bodyfat %</h2>
                        <CountUp
                            delay={0.5}
                            duration={1}
                            className="font-bold text-2xl text-blue-400"
                            end={page.props.flash.data.goal_bodyfat}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Maximum calories per day</h2>
                        <CountUp
                            delay={1}
                            duration={2}
                            className="font-bold text-2xl text-green-500"
                            end={page.props.flash.data.calories}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Protein goal per day</h2>
                        <CountUp
                            delay={1.5}
                            duration={1}
                            className="font-bold text-2xl text-purple-500"
                            end={page.props.flash.data.protein}
                        />
                    </div>
                    <p className="text-red-400 flex text-xs items-center max-w-xs font-bold">
                        <CircleAlert className="flex-shrink-0 mr-2" /> Note:
                        Bodyfat % is an approximation based on the data you
                        provided and may not be 100% accurate.
                    </p>
                    <Dialog open={open} onOpenChange={setOpen}>
                        <DialogTrigger asChild>
                            <Button className="mx-auto mt-10">
                                Show Example Diet
                            </Button>
                        </DialogTrigger>
                        <DietPlanModal
                            isOpen={open}
                            onClose={() => setOpen(false)}
                            currentBodyFat={
                                page.props.flash.data.current_bodyfat
                            }
                            goalBodyFat={page.props.flash.data.goal_bodyfat}
                            timeframe={3}
                        />
                    </Dialog>
                </div>
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
        <DialogContent className="max-w-md max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>
                    Here's a Sample Day Based on Your Goals
                </DialogTitle>
            </DialogHeader>

            <div className="space-y-4">
                <p className="text-gray-600 text-sm">
                    This is a personalized example diet plan based on your
                    current body fat ({currentBodyFat}%) and your goal body fat
                    ({goalBodyFat}%). Remember, this is just an example and you
                    should always consult a professional before starting a diet.
                </p>

                <div className="space-y-2">
                    <div className="flex justify-between text-sm text-gray-600">
                        <span>Current: {currentBodyFat}%</span>
                        <span>Goal: {goalBodyFat}%</span>
                    </div>
                    <Progress value={progressPercentage} className="h-2" />
                    <p className="text-sm text-center text-emerald-600 font-medium">
                        You're aiming to drop {currentBodyFat - goalBodyFat}%
                        body fat in {timeframe} months
                    </p>
                </div>

                <div className="flex space-x-2">
                    <Button
                        variant={showAlternatives ? "default" : "outline"}
                        size="sm"
                        onClick={() => setShowAlternatives(!showAlternatives)}
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

                <div className="space-y-3">
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
                                            (step: string, index: number) => (
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
                                                    Higher protein, lower carb
                                                    version
                                                </li>
                                                <li>
                                                    Vegetarian alternative with
                                                    same nutrition profile
                                                </li>
                                            </ul>
                                        </div>
                                    )}
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                <div className="bg-gray-50 p-4 rounded-lg">
                    <h3 className="font-medium mb-2">
                        Want your full 7-day plan, grocery list, and prep guide?
                    </h3>
                    <p className="text-sm text-gray-600 mb-4">
                        We'll send it right to your inbox, along with tips to
                        help you stay on track.
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
        </DialogContent>
    )
}
