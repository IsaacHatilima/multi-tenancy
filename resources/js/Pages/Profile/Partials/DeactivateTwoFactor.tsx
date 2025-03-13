import { router, useForm } from '@inertiajs/react';
import { Button, Modal, PasswordInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler } from 'react';

function DeactivateTwoFactor() {
    const [loading, { open: openLoading, close: closeLoading }] =
        useDisclosure();
    const [opened, { open: openModal, close: closeModal }] =
        useDisclosure(false);
    const { data, setData, errors, put } = useForm({
        current_password: '',
    });

    const handleTwoFAPasswordConfirm: FormEventHandler = (e) => {
        e.preventDefault();
        openLoading();

        put(route('disable.fortify'), {
            preserveScroll: true,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: '2FA has been enabled.',
                    color: 'green',
                });
                router.put(route('email.fa', 'disable'), {
                    current_password: data.current_password,
                });
                closeModal();
                closeLoading();
            },
            onError: () => {},
            onFinish: () => {
                close();
            },
        });
    };

    return (
        <div className="mt-2 flex items-center justify-center gap-4">
            <Button
                onClick={openModal}
                type="button"
                variant="filled"
                color="red"
            >
                De-Activate 2FA
            </Button>
            <Modal
                opened={opened}
                onClose={closeModal}
                title="De-Activate Two-Factor Authentication"
            >
                <p className="font-semibold">
                    Are you sure? this action cannot be undone.
                </p>
                <div className="px-4">
                    <form onSubmit={handleTwoFAPasswordConfirm}>
                        <PasswordInput
                            mt="xl"
                            label="Password"
                            placeholder="Password"
                            error={errors.current_password}
                            withAsterisk
                            inputWrapperOrder={['label', 'input', 'error']}
                            name="current_password"
                            value={data.current_password}
                            onChange={(e) =>
                                setData('current_password', e.target.value)
                            }
                            autoFocus={true}
                        />

                        <div className="my-4 flex items-center gap-4">
                            <Button
                                type="submit"
                                fullWidth
                                variant="filled"
                                color="red"
                                loading={loading}
                                loaderProps={{ type: 'dots' }}
                            >
                                De-Activate 2FA
                            </Button>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    );
}

export default DeactivateTwoFactor;
