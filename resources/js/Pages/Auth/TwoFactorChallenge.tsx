import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import { Alert, Button, PinInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler, useState } from 'react';

function TwoFactorChallenge() {
    const [loading, { open, close }] = useDisclosure();
    const [twoFactorError, setTwoFactorError] = useState('');
    const { data, setData, post } = useForm({
        code: '',
    });
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        open();
        post('/two-factor-challenge', {
            onFinish: () => {
                close();
            },
            onError: (error) => {
                setTwoFactorError(error.code);
                notifications.show({
                    title: 'Warning',
                    message: 'Invalid 2FA Code.',
                    color: 'yellow',
                });
            },
        });
    };

    return (
        <GuestLayout>
            <Head title="2FA Challenge" />

            {twoFactorError && (
                <Alert variant="light" color="yellow" title="Warning">
                    {twoFactorError}
                </Alert>
            )}

            <div className="flex flex-col items-center justify-center gap-4">
                <h1 className="pt-4">Enter you 2FA Code to continue</h1>
                <form onSubmit={submit}>
                    <PinInput
                        mt="sm"
                        oneTimeCode
                        type="number"
                        length={6}
                        inputMode="numeric"
                        name="code"
                        value={data.code}
                        onChange={(value: string) => setData('code', value)}
                        autoFocus={true}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                submit(e);
                            }
                        }}
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
                            Continue
                        </Button>
                    </div>
                </form>
            </div>
        </GuestLayout>
    );
}

export default TwoFactorChallenge;
