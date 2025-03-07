import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import CreateTenant from '@/Pages/Tenant/Partials/CreateTenant';
import { PaginatedTenants, Tenant } from '@/types/tenant';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import {
    Badge,
    Card,
    Group,
    Input,
    Pagination,
    Table,
    TextInput,
} from '@mantine/core';
import { debounce } from 'lodash';
import { useEffect } from 'react';

interface Filters {
    tenant_number: string | null;
    name: string | null;
    domain: string | null;
    status: string | null;
    contact_name: string | null;
}

function Index() {
    const paginatedTenants: PaginatedTenants = usePage().props
        .tenants as PaginatedTenants;
    const filters: Filters = usePage().props.filters as Filters;
    const { data, setData } = useForm({
        tenant_number: filters?.tenant_number || '',
        name: filters?.name || '',
        domain: filters?.domain || '',
        status: filters?.status || '',
        contact_name: filters?.contact_name || '',
        sorting: 'desc',
    });
    const handleSearch = debounce(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 1;
        const filtersApplied = Object.keys(data).some(
            (key) =>
                key !== 'sorting' &&
                data[key as keyof Filters] !== '' &&
                data[key as keyof Filters] !== null,
        );
        const pageToUse = filtersApplied ? 1 : currentPage;
        const params: Record<string, string | number> = {
            ...data,
            page: pageToUse,
        };

        Object.keys(params).forEach((key) => {
            if (params[key] === '' || params[key] === null) {
                delete params[key];
            }
        });

        router.get(route('tenants'), params, {
            preserveState: true,
        });
    }, 300);

    useEffect(() => {
        handleSearch();
    }, [data]);

    const rows = paginatedTenants.data.map((tenant: Tenant) => (
        <Table.Tr key={tenant.id}>
            <Table.Td>
                <Link href={route('tenants.show', tenant.id)}>
                    <span className="text-sky-600">{tenant.tenant_number}</span>
                </Link>
            </Table.Td>
            <Table.Td>{tenant.name}</Table.Td>
            <Table.Td>{tenant.domain.domain}</Table.Td>
            <Table.Td>
                <Badge
                    color={tenant.status == 'active' ? 'green' : 'red'}
                    variant="outline"
                    size="sm"
                >
                    {tenant.status}
                </Badge>
            </Table.Td>
            <Table.Td>
                {tenant.contact_first_name} {tenant.contact_last_name}
            </Table.Td>
            <Table.Td>
                {tenant.created_by.profile.first_name}{' '}
                {tenant.created_by.profile.last_name}
            </Table.Td>
            <Table.Td>{tenant.created_at}</Table.Td>
        </Table.Tr>
    ));

    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <Card shadow="sm" padding="lg" radius="md" withBorder>
                <div className="mb-4 flex items-center justify-end">
                    <CreateTenant />
                </div>

                <Table striped highlightOnHover>
                    <Table.Thead>
                        <Table.Tr>
                            <Table.Th>Tenant Number</Table.Th>
                            <Table.Th>Name</Table.Th>
                            <Table.Th>Subdomain</Table.Th>
                            <Table.Th>Status</Table.Th>
                            <Table.Th>Contact</Table.Th>
                            <Table.Th>Created By</Table.Th>
                            <Table.Th>Created</Table.Th>
                        </Table.Tr>
                        <Table.Tr>
                            <Table.Th>
                                <TextInput
                                    id="tenant_number"
                                    name="tenant_number"
                                    placeholder="Tenant Number"
                                    value={data.tenant_number}
                                    onChange={(e) =>
                                        setData('tenant_number', e.target.value)
                                    }
                                />
                            </Table.Th>
                            <Table.Th>
                                <TextInput
                                    id="name"
                                    name="name"
                                    placeholder="Tenant Name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData('name', e.target.value)
                                    }
                                />
                            </Table.Th>
                            <Table.Th>
                                <TextInput
                                    id="domain"
                                    name="domain"
                                    placeholder="Subdomain"
                                    value={data.domain}
                                    onChange={(e) =>
                                        setData('domain', e.target.value)
                                    }
                                />
                            </Table.Th>
                            <Table.Th>
                                <Input
                                    id="status"
                                    name="status"
                                    component="select"
                                    value={data.status}
                                    onChange={(e) =>
                                        setData('status', e.target.value)
                                    }
                                >
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="in-active">In-Active</option>
                                </Input>
                            </Table.Th>
                            <Table.Th>
                                <TextInput
                                    id="contact_name"
                                    name="contact_name"
                                    placeholder="Contact Name"
                                    value={data.contact_name}
                                    onChange={(e) =>
                                        setData('contact_name', e.target.value)
                                    }
                                />
                            </Table.Th>
                            <Table.Th></Table.Th>
                            <Table.Th>
                                <Input
                                    id="sorting"
                                    name="sorting"
                                    component="select"
                                    value={data.sorting}
                                    onChange={(e) =>
                                        setData('sorting', e.target.value)
                                    }
                                >
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </Input>
                            </Table.Th>
                        </Table.Tr>
                    </Table.Thead>
                    <Table.Tbody>{rows}</Table.Tbody>
                </Table>

                <div className="mt-4 flex justify-end">
                    <Pagination.Root
                        total={paginatedTenants.last_page}
                        value={paginatedTenants.current_page}
                        getItemProps={(page) => ({
                            href: paginatedTenants.links[page]?.url,
                            onClick: () => {
                                if (paginatedTenants.links[page]?.url) {
                                    router.get(
                                        paginatedTenants.links[page].url,
                                    );
                                }
                            },
                        })}
                    >
                        <Group gap={5} justify="center">
                            <Pagination.First
                                onClick={() =>
                                    router.visit(paginatedTenants.path)
                                }
                            />
                            <Pagination.Previous
                                onClick={() =>
                                    router.visit(paginatedTenants.prev_page_url)
                                }
                            />

                            <Pagination.Items />

                            <Pagination.Next
                                onClick={() =>
                                    router.visit(paginatedTenants.next_page_url)
                                }
                            />
                            <Pagination.Last
                                onClick={() =>
                                    router.visit(paginatedTenants.last_page_url)
                                }
                            />
                        </Group>
                    </Pagination.Root>
                </div>
                <div className="mt-4 flex justify-end">
                    <p className="text-sm font-thin text-gray-400">
                        {paginatedTenants.from} to {paginatedTenants.to} of{' '}
                        {paginatedTenants.total}
                    </p>
                </div>
            </Card>
        </AuthenticatedLayout>
    );
}

export default Index;
