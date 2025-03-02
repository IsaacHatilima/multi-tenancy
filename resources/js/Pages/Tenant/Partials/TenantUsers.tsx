import { PaginatedUsers, User } from '@/types/user';
import { router } from '@inertiajs/react';
import { Group, Pagination, Table } from '@mantine/core';

function TenantUsers({ tenantUsers }: { tenantUsers: PaginatedUsers }) {
    const rows = tenantUsers.data.map((user: User) => (
        <Table.Tr key={user.id}>
            <Table.Td>{user.profile.first_name}</Table.Td>
            <Table.Td>{user.profile.last_name}</Table.Td>
        </Table.Tr>
    ));

    return (
        <div>
            <h2 className="mb-4 text-lg font-semibold">Tenant Users</h2>
            <Table striped highlightOnHover>
                <Table.Thead>
                    <Table.Tr>
                        <Table.Th>First Name</Table.Th>
                        <Table.Th>Last Name</Table.Th>
                    </Table.Tr>
                </Table.Thead>
                <Table.Tbody>{rows}</Table.Tbody>
            </Table>

            <div className="mt-4 flex justify-end">
                <Pagination.Root
                    total={tenantUsers.last_page}
                    value={tenantUsers.current_page}
                    getItemProps={(page) => ({
                        href: tenantUsers.links[page]?.url,
                        onClick: () => {
                            if (tenantUsers.links[page]?.url) {
                                router.visit(
                                    page === 1
                                        ? tenantUsers.path
                                        : tenantUsers.links[page].url,
                                );
                            }
                        },
                    })}
                >
                    <Group gap={5} justify="center">
                        <Pagination.First
                            onClick={() => router.visit(tenantUsers.path)}
                        />
                        <Pagination.Previous
                            onClick={() =>
                                router.visit(tenantUsers.prev_page_url)
                            }
                        />

                        <Pagination.Items />

                        <Pagination.Next
                            onClick={() =>
                                router.visit(tenantUsers.next_page_url)
                            }
                        />
                        <Pagination.Last
                            onClick={() =>
                                router.visit(tenantUsers.last_page_url)
                            }
                        />
                    </Group>
                </Pagination.Root>
            </div>
            <div className="mt-4 flex justify-end">
                <p className="text-sm font-thin text-gray-400">
                    {tenantUsers.from} to {tenantUsers.to} of{' '}
                    {tenantUsers.total}
                </p>
            </div>
        </div>
    );
}

export default TenantUsers;
