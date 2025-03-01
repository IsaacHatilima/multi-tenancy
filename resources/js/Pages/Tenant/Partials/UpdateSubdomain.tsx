import { Tenant } from '@/types/tenant';
import { router, useForm } from '@inertiajs/react';
import { Button, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEvent, FormEventHandler } from 'react';

function UpdateSubdomain({ tenant }: { tenant: Tenant }) {
    const [loading, { open, close }] = useDisclosure();
    const { data, setData, errors, put } = useForm({
        domain: tenant.domain.domain,
    });

    const handleSubmit: FormEventHandler = (e: FormEvent<Element>): void => {
        e.preventDefault();
        open();
        put(route('domains.update', tenant.domain.id), {
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Tenant Subdomain has been updated successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {
                close();
                router.visit(route('tenants.show', tenant.id), {
                    only: ['tenant'],
                });
            },
        });
    };

    return (
        <div>
            <form onSubmit={handleSubmit} className="mt-6">
                <div className="mb-4 grid w-full gap-4 md:grid-cols-3">
                    <TextInput
                        id="domain"
                        name="domain"
                        value={data.domain}
                        error={errors.domain}
                        withAsterisk
                        autoComplete="tel"
                        mt="md"
                        label="Subdomain"
                        onChange={(e) => setData('domain', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                </div>

                <div className="flex justify-end gap-2">
                    <Button
                        type="submit"
                        variant="filled"
                        color="black"
                        loading={loading}
                        loaderProps={{ type: 'dots' }}
                    >
                        Update
                    </Button>
                </div>
            </form>
        </div>
    );
}

export default UpdateSubdomain;
