import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
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
import dayjs from 'dayjs';
import { debounce } from 'lodash';
import { useEffect } from 'react';

function Index() {
    const paginatedTenants: PaginatedTenants = usePage().props
        .tenants as PaginatedTenants;

    const formatDate = (dateString: string) => {
        return dayjs(dateString).format('YYYY-MM-DD HH:mm:ss');
    };

    const rows = paginatedTenants.data.map((tenant: Tenant) => (
        <Table.Tr key={tenant.id}>
            <Table.Td>
                <Link href={route('tenants.show', tenant.slug)}>
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
            <Table.Td>{tenant.contact_name}</Table.Td>
            <Table.Td>{formatDate(tenant.created_at)}</Table.Td>
        </Table.Tr>
    ));

    const { data, setData, get } = useForm({
        tenant_number: '',
        name: '',
        domain: '',
        status: '',
        contact_name: '',
        sorting: 'descending',
    });

    const isFiltersEmpty = () => {
        return Object.entries(data).every(([key, value]) => {
            if (key === 'sorting') return true;
            return !value || value.trim() === '';
        });
    };

    const handleSearch = debounce(() => {
        if (isFiltersEmpty()) {
            router.get(
                route('tenants'),
                {},
                { replace: true, preserveState: true },
            );
        } else {
            get(route('tenants'), { preserveState: true, replace: true });
        }
    }, 300);

    useEffect(() => {
        handleSearch();
    }, [data]);

    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <Card shadow="sm" padding="lg" radius="md">
                <div>
                    <Table striped highlightOnHover>
                        <Table.Thead>
                            <Table.Tr>
                                <Table.Th>Tenant Number</Table.Th>
                                <Table.Th>Name</Table.Th>
                                <Table.Th>Domain</Table.Th>
                                <Table.Th>Status</Table.Th>
                                <Table.Th>Contact</Table.Th>
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
                                            setData(
                                                'tenant_number',
                                                e.target.value,
                                            )
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
                                        placeholder="Domain"
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
                                        <option value="in-active">
                                            In-Active
                                        </option>
                                    </Input>
                                </Table.Th>
                                <Table.Th>
                                    <TextInput
                                        id="contact_name"
                                        name="contact_name"
                                        placeholder="Contact Name"
                                        value={data.contact_name}
                                        onChange={(e) =>
                                            setData(
                                                'contact_name',
                                                e.target.value,
                                            )
                                        }
                                    />
                                </Table.Th>
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
                                        <option value="ascending">
                                            Ascending
                                        </option>
                                        <option value="descending">
                                            Descending
                                        </option>
                                    </Input>
                                </Table.Th>
                            </Table.Tr>
                        </Table.Thead>
                        <Table.Tbody>{rows}</Table.Tbody>
                    </Table>
                </div>

                <div className="mt-4 flex justify-end">
                    <Pagination.Root
                        total={paginatedTenants.last_page}
                        value={paginatedTenants.current_page}
                        getItemProps={(page) => ({
                            href: paginatedTenants.links[page]?.url,
                            onClick: () => {
                                if (paginatedTenants.links[page]?.url) {
                                    router.visit(
                                        page == 1
                                            ? paginatedTenants.path
                                            : paginatedTenants.links[page].url,
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
