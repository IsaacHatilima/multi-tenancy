import TablePagination from '@/Components/TablePagination';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import CreateUser from '@/Pages/Tenant/TenantPages/CreateUser';
import EditUser from '@/Pages/Tenant/TenantPages/EditUser';
import ToggleUserState from '@/Pages/Tenant/TenantPages/Partials/ToggleUserState';
import { PaginatedUsers, TenantUserFilter, User } from '@/types/user';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { Badge, Card, Input, Table, TextInput } from '@mantine/core';
import { debounce } from 'lodash';
import { useEffect } from 'react';
import { FaUserTie } from 'react-icons/fa';
import { FaUserLarge } from 'react-icons/fa6';

function Users() {
    const tenantUsers = usePage().props.users as PaginatedUsers;
    const filters: TenantUserFilter = usePage().props
        .filters as TenantUserFilter;
    const { data, setData } = useForm({
        first_name: filters?.first_name || '',
        last_name: filters?.last_name || '',
        email: filters?.email || '',
        role: filters?.role || '',
        verified: filters?.verified || '',
        active: filters?.active || '',
    });

    const rows = tenantUsers?.data.map((user: User) => (
        <Table.Tr key={user.id}>
            <Table.Td>{user.profile.first_name}</Table.Td>
            <Table.Td>{user.profile.last_name}</Table.Td>
            <Table.Td>{user.email}</Table.Td>
            <Table.Td>
                <Badge
                    color={user.role == 'Admin' ? 'green' : 'cyan'}
                    variant="outline"
                    size="sm"
                >
                    <div className="flex items-center gap-2 p-1">
                        {user.role == 'Admin' ? <FaUserTie /> : <FaUserLarge />}
                        {user.role}
                    </div>
                </Badge>
            </Table.Td>
            <Table.Td>
                <Badge
                    color={user.email_verified_at != null ? 'green' : 'red'}
                    variant="outline"
                    size="sm"
                >
                    {user.email_verified_at != null ? 'Verified' : 'Unverified'}
                </Badge>
            </Table.Td>
            <Table.Td>
                <Badge
                    color={user.is_active ? 'green' : 'red'}
                    variant="outline"
                    size="sm"
                >
                    <div className="flex items-center gap-2 p-1">
                        {user.is_active ? 'Active' : 'Inactive'}
                    </div>
                </Badge>
            </Table.Td>
            <Table.Td className="flex gap-4">
                <EditUser user={user} />
                <ToggleUserState user={user} />
            </Table.Td>
        </Table.Tr>
    ));

    const handleSearch = debounce(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || '1';
        const filtersPresent = Object.keys(data).some((key) =>
            urlParams.has(key),
        );
        const setOrDeleteParam = (key: string, value: string | undefined) => {
            if (value) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
        };

        setOrDeleteParam('first_name', data.first_name);
        setOrDeleteParam('last_name', data.last_name);
        setOrDeleteParam('email', data.email);
        setOrDeleteParam('role', data.role);
        setOrDeleteParam('verified', data.verified);
        setOrDeleteParam('active', data.active);

        urlParams.set('page', filtersPresent ? '1' : currentPage);

        const targetUrl = `${route('users')}?${urlParams.toString()}`;

        router.get(
            targetUrl,
            {},
            {
                replace: true,
                preserveState: true,
                preserveScroll: true,
            },
        );
    }, 300);

    useEffect(() => {
        handleSearch();
    }, [data]);

    return (
        <AuthenticatedLayout>
            <Head title="Tenant Users" />
            <Card
                shadow="sm"
                padding="lg"
                radius="md"
                withBorder
                className="mt-2"
            >
                <div className="flex justify-between">
                    <h2 className="mb-4 text-lg font-semibold">Users</h2>
                    <CreateUser />
                </div>
                <Table striped highlightOnHover>
                    <Table.Thead>
                        <Table.Tr>
                            <Table.Th>First Name</Table.Th>
                            <Table.Th>Last Name</Table.Th>
                            <Table.Th>Email</Table.Th>
                            <Table.Th>Role</Table.Th>
                            <Table.Th>Email Verified</Table.Th>
                            <Table.Th>Account State</Table.Th>
                            <Table.Th>Action</Table.Th>
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
                            <Table.Th>
                                <TextInput
                                    className="font-medium"
                                    id="email"
                                    name="email"
                                    placeholder="E-Mail"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData('email', e.target.value)
                                    }
                                />
                            </Table.Th>
                            <Table.Th>
                                <Input
                                    id="role"
                                    name="role"
                                    component="select"
                                    value={data.role}
                                    onChange={(e) =>
                                        setData('role', e.target.value)
                                    }
                                >
                                    <option value="">All</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </Input>
                            </Table.Th>
                            <Table.Th>
                                <Input
                                    id="verified"
                                    name="verified"
                                    component="select"
                                    value={data.verified}
                                    onChange={(e) =>
                                        setData('verified', e.target.value)
                                    }
                                >
                                    <option value="">All</option>
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </Input>
                            </Table.Th>
                            <Table.Th>
                                <Input
                                    id="active"
                                    name="active"
                                    component="select"
                                    value={data.active}
                                    onChange={(e) =>
                                        setData('active', e.target.value)
                                    }
                                >
                                    <option value="">All</option>
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </Input>
                            </Table.Th>
                        </Table.Tr>
                    </Table.Thead>
                    <Table.Tbody>{rows}</Table.Tbody>
                </Table>

                <TablePagination data={tenantUsers} />
            </Card>
        </AuthenticatedLayout>
    );
}

export default Users;
