import { Input } from "@/Components/ui/input"
import { Head } from "@inertiajs/react"

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex items-center justify-center">
                <div className="grid gap-1">
                    <Input type="number" />
                </div>
            </div>
        </>
    )
}
