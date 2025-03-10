import { User } from '@/types/user';
import { useForm } from '@inertiajs/react';
import { Button, Modal, PasswordInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEvent, FormEventHandler, useRef } from 'react';

function ToggleUserState({ user }: { user: User }) {
    const [openCreateTenantModal, createTenantModalManager] =
        useDisclosure(false);
    const passwordInput = useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        reset,
        delete: destroy,
        errors,
    } = useForm({
        current_password: '',
    });

    const handleToggleState: FormEventHandler = (
        e: FormEvent<Element>,
    ): void => {
        e.preventDefault();
        destroy(route('users.toggle', user.id), {
            preserveScroll: true,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Your status updated successfully!',
                    color: 'green',
                });
                createTenantModalManager.close();
            },
            onError: () => passwordInput.current?.focus(),
            onFinish: () => {
                reset();
            },
        });
    };

    const handleDelete: FormEventHandler = (e: FormEvent<Element>): void => {
        e.preventDefault();
        destroy(route('users.destroy', user.id), {
            preserveScroll: true,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: 'Your account deleted successfully!',
                    color: 'green',
                });
                createTenantModalManager.close();
            },
            onError: () => passwordInput.current?.focus(),
            onFinish: () => {
                reset();
            },
        });
    };

    const handleSubmit: FormEventHandler = (event) => {
        event.preventDefault();

        const submitEvent = event.nativeEvent as SubmitEvent;
        const button = submitEvent.submitter as HTMLButtonElement;
        const action = button?.value;

        if (action === 'toggle') {
            handleToggleState(event);
        } else {
            handleDelete(event);
        }
    };

    return (
        <>
            <Modal
                opened={openCreateTenantModal}
                onClose={() => {
                    createTenantModalManager.close();
                }}
                size="xl"
                title="Delete User"
            >
                <header>
                    <div className="mb-2 w-full rounded-md bg-red-600 p-3">
                        <h2 className="text-lg font-medium text-white">
                            Danger Zone
                        </h2>
                    </div>

                    <h2 className="text-lg font-medium">Delete Account</h2>

                    <p className="mt-1 text-sm">
                        Once your account is deleted, all of its resources and
                        data will be permanently deleted.
                    </p>
                </header>

                <form onSubmit={handleSubmit} className="mt-6 w-full">
                    <div className="w-full">
                        <div className="">
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
                            name="action"
                            value="toggle"
                            variant="filled"
                            color={user.is_active ? 'yellow' : 'green'}
                            loaderProps={{ type: 'dots' }}
                        >
                            {user.is_active ? 'Deactivate' : 'Activate'}
                        </Button>

                        <Button
                            type="submit"
                            name="action"
                            value="delete"
                            variant="filled"
                            color="red"
                            loaderProps={{ type: 'dots' }}
                        >
                            Delete
                        </Button>
                    </div>
                </form>
            </Modal>

            <span
                className="cursor-pointer text-red-600 hover:underline"
                onClick={() => {
                    createTenantModalManager.open();
                }}
            >
                Delete User
            </span>
        </>
    );
}

export default ToggleUserState;
