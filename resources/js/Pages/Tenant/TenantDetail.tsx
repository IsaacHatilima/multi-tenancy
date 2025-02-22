import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Tenant } from '@/types/tenant';
import { Head, usePage } from '@inertiajs/react';
import { Card } from '@mantine/core';

function TenantDetail() {
    const tenant: Tenant = usePage().props.tenant;
    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <Card shadow="sm" padding="lg" radius="md">
                {tenant.name}
            </Card>
        </AuthenticatedLayout>
    );
}

export default TenantDetail;
