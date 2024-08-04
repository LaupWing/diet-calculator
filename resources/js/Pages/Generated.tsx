import { Button } from "@/Components/ui/button"
import { Head, usePage } from "@inertiajs/react"
import CountUp from "react-countup"

export default function Welcome() {
    const page = usePage()
    console.log(page.props.errors)
    console.log(page.props.flash)

    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
                <div className="grid gap-2">
                    <div className="flex flex-col">
                        <h2>Current Bodyfat %</h2>
                        <CountUp
                            className="font-bold text-2xl text-red-400"
                            end={25}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Goal Bodyfat %</h2>
                        <CountUp
                            className="font-bold text-2xl text-blue-400"
                            end={15}
                        />
                    </div>
                    <div className="flex flex-col">
                        <h2>Maximum calories per day</h2>
                        <CountUp
                            className="font-bold text-2xl text-green-500"
                            end={1900}
                        />
                    </div>
                    <Button className="mx-auto mt-10">Show Example Diet</Button>
                </div>
            </div>
        </>
    )
}
