import { router, useForm } from '@inertiajs/react';
import { Button, Modal, PasswordInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import axios from 'axios';
import React, { useState } from 'react';

function EnableTowFactor({ refreshUser }: { refreshUser: () => void }) {
    const [opened, { open: openModal, close: closeModal }] =
        useDisclosure(false);
    const [loading, { open: openLoading, close: closeLoading }] =
        useDisclosure();
    const [errors, setErrors] = useState<{ password?: string }>({});

    const { data, setData } = useForm({
        password: '',
    });

    const handleTwoFAPasswordConfirm = (e: React.FormEvent) => {
        e.preventDefault();
        openLoading();

        if (data.password !== '') {
            axios
                .post('/user/confirm-password', { password: data.password })
                .then(() => {
                    handleActivateTwoFactor();
                })
                .catch(() => {
                    setErrors({ password: 'Invalid Password.' });
                });
        } else {
            setErrors({ password: 'Password is required.' });
            closeLoading();
        }
    };

    const changeAuthType = () => {
        router.put(route('email.fa', 'fortify'));
    };

    const handleActivateTwoFactor = () => {
        openLoading();
        axios
            .post('/user/two-factor-authentication')
            .then(() => {
                refreshUser();
                notifications.show({
                    title: 'Success',
                    message: '2FA has been enabled.',
                    color: 'green',
                });
                changeAuthType();
                closeModal();
                closeLoading();
            })
            .catch(() => {
                notifications.show({
                    title: 'Warning',
                    message: 'Unable to enable 2FA.',
                    color: 'yellow',
                });
                closeLoading();
            });
    };

    return (
        <div className="mt-2 flex items-center justify-center gap-4">
            <Button
                onClick={openModal}
                type="button"
                variant="filled"
                color="rgba(0, 0, 0, 1)"
            >
                Active 2FA
            </Button>
            <Modal opened={opened} onClose={closeModal} title="Authentication">
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
                                color="rgba(0, 0, 0, 1)"
                                loading={loading}
                                loaderProps={{ type: 'dots' }}
                            >
                                Active 2FA
                            </Button>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    );
}

export default EnableTowFactor;
