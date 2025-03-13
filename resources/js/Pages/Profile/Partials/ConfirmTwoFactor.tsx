import { useForm } from '@inertiajs/react';
import { Button, Modal, PinInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler } from 'react';

function ConfirmTwoFactor() {
    const [opened, { open: openModal, close: closeModal }] =
        useDisclosure(false);
    const [loading, { open: openLoading, close: closeLoading }] =
        useDisclosure();
    const { data, setData, errors, put } = useForm({
        code: '',
    });

    const handleConfirmTwoFactor: FormEventHandler = (e) => {
        e.preventDefault();
        openLoading();

        put(route('confirm.fortify'), {
            preserveScroll: true,
            onSuccess: () => {
                notifications.show({
                    title: 'Success',
                    message: '2FA has been confirmed.',
                    color: 'green',
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
                color="rgba(0, 0, 0, 1)"
            >
                Confirm 2FA
            </Button>
            <Modal opened={opened} onClose={closeModal} title="Authentication">
                <div className="flex items-center justify-center gap-4 px-4">
                    <form onSubmit={handleConfirmTwoFactor}>
                        <PinInput
                            mt="xl"
                            oneTimeCode
                            type="number"
                            length={6}
                            inputMode="numeric"
                            name="code"
                            error={!!errors.code}
                            value={data.code}
                            onChange={(value: string) => setData('code', value)}
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
                                Confirm 2FA
                            </Button>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    );
}

export default ConfirmTwoFactor;
