import FormWrapper from '@/components/form-wrapper';
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

interface Category {
    id: string;
    name: string;
}
interface SiteFormData {
    id: string;
    name: string;
    description: string | null;
    site_category_id: string | null;
}

export default function SitesEdit({ site, categories = [] as Category[] }: { site: SiteFormData; categories: Category[] }) {
    const { data, setData, put, processing, errors, wasSuccessful } = useForm({
        name: site.name ?? '',
        description: site.description ?? '',
        site_category_id: site.site_category_id ?? undefined,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(route('sites.update', site.id), { preserveScroll: true });
    }

    const breadcrumbs = [
        { title: 'Sites', href: route('sites.index') },
        { title: 'Edit site', href: route('sites.edit', site.id) },
    ];

    const { trans } = useLingua();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.sites.edit.title', { name: site.name })} />

            <PageWrapper title={trans('messages.sites.edit.title', { name: site.name })} subtitle={trans('messages.sites.edit.subtitle')}>
                {wasSuccessful && (
                    <Alert variant="success">
                        <AlertTitle>{trans('messages.sites.edit.success_message.title')}</AlertTitle>
                        <AlertDescription>{trans('messages.sites.edit.success_message.description')}</AlertDescription>
                    </Alert>
                )}
                <FormWrapper title={trans('messages.sites.edit.form_title')}>
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
                                {trans('messages.sites.edit.buttons.update')}
                            </Button>
                            <span className="text-sm text-neutral-600">or</span>
                            <Link href={route('sites.index')} className="text-sm text-neutral-900 hover:underline">
                                {trans('messages.sites.edit.buttons.cancel')}
                            </Link>
                        </div>
                    </form>
                </FormWrapper>
            </PageWrapper>
        </AppLayout>
    );
}
