import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TenantData from '@/Pages/Tenant/Partials/TenantData';
import UpdateSubdomain from '@/Pages/Tenant/Partials/UpdateSubdomain';
import { Tenant } from '@/types/tenant';
import { Head, usePage } from '@inertiajs/react';
import { Badge, Card, Divider, Tabs, Text, Title } from '@mantine/core';
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

            <Tabs variant="outline" radius="md" defaultValue="details">
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

                <Card shadow="sm" padding="lg" radius="md">
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

                    <Tabs.Panel value="users">Messages tab content</Tabs.Panel>

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

export default TenantDetail;
