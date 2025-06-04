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
import { useToast } from "@/Components/ui/use-toast"
import { Head, router, useForm, usePage } from "@inertiajs/react"
import { CircleAlert, Loader2 } from "lucide-react"
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
                        <DialogContent
                            onOpenAutoFocus={(el) => el.preventDefault()}
                            className="w-[90%] max-w-sm rounded"
                        >
                            {form.processing && (
                                <div className="inset-0 absolute text-blue-500 flex-col flex items-center px-6 z-[100] justify-center bg-white/90">
                                    <Loader2 className="animate-spin h-10 w-10  mx-auto" />
                                    <p className="text-center mt-2 text-sm font-bold">
                                        Pdf is generating and sending to your
                                        email!
                                    </p>
                                </div>
                            )}
                            <DialogHeader>
                                <DialogTitle>Example Diet</DialogTitle>
                                <DialogDescription className="text-xs text-left">
                                    <p>
                                        This is just an example diet plan based
                                        on your current bodyfat % and your goal
                                        bodyfat %. But remember, this is just an
                                        example and you should always consult a
                                        professional before starting a diet.
                                    </p>
                                    <p className="text-blue-500 mt-2 font-bold">
                                        In the email you will receive a pdf with
                                        instructions on how to prepare the meals
                                        and a grocery list.
                                    </p>
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
                            <form
                                onSubmit={handleSubmit}
                                className="mt-4 grid gap-2 text-sm"
                            >
                                <p>
                                    Want to receive a pdf with the info? Fill in
                                    your email below
                                </p>
                                <div className="grid gap-1">
                                    <Label>Email</Label>
                                    <Input
                                        required
                                        value={form.data.email}
                                        onChange={(e) =>
                                            form.setData(
                                                "email",
                                                e.target.value
                                            )
                                        }
                                        type="email"
                                    />
                                </div>
                                <Button disabled={form.processing}>Send</Button>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>
        </>
    )
}
