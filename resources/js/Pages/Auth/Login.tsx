import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import {
    Alert,
    Button,
    Checkbox,
    Divider,
    PasswordInput,
    TextInput,
} from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { FormEventHandler } from 'react';
import { FcGoogle } from 'react-icons/fc';

interface SocialAuthProps {
    google: boolean;
}

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const socialAuth = usePage().props.socialAuth as SocialAuthProps;
    const [loading, { open, close }] = useDisclosure();
    const { data, setData, post, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        open();
        post('/login', {
            onFinish: () => {
                reset('password');
            },
            onError: () => {
                close();
            },
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && (
                <Alert variant="light" color="green" title="Success">
                    {status}
                </Alert>
            )}

            <form onSubmit={submit}>
                <TextInput
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    error={errors.email}
                    withAsterisk
                    autoComplete="username"
                    mt="md"
                    label="E-Mail"
                    placeholder="E-Mail"
                    onChange={(e) => setData('email', e.target.value)}
                    inputWrapperOrder={[
                        'label',
                        'input',
                        'description',
                        'error',
                    ]}
                />

                <PasswordInput
                    id="password"
                    type="password"
                    name="password"
                    value={data.password}
                    error={errors.password}
                    autoComplete="password"
                    mt="md"
                    label="Password"
                    placeholder="Password"
                    onChange={(e) => setData('password', e.target.value)}
                    inputWrapperOrder={[
                        'label',
                        'input',
                        'description',
                        'error',
                    ]}
                />

                <div className="mt-4 flex items-center justify-between">
                    <label className="flex items-center">
                        <Checkbox
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                        />
                        <span className="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="mt-3 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Forgot your password?
                        </Link>
                    )}
                </div>

                <div className="mt-4 flex flex-col items-center justify-end">
                    <Button
                        type="submit"
                        variant="filled"
                        color="black"
                        fullWidth
                        loading={loading}
                        loaderProps={{ type: 'dots' }}
                    >
                        Log in
                    </Button>
                </div>
            </form>

            <Divider my="xs" label="Or" labelPosition="center" />

            <div className="mt-3 flex flex-col items-center justify-between gap-1">
                {socialAuth.google && (
                    <Link
                        href="#"
                        className="flex w-full items-center justify-center gap-2 rounded-md bg-white p-2 shadow-lg hover:bg-gray-50"
                    >
                        <FcGoogle size={25} />
                        Continue with Google
                    </Link>
                )}
            </div>
            <div className="mt-2 flex items-center justify-end">
                <Link
                    href={route('register')}
                    className="mt-3 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Don't have an account? Register here
                </Link>
            </div>
        </GuestLayout>
    );
}
