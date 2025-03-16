import CentralSideNav from '@/Components/CentralSideNav';
import TenantSideNav from '@/Components/TenantSideNav';
import TopNav from '@/Components/TopNav';
import { Tenant } from '@/types/tenant';
import { User } from '@/types/user';
import { usePage } from '@inertiajs/react';
import { AppShell, Burger } from '@mantine/core';
import { useDisclosure, useMediaQuery } from '@mantine/hooks';
import { PropsWithChildren, ReactNode } from 'react';

export default function Authenticated({
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const user: User = usePage().props.auth.user;
    const tenant: Tenant = usePage().props.tenant;
    const [opened, { toggle }] = useDisclosure();

    return (
        <AppShell
            header={{ height: 60 }}
            navbar={{
                width: 300,
                breakpoint: 'sm',
                collapsed: { mobile: !opened },
            }}
            padding="md"
        >
            <AppShell.Header withBorder={false} className="shadow-md">
                <div className="flex h-full w-full items-center justify-between">
                    <div className="ml-4">
                        <Burger
                            opened={opened}
                            onClick={toggle}
                            hiddenFrom="sm"
                            size="md"
                        />
                        {useMediaQuery('(min-width: 56.25em)') && <h1>Logo</h1>}
                    </div>
                    <TopNav user={user} />
                </div>
            </AppShell.Header>
            <AppShell.Navbar p="md" withBorder={false} className="shadow-xl">
                {!tenant ? <CentralSideNav /> : <TenantSideNav user={user} />}
            </AppShell.Navbar>
            <AppShell.Main>{children}</AppShell.Main>
        </AppShell>
    );
}
