import { Tenant } from '@/types/tenant';
import { useForm } from '@inertiajs/react';
import { Button, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEvent, FormEventHandler } from 'react';

function TenantData({ tenant }: { tenant: Tenant }) {
    const [loading, { open, close }] = useDisclosure();
    const { data, setData, errors, put } = useForm({
        id: tenant.id,
        name: tenant.name,
        address: tenant.address,
        city: tenant.city,
        state: tenant.state,
        country: tenant.country,
        zip: tenant.zip,
        tenant_number: tenant.tenant_number,
        slug: tenant.slug,
        contact_name: tenant.contact_name,
        contact_email: tenant.contact_email,
        contact_phone: tenant.contact_phone,
    });

    const handleSubmit: FormEventHandler = (e: FormEvent<Element>): void => {
        e.preventDefault();
        open();
        put(route('tenants.update', tenant.id), {
            preserveState: false,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Tenant has been updated successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {
                close();
            },
        });
    };

    return (
        <div>
            <form onSubmit={handleSubmit} className="mt-6">
                <div className="mb-4 grid w-full gap-4 md:grid-cols-3">
                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        error={errors.name}
                        withAsterisk
                        autoComplete="name"
                        mt="md"
                        autoFocus
                        label="Tenant Name"
                        onChange={(e) => setData('name', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="address"
                        name="address"
                        value={data.address}
                        error={errors.address}
                        withAsterisk
                        autoComplete="address"
                        mt="md"
                        label="Address"
                        onChange={(e) => setData('address', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />

                    <TextInput
                        id="city"
                        name="city"
                        value={data.city}
                        error={errors.city}
                        withAsterisk
                        autoComplete="city"
                        mt="md"
                        label="City"
                        onChange={(e) => setData('city', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />

                    <TextInput
                        id="state"
                        name="state"
                        value={data.state}
                        error={errors.state}
                        withAsterisk
                        autoComplete="state"
                        mt="md"
                        label="State"
                        onChange={(e) => setData('state', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="zip"
                        name="zip"
                        value={data.zip}
                        error={errors.zip}
                        withAsterisk
                        autoComplete="zip"
                        mt="md"
                        label="Postal Code"
                        onChange={(e) => setData('zip', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="country"
                        name="country"
                        value={data.country}
                        error={errors.country}
                        withAsterisk
                        autoComplete="country"
                        mt="md"
                        label="Country"
                        onChange={(e) => setData('country', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="contact_name"
                        name="contact_name"
                        value={data.contact_name}
                        error={errors.contact_name}
                        withAsterisk
                        autoComplete="contact_name"
                        mt="md"
                        label="Contact Name"
                        onChange={(e) =>
                            setData('contact_name', e.target.value)
                        }
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="contact_email"
                        name="contact_email"
                        value={data.contact_email}
                        error={errors.contact_email}
                        withAsterisk
                        autoComplete="contact_email"
                        mt="md"
                        label="Contact E-Mail"
                        onChange={(e) =>
                            setData('contact_email', e.target.value)
                        }
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="contact_phone"
                        name="contact_phone"
                        value={data.contact_phone}
                        error={errors.contact_phone}
                        withAsterisk
                        autoComplete="contact_phone"
                        mt="md"
                        label="Contact Phone (only numbers)"
                        onChange={(e) =>
                            setData('contact_phone', e.target.value)
                        }
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                </div>

                <div className="flex justify-end">
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

export default TenantData;
