import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { PaginatedTenants, Tenant } from '@/types/tenant';
import { Head, router, usePage } from '@inertiajs/react';
import { Card, Group, Pagination, Table } from '@mantine/core';

function Index() {
    const paginatedTenants: PaginatedTenants = usePage().props
        .tenants as PaginatedTenants;

    const rows = paginatedTenants.data.map((tenant: Tenant) => (
        <Table.Tr key={tenant.id}>
            <Table.Td>{tenant.id}</Table.Td>
            <Table.Td>{tenant.tenancy_db_name}</Table.Td>
            <Table.Td>{tenant.domain.domain}</Table.Td>
            <Table.Td>{tenant.plan}</Table.Td>
            <Table.Td>{tenant.created_at}</Table.Td>
            <Table.Td>{tenant.updated_at}</Table.Td>
        </Table.Tr>
    ));

    return (
        <AuthenticatedLayout>
            <Head title="Tenants" />

            <div className="">
                <Card shadow="sm" padding="lg" radius="md">
                    <div>
                        <Table>
                            <Table.Thead>
                                <Table.Tr>
                                    <Table.Th>Id</Table.Th>
                                    <Table.Th>Database</Table.Th>
                                    <Table.Th>Domain</Table.Th>
                                    <Table.Th>Plan</Table.Th>
                                    <Table.Th>Created</Table.Th>
                                    <Table.Th>Updated</Table.Th>
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
                                                : paginatedTenants.links[page]
                                                      .url,
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
                                        router.visit(
                                            paginatedTenants.prev_page_url,
                                        )
                                    }
                                />

                                <Pagination.Items />

                                <Pagination.Next
                                    onClick={() =>
                                        router.visit(
                                            paginatedTenants.next_page_url,
                                        )
                                    }
                                />
                                <Pagination.Last
                                    onClick={() =>
                                        router.visit(
                                            paginatedTenants.last_page_url,
                                        )
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
            </div>
        </AuthenticatedLayout>
    );
}

export default Index;
