function FormWrapper(props: { children: React.ReactNode; title?: string; subtitle?: string }) {
    return (
        <div className="rounded-lg border border-neutral-200 bg-white px-6 py-4 shadow-sm">
            <div className="mt-2 mb-4 flex-1">
                <h1 className="text-xl font-bold">{props.title}</h1>
                {props.subtitle && <p className="text-sm text-gray-500">{props.subtitle}</p>}
            </div>
            {props.children}
        </div>
    );
}

export default FormWrapper;
