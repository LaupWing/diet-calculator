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
                <div className="grid gap-4">
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
                        <Label>Desired months to achieve goal</Label>
                        <Input className="w-36" type="number" />
                    </div>
                </div>
            </div>
        </>
    )
}
