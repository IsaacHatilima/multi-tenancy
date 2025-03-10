import { useForm, usePage } from '@inertiajs/react';
import { Button, PasswordInput } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import { FormEventHandler } from 'react';

export default function UpdatePasswordForm() {
    const [loading, { open, close }] = useDisclosure();
    const social_auth = usePage().props.social_auth;

    const { data, setData, errors, put, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword: FormEventHandler = (e) => {
        e.preventDefault();
        open();

        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                notifications.show({
                    title: 'Success',
                    message: 'Password Updated.',
                    color: 'green',
                });
            },
            onError: (errors) => {
                if (errors.password) {
                    reset('password', 'password_confirmation');
                }

                if (errors.current_password) {
                    reset('current_password');
                }
                notifications.show({
                    title: 'Warning',
                    message: 'Unable to update Password.',
                    color: 'yellow',
                });
            },
            onFinish: () => {
                close();
            },
        });
    };

    return (
        <section>
            <header>
                <h2 className="text-lg font-medium">Update Password</h2>

                <p className="mt-1 text-sm">
                    Ensure your account is using a long, random password to stay
                    secure.
                </p>
            </header>

            <form onSubmit={updatePassword} className="mt-6 space-y-6">
                {!social_auth && (
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        value={data.current_password}
                        error={errors.current_password}
                        autoComplete="current_password"
                        mt="md"
                        withAsterisk
                        label="Current Password"
                        placeholder="Current Password"
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
                )}

                <PasswordInput
                    id="password"
                    name="password"
                    value={data.password}
                    error={errors.password}
                    autoComplete="password"
                    mt="md"
                    withAsterisk
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

                <PasswordInput
                    id="password_confirmation"
                    withAsterisk
                    name="password_confirmation"
                    value={data.password_confirmation}
                    error={errors.password_confirmation}
                    autoComplete="password_confirmation"
                    mt="md"
                    label="Password Confirmation"
                    placeholder="Password Confirmation"
                    onChange={(e) =>
                        setData('password_confirmation', e.target.value)
                    }
                    inputWrapperOrder={[
                        'label',
                        'input',
                        'description',
                        'error',
                    ]}
                />

                <div className="flex justify-end">
                    <Button
                        type="submit"
                        variant="filled"
                        color="black"
                        loading={loading}
                        loaderProps={{ type: 'dots' }}
                    >
                        Save
                    </Button>
                </div>
            </form>
        </section>
    );
}
