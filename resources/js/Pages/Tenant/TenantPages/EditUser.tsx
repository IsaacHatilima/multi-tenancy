import { User } from '@/types/user';
import { useForm } from '@inertiajs/react';
import { Button, Modal, Select, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler } from 'react';

function EditUser({ user }: { user: User }) {
    const [openCreateTenantModal, createTenantModalManager] =
        useDisclosure(false);
    const { data, setData, errors, put, reset } = useForm({
        email: user.email,
        first_name: user.profile.first_name,
        last_name: user.profile.last_name,
        role: user.role,
    });

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('users.update', user.id), {
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'User has been updated successfully!',
                    color: 'green',
                });
                createTenantModalManager.close();
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
                title="Create User"
            >
                <form onSubmit={handleSubmit} className="mt-6 w-full">
                    <div className="w-full">
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <TextInput
                                className="w-full"
                                id="first_name"
                                name="first_name"
                                value={data.first_name}
                                error={errors.first_name}
                                withAsterisk
                                autoComplete="first_name"
                                mt="md"
                                autoFocus
                                label="First Name"
                                onChange={(e) =>
                                    setData('first_name', e.target.value)
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
                                id="last_name"
                                name="last_name"
                                value={data.last_name}
                                error={errors.last_name}
                                withAsterisk
                                autoComplete="last_name"
                                mt="md"
                                label="Last Name"
                                onChange={(e) =>
                                    setData('last_name', e.target.value)
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
                                id="email"
                                name="email"
                                value={data.email}
                                error={errors.email}
                                withAsterisk
                                autoComplete="email"
                                mt="md"
                                label="Email"
                                onChange={(e) =>
                                    setData('email', e.target.value)
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
                                label="Role"
                                error={errors.role}
                                value={data.role}
                                withAsterisk
                                data={[
                                    { label: 'Admin', value: 'Admin' },
                                    { label: 'User', value: 'User' },
                                ]}
                                onChange={(_value, option) => {
                                    setData('role', option.value);
                                }}
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
                            Update
                        </Button>
                    </div>
                </form>
            </Modal>

            <span
                className="cursor-pointer text-sky-600 hover:underline"
                onClick={() => {
                    createTenantModalManager.open();
                }}
            >
                Edit User
            </span>
        </>
    );
}

export default EditUser;
