import { User } from '@/types/user';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import { Button, Select, TextInput } from '@mantine/core';
import { DateInput } from '@mantine/dates';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import { FormEvent, FormEventHandler } from 'react';

dayjs.extend(utc);

export default function UpdateProfileInformation({
    mustVerifyEmail,
    status,
}: {
    mustVerifyEmail: boolean;
    status?: string;
}) {
    const user: User = usePage().props.auth.user;
    const [loading, { open, close }] = useDisclosure();

    const { data, setData, patch, errors } = useForm({
        first_name: user.profile.first_name,
        last_name: user.profile.last_name,
        email: user.email,
        gender: user.profile.gender,
        date_of_birth: user.profile.date_of_birth
            ? dayjs(user.profile.date_of_birth).format('YYYY-MM-DD')
            : null,
    });

    const submit: FormEventHandler = (e: FormEvent<Element>): void => {
        e.preventDefault();
        open();
        patch(route('profile.update'), {
            onSuccess: () => {
                router.visit(route('profile.edit'), {
                    only: ['user'],
                });
                notifications.show({
                    title: 'Success',
                    message: 'Your profile has been updated successfully!',
                    color: 'green',
                });
            },
            onFinish: () => {
                close();
            },
        });
    };

    return (
        <section className="w-full">
            <header>
                <h2 className="text-lg font-medium">Profile Information</h2>
                <p className="mt-1 text-sm">
                    Update your account's profile information and email address.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6">
                <div className="mb-4 grid w-full gap-4 md:grid-cols-3">
                    <TextInput
                        id="firstname"
                        name="firstname"
                        value={data.first_name}
                        error={errors.first_name}
                        withAsterisk
                        autoComplete="firstname"
                        mt="md"
                        autoFocus
                        label="First Name"
                        placeholder="First Name"
                        onChange={(e) => setData('first_name', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />
                    <TextInput
                        id="lastname"
                        name="lastname"
                        value={data.last_name}
                        error={errors.last_name}
                        withAsterisk
                        autoComplete="lastname"
                        mt="md"
                        label="Last Name"
                        placeholder="Last Name"
                        onChange={(e) => setData('last_name', e.target.value)}
                        inputWrapperOrder={[
                            'label',
                            'input',
                            'description',
                            'error',
                        ]}
                    />

                    <TextInput
                        id="email"
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

                    <Select
                        mt="md"
                        label="Gender"
                        error={errors.gender}
                        value={data.gender}
                        data={[
                            { value: 'male', label: 'Male' },
                            { value: 'female', label: 'Female' },
                            { value: 'other', label: 'Other' },
                        ]}
                        onChange={(_value, option) => {
                            setData('gender', option.value);
                        }}
                    />

                    <DateInput
                        mt="md"
                        label="Date of Birth"
                        placeholder="Date of Birth"
                        error={errors.date_of_birth}
                        value={
                            data.date_of_birth
                                ? new Date(data.date_of_birth)
                                : null
                        }
                        onChange={(date) => {
                            if (date) {
                                const formattedDate =
                                    dayjs(date).format('YYYY-MM-DD');
                                setData('date_of_birth', formattedDate);
                            } else {
                                setData('date_of_birth', null);
                            }
                        }}
                        valueFormat="YYYY-MM-DD"
                    />
                </div>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div className="mb-2 grid grid-cols-1 gap-1">
                        {status === 'verification-link-sent' && (
                            <div className="mt-2 flex justify-end text-sm font-medium text-green-600">
                                A new verification link has been sent to your
                                email address.
                            </div>
                        )}
                        <div className="flex justify-end">
                            <p className="mt-2 text-sm text-gray-800">
                                Your email address is unverified.
                                <Link
                                    href={route('verification.send')}
                                    method="post"
                                    as="button"
                                    className="text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none"
                                >
                                    Click here to re-send the verification
                                    email.
                                </Link>
                            </p>
                        </div>
                    </div>
                )}

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
