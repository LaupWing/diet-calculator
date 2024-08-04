import { Head, usePage } from "@inertiajs/react"

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
                        <h2>Maximum calories per day</h2>
                        <p>{page.props.flash.data.calories}</p>
                    </div>
                </div>
            </div>
        </>
    )
}
