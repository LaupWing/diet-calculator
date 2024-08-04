import { Button } from "@/Components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog"
import { Head, router, usePage } from "@inertiajs/react"
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
        }
    }>()

    if (!page.props.flash.data) {
        router.replace("/")
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
                    <Dialog>
                        <DialogTrigger asChild>
                            <Button className="mx-auto mt-10">
                                Show Example Diet
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="max-w-[90%] rounded">
                            <DialogHeader>
                                <DialogTitle>Example Diet</DialogTitle>
                                <DialogDescription className="text-xs text-left">
                                    This is just an example diet plan based on
                                    your current bodyfat % and your goal bodyfat
                                    %. But remember, this is just an example and
                                    you should always consult a professional
                                    before starting a diet.
                                </DialogDescription>
                            </DialogHeader>
                            <ul className="grid gap-1 text-sm">
                                {page.props.flash.data.meal_plan.map((meal) => (
                                    <li
                                        key={meal.recipe_name}
                                        className="flex justify-between"
                                    >
                                        <span>{meal.recipe_name}</span>
                                        <span>{meal.calories} kcal</span>
                                    </li>
                                ))}
                            </ul>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>
        </>
    )
}
