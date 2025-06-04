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
    Loader,
    Loader2,
    Send,
    X,
} from "lucide-react"
import { useState } from "react"
import CountUp from "react-countup"

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

export default function Welcome() {
    const page = usePage<{
        flash: {
            data: {
                protein: number
                current_bodyfat: number
                calories: number
                goal_bodyfat: number
                meal_plan: {
                    day1: DayPlan
                    day2: DayPlan
                }
                months: number
            }
            guest_id: string
        }
    }>()
    const [open, setOpen] = useState(false)
    const [isSubmitting, setIsSubmitting] = useState(false)

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
        months: page.props.flash.data.months,
    })
    const { toast } = useToast()

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        if (form.data.email === "") {
            toast({
                title: "Error",
                description: "Email is required",
                variant: "destructive",
            })
            return
        }
        setIsSubmitting(true)
        form.post(route("submit-email"), {
            preserveState: true,
            onSuccess: () => {
                setIsSubmitting(false)
                toast({
                    title: "Success",
                    description: "Email sent",
                })
                setOpen(false)
            },
            onError: () => {
                setIsSubmitting(false)
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
                            isSubmitting={isSubmitting}
                            handleSubmit={handleSubmit}
                            mealPlan={page.props.flash.data.meal_plan}
                            months={page.props.flash.data.months}
                            form={form}
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

interface DietPlanModalProps {
    months: number
    currentBodyFat: number
    goalBodyFat: number
    timeframe: number // in months
    mealPlan: Record<string, DayPlan>
    form: any
    handleSubmit: (e: React.FormEvent) => void
    isSubmitting: boolean
}

function DietPlanModal({
    months,
    currentBodyFat,
    goalBodyFat,
    mealPlan,
    handleSubmit,
    form,
    isSubmitting,
}: DietPlanModalProps) {
    const [expandedMeal, setExpandedMeal] = useState<string | null>(null)
    const [currentDay, setCurrentDay] = useState("day1")

    const toggleMealExpansion = (mealType: string) => {
        if (expandedMeal === mealType) {
            setExpandedMeal(null)
        } else {
            setExpandedMeal(mealType)
        }
    }

    const dayPlan = mealPlan[currentDay]
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
            {isSubmitting && (
                <div className="absolute z-50 flex-col inset-0 bg-white bg-opacity-50 backdrop-blur-sm flex items-center justify-center gap-2">
                    <Loader
                        className="animate-spin h-8 w-8 text-blue-500"
                        size={32}
                    />
                    <p className="animate-pulse">
                        Sending your personalize meal plan...
                    </p>
                </div>
            )}
            <div className="space-y-4">
                <div className="space-y-2">
                    <div className="flex justify-between text-sm text-gray-600">
                        <span>Current: {currentBodyFat}%</span>
                        <span>Goal: {goalBodyFat}%</span>
                    </div>
                    <Progress value={progressPercentage} className="h-2" />
                    <p className="text-sm text-center text-emerald-600 font-medium">
                        You're aiming to drop {currentBodyFat - goalBodyFat}%
                        body fat in {months} months
                    </p>
                </div>
                <div className="bg-gray-50 p-4 border rounded-lg">
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
                                value={form.email}
                                onChange={(e) =>
                                    form.setData("email", e.target.value)
                                }
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
                <div className="flex space-x-2">
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
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </DialogContent>
    )
}
