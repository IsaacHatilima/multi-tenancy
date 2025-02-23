import { Tenant } from '@/types/tenant';
import { useForm } from '@inertiajs/react';
import { Button, Modal, PasswordInput, Select, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEvent, FormEventHandler } from 'react';

function TenantData({ tenant }: { tenant: Tenant }) {
    const [loading, { open, close }] = useDisclosure();
    const {
        data,
        setData,
        errors,
        put,
        delete: destroy,
        reset,
    } = useForm({
        id: tenant.id,
        name: tenant.name,
        address: tenant.address,
        city: tenant.city,
        state: tenant.state,
        status: tenant.status,
        country: tenant.country,
        zip: tenant.zip,
        tenant_number: tenant.tenant_number,
        slug: tenant.slug,
        contact_name: tenant.contact_name,
        contact_email: tenant.contact_email,
        contact_phone: tenant.contact_phone,
        current_password: '',
    });
    const [openState, modalManager] = useDisclosure(false);

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

    const handleDeleteTenant: FormEventHandler = (
        e: FormEvent<Element>,
    ): void => {
        e.preventDefault();
        open();
        destroy(route('tenants.destroy', tenant.id), {
            preserveScroll: true,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Tenant has been deleted successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {
                reset();
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
                        autoComplete="address-line1"
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
                        autoComplete="postal-code"
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
                        autoComplete="name"
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
                        autoComplete="email"
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
                        autoComplete="tel"
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
                    <Select
                        mt="md"
                        label="Status"
                        error={errors.status}
                        value={data.status}
                        data={[
                            { value: 'active', label: 'Active' },
                            { value: 'in-active', label: 'In-Active' },
                        ]}
                        onChange={(_value, option) => {
                            setData('status', option.value);
                        }}
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

                    <Button
                        onClick={() => {
                            modalManager.open();
                        }}
                        variant="filled"
                        color="red"
                    >
                        Delete Tenant
                    </Button>
                </div>
            </form>
            <Modal
                opened={openState}
                onClose={() => {
                    modalManager.close();
                }}
                title="Delete Tenant"
            >
                <form onSubmit={handleDeleteTenant} className="p-6">
                    <h2 className="text-lg font-medium">
                        Are you sure you want to delete this Tenant?
                    </h2>

                    <p className="mt-1 text-sm">
                        Once tenant account is deleted, all of its resources and
                        data will be permanently deleted. Please enter your
                        password to confirm you would like to permanently delete
                        the tenant account.
                    </p>

                    <div className="mt-6">
                        <PasswordInput
                            id="current_password"
                            name="current_password"
                            value={data.current_password}
                            error={errors.current_password}
                            autoComplete="password"
                            data-autofocus
                            mt="md"
                            label="Password"
                            placeholder="Password"
                            onChange={(e) =>
                                setData('current_password', e.target.value)
                            }
                            inputWrapperOrder={[
                                'label',
                                'input',
                                'description',
                                'error',
                            ]}
                        />
                    </div>

                    <div className="mt-6 flex justify-end">
                        <Button
                            onClick={() => {
                                modalManager.close();
                            }}
                        >
                            Cancel
                        </Button>

                        <Button
                            type="submit"
                            className="ms-3"
                            loading={loading}
                            loaderProps={{ type: 'dots' }}
                            variant="filled"
                            color="red"
                        >
                            Delete Tenant
                        </Button>
                    </div>
                </form>
            </Modal>
        </div>
    );
}

export default TenantData;
