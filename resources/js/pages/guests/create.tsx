import InputError from '@/components/input-error';
import { PageWrapper } from '@/components/page-wrapper';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, Link, useForm } from '@inertiajs/react';

function FormWrapper(props: { children: React.ReactNode }) {
    return <div className="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">{props.children}</div>;
}

export default function GuestsCreate() {
    const { data, setData, post, processing, errors, wasSuccessful } = useForm({
        firstname: '',
        lastname: '',
        email: '',
        street: '',
        house_number: '',
        postal_code: '',
        city: '',
        country: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('guests.store'), {
            preserveScroll: true,
        });
    }

    const breadcrumbs = [
        { title: 'Guests', href: route('guests.index') },
        { title: 'Create guest', href: route('guests.create') },
    ];

    const { trans } = useLingua();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.guests.create.title', {}, true)} />

            <PageWrapper title={trans('messages.guests.create.title')} subtitle={trans('messages.guests.create.subtitle')}>
                {wasSuccessful && (
                    <Alert variant="success">
                        <AlertTitle>{trans('messages.guests.create.success_message.title')}</AlertTitle>
                        <AlertDescription>{trans('messages.guests.create.success_message.description')}</AlertDescription>
                    </Alert>
                )}
                <FormWrapper>
                    <form onSubmit={submit} className="grid grid-cols-1 gap-6">
                        <div className="grid gap-2 sm:grid-cols-2 sm:gap-4">
                            <div className="grid gap-2">
                                <Label htmlFor="firstname">{trans('messages.guests.create.fields.firstname')}</Label>
                                <Input id="firstname" value={data.firstname} onChange={(e) => setData('firstname', e.target.value)} />
                                <InputError message={errors.firstname} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="lastname">{trans('messages.guests.create.fields.lastname')}</Label>
                                <Input id="lastname" value={data.lastname} onChange={(e) => setData('lastname', e.target.value)} />
                                <InputError message={errors.lastname} />
                            </div>
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email">{trans('messages.guests.create.fields.email')}</Label>
                            <Input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2 sm:grid-cols-3 sm:gap-4">
                            <div className="grid gap-2 sm:col-span-2">
                                <Label htmlFor="street">{trans('messages.guests.create.fields.street')}</Label>
                                <Input id="street" value={data.street} onChange={(e) => setData('street', e.target.value)} />
                                <InputError message={errors.street} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="house_number">{trans('messages.guests.create.fields.house_number')}</Label>
                                <Input id="house_number" value={data.house_number} onChange={(e) => setData('house_number', e.target.value)} />
                                <InputError message={errors.house_number} />
                            </div>
                        </div>

                        <div className="grid gap-2 sm:grid-cols-3 sm:gap-4">
                            <div className="grid gap-2">
                                <Label htmlFor="postal_code">{trans('messages.guests.create.fields.postal_code')}</Label>
                                <Input id="postal_code" value={data.postal_code} onChange={(e) => setData('postal_code', e.target.value)} />
                                <InputError message={errors.postal_code} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="city">{trans('messages.guests.create.fields.city')}</Label>
                                <Input id="city" value={data.city} onChange={(e) => setData('city', e.target.value)} />
                                <InputError message={errors.city} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="country">{trans('messages.guests.create.fields.country')}</Label>
                                <Input id="country" value={data.country} onChange={(e) => setData('country', e.target.value)} />
                                <InputError message={errors.country} />
                            </div>
                        </div>

                        <div className="flex items-center gap-2">
                            <Button type="submit" disabled={processing}>
                                {trans('messages.guests.create.buttons.create')}
                            </Button>
                            <span className="text-sm text-neutral-600">or</span>
                            <Link href={route('guests.index')} className="text-sm text-neutral-900 hover:underline">
                                {trans('messages.guests.create.buttons.cancel')}
                            </Link>
                        </div>
                    </form>
                </FormWrapper>
            </PageWrapper>
        </AppLayout>
    );
}
