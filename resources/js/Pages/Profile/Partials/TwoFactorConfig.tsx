import ConfirmTwoFactor from '@/Pages/Profile/Partials/ConfirmTwoFactor';
import DeactivateTwoFactor from '@/Pages/Profile/Partials/DeactivateTwoFactor';
import EnableTowFactor from '@/Pages/Profile/Partials/EnableTowFactor';
import { User } from '@/types/user';
import { router, usePage } from '@inertiajs/react';
import { Button } from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { notifications } from '@mantine/notifications';
import axios from 'axios';
import { useEffect, useRef, useState } from 'react';

function TwoFactorConfig() {
    const user: User = usePage().props.auth.user;
    const setupCode = usePage().props.otpCode as string;

    const [qrCodeSvg, setQrCodeSvg] = useState<string | null>(null);
    const [recoveryCodes, setRecoveryCodes] = useState<string[] | null>(null);
    const [loading, { open, close }] = useDisclosure();
    useRef<ReturnType<typeof setTimeout> | null>(null);
    // Get 2FA QR Code
    const handleGetTwoFactorQRCode = () => {
        axios.get('/user/two-factor-qr-code').then((response) => {
            setQrCodeSvg(response.data.svg);
        });
    };

    const handleGetTwoFactorRecoveryCodes = () => {
        axios.get('/user/two-factor-recovery-codes').then((response) => {
            setRecoveryCodes(response.data); // Update the ref's value
        });
    };

    function refreshUser() {
        router.get(
            route('security.edit'),
            {},
            {
                preserveScroll: true,
            },
        );
    }

    useEffect(() => {
        if (user.two_factor_secret && !user.copied_codes) {
            handleGetTwoFactorQRCode();
            handleGetTwoFactorRecoveryCodes();
        }
    });

    const handleCopiedCodes = () => {
        axios
            .put(route('security.put'))
            .then(() => {
                refreshUser();
                notifications.show({
                    title: 'Success',
                    message: '2FA recovery codes copied.',
                    color: 'green',
                });
            })
            .catch(() => {
                notifications.show({
                    title: 'Warning',
                    message: 'Unable to copy 2FA recovery codes.',
                    color: 'yellow',
                });
            });
    };

    const handleDownloadCodes = () => {
        open();

        const blob = new Blob([recoveryCodes!.join('\n')], {
            type: 'text/plain',
        });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'recoveryCodes.txt';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);

        handleCopiedCodes();

        close();
    };

    return (
        <section>
            <header>
                <h2 className="text-lg font-medium">
                    Two-Factor Authentication Settings
                </h2>
                <p className="mt-1 text-sm">
                    If you logout before completing the process, deactivate and
                    restart.
                </p>
            </header>
            <div className="mt-4">
                {qrCodeSvg && !user.two_factor_confirmed_at && (
                    <div className="mb-4 flex flex-col items-center justify-center">
                        <div
                            dangerouslySetInnerHTML={{ __html: qrCodeSvg }}
                            className="qr-code my-10 flex items-center justify-center"
                        />
                        <h1 className="font-bold text-black">
                            Setup Code: {setupCode}
                        </h1>
                    </div>
                )}

                {recoveryCodes &&
                    user.two_factor_confirmed_at &&
                    !user.copied_codes && (
                        <div className="my-10 flex items-center justify-center">
                            <ul>
                                {recoveryCodes.map(
                                    (code: string, index: number) => (
                                        <li
                                            key={index}
                                            className="mb-2 font-bold text-gray-900"
                                        >
                                            {code}
                                        </li>
                                    ),
                                )}
                            </ul>
                        </div>
                    )}
            </div>

            {user.two_factor_secret &&
            user.two_factor_recovery_codes &&
            user.two_factor_confirmed_at ? (
                <div className="flex flex-col items-center justify-center gap-2 md:flex-row">
                    <DeactivateTwoFactor refreshUser={refreshUser} />
                    {!user.copied_codes && (
                        <div className="mt-2">
                            <Button
                                type="button"
                                variant="filled"
                                loading={loading}
                                loaderProps={{ type: 'dots' }}
                                onClick={handleDownloadCodes}
                            >
                                Copy Codes
                            </Button>
                        </div>
                    )}
                </div>
            ) : user.two_factor_secret && user.two_factor_recovery_codes ? (
                <div className="flex flex-col items-center justify-center gap-2 md:flex-row">
                    <DeactivateTwoFactor refreshUser={refreshUser} />
                    <ConfirmTwoFactor refreshUser={refreshUser} />
                </div>
            ) : (
                <EnableTowFactor refreshUser={refreshUser} />
            )}
        </section>
    );
}

export default TwoFactorConfig;
