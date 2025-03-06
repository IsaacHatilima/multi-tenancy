import { Tenant } from '@/types/tenant';
import { PaginatedUsers, User } from '@/types/user';
import { router, useForm, usePage } from '@inertiajs/react';
import { Group, Pagination, Table, TextInput } from '@mantine/core';
import { debounce } from 'lodash';
import { useEffect } from 'react';

interface Filters {
    first_name: string | null;
    last_name: string | null;
}

function TenantUsers() {
    const tenant: Tenant = usePage().props.tenant;
    const tenantUsers = usePage().props.tenant_users as PaginatedUsers;
    const filters: Filters = usePage().props.filters as Filters;

    const rows = tenantUsers?.data.map((user: User) => (
        <Table.Tr key={user.id}>
            <Table.Td>{user.profile.first_name}</Table.Td>
            <Table.Td>{user.profile.last_name}</Table.Td>
        </Table.Tr>
    ));

    const { data, setData } = useForm({
        first_name: filters?.first_name || '',
        last_name: filters?.last_name || '',
    });

    const handleSearch = debounce(() => {
        const urlParams = new URLSearchParams(window.location.search);

        const currentPage = urlParams.get('page') || '1';
        const hadFilters =
            urlParams.has('first_name') || urlParams.has('last_name');

        const setOrDeleteParam = (key: string, value: string | undefined) => {
            if (value) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
        };

        setOrDeleteParam('first_name', data.first_name);
        setOrDeleteParam('last_name', data.last_name);

        const hasFilters =
            urlParams.has('first_name') || urlParams.has('last_name');

        if (hasFilters) {
            urlParams.set('page', '1');
        } else if (hadFilters) {
            urlParams.set('page', currentPage);
        }

        const targetUrl = `${route('tenants.show', tenant.id)}?${urlParams.toString()}`;

        router.get(
            targetUrl,
            {},
            {
                only: ['tenant_users', 'filters'],
                replace: true,
                preserveState: true,
                preserveScroll: true,
            },
        );
    }, 300);

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters =
            urlParams.has('first_name') || urlParams.has('last_name');

        if (!hasFilters) {
            setData({
                first_name: '',
                last_name: '',
            });
        }
    }, [window.location.search]);

    useEffect(() => {
        handleSearch();
    }, [data]);

    return (
        <div>
            <h2 className="mb-4 text-lg font-semibold">Tenant Users</h2>
            <Table striped highlightOnHover>
                <Table.Thead>
                    <Table.Tr>
                        <Table.Th>First Name</Table.Th>
                        <Table.Th>Last Name</Table.Th>
                    </Table.Tr>
                    <Table.Tr>
                        <Table.Th>
                            <TextInput
                                className="font-medium"
                                id="first_name"
                                name="first_name"
                                placeholder="First Name"
                                value={data.first_name}
                                onChange={(e) =>
                                    setData('first_name', e.target.value)
                                }
                            />
                        </Table.Th>
                        <Table.Th>
                            <TextInput
                                className="font-medium"
                                id="last_name"
                                name="last_name"
                                placeholder="Last Name"
                                value={data.last_name}
                                onChange={(e) =>
                                    setData('last_name', e.target.value)
                                }
                            />
                        </Table.Th>
                    </Table.Tr>
                </Table.Thead>
                <Table.Tbody>{rows}</Table.Tbody>
            </Table>

            <div className="mt-4 flex justify-end">
                <Pagination.Root
                    total={tenantUsers?.last_page}
                    value={tenantUsers?.current_page}
                    getItemProps={(page) => ({
                        href: tenantUsers?.links[page]?.url,
                        onClick: () => {
                            if (tenantUsers?.links[page]?.url) {
                                router.visit(tenantUsers?.links[page].url);
                            }
                        },
                    })}
                >
                    <Group gap={5} justify="center">
                        <Pagination.First
                            onClick={() => router.visit(tenantUsers?.path)}
                        />
                        <Pagination.Previous
                            onClick={() =>
                                router.visit(tenantUsers?.prev_page_url)
                            }
                        />

                        <Pagination.Items />

                        <Pagination.Next
                            onClick={() =>
                                router.visit(tenantUsers?.next_page_url)
                            }
                        />
                        <Pagination.Last
                            onClick={() =>
                                router.visit(tenantUsers?.last_page_url)
                            }
                        />
                    </Group>
                </Pagination.Root>
            </div>
            <div className="mt-4 flex justify-end">
                <p className="text-sm font-thin text-gray-400">
                    {tenantUsers?.from} to {tenantUsers?.to} of{' '}
                    {tenantUsers?.total}
                </p>
            </div>
        </div>
    );
}

export default TenantUsers;
