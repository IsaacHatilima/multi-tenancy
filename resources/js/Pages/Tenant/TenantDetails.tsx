import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TenantData from '@/Pages/Tenant/Partials/TenantData';
import TenantUsers from '@/Pages/Tenant/Partials/TenantUsers';
import UpdateSubdomain from '@/Pages/Tenant/Partials/UpdateSubdomain';
import { Tenant } from '@/types/tenant';
import { Head, usePage, WhenVisible } from '@inertiajs/react';
import { Badge, Card, Divider, Tabs, Text, Title } from '@mantine/core';
import { useEffect, useState } from 'react';
import {
    MdOutlineContactMail,
    MdOutlinePayments,
    MdOutlinePeopleAlt,
    MdOutlineStickyNote2,
} from 'react-icons/md';

function TenantDetails() {
    const tenant: Tenant = usePage().props.tenant;
    const defaultTab = 'details';
    const queryParams = new URLSearchParams(window.location.search);
    const initialTab = queryParams.get('tab') || defaultTab;
    const [activeTab, setActiveTab] = useState(initialTab);

    const handleTabChange = (value: string | null) => {
        if (!value) return;

        setActiveTab(value);

        const newUrl = new URL(window.location.href);
        newUrl.searchParams.set('tab', value);
        window.history.pushState({}, '', newUrl);
    };

    useEffect(() => {
        const queryParams = new URLSearchParams(window.location.search);
        if (!queryParams.has('tab')) {
            setActiveTab(defaultTab);
        }
    }, [window.location.search]);

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        if (tab !== 'users') {
            urlParams.delete('first_name');
            urlParams.delete('last_name');

            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, '', newUrl);
        }
    }, [window.location.search]);

    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <Tabs
                variant="pills"
                color="blue"
                value={activeTab}
                onChange={handleTabChange}
            >
                <Tabs.List>
                    <Tabs.Tab
                        value="details"
                        leftSection={<MdOutlineContactMail size={15} />}
                    >
                        Tenant Details
                    </Tabs.Tab>
                    <Tabs.Tab
                        value="users"
                        leftSection={<MdOutlinePeopleAlt size={15} />}
                    >
                        Users
                    </Tabs.Tab>
                    <Tabs.Tab
                        value="payments"
                        leftSection={<MdOutlinePayments size={15} />}
                    >
                        Payments
                    </Tabs.Tab>
                    <Tabs.Tab
                        value="licenses"
                        leftSection={<MdOutlineStickyNote2 size={15} />}
                    >
                        Licenses
                    </Tabs.Tab>
                </Tabs.List>

                <Card
                    shadow="sm"
                    padding="lg"
                    radius="md"
                    withBorder
                    className="mt-2"
                >
                    <Tabs.Panel value="details">
                        <div>
                            <Title order={3}>Tenant Details</Title>
                            <div className="mt-2 flex items-center gap-2">
                                <Text c="dimmed">{tenant.tenant_number}</Text>
                                <Badge
                                    color={
                                        tenant.status == 'active'
                                            ? 'green'
                                            : 'red'
                                    }
                                    variant="outline"
                                    size="sm"
                                >
                                    {tenant.status}
                                </Badge>
                            </div>

                            <TenantData tenant={tenant} />
                        </div>

                        <Divider my="md" />

                        <div>
                            <Title order={3}>Domain Details</Title>
                            <UpdateSubdomain tenant={tenant} />
                        </div>
                    </Tabs.Panel>

                    <Tabs.Panel value="users">
                        <WhenVisible data="tenant_users" fallback="Loading">
                            <TenantUsers />
                        </WhenVisible>
                    </Tabs.Panel>

                    <Tabs.Panel value="payments">
                        Settings tab content
                    </Tabs.Panel>
                    <Tabs.Panel value="licenses">
                        Licenses tab content
                    </Tabs.Panel>
                </Card>
            </Tabs>
        </AuthenticatedLayout>
    );
}

export default TenantDetails;
