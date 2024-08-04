import { Button } from "@/Components/ui/button"
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
                            className="font-bold text-2xl text-red-400"
                            end={page.props.flash.data.current_bodyfat}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Goal Bodyfat %</h2>
                        <CountUp
                            className="font-bold text-2xl text-blue-400"
                            end={page.props.flash.data.goal_bodyfat}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Maximum calories per day</h2>
                        <CountUp
                            className="font-bold text-2xl text-green-500"
                            end={page.props.flash.data.calories}
                        />
                    </div>
                    <Button className="mx-auto mt-10">Show Example Diet</Button>
                </div>
            </div>
        </>
    )
}
