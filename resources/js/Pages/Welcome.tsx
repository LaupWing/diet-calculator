import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Head } from "@inertiajs/react"

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
                <div className="grid gap-4">
                    <div className="grid gap-1 items-start">
                        <Label>Current Weight</Label>
                        <Input className="w-36" type="number" />
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
