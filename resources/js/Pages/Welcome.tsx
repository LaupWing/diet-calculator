import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Head } from "@inertiajs/react"

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex items-center justify-center">
                <div className="grid gap-1 justify-start">
                    <Label>
                        In how many months you want to achieve your goal
                    </Label>
                    <Input type="number" />
                </div>
            </div>
        </>
    )
}
