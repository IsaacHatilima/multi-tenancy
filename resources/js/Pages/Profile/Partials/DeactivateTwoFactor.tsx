import { router, useForm } from '@inertiajs/react';
import { Button, Modal, PasswordInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import axios from 'axios';
import React, { useState } from 'react';

function DeactivateTwoFactor({ refreshUser }: { refreshUser: () => void }) {
    const [loading, { open: openLoading, close: closeLoading }] =
        useDisclosure();
    const [opened, { open: openModal, close: closeModal }] =
        useDisclosure(false);
    const [errors, setErrors] = useState<{ password?: string }>({});

    const { data, setData } = useForm({
        password: '',
    });

    const changeAuthType = () => {
        router.put(route('email.fa', 'disable'));
    };

    const handleDeactivateTwoFactor = () => {
        openLoading();
        axios
            .delete('/user/two-factor-authentication')
            .then(() => {
                refreshUser();
                notifications.show({
                    title: 'Success',
                    message: '2FA has been de-activated.',
                    color: 'green',
                });
                changeAuthType();
                closeModal();
                closeLoading();
            })
            .catch(() => {
                notifications.show({
                    title: 'Warning',
                    message: 'Unable to de-activate 2FA.',
                    color: 'yellow',
                });
                closeLoading();
            });
    };

    const handleTwoFAPasswordConfirm = (e: React.FormEvent) => {
        e.preventDefault();
        openLoading();

        if (data.password !== '') {
            axios
                .post('/user/confirm-password', { password: data.password })
                .then(() => {
                    handleDeactivateTwoFactor();
                })
                .catch(() => {
                    setErrors({ password: 'Invalid Password.' });
                });
        } else {
            setErrors({ password: 'Password is required.' });
            closeLoading();
        }
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
                            error={errors.password}
                            withAsterisk
                            inputWrapperOrder={['label', 'input', 'error']}
                            name="password"
                            value={data.password}
                            onChange={(e) =>
                                setData('password', e.target.value)
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
