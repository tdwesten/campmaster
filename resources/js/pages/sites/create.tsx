import InputError from '@/components/input-error';
import { PageWrapper } from '@/components/page-wrapper';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, Link, useForm } from '@inertiajs/react';

function FormWrapper(props: { children: React.ReactNode }) {
    return <div className="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">{props.children}</div>;
}

interface Category {
    id: string;
    name: string;
}

export default function SitesCreate({ categories = [] as Category[] }: { categories: Category[] }) {
    const { data, setData, post, processing, errors, wasSuccessful } = useForm({
        name: '',
        description: '',
        site_category_id: '' as string | undefined,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('sites.store'), { preserveScroll: true });
    }

    const breadcrumbs = [
        { title: 'Sites', href: route('sites.index') },
        { title: 'Create site', href: route('sites.create') },
    ];

    const { trans } = useLingua();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.sites.create.title', {}, true)} />

            <PageWrapper title={trans('messages.sites.create.title')} subtitle={trans('messages.sites.create.subtitle')}>
                {wasSuccessful && (
                    <Alert variant="success">
                        <AlertTitle>{trans('messages.sites.create.success_message.title')}</AlertTitle>
                        <AlertDescription>{trans('messages.sites.create.success_message.description')}</AlertDescription>
                    </Alert>
                )}
                <FormWrapper>
                    <form onSubmit={submit} className="grid grid-cols-1 gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="name">{trans('messages.sites.fields.name')}</Label>
                            <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                            <InputError message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="site_category_id">{trans('messages.sites.fields.category')}</Label>
                            <Select value={data.site_category_id ?? ''} onValueChange={(v) => setData('site_category_id', v || undefined)}>
                                <SelectTrigger id="site_category_id">
                                    <SelectValue placeholder={trans('messages.sites.placeholders.select_category')} />
                                </SelectTrigger>
                                <SelectContent>
                                    {categories.map((c) => (
                                        <SelectItem key={c.id} value={c.id}>
                                            {c.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <InputError message={errors.site_category_id} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="description">{trans('messages.sites.fields.description')}</Label>
                            <Input id="description" value={data.description} onChange={(e) => setData('description', e.target.value)} />
                            <InputError message={errors.description} />
                        </div>

                        <div className="flex items-center gap-2">
                            <Button type="submit" disabled={processing}>
                                {trans('messages.sites.create.buttons.create')}
                            </Button>
                            <span className="text-sm text-neutral-600">or</span>
                            <Link href={route('sites.index')} className="text-sm text-neutral-900 hover:underline">
                                {trans('messages.sites.create.buttons.cancel')}
                            </Link>
                        </div>
                    </form>
                </FormWrapper>
            </PageWrapper>
        </AppLayout>
    );
}
