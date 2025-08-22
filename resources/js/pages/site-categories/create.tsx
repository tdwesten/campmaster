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

export default function SiteCategoriesCreate() {
    const { data, setData, post, processing, errors, wasSuccessful } = useForm({
        name: '',
        description: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('site-categories.store'), { preserveScroll: true });
    }

    const breadcrumbs = [
        { title: 'Site categories', href: route('site-categories.index') },
        { title: 'Create category', href: route('site-categories.create') },
    ];

    const { trans } = useLingua();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.site_categories.create.title', {}, true)} />

            <PageWrapper title={trans('messages.site_categories.create.title')} subtitle={trans('messages.site_categories.create.subtitle')}>
                {wasSuccessful && (
                    <Alert variant="success">
                        <AlertTitle>{trans('messages.site_categories.create.success_message.title')}</AlertTitle>
                        <AlertDescription>{trans('messages.site_categories.create.success_message.description')}</AlertDescription>
                    </Alert>
                )}
                <FormWrapper>
                    <form onSubmit={submit} className="grid grid-cols-1 gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="name">{trans('messages.site_categories.fields.name')}</Label>
                            <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                            <InputError message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="description">{trans('messages.site_categories.fields.description')}</Label>
                            <Input id="description" value={data.description} onChange={(e) => setData('description', e.target.value)} />
                            <InputError message={errors.description} />
                        </div>

                        <div className="flex items-center gap-2">
                            <Button type="submit" disabled={processing}>
                                {trans('messages.site_categories.create.buttons.create')}
                            </Button>
                            <span className="text-sm text-neutral-600">or</span>
                            <Link href={route('site-categories.index')} className="text-sm text-neutral-900 hover:underline">
                                {trans('messages.site_categories.create.buttons.cancel')}
                            </Link>
                        </div>
                    </form>
                </FormWrapper>
            </PageWrapper>
        </AppLayout>
    );
}
