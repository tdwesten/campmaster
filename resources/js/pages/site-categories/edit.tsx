import FormWrapper from '@/components/form-wrapper';
import InputError from '@/components/input-error';
import { PageWrapper } from '@/components/page-wrapper';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, Link, useForm } from '@inertiajs/react';

interface CategoryFormData {
    id: string;
    name: string;
    description: string | null;
    slug: string;
}

export default function SiteCategoriesEdit({ category }: { category: CategoryFormData }) {
    const { data, setData, put, processing, errors, wasSuccessful } = useForm({
        name: category.name ?? '',
        description: category.description ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(route('site-categories.update', category.id), { preserveScroll: true });
    }

    const breadcrumbs = [
        { title: 'Site categories', href: route('site-categories.index') },
        { title: 'Edit category', href: route('site-categories.edit', category.id) },
    ];

    const { trans } = useLingua();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.site_categories.edit.title', { name: category.name })} />

            <PageWrapper
                title={trans('messages.site_categories.edit.title', { name: category.name })}
                subtitle={trans('messages.site_categories.edit.subtitle')}
            >
                {wasSuccessful && (
                    <Alert variant="success">
                        <AlertTitle>{trans('messages.site_categories.edit.success_message.title')}</AlertTitle>
                        <AlertDescription>{trans('messages.site_categories.edit.success_message.description')}</AlertDescription>
                    </Alert>
                )}
                <FormWrapper title={trans('messages.site_categories.edit.form_title')}>
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
                                {trans('messages.site_categories.edit.buttons.update')}
                            </Button>
                            <span className="text-sm text-neutral-600">or</span>
                            <Link href={route('site-categories.index')} className="text-sm text-neutral-900 hover:underline">
                                {trans('messages.site_categories.edit.buttons.cancel')}
                            </Link>
                        </div>
                    </form>
                </FormWrapper>
            </PageWrapper>
        </AppLayout>
    );
}
