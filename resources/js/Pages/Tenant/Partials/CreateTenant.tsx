import { useForm } from '@inertiajs/react';
import { Button, Modal, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler } from 'react';

function CreateTenant() {
    const [openCreateTenantModal, createTenantModalManager] =
        useDisclosure(false);
    const { data, setData, errors, post, reset } = useForm({
        name: '',
        address: '',
        city: '',
        state: '',
        status: '',
        country: '',
        zip: '',
        tenant_number: '',
        slug: '',
        contact_first_name: '',
        contact_last_name: '',
        contact_email: '',
        contact_phone: '',
        current_password: '',
        domain: '',
    });

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('tenants.store'), {
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Tenant has been created successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {},
            onError: () => {},
        });
    };

    return (
        <>
            <Modal
                opened={openCreateTenantModal}
                onClose={() => {
                    createTenantModalManager.close();
                }}
                size="xl"
                title="Create Tenant"
            >
                <form onSubmit={handleSubmit} className="mt-6 w-full">
                    <div className="w-full">
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
                                id="name"
                                name="name"
                                value={data.name}
                                error={errors.name}
                                withAsterisk
                                autoComplete="name"
                                mt="md"
                                autoFocus
                                label="Tenant Name"
                                onChange={(e) =>
                                    setData('name', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                            <TextInput
                                className="w-full"
                                id="address"
                                name="address"
                                value={data.address}
                                error={errors.address}
                                withAsterisk
                                autoComplete="address-line1"
                                mt="md"
                                label="Address"
                                onChange={(e) =>
                                    setData('address', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
                                id="city"
                                name="city"
                                value={data.city}
                                error={errors.city}
                                withAsterisk
                                autoComplete="city"
                                mt="md"
                                label="City"
                                onChange={(e) =>
                                    setData('city', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />

                            <TextInput
                                className="w-full"
                                id="state"
                                name="state"
                                value={data.state}
                                error={errors.state}
                                withAsterisk
                                autoComplete="state"
                                mt="md"
                                label="State"
                                onChange={(e) =>
                                    setData('state', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
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
                                className="w-full"
                                id="country"
                                name="country"
                                value={data.country}
                                error={errors.country}
                                withAsterisk
                                autoComplete="country"
                                mt="md"
                                label="Country"
                                onChange={(e) =>
                                    setData('country', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
                                id="contact_first_name"
                                name="contact_first_name"
                                value={data.contact_first_name}
                                error={errors.contact_first_name}
                                withAsterisk
                                autoComplete="first_name"
                                mt="md"
                                label="Contact First Name"
                                onChange={(e) =>
                                    setData(
                                        'contact_first_name',
                                        e.target.value,
                                    )
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                            <TextInput
                                className="w-full"
                                id="contact_last_name"
                                name="contact_last_name"
                                value={data.contact_last_name}
                                error={errors.contact_last_name}
                                withAsterisk
                                autoComplete="last_name"
                                mt="md"
                                label="Contact Last Name"
                                onChange={(e) =>
                                    setData('contact_last_name', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
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
                                className="w-full"
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
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
                                id="domain"
                                name="domain"
                                value={data.domain}
                                error={errors.domain}
                                withAsterisk
                                autoComplete="tel"
                                mt="md"
                                label="Domain"
                                onChange={(e) =>
                                    setData('domain', e.target.value)
                                }
                                inputWrapperOrder={[
                                    'label',
                                    'input',
                                    'description',
                                    'error',
                                ]}
                            />
                        </div>
                    </div>

                    <div className="mt-4 flex justify-end gap-2">
                        <Button
                            variant="default"
                            onClick={() => {
                                createTenantModalManager.close();
                                reset();
                            }}
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            variant="filled"
                            color="black"
                            loaderProps={{ type: 'dots' }}
                        >
                            Create Tenant
                        </Button>
                    </div>
                </form>
            </Modal>

            <Button
                variant="filled"
                color="black"
                onClick={() => {
                    createTenantModalManager.open();
                }}
            >
                Create Tenant
            </Button>
        </>
    );
}

export default CreateTenant;
