import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select"
import { Head } from "@inertiajs/react"
import { useState } from "react"

export default function Welcome() {
    const [unit, setUnit] = useState<"lbs" | "kg">("lbs")

    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
                <div className="grid gap-10">
                    <div className="grid gap-3">
                        <h2 className="uppercase font-bold text-sm text-slate-300">
                            Info
                        </h2>
                        <div className="grid gap-2 grid-cols-2">
                            <div className="grid gap-1 items-start">
                                <Label>Age</Label>
                                <Input className="w-36" type="number" />
                            </div>
                            <div className="grid gap-1 items-start">
                                <Label>Height</Label>
                                <Input className="w-36" type="number" />
                            </div>
                        </div>
                        <div className="grid gap-1 items-start">
                            <Label>Current Weight</Label>
                            <div className="flex gap-2">
                                <Input className="w-36" type="number" />
                                <Select
                                    value={unit}
                                    onValueChange={(e) =>
                                        setUnit(e as "lbs" | "kg")
                                    }
                                    defaultValue={unit}
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
                            <Label>Physical Activity</Label>
                            <Select
                                value={unit}
                                onValueChange={(e) =>
                                    setUnit(e as "lbs" | "kg")
                                }
                                defaultValue={unit}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Physical Activity" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="sedentary">
                                        Sedentary: Little or no exercise.
                                    </SelectItem>
                                    <SelectItem value="lightly">
                                        Lightly active: Light exercise 1-3 days
                                        a week.
                                    </SelectItem>
                                    <SelectItem value="moderately">
                                        Moderately active: Moderate exercise 3-5
                                        days a week.
                                    </SelectItem>
                                    <SelectItem value="very">
                                        Very active: Hard exercise 6-7 days a
                                        week.
                                    </SelectItem>
                                    <SelectItem value="extra">
                                        Extra active: Very hard exercise or
                                        physical job.
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <div className="grid gap-3">
                        <h2 className="uppercase font-bold text-sm text-slate-300">
                            Goal
                        </h2>
                        <div className="grid gap-1 items-start">
                            <Label>Desired Weight</Label>
                            <div className="flex gap-2">
                                <Input className="w-36" type="number" />
                                <Select
                                    value={unit}
                                    onValueChange={(e) =>
                                        setUnit(e as "lbs" | "kg")
                                    }
                                    defaultValue={unit}
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
                            <Label>Total months to achieve goal</Label>
                            <Input className="w-36" type="number" />
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
