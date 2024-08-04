import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group"
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select"
import { cn } from "@/lib/utils"
import { Head, useForm, usePage } from "@inertiajs/react"
import { LoaderCircle, Sparkles } from "lucide-react"

export default function Welcome() {
    const form = useForm<{
        gender: "male" | "female"
        age: number | null
        height: number | null
        weight: number | null
        activity:
            | "sedentary"
            | "lightly"
            | "moderately"
            | "very"
            | "extra"
            | null
        goal_weight: number | null
        goal_months: number | null
        unit: "lbs" | "kg"
    }>({
        gender: "male",
        age: null,
        height: null,
        weight: null,
        activity: null,
        goal_weight: null,
        goal_months: null,
        unit: "lbs",
    })
    const page = usePage()
    console.log(page.props.errors)
    console.log(page.props.flash)

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault()
        form.post(route("generate"))
    }

    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
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
                                        e as
                                            | "sedentary"
                                            | "lightly"
                                            | "moderately"
                                            | "very"
                                            | "extra"
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
