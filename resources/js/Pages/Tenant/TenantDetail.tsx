import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Tenant } from '@/types/tenant';
import { Head, usePage } from '@inertiajs/react';

function TenantDetail() {
    const tenant: Tenant = usePage().props.tenant;
    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />
            {tenant.name}
        </AuthenticatedLayout>
    );
}

export default TenantDetail;
