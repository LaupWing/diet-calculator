import { Head, useForm, usePage } from "@inertiajs/react"

export default function Welcome() {
    const page = usePage()
    console.log(page.props.errors)
    console.log(page.props.flash)

    return (
        <>
            <Head title="Welcome" />
            <div className="h-screen w-screen flex flex-col gap-6 items-center justify-center">
                <div>Showing results</div>
            </div>
        </>
    )
}
