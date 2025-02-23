import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Tenant } from '@/types/tenant';
import { Head, usePage } from '@inertiajs/react';
import { Card, Tabs } from '@mantine/core';
import {
    MdOutlineContactMail,
    MdOutlinePayments,
    MdOutlinePeopleAlt,
    MdOutlineStickyNote2,
} from 'react-icons/md';

function TenantDetail() {
    const tenant: Tenant = usePage().props.tenant;
    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <Card shadow="sm" padding="lg" radius="md">
                <Tabs variant="outline" defaultValue="gallery">
                    <Tabs.List>
                        <Tabs.Tab
                            value="gallery"
                            leftSection={<MdOutlineContactMail size={15} />}
                        >
                            Tenant Details
                        </Tabs.Tab>
                        <Tabs.Tab
                            value="messages"
                            leftSection={<MdOutlinePeopleAlt size={15} />}
                        >
                            Users
                        </Tabs.Tab>
                        <Tabs.Tab
                            value="settings"
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

                    <Tabs.Panel value="gallery">{tenant.name}</Tabs.Panel>

                    <Tabs.Panel value="messages">
                        Messages tab content
                    </Tabs.Panel>

                    <Tabs.Panel value="settings">
                        Settings tab content
                    </Tabs.Panel>
                    <Tabs.Panel value="licenses">
                        Licenses tab content
                    </Tabs.Panel>
                </Tabs>
            </Card>
        </AuthenticatedLayout>
    );
}

export default TenantDetail;
