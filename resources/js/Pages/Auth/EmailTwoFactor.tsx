import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Alert, Button, TextInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { FormEventHandler } from 'react';

function EmailTwoFactor({ codeRequested }: { codeRequested?: string }) {
    const [loading, { open, close }] = useDisclosure();
    const { data, setData, post, errors, reset } = useForm<{
        code: string;
    }>({
        code: '',
    });

    const handleFormSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        open();
        post(route('login.email.two.post'), {
            onFinish: () => {
                reset();
            },
            onError: () => {
                close();
            },
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {codeRequested && (
                <Alert variant="light" color="green" title="Success">
                    {codeRequested}
                </Alert>
            )}

            <form onSubmit={handleFormSubmit}>
                <TextInput
                    id="code"
                    name="code"
                    value={data.code}
                    error={errors.code}
                    withAsterisk
                    autoComplete="code"
                    mt="md"
                    label="Auth Code"
                    placeholder="Auth Code"
                    onChange={(e) => setData('code', e.target.value)}
                    inputWrapperOrder={[
                        'label',
                        'input',
                        'description',
                        'error',
                    ]}
                />

                <div className="mt-4 flex flex-col items-center justify-end">
                    <Button
                        type="submit"
                        variant="filled"
                        color="black"
                        fullWidth
                        loading={loading}
                        loaderProps={{ type: 'dots' }}
                    >
                        Login
                    </Button>
                </div>
            </form>
            <Link
                href={route('new.two.factor.code')}
                className="mt-3 rounded-md text-sm underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Request new code
            </Link>
        </GuestLayout>
    );
}

export default EmailTwoFactor;
